<?php
session_start();
include "config.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'msg' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

$id = intval($_POST['id'] ?? 0);
$csrf = $_POST['csrf_token'] ?? '';

if (!$id || $csrf !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'msg' => 'Invalid request']);
    exit;
}

// Check ownership or admin
$stmt = $conn->prepare("SELECT user_id FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();

if (!$post) {
    echo json_encode(['success' => false, 'msg' => 'Post not found']);
    exit;
}

if ($role !== 'admin' && $post['user_id'] != $user_id) {
    echo json_encode(['success' => false, 'msg' => 'Not authorized']);
    exit;
}

// Delete post
$stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'msg' => 'Post deleted']);
} else {
    echo json_encode(['success' => false, 'msg' => 'Failed to delete']);
}
$stmt->close();
$conn->close();
?>
