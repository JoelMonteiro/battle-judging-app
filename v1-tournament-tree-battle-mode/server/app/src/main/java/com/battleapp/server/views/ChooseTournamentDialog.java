package com.battleapp.server.views;

import android.content.Context;
import android.content.DialogInterface;
import android.os.Bundle;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatDialogFragment;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Spinner;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

import com.battleapp.server.R;
import com.battleapp.server.connection.Database;
import com.battleapp.server.connection.VolleySingleton;
import com.battleapp.server.models.Tournament;

/** Diálogo para escolher o torneio. Usa o Volley (antes usava também a biblioteca AsyncHttpClient). */
public class ChooseTournamentDialog extends AppCompatDialogFragment {
    private Spinner spinTournament;
    private ChooseTournamentListner listner;

    @Override
    public android.app.Dialog onCreateDialog(Bundle savedInstanceState) {
        AlertDialog.Builder builder = new AlertDialog.Builder(getActivity(), R.style.Dialog);

        LayoutInflater inflater = getActivity().getLayoutInflater();
        View view = inflater.inflate(R.layout.dialog_spinner, null);

        builder.setView(view)
                .setTitle("Choose a Tournament")
                .setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                    }
                })
                .setPositiveButton("OK", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        Object selected = spinTournament.getSelectedItem();
                        if (selected instanceof Tournament && !"0".equals(((Tournament) selected).getId())) {
                            Tournament t = (Tournament) selected;
                            listner.selectedTournament(t.getId(), t.getName());
                        }
                    }
                });

        spinTournament = view.findViewById(R.id.spinTournaments);
        loadTournaments();

        return builder.create();
    }

    private void loadTournaments() {
        StringRequest request = new StringRequest(Request.Method.POST,
                Database.URL_SELECT_TOURNAMENTS_NOT_PICKED,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            fillSpinner(new JSONObject(response).getJSONArray("tournaments"));
                        } catch (JSONException e) {
                            fillSpinner(new JSONArray());
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        fillSpinner(new JSONArray());
                    }
                });

        VolleySingleton.getInstance(getActivity()).add(request);
    }

    private void fillSpinner(JSONArray jsonArray) {
        if (getActivity() == null) {
            return; // o diálogo já foi fechado
        }
        ArrayList<Tournament> list = new ArrayList<>();
        for (int i = 0; i < jsonArray.length(); i++) {
            JSONObject json = jsonArray.optJSONObject(i);
            if (json != null) {
                list.add(new Tournament(json.optString("fldID"), json.optString("nome")));
            }
        }
        if (list.isEmpty()) {
            list.add(new Tournament("0", "No More Tournaments"));
        }
        ArrayAdapter<Tournament> adapter = new ArrayAdapter<>(getActivity(),
                android.R.layout.simple_spinner_item, list);
        spinTournament.setAdapter(adapter);
    }

    @Override
    public void onAttach(Context context) {
        super.onAttach(context);
        try {
            listner = (ChooseTournamentListner) context;
        } catch (ClassCastException e) {
            throw new ClassCastException(context.toString() + " must implement ChooseTournamentListner");
        }
    }

    public interface ChooseTournamentListner {
        void selectedTournament(String tournamentID, String tournamentName);
    }
}
