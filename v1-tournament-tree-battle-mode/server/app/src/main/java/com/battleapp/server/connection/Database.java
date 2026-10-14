package com.battleapp.server.connection;

public class Database {
    // Endereço do servidor PHP (pasta display/android). Mudar para o IP/domínio do teu servidor.
    private static final String ROOT_URL = "http://YOUR_SERVER/battle-app/android/";

    public static final String URL_SELECT_TOURNAMENTS_NOT_PICKED = ROOT_URL + "selectTournamentsNotPicked.php";
    public static final String URL_UPDATE_TOURNAMENT = ROOT_URL + "updateTournament.php";
    public static final String URL_SELECT_ROUNDS_BY_TOURNAMENT = ROOT_URL + "selectRoundsByTournament.php";

    // Nós do Firebase
    public static final String FB_ROUND_INFO = "RoundInfo";             // lido pelos juízes
    public static final String FB_VOTES = "Votes";                      // Votes/{judgeId}: escrito pelos juízes
    public static final String FB_JUDGES = "Judges";                    // Judges/{judgeId}/Name
    public static final String FB_ROUND_VOTE_INFO = "RoundVoteInfo";    // lido pelo display (web)
    public static final String FB_TOURNAMENT_STATE = "TournamentState"; // estado do torneio (para retomar)
}
