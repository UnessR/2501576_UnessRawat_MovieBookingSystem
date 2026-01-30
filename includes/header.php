<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Movie Booking System</title>

    <!-- MAIN STYLES -->
    <link rel="stylesheet" href="../assets/css/style.css?v=3">
</head>
<body>

<header class="site-header">
    <nav class="nav">
        <a href="index.php">Home</a>

        <?php if (isset($_SESSION['user_id'])): ?>

            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="../admin/dashboard.php">Admin Dashboard</a>
            <?php endif; ?>

            <a href="booking_history.php">My Bookings</a>
            <a href="logout.php">Logout</a>

        <?php else: ?>

            <a href="login.php">Login</a>

        <?php endif; ?>
    </nav>
</header>

