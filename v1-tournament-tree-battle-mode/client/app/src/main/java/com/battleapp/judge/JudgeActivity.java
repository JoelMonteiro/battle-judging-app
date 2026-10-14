package com.battleapp.judge;

import android.content.SharedPreferences;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.ImageButton;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.StringRequest;
import com.google.firebase.database.DataSnapshot;
import com.google.firebase.database.DatabaseError;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;
import com.google.firebase.database.ValueEventListener;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

import com.battleapp.judge.connection.Database;
import com.battleapp.judge.connection.VolleySingleton;
import com.battleapp.judge.views.ChangeNameDialog;

/**
 * Ecrã de um juiz.
 *
 * Correções em relação à versão original:
 *  - Cada juiz escreve no SEU nó (Votes/{id}) com uma única escrita atómica.
 *    Antes os 5 juízes escreviam nos mesmos campos (VoteInfo/Vote, Nome, ID) com 3 setValue
 *    separados, por isso votos quase simultâneos apagavam-se uns aos outros.
 *  - O voto leva o RoundId da ronda atual: o servidor ignora votos de rondas antigas e o juiz
 *    sabe sempre se já votou nesta ronda, mesmo depois de reiniciar a app.
 *  - Leituras do Firebase à prova de null (antes: getValue().toString() -> NullPointerException).
 *  - O listener é removido no onDestroy (antes acumulava listeners a cada rotação do ecrã).
 *  - Comparações de Strings com equals() em vez de == / !=.
 *  - Uma única RequestQueue do Volley (VolleySingleton).
 */
public class JudgeActivity extends AppCompatActivity implements ChangeNameDialog.ChangeNameListner {
    private static final String PREF_ID = "ID";
    private static final String PREF_NAME = "NAME";

    private DatabaseReference roundInfoRef;
    private ValueEventListener roundInfoListener;

    private SharedPreferences mPreferences;
    private String judgeId = "";
    private String judgeName = "";

    private String currentRoundId = "";
    private boolean roundOngoing = false;

