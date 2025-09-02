<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

include "config.php";

// Fetch posts1 without join
$sql = "SELECT id, title, content FROM posts1 ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ApexPlanet - Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Welcome to ApexPlanet 🎉</h1>
    <h2>Hello, <?php echo htmlspecialchars($_SESSION["user"]); ?></h2>

    <p>
        <a href="create.php">➕ Add Post</a> | 
        <a href="logout.php">🚪 Logout</a>
    </p>

    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th><th>Title</th><th>Content</th><th>Actions</th>
        </tr>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row["id"]) ?></td>
                <td><?= htmlspecialchars($row["title"]) ?></td>
                <td><?= htmlspecialchars($row["content"]) ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>">✏️ Edit</a> | 
                    <a href="delete.php?id=<?= $row['id'] ?>">🗑 Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">No posts found.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>