<?php
require "../config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");

    try {
        $stmt->execute([$username, $password]);
        $message = "Registration successful. You can now login.";
    } catch (PDOException $e) {
        $message = "Username already exists.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
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
        button { width: 100%; padding: 10px; background: green; color: white; border: none; }
        .msg { text-align: center; margin-top: 10px; }
    </style>
</head>
<body>

<div class="box">
    <h2>Create Account</h2>
    <p>Register to book movie tickets</p>

    <form method="POST">
        <input type="text" name="username" placeholder="Choose a username" required>
        <input type="password" name="password" placeholder="Choose a password" required>
        <button type="submit">Register</button>
    </form>

    <div class="msg">
        <?= $message ?>
        <p><a href="login.php">Back to Login</a></p>
    </div>
</div>

</body>
</html>
