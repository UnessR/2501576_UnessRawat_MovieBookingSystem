<?php
session_start();

if (
    !isset($_POST['csrf_token']) ||
    $_POST['csrf_token'] !== $_SESSION['csrf_token']
) {
    die("Invalid CSRF token");
}

require "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    die("Login required");
}

$user_id = $_SESSION['user_id'];
$show_id = $_POST['show_id'];
$seats   = explode(',', $_POST['seats']);

if (empty($seats[0])) {
    die("No seats selected");
}

/* Check for already booked seats */
$stmt = $pdo->prepare(
    "SELECT seat_no FROM booked_seats 
     WHERE show_id = ? AND seat_no IN (" .
     implode(',', array_fill(0, count($seats), '?')) . ")"
);

$params = array_merge([$show_id], $seats);
$stmt->execute($params);

if ($stmt->rowCount() > 0) {
    die("One or more selected seats are already booked");
}

/* Save seats */
$stmt = $pdo->prepare(
    "INSERT INTO booked_seats (show_id, seat_no, user_id)
     VALUES (?, ?, ?)"
);

foreach ($seats as $seat) {
    $stmt->execute([$show_id, $seat, $user_id]);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmed</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="text-align:center; padding:40px;">

    <h2>✅ Booking Successful!</h2>
    <p>Your seats have been booked successfully.</p>

    <br>

    <a href="index.php">
        <button>🏠 Return to Homepage</button>
    </a>

    <a href="booking_history.php">
        <button>📄 View Booking History</button>
    </a>

</body>
</html>

