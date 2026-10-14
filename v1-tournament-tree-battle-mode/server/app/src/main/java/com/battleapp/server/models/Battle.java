package com.battleapp.server.models;

import java.util.HashMap;
import java.util.Map;

/**
 * Uma batalha da árvore do torneio (azul vs vermelho).
 * Um canto "---" (ID 0) significa que não há adversário: o outro passa diretamente (bye).
 */
public class Battle {
    public static final String EMPTY_CORNER = "---";

    private String blueCorner, redCorner;
    private String blueCornerImage, redCornerImage;
    private int blueCornerID, redCornerID;
    private int order;
    private String winner;        // nome do vencedor, null enquanto não houver
    private String winnerImage;
    private int winnerID;

    public Battle(String blueCorner, String redCorner, int blueCornerID, int redCornerID,
                  String blueCornerImage, String redCornerImage, int order) {
        this.blueCorner = blueCorner == null ? EMPTY_CORNER : blueCorner;
        this.redCorner = redCorner == null ? EMPTY_CORNER : redCorner;
        this.blueCornerID = blueCornerID;
        this.redCornerID = redCornerID;
        this.blueCornerImage = isBlank(blueCornerImage) ? "dummyBlue" : blueCornerImage;
        this.redCornerImage = isBlank(redCornerImage) ? "dummyRed" : redCornerImage;
        this.order = order;

        if (isEmptyCorner(this.blueCorner, blueCornerID)) {
            setWinner(this.redCorner, this.redCornerID, this.redCornerImage);
        } else if (isEmptyCorner(this.redCorner, redCornerID)) {
            setWinner(this.blueCorner, this.blueCornerID, this.blueCornerImage);
        }
    }

    private static boolean isBlank(String s) {
        return s == null || s.trim().isEmpty() || "null".equalsIgnoreCase(s.trim());
    }

    private static boolean isEmptyCorner(String name, int id) {
        return id == 0 || EMPTY_CORNER.equals(name);
    }

    /** Batalha só com um competidor: o vencedor é conhecido sem rondas. */
    public boolean isBye() {
        return isEmptyCorner(blueCorner, blueCornerID) || isEmptyCorner(redCorner, redCornerID);
    }

    public boolean isFinished() {
        return winner != null;
    }

    public void setBlueWinner() {
        setWinner(blueCorner, blueCornerID, blueCornerImage);
    }

    public void setRedWinner() {
        setWinner(redCorner, redCornerID, redCornerImage);
    }

    private void setWinner(String name, int id, String image) {
        this.winner = name;
        this.winnerID = id;
        this.winnerImage = image;
    }

    //---------------- persistência no Firebase (para retomar o torneio se a app fechar)

    public Map<String, Object> toMap() {
        Map<String, Object> map = new HashMap<>();
        map.put("blueCorner", blueCorner);
        map.put("redCorner", redCorner);
        map.put("blueCornerID", blueCornerID);
        map.put("redCornerID", redCornerID);
        map.put("blueCornerImage", blueCornerImage);
        map.put("redCornerImage", redCornerImage);
        map.put("order", order);
        map.put("winner", winner);
        map.put("winnerID", winnerID);
        map.put("winnerImage", winnerImage);
        return map;
    }

    public static Battle fromMap(Map<String, Object> map) {
        Battle b = new Battle(
                asString(map.get("blueCorner")), asString(map.get("redCorner")),
                asInt(map.get("blueCornerID")), asInt(map.get("redCornerID")),
                asString(map.get("blueCornerImage")), asString(map.get("redCornerImage")),
                asInt(map.get("order")));
        String w = asString(map.get("winner"));
        if (w != null) {
            b.setWinner(w, asInt(map.get("winnerID")), asString(map.get("winnerImage")));
        }
        return b;
    }

    private static String asString(Object o) {
        return o == null ? null : o.toString();
    }

    private static int asInt(Object o) {
        if (o instanceof Number) {
            return ((Number) o).intValue();
        }
        try {
            return o == null ? 0 : Integer.parseInt(o.toString());
        } catch (NumberFormatException e) {
            return 0;
        }
    }

    //---------------- getters

    public String getBlueCorner() { return blueCorner; }
    public String getRedCorner() { return redCorner; }
    public int getBlueCornerID() { return blueCornerID; }
    public int getRedCornerID() { return redCornerID; }
    public String getBlueCornerImage() { return blueCornerImage; }
    public String getRedCornerImage() { return redCornerImage; }
    public int getOrder() { return order; }
    public String getWinner() { return winner; }
    public int getWinnerID() { return winnerID; }
    public String getWinnerImage() { return winnerImage; }

    @Override
    public String toString() {
        return "> " + blueCornerID + ":" + blueCorner + " - " + redCornerID + ":" + redCorner + " || " + winner;
    }
}
