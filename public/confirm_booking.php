<?php
session_start();
require "../config/db.php";

$user_id = $_SESSION['user_id'];
$show_id = $_POST['show_id'];
$seats   = $_POST['seats'];

/* Check seat availability */
$stmt = $pdo->prepare("SELECT available_seats FROM shows WHERE show_id = ?");
$stmt->execute([$show_id]);
$show = $stmt->fetch();

if ($seats > $show['available_seats']) {
    die("Not enough seats available");
}

/* Insert booking */
$stmt = $pdo->prepare(
    "INSERT INTO bookings (user_id, show_id, seats)
     VALUES (?, ?, ?)"
);
$stmt->execute([$user_id, $show_id, $seats]);

/* Reduce seats */
$stmt = $pdo->prepare(
    "UPDATE shows 
     SET available_seats = available_seats - ?
     WHERE show_id = ?"
);
$stmt->execute([$seats, $show_id]);

echo "Booking successful!";
