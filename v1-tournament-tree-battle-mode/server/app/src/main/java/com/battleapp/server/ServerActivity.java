package com.battleapp.server;

import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.google.firebase.database.DataSnapshot;
import com.google.firebase.database.DatabaseError;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;
import com.google.firebase.database.ValueEventListener;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import com.battleapp.server.connection.Database;
import com.battleapp.server.connection.VolleySingleton;
import com.battleapp.server.models.Battle;
import com.battleapp.server.views.ChooseTournamentDialog;

/**
 * App do servidor: gere a árvore do torneio, abre/fecha rondas e conta os votos dos juízes.
 *
 * O que mudou em relação à versão original (ver README, secção "Post-mortem da v1"):
 *  - Os votos chegam em Votes/{judgeId} (um nó por juiz) e não numa "caixa" partilhada.
 *  - Já não há o ciclo "RoundVoteInfo muda -> reset ao VoteInfo -> 18 escritas -> ..." que apagava votos.
 *  - Todas as escritas de um nó são feitas de uma vez (updateChildren / setValue de um Map).
 *  - O estado do torneio (árvore, batalha, pontos, ronda) é guardado em TournamentState no Firebase
 *    e retomado no arranque: se a app fechar a meio, o torneio continua de onde estava.
 *  - Implementados os TODOs: avançar ronda/batalha, contagem de votos, vencedor da ronda e da batalha,
 *    construção dos níveis seguintes da árvore, nome dos juízes vindo das apps dos juízes.
 *  - Leituras do Firebase à prova de null; listeners removidos no onDestroy; uma só RequestQueue.
 */
public class ServerActivity extends AppCompatActivity implements ChooseTournamentDialog.ChooseTournamentListner {
    private static final int NUM_JUDGES = 5;
    private static final String BLUE = "BLUE", RED = "RED", DRAW = "DRAW", BLANK = "BLANK";

    // Firebase
    private DatabaseReference votesRef, judgesRef;
    private ValueEventListener votesListener, judgesListener;

    // UI
    private TextView[] txtJudges;
    private ImageView[] imgJudges;
    private TextView txtBlueCorner, txtRounds, txtPoints, txtRedCorner;
    private Button btnNextRound, btnChangeTournament;

    // Estado do torneio (persistido em TournamentState)
    private String tournamentId = "", tournamentName = "";
    private List<Battle> battles = new ArrayList<>();
    private int level = 0, battleIndex = -1;
    private int bluePoints = 0, redPoints = 0, roundNumber = 0;
    private String roundId = "";
    private boolean roundOpen = false, tournamentOngoing = false;
    private String champion = "", lastResult = "";

