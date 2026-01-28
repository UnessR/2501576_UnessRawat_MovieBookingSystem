<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}

if (!isset($_GET['id'])) {
    die("Show ID missing");
}

$show_id = $_GET['id'];

/* Delete booked seats first (important) */
$stmt = $pdo->prepare("DELETE FROM booked_seats WHERE show_id = ?");
$stmt->execute([$show_id]);

/* Delete show */
$stmt = $pdo->prepare("DELETE FROM shows WHERE show_id = ?");
$stmt->execute([$show_id]);

header("Location: shows_list.php");
exit;
