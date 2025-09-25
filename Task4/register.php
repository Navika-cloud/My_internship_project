<?php
include "config.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRF check
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("⚠️ Invalid CSRF token");
    }

    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $role = "user"; // All new users are normal users

    if (empty($username) || empty($password)) {
        $error = "⚠️ All fields are required!";
    } elseif (strlen($username) < 4) {
        $error = "⚠️ Username must be at least 4 characters.";
    } elseif (strlen($password) < 6) {
        $error = "⚠️ Password must be at least 6 characters.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "⚠️ Username already exists.";
        } else {
            $stmt->close();
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashedPassword, $role);
            if ($stmt->execute()) {
                header("Location: login.php");
                exit;
            } else {
                $error = "❌ Error: " . $conn->error;
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Register</h2>
    <?php if ($error) echo "<div class='error-message'>$error</div>"; ?>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <label>Username</label>
        <input type="text" name="username" required minlength="4">
        <label>Password</label>
        <input type="password" name="password" required minlength="6">
        <button type="submit">Register</button>
    </form>
</div>
</body>
</html>