    // Estado da ronda atual (reconstruído a partir de Votes)
    private final Map<String, String> currentVotes = new HashMap<>();
    private final String[] judgeNames = new String[NUM_JUDGES];

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        requestWindowFeature(Window.FEATURE_NO_TITLE);
        getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN, WindowManager.LayoutParams.FLAG_FULLSCREEN);
        setContentView(R.layout.activity_server);

        txtJudges = new TextView[]{findViewById(R.id.txtJudge1), findViewById(R.id.txtJudge2),
                findViewById(R.id.txtJudge3), findViewById(R.id.txtJudge4), findViewById(R.id.txtJudge5)};
        imgJudges = new ImageView[]{findViewById(R.id.imgJudge1), findViewById(R.id.imgJudge2),
                findViewById(R.id.imgJudge3), findViewById(R.id.imgJudge4), findViewById(R.id.imgJudge5)};
        txtBlueCorner = findViewById(R.id.txtBlueCorner);
        txtRounds = findViewById(R.id.txtRoundNumber);
        txtPoints = findViewById(R.id.txtPoints);
        txtRedCorner = findViewById(R.id.txtRedCorner);
        btnNextRound = findViewById(R.id.btnNextRound);
        btnChangeTournament = findViewById(R.id.btnChangeTournament);

        for (int i = 0; i < NUM_JUDGES; i++) {
            judgeNames[i] = "Judge " + (i + 1);
        }

        btnNextRound.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                onNextPressed();
            }
        });
        btnChangeTournament.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                new ChooseTournamentDialog().show(getSupportFragmentManager(), "Choose Tournament");
            }
        });

        btnNextRound.setEnabled(false);
        btnChangeTournament.setEnabled(false);
        restoreState();
    }

    @Override
    protected void onDestroy() {
        if (votesRef != null && votesListener != null) {
            votesRef.removeEventListener(votesListener);
        }
        if (judgesRef != null && judgesListener != null) {
            judgesRef.removeEventListener(judgesListener);
        }
        super.onDestroy();
    }

    //------------------------------------------------------------------------------------------//
    // Torneio

    @Override
    public void selectedTournament(String id, String name) {
        loadBattles(id, name);
    }

    /** Lê as batalhas do 1.º nível da árvore (criadas no BackOffice / Associação). */
    private void loadBattles(final String id, final String name) {
        StringRequest request = new StringRequest(Request.Method.POST,
                Database.URL_SELECT_ROUNDS_BY_TOURNAMENT,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        List<Battle> list = new ArrayList<>();
                        try {
                            JSONArray json = new JSONObject(response).getJSONArray("battles");
                            for (int i = 0; i < json.length(); i++) {
                                JSONObject b = json.getJSONObject(i);
                                list.add(new Battle(
                                        b.optString("blueCornerName"), b.optString("redCornerName"),
                                        b.optInt("blueCornerID"),     // antes lia "redCornerID" aqui (copy-paste)
                                        b.optInt("redCornerID"),
                                        b.optString("blueCornerImage"), b.optString("redCornerImage"),
                                        b.optInt("ordem")));
                            }
                        } catch (JSONException e) {
                            toast(getString(R.string.server_error));
                            return;
                        }
                        if (list.isEmpty()) {
                            toast(getString(R.string.no_battles));
                            return;
                        }
                        Collections.sort(list, new Comparator<Battle>() {
                            @Override
                            public int compare(Battle a, Battle b) {
                                return a.getOrder() - b.getOrder();
                            }
                        });
                        startTournament(id, name, list);
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        toast(getString(R.string.server_error));
                    }
                }) {
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<>();
                params.put("Id", id);
                return params;
            }
        };
        VolleySingleton.getInstance(this).add(request);
    }

    private void startTournament(String id, String name, List<Battle> firstLevel) {
        tournamentId = id;
        tournamentName = name == null ? "" : name;
        battles = firstLevel;
        level = 1;
        battleIndex = -1;
        champion = "";
        lastResult = "";
        tournamentOngoing = true;
        btnChangeTournament.setEnabled(false);
        markTournamentPicked(id);
        nextBattle();
    }

    private void markTournamentPicked(final String id) {
        StringRequest request = new StringRequest(Request.Method.POST, Database.URL_UPDATE_TOURNAMENT,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) { }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        toast(getString(R.string.server_error));
                    }
                }) {
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<>();
                params.put("Id", id);
                return params;
            }
        };
        VolleySingleton.getInstance(this).add(request);
    }

    /** Avança para a próxima batalha por decidir; quando o nível acaba, constrói o nível seguinte. */
    private void nextBattle() {
        while (true) {
            battleIndex++;
            while (battleIndex < battles.size() && battles.get(battleIndex).isFinished()) {
                battleIndex++; // batalhas "bye" (só um competidor) já têm vencedor
            }
            if (battleIndex < battles.size()) {
                bluePoints = 0;
                redPoints = 0;
                roundNumber = 0;
                lastResult = "";
                startRound();
                return;
            }

            // Nível concluído: os vencedores, por ordem, formam as batalhas do nível seguinte
            List<Battle> winners = new ArrayList<>(battles);
            if (winners.size() <= 1) {
                Battle last = winners.isEmpty() ? null : winners.get(0);
                finishTournament(last == null ? "" : last.getWinner());
                return;
            }
            List<Battle> nextLevel = new ArrayList<>();
            for (int i = 0; i < winners.size(); i += 2) {
                Battle a = winners.get(i);
                Battle b = (i + 1 < winners.size()) ? winners.get(i + 1) : null;
                nextLevel.add(new Battle(
                        a.getWinner(), b == null ? Battle.EMPTY_CORNER : b.getWinner(),
                        a.getWinnerID(), b == null ? 0 : b.getWinnerID(),
                        a.getWinnerImage(), b == null ? "" : b.getWinnerImage(),
                        i / 2));
            }
            battles = nextLevel;
            level++;
            battleIndex = -1;
        }
    }

    private void finishTournament(String winner) {
        tournamentOngoing = false;
        roundOpen = false;
        champion = winner == null ? "" : winner;
        currentVotes.clear();
        pushRoundInfo();
        pushDisplay();
        saveState();
        refreshUi();
        btnNextRound.setEnabled(false);
        btnChangeTournament.setEnabled(true);
        toast(getString(R.string.tournament_winner, champion));
    }

    //------------------------------------------------------------------------------------------//
    // Rondas

    private void onNextPressed() {
        Battle battle = currentBattle();
        if (battle == null || roundOpen) {
            return;
        }
        if (battle.isFinished()) {
            nextBattle();
        } else {
            startRound(); // ronda seguinte da mesma batalha (ou desempate)
        }
    }

    private void startRound() {
        roundNumber++;
        roundId = tournamentId + "-L" + level + "-B" + battleIndex + "-R" + roundNumber + "-" + System.currentTimeMillis();
        roundOpen = true;
        currentVotes.clear();
        btnNextRound.setEnabled(false);
        pushRoundInfo();
        pushDisplay();
        saveState();
        refreshUi();
    }

    /** Melhor de 3 rondas; a final (nível com uma só batalha) é à melhor de 5. */
    private int totalRounds() {
        return battles.size() == 1 ? 5 : 3;
    }

    private void closeRound() {
        Battle battle = currentBattle();
        if (battle == null) {
            return;
        }
        roundOpen = false;

        int blue = 0, red = 0;
        for (String v : currentVotes.values()) {
            if (BLUE.equals(v)) blue++;
            else if (RED.equals(v)) red++;
        }
        if (blue > red) {
            bluePoints++;
            lastResult = getString(R.string.round_winner, roundNumber, battle.getBlueCorner());
        } else if (red > blue) {
            redPoints++;
            lastResult = getString(R.string.round_winner, roundNumber, battle.getRedCorner());
        } else {
            lastResult = getString(R.string.round_draw, roundNumber);
        }

        int needed = totalRounds() / 2 + 1;
        boolean roundsOver = roundNumber >= totalRounds();
        if (bluePoints >= needed || (roundsOver && bluePoints > redPoints)) {
            battle.setBlueWinner();
        } else if (redPoints >= needed || (roundsOver && redPoints > bluePoints)) {
            battle.setRedWinner();
        }
        // Empatados no fim das rondas: continua com rondas de desempate até alguém ganhar.

        pushRoundInfo();
        pushDisplay();
        saveState();
        refreshUi();
        btnNextRound.setEnabled(true);
    }

    private Battle currentBattle() {
        return (battleIndex >= 0 && battleIndex < battles.size()) ? battles.get(battleIndex) : null;
    }

    //------------------------------------------------------------------------------------------//
    // Firebase: listeners

    private void startListeners() {
        votesRef = FirebaseDatabase.getInstance().getReference(Database.FB_VOTES);
        votesListener = new ValueEventListener() {
            @Override
            public void onDataChange(DataSnapshot snapshot) {
                onVotesChanged(snapshot);
            }

            @Override
            public void onCancelled(DatabaseError error) {
                toast(error.getMessage());
            }
        };
        votesRef.addValueEventListener(votesListener);

        judgesRef = FirebaseDatabase.getInstance().getReference(Database.FB_JUDGES);
        judgesListener = new ValueEventListener() {
            @Override
            public void onDataChange(DataSnapshot snapshot) {
                for (DataSnapshot judge : snapshot.getChildren()) {
                    int i = judgeIndex(judge.getKey());
                    String name = str(judge.child("Name"));
                    if (i >= 0 && !name.isEmpty()) {
                        judgeNames[i] = name;
                    }
                }
                refreshUi();
                pushDisplay();
            }

            @Override
            public void onCancelled(DatabaseError error) { }
        };
        judgesRef.addValueEventListener(judgesListener);
    }

    /** Recalcula os votos da ronda aberta a partir de Votes/{1..5}, ignorando votos de outras rondas. */
    private void onVotesChanged(DataSnapshot snapshot) {
        if (!roundOpen) {
            return;
        }
        currentVotes.clear();
        for (DataSnapshot judge : snapshot.getChildren()) {
            int i = judgeIndex(judge.getKey());
            if (i < 0 || !roundId.equals(str(judge.child("RoundId")))) {
                continue;
            }
            String vote = str(judge.child("Vote"));
            if (BLUE.equals(vote) || RED.equals(vote) || DRAW.equals(vote)) {
                currentVotes.put(judge.getKey(), vote);
            }
            String name = str(judge.child("Name"));
            if (!name.isEmpty()) {
                judgeNames[i] = name;
            }
        }
        refreshUi();
        pushDisplay();
        if (currentVotes.size() >= NUM_JUDGES) {
            closeRound();
        }
    }

    private static int judgeIndex(String key) {
        try {
            int n = Integer.parseInt(key);
            return (n >= 1 && n <= NUM_JUDGES) ? n - 1 : -1;
        } catch (NumberFormatException e) {
            return -1;
        }
    }

    private static String str(DataSnapshot snapshot) {
        Object value = snapshot.getValue();
        return value == null ? "" : value.toString();
    }

    //------------------------------------------------------------------------------------------//
    // Firebase: escritas (cada nó numa única escrita)

    /** RoundInfo: o que as apps dos juízes precisam de saber. */
    private void pushRoundInfo() {
        Battle battle = currentBattle();
        Map<String, Object> info = new HashMap<>();
        info.put("BlueCorner", battle == null ? "" : battle.getBlueCorner());
        info.put("RedCorner", battle == null ? "" : battle.getRedCorner());
        info.put("RoundNumber", String.valueOf(roundNumber));
        info.put("RoundId", roundId);
        info.put("isOngoing", roundOpen ? "True" : "False");
        FirebaseDatabase.getInstance().getReference(Database.FB_ROUND_INFO).updateChildren(info);
    }

    /** RoundVoteInfo: o que o display web mostra (mesmas chaves que o display.js já usa). */
    private void pushDisplay() {
        Battle battle = currentBattle();
        Map<String, Object> info = new HashMap<>();
        for (int i = 0; i < NUM_JUDGES; i++) {
            String vote = currentVotes.get(String.valueOf(i + 1));
            info.put("Judge" + (i + 1), judgeNames[i]);
            info.put("Vote" + (i + 1), vote == null ? BLANK : vote);
        }
        info.put("BlueCorner", battle == null ? "" : battle.getBlueCorner());
        info.put("RedCorner", battle == null ? "" : battle.getRedCorner());
        info.put("BlueCornerImg", battle == null ? "dummyBlue" : battle.getBlueCornerImage());
        info.put("RedCornerImg", battle == null ? "dummyRed" : battle.getRedCornerImage());
        info.put("RoundNumber", String.valueOf(roundNumber));
        info.put("Points", bluePoints + " - " + redPoints);
        info.put("BluePoints", bluePoints);
        info.put("RedPoints", redPoints);
        info.put("Tournament", tournamentName);

        String winner;
        if (!tournamentOngoing && !champion.isEmpty()) {
            winner = getString(R.string.tournament_winner, champion);
        } else if (battle != null && battle.isFinished() && !roundOpen) {
            winner = getString(R.string.battle_winner, battle.getWinner());
        } else {
            winner = roundOpen ? "" : lastResult;
        }
        info.put("Winner", winner);
        info.put("isOngoingRound", roundOpen ? "True" : "False");
        info.put("isOngoingTournament", tournamentOngoing ? "True" : "False");
        FirebaseDatabase.getInstance().getReference(Database.FB_ROUND_VOTE_INFO).updateChildren(info);
    }

    //------------------------------------------------------------------------------------------//
    // Persistência do estado (a árvore já não vive só na memória do ecrã)

    private void saveState() {
        List<Map<String, Object>> list = new ArrayList<>();
        for (Battle b : battles) {
            list.add(b.toMap());
        }
        Map<String, Object> state = new HashMap<>();
        state.put("tournamentId", tournamentId);
        state.put("tournamentName", tournamentName);
        state.put("level", level);
        state.put("battleIndex", battleIndex);
        state.put("bluePoints", bluePoints);
        state.put("redPoints", redPoints);
        state.put("roundNumber", roundNumber);
        state.put("roundId", roundId);
        state.put("roundOpen", roundOpen);
        state.put("ongoing", tournamentOngoing);
        state.put("champion", champion);
        state.put("lastResult", lastResult);
        state.put("battles", list);
        FirebaseDatabase.getInstance().getReference(Database.FB_TOURNAMENT_STATE).setValue(state);
    }

    @SuppressWarnings("unchecked")
    private void restoreState() {
        FirebaseDatabase.getInstance().getReference(Database.FB_TOURNAMENT_STATE)
                .addListenerForSingleValueEvent(new ValueEventListener() {
                    @Override
                    public void onDataChange(DataSnapshot s) {
                        boolean ongoing = Boolean.TRUE.equals(s.child("ongoing").getValue(Boolean.class));
                        if (s.exists() && ongoing) {
                            tournamentId = str(s.child("tournamentId"));
                            tournamentName = str(s.child("tournamentName"));
                            level = intOf(s.child("level"));
                            battleIndex = intOf(s.child("battleIndex"));
                            bluePoints = intOf(s.child("bluePoints"));
                            redPoints = intOf(s.child("redPoints"));
                            roundNumber = intOf(s.child("roundNumber"));
                            roundId = str(s.child("roundId"));
                            roundOpen = Boolean.TRUE.equals(s.child("roundOpen").getValue(Boolean.class));
                            champion = str(s.child("champion"));
                            lastResult = str(s.child("lastResult"));
                            tournamentOngoing = true;
                            battles = new ArrayList<>();
                            for (DataSnapshot b : s.child("battles").getChildren()) {
                                Object value = b.getValue();
                                if (value instanceof Map) {
                                    battles.add(Battle.fromMap((Map<String, Object>) value));
                                }
                            }
                            btnChangeTournament.setEnabled(false);
                            btnNextRound.setEnabled(!roundOpen && currentBattle() != null);
                            toast(getString(R.string.tournament_resumed));
                        } else {
                            btnChangeTournament.setEnabled(true);
                        }
                        refreshUi();
                        startListeners(); // só depois de restaurar, para recontar os votos da ronda aberta
                    }

                    @Override
                    public void onCancelled(DatabaseError error) {
                        btnChangeTournament.setEnabled(true);
                        startListeners();
                    }
                });
    }

    private static int intOf(DataSnapshot s) {
        Object v = s.getValue();
        if (v instanceof Number) {
            return ((Number) v).intValue();
        }
        try {
            return v == null ? 0 : Integer.parseInt(v.toString());
        } catch (NumberFormatException e) {
            return 0;
        }
    }

    //------------------------------------------------------------------------------------------//
    // UI

    private void refreshUi() {
        Battle battle = currentBattle();
        txtBlueCorner.setText(battle == null ? "" : battle.getBlueCorner());
        txtRedCorner.setText(battle == null ? "" : battle.getRedCorner());
        txtRounds.setText(battle == null ? "" : getString(R.string.round_label, level, roundNumber, totalRounds()));
        txtPoints.setText(bluePoints + " - " + redPoints);

        for (int i = 0; i < NUM_JUDGES; i++) {
            txtJudges[i].setText(judgeNames[i]);
            String vote = currentVotes.get(String.valueOf(i + 1));
            if (BLUE.equals(vote)) {
                imgJudges[i].setImageResource(R.drawable.blue_color);
            } else if (RED.equals(vote)) {
                imgJudges[i].setImageResource(R.drawable.red_color);
            } else if (DRAW.equals(vote)) {
                imgJudges[i].setImageResource(R.drawable.draw_color);
            } else {
                imgJudges[i].setImageResource(R.drawable.default_color);
            }
        }
    }

    private void toast(String message) {
        Toast.makeText(getApplicationContext(), message, Toast.LENGTH_LONG).show();
    }
}
