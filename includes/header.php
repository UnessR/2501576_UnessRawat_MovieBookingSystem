<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header style="padding:10px; background:#eee;">
    <a href="index.php">Home</a>

    <?php if (isset($_SESSION['user_id'])): ?>

        <?php if ($_SESSION['role'] === 'admin'): ?>
            | <a href="../admin/dashboard.php">Admin Dashboard</a>
        <?php endif; ?>

        | <a href="logout.php">Logout</a>

    <?php else: ?>

        | <a href="login.php">Login</a>

    <?php endif; ?>
</header>
<hr>