    private ImageButton btnBlue, btnDraw, btnRed;
    private TextView txtBlue, txtRed, txtLog;
    private TextView txtJudgeName;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        requestWindowFeature(Window.FEATURE_NO_TITLE);
        getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN, WindowManager.LayoutParams.FLAG_FULLSCREEN);
        setContentView(R.layout.activity_judge);

        txtJudgeName = findViewById(R.id.txtJudgeName);
        btnBlue = findViewById(R.id.btnBlue);
        btnDraw = findViewById(R.id.btnDraw);
        btnRed = findViewById(R.id.btnRed);
        txtBlue = findViewById(R.id.txtBlue);
        txtRed = findViewById(R.id.txtRed);
        txtLog = findViewById(R.id.txtLog);

        txtJudgeName.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                new ChangeNameDialog().show(getSupportFragmentManager(), "Change Judge Name");
            }
        });
        btnBlue.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                vote(getString(R.string.blue), R.drawable.button_blue_clicked, btnBlue, R.string.click_first);
            }
        });
        btnDraw.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                vote(getString(R.string.draw), R.drawable.button_draw_clicked, btnDraw, R.string.click_draw);
            }
        });
        btnRed.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                vote(getString(R.string.red), R.drawable.button_red_clicked, btnRed, R.string.click_second);
            }
        });

        mPreferences = PreferenceManager.getDefaultSharedPreferences(this);
        judgeId = mPreferences.getString(PREF_ID, "");
        judgeName = mPreferences.getString(PREF_NAME, "");
        txtJudgeName.setText(judgeName);

        disableButtons();
        disableChangeName();

        if (judgeId.isEmpty()) {
            claimJudgeSlot();
        } else {
            onJudgeReady();
        }
    }

    @Override
    protected void onDestroy() {
        if (roundInfoRef != null && roundInfoListener != null) {
            roundInfoRef.removeEventListener(roundInfoListener);
        }
        super.onDestroy();
    }

    //----------------------------//
    // Identidade do juiz

    /** Pede ao servidor PHP um lugar de juiz livre (1 a 5). Só acontece na primeira vez. */
    private void claimJudgeSlot() {
        JsonObjectRequest request = new JsonObjectRequest(Request.Method.POST,
                Database.URL_GET_AVAILABLE_JUDGE, null,
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {
                        try {
                            JSONArray judges = response.getJSONArray("judges");
                            JSONObject judge = judges.length() > 0 ? judges.getJSONObject(0) : null;

                            if (judge == null || judge.isNull("ID")) {
                                showMessage(getString(R.string.judges_full));
                                return;
                            }

                            judgeId = judge.getString("ID");
                            judgeName = judge.optString("nome", "Judge " + judgeId);
                            mPreferences.edit()
                                    .putString(PREF_ID, judgeId)
                                    .putString(PREF_NAME, judgeName)
                                    .apply();
                            txtJudgeName.setText(judgeName);
                            onJudgeReady();
                        } catch (JSONException e) {
                            showMessage(getString(R.string.server_error));
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        showMessage(getString(R.string.server_error));
                    }
                });

        VolleySingleton.getInstance(this).add(request);
    }

    private void onJudgeReady() {
        markConnected();
        publishName();
        enableChangeName();
        listenRoundInfo();
    }

    private void markConnected() {
        StringRequest request = new StringRequest(Request.Method.POST,
                Database.URL_UPDATE_CONNECTED_JUDGE,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) { }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Toast.makeText(getApplicationContext(), R.string.server_error, Toast.LENGTH_SHORT).show();
                    }
                }) {
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<>();
                params.put("Id", judgeId);
                params.put("connected", "1");
                return params;
            }
        };

        VolleySingleton.getInstance(this).add(request);
    }

    /** Publica o nome do juiz para o servidor/display (antes era um TODO no servidor). */
    private void publishName() {
        FirebaseDatabase.getInstance().getReference(Database.FB_JUDGES)
                .child(judgeId).child("Name").setValue(judgeName);
    }

    @Override
    public void applyChanges(String newJudgeName) {
        if (newJudgeName != null && !newJudgeName.trim().isEmpty()) {
            judgeName = newJudgeName.trim();
            mPreferences.edit().putString(PREF_NAME, judgeName).apply();
            txtJudgeName.setText(judgeName);
            if (!judgeId.isEmpty()) {
                publishName();
            }
        }
    }

    //----------------------------//
    // Ronda e votos

    private void listenRoundInfo() {
        if (roundInfoListener != null) {
            return; // já está a ouvir
        }
        roundInfoRef = FirebaseDatabase.getInstance().getReference(Database.FB_ROUND_INFO);
        roundInfoListener = new ValueEventListener() {
            @Override
            public void onDataChange(DataSnapshot snapshot) {
                String blueCorner = str(snapshot.child("BlueCorner"));
                String redCorner = str(snapshot.child("RedCorner"));
                currentRoundId = str(snapshot.child("RoundId"));
                roundOngoing = "True".equals(str(snapshot.child("isOngoing")));

                if (!roundOngoing || currentRoundId.isEmpty()) {
                    hideRoundInfo();
                    disableButtons();
                    return;
                }
                displayRoundInfo(blueCorner, redCorner);
                checkIfAlreadyVoted(currentRoundId);
            }

            @Override
            public void onCancelled(DatabaseError error) {
                showMessage(getString(R.string.server_error));
            }
        };
        roundInfoRef.addValueEventListener(roundInfoListener);
    }

    /** O Firebase é a fonte de verdade: se o meu voto já tem o RoundId atual, já votei. */
    private void checkIfAlreadyVoted(final String roundId) {
        FirebaseDatabase.getInstance().getReference(Database.FB_VOTES).child(judgeId)
                .addListenerForSingleValueEvent(new ValueEventListener() {
                    @Override
                    public void onDataChange(DataSnapshot snapshot) {
                        if (!roundId.equals(currentRoundId)) {
                            return; // entretanto mudou a ronda
                        }
                        boolean voted = roundId.equals(str(snapshot.child("RoundId")));
                        if (voted) {
                            disableButtons();
                        } else {
                            enableButtons();
                        }
                    }

                    @Override
                    public void onCancelled(DatabaseError error) {
                        enableButtons();
                    }
                });
    }

    private void vote(String vote, int clickedImage, ImageButton button, int toastText) {
        if (!roundOngoing || currentRoundId.isEmpty()) {
            return;
        }
        disableButtons();
        button.setImageResource(clickedImage);

        // Uma única escrita, no nó deste juiz: voto, nome e ronda chegam juntos ao servidor.
        Map<String, Object> data = new HashMap<>();
        data.put("Vote", vote);
        data.put("Name", judgeName);
        data.put("RoundId", currentRoundId);

        final int okText = toastText;
        FirebaseDatabase.getInstance().getReference(Database.FB_VOTES).child(judgeId)
                .setValue(data, new DatabaseReference.CompletionListener() {
                    @Override
                    public void onComplete(DatabaseError error, DatabaseReference ref) {
                        if (error == null) {
                            Toast.makeText(getApplicationContext(), okText, Toast.LENGTH_SHORT).show();
                        } else {
                            Toast.makeText(getApplicationContext(), R.string.vote_failed, Toast.LENGTH_LONG).show();
                            enableButtons(); // deixa tentar outra vez
                        }
                    }
                });
    }

    private static String str(DataSnapshot snapshot) {
        Object value = snapshot.getValue();
        return value == null ? "" : value.toString();
    }

    //----------------------------//
    // UI

    private void enableChangeName() {
        txtJudgeName.setEnabled(true);
    }

    private void disableChangeName() {
        txtJudgeName.setEnabled(false);
    }

    private void enableButtons() {
        btnBlue.setEnabled(true);
        btnDraw.setEnabled(true);
        btnRed.setEnabled(true);

        btnBlue.setImageResource(R.drawable.button_blue);
        btnDraw.setImageResource(R.drawable.button_draw);
        btnRed.setImageResource(R.drawable.button_red);
        hideMessage();
    }

    private void disableButtons() {
        btnBlue.setEnabled(false);
        btnDraw.setEnabled(false);
        btnRed.setEnabled(false);
        showMessage(getString(R.string.waiting_for_server));
    }

    private void displayRoundInfo(String blueCorner, String redCorner) {
        txtBlue.setText(blueCorner);
        txtBlue.setVisibility(View.VISIBLE);
        txtRed.setText(redCorner);
        txtRed.setVisibility(View.VISIBLE);
    }

    private void hideRoundInfo() {
        txtBlue.setText("");
        txtBlue.setVisibility(View.GONE);
        txtRed.setText("");
        txtRed.setVisibility(View.GONE);
    }

    private void showMessage(String message) {
        txtLog.setText(message);
        txtLog.setVisibility(View.VISIBLE);
    }

    private void hideMessage() {
        txtLog.setText("");
        txtLog.setVisibility(View.GONE);
    }
}
