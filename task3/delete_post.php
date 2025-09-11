<?php
header('Content-Type: application/json');
session_start();
$conn = new mysqli("localhost", "root", "", "apexplanet_db"); // Use your DB name
$id = intval($_POST['id'] ?? 0);
if ($id > 0) {
    $conn->query("DELETE FROM posts WHERE id = $id");
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}