<?php
include "config.php";
$id = $_GET["id"];
$result = $conn->query("SELECT * FROM posts1 WHERE id=$id");
$post = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title  = $_POST["title"];
    $content = $_POST["content"];

    $sql = "UPDATE posts1 SET title='$title', content='$content' WHERE id=$id";
    if ($conn->query($sql)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Post</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .create-form {
            background: #fff;
            padding: 40px 36px 32px 36px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(34,34,59,0.10);
            min-width: 340px;
            max-width: 380px;
            margin: 60px auto 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .create-form h2 {
            margin-bottom: 24px;
            color: #22223b;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .create-form input[type="text"] {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid #c9c9c9;
            border-radius: 8px;
            font-size: 16px;
            background: #f7f7fb;
            transition: border 0.2s;
            margin-bottom: 18px;
            box-sizing: border-box;
        }
        .create-form input[type="text"]:focus {
            border-color: #4f8cff;
            outline: none;
            background: #eef4ff;
        }
        .create-form button[type="submit"] {
            width: 100%;
            padding: 13px;
            background: linear-gradient(90deg, #4f8cff 0%, #4361ee 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 17px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 8px rgba(67,97,238,0.08);
        }
        .create-form button[type="submit"]:hover {
            background: linear-gradient(90deg, #4361ee 0%, #4f8cff 100%);
            box-shadow: 0 4px 16px rgba(67,97,238,0.13);
        }
    </style>
</head>
<body>
<form method="post" class="create-form">
    <h2>Edit Post</h2>
    <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required><br><br>
    <input type="text" name="content" value="<?= htmlspecialchars($post['content']) ?>" required><br><br>
    <button type="submit">Update</button>
</form>
</body>