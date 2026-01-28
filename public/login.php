<?php
session_start();
require "../config/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];

        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body { font-family: Arial; background:#f4f4f4; }
        .box {
            width: 350px;
            margin: 80px auto;
            padding: 20px;
            background: white;
            border-radius: 6px;
            box-shadow: 0 0 10px rgba(0,0,0,.1);
        }
        input { width: 100%; padding: 10px; margin: 8px 0; }
        button { width: 100%; padding: 10px; background: #333; color: white; border: none; }
        .error { color: red; text-align: center; }
        .link { text-align: center; margin-top: 10px; }
    </style>
</head>
<body>

<div class="box">
    <h2>Login</h2>
    <p>Please enter your username and password</p>

    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Enter your username" required>
        <input type="password" name="password" placeholder="Enter your password" required>
        <button type="submit">Login</button>
    </form>

    <div class="link">
        <p>Don’t have an account? <a href="register.php">Register here</a></p>
    </div>
</div>

</body>
</html>
