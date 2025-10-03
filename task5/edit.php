<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) die("Invalid post ID");

// Fetch post
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();

if (!$post) die("Post not found");

// Check permissions
if ($role !== 'admin' && $post['user_id'] != $user_id) die("⛔ Not allowed");

// Handle update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) die("⚠️ Invalid CSRF token");
    $title = trim($_POST["post_title"]);
    $content = trim($_POST["post_content"]);

    if ($title && $content) {
        $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
        $stmt->bind_param("ssi", $title, $content, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: index.php");
        exit;
    } else echo "⚠️ Title and content cannot be empty.";
}
?>
