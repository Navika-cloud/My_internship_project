<?php
header('Content-Type: application/json');
session_start();
$conn = new mysqli("localhost", "root", "", "apexplanet_db");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'DB connection failed']);
    exit;
}
$id = intval($_POST['id'] ?? 0);
if ($id > 0) {
    $conn->query("UPDATE posts SET likes = likes + 1 WHERE id = $id");
    $res = $conn->query("SELECT likes FROM posts WHERE id = $id");
    $row = $res->fetch_assoc();
    echo json_encode(['success' => true, 'likes' => $row['likes']]);
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid ID']);
}