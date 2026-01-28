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

/* Fetch show */
$stmt = $pdo->prepare("SELECT * FROM shows WHERE show_id = ?");
$stmt->execute([$show_id]);
$show = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$show) {
    die("Show not found");
}

/* Update */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $date  = $_POST['show_date'];
    $time  = $_POST['show_time'];
    $price = $_POST['price'];
    $seats = $_POST['available_seats'];

    $stmt = $pdo->prepare(
        "UPDATE shows 
         SET show_date=?, show_time=?, price=?, available_seats=?
         WHERE show_id=?"
    );
    $stmt->execute([$date, $time, $price, $seats, $show_id]);

    header("Location: shows_list.php");
    exit;
}
?>

<h2>Edit Show</h2>

<a href="shows_list.php">← Back</a>
<br><br>

<form method="POST">

    <label>Date</label><br>
    <input type="date" name="show_date" value="<?= $show['show_date'] ?>" required>
    <br><br>

    <label>Time</label><br>
    <input type="time" name="show_time" value="<?= $show['show_time'] ?>" required>
    <br><br>

    <label>Price</label><br>
    <input type="number" step="0.01" name="price" value="<?= $show['price'] ?>" required>
    <br><br>

    <label>Available Seats</label><br>
    <input type="number" name="available_seats" value="<?= $show['available_seats'] ?>" required>
    <br><br>

    <button type="submit">Update Show</button>

</form>
