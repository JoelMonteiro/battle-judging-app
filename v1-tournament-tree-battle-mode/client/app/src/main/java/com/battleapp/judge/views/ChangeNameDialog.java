package com.battleapp.judge.views;

import android.content.Context;
import android.content.DialogInterface;
import android.os.Bundle;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatDialogFragment;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.EditText;

import com.battleapp.judge.R;

public class ChangeNameDialog extends AppCompatDialogFragment {
    private EditText editJudgeName;
    private ChangeNameListner listner;

    @Override
    public android.app.Dialog onCreateDialog(Bundle savedInstanceState) {
        AlertDialog.Builder builder = new AlertDialog.Builder(getActivity(), R.style.Dialog);

        LayoutInflater inflater = getActivity().getLayoutInflater();
        View view = inflater.inflate(R.layout.dialog_change_name, null);

        builder.setView(view)
                .setTitle("Change Judge Name")
                .setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {

                    }
                })
                .setPositiveButton("OK", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        String newJudgeName = editJudgeName.getText().toString();
                        listner.applyChanges(newJudgeName);
                    }
                });

        editJudgeName = view.findViewById(R.id.editJudgeName);

        return builder.create();
    }

    @Override
    public void onAttach(Context context) {
        super.onAttach(context);

        try {
            listner = (ChangeNameListner) context;
        } catch (ClassCastException e) {
            throw new ClassCastException(context.toString() +
                    "Must implement ChangeNameListner");
        }
    }

    public interface ChangeNameListner {
        void applyChanges(String newJudgeName);
    }
}
