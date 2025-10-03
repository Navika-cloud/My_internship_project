<?php
session_start();
include "config.php";

// Force login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Validate post ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("Invalid post ID.");
}

// Fetch post to check ownership
$stmt = $conn->prepare("SELECT user_id FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();

if (!$post) {
    die("Post not found.");
}

// Check permissions (admin or owner)
if ($role !== 'admin' && $post['user_id'] != $user_id) {
    die("⛔ You are not allowed to delete this post.");
}

// Delete post
$stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: index.php?msg=Post+deleted+successfully");
    exit;
} else {
    echo "Error deleting post: " . $conn->error;
    $stmt->close();
}
?>
