<?php
session_start();
require "../config/db.php";

if (!isset($_POST['csrf_token']) ||
    $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Invalid CSRF token");
}

if (!isset($_SESSION['user_id'])) {
    die("Login required");
}

$user_id = $_SESSION['user_id'];
$show_id = $_POST['show_id'];

$stmt = $pdo->prepare(
    "DELETE FROM booked_seats
     WHERE user_id = ? AND show_id = ?"
);
$stmt->execute([$user_id, $show_id]);

header("Location: booking_history.php");
exit;
