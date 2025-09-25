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
    $role = $_POST["role"] ?? "user";

    if (empty($username) || empty($password)) {
        $error = "⚠️ All fields are required!";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ? AND role = ?");
        $stmt->bind_param("ss", $username, $role);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user["password"])) {
                session_regenerate_id(true); // Secure session
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["role"] = $user["role"];
                header("Location: index.php");
                exit;
            } else $error = "❌ Invalid password!";
        } else $error = "❌ User not found!";
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <?php if ($error) echo "<div class='error-message'>$error</div>"; ?>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <label>Username</label>
        <input type="text" name="username" required>
        <label>Password</label>
        <input type="password" name="password" required>
        <label>Login As</label>
        <select name="role" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>
