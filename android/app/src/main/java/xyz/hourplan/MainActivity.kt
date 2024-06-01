package xyz.hourplan

import android.annotation.SuppressLint
import android.app.Activity
import android.os.Bundle
import android.os.Handler
import android.os.Looper
import android.view.Window
import android.webkit.*
import android.widget.Toast

class MainActivity : Activity() {
    private var shouldExit: Boolean = false

    @SuppressLint("SetJavaScriptEnabled")
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        this.requestWindowFeature(Window.FEATURE_NO_TITLE)
        this.setContentView(R.layout.activity_main)

        val webView: WebView = findViewById(R.id.main_web_view)
        webView.settings.allowContentAccess = true
        webView.settings.javaScriptEnabled = true

        WebView.setWebContentsDebuggingEnabled(true)
        webView.loadUrl("https://hourplan.000webhostapp.com/")
    }

    @Deprecated("Deprecated in Java")
    override fun onBackPressed() {
        when {
            this.shouldExit -> this.finish()
            else -> {
                Toast.makeText(this, "Press back again to exit.", Toast.LENGTH_SHORT).show()
                shouldExit = true

                Handler(Looper.getMainLooper()).postDelayed({
                    shouldExit = false
                }, 3000)
            }
        }
    }
}