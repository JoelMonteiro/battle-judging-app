package com.battleapp.judge.connection;

public class Database {
    // Endereço do servidor PHP (pasta display/android). Mudar para o IP/domínio do teu servidor.
    private static final String ROOT_URL = "http://YOUR_SERVER/battle-app/android/";

    public static final String URL_GET_AVAILABLE_JUDGE = ROOT_URL + "getAvailableJudge.php";
    public static final String URL_UPDATE_CONNECTED_JUDGE = ROOT_URL + "updateJudgeConnectedById.php";

    // Nós do Firebase partilhados entre juízes, servidor e display
    public static final String FB_ROUND_INFO = "RoundInfo";   // ronda atual (escrito só pelo servidor)
    public static final String FB_VOTES = "Votes";            // Votes/{judgeId} -> um nó por juiz
    public static final String FB_JUDGES = "Judges";          // Judges/{judgeId}/Name
}
