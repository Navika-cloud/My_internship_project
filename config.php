<?php
$host = "localhost";
$user = "root";   // default in XAMPP
$pass = "";       // leave empty in XAMPP
$db   = "apexplanet_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
