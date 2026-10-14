package com.battleapp.judge.connection;

import android.content.Context;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.toolbox.Volley;

/**
 * Uma única RequestQueue para toda a app.
 * (Antes era criada uma RequestQueue nova em cada pedido, o que cria threads e caches a mais.)
 */
public class VolleySingleton {
    private static VolleySingleton instance;
    private final RequestQueue requestQueue;

    private VolleySingleton(Context context) {
        requestQueue = Volley.newRequestQueue(context.getApplicationContext());
    }

    public static synchronized VolleySingleton getInstance(Context context) {
        if (instance == null) {
            instance = new VolleySingleton(context);
        }
        return instance;
    }

    public <T> void add(Request<T> request) {
        requestQueue.add(request);
    }
}
