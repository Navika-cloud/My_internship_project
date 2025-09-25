<?php
// Start session securely
if (session_status() == PHP_SESSION_NONE) {
    // Set session cookie parameters BEFORE starting session
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '', // your domain
        'secure' => false, // set to true if using HTTPS
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    session_start();
}

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "apexplanet_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Session timeout
$timeout = 1800; // 30 minutes
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_destroy();
}
$_SESSION['last_activity'] = time();
?>
