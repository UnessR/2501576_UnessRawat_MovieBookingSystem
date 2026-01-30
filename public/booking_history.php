<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare(
    "SELECT 
    s.show_id,
    m.title,
    s.show_date,
    s.show_time,
    GROUP_CONCAT(b.seat_no ORDER BY b.seat_no SEPARATOR ', ') AS seats
FROM booked_seats b
JOIN shows s ON b.show_id = s.show_id
JOIN movies m ON s.movie_id = m.movie_id
WHERE b.user_id = ?
GROUP BY s.show_id
ORDER BY s.show_date DESC, s.show_time DESC"
);
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
</head>
<body>

<h2>🎟 My Booking History</h2>

<a href="index.php">← Back to Home</a>
<br><br>

<?php if (empty($bookings)): ?>
    <p>No bookings found.</p>
<?php else: ?>

<table border="1" cellpadding="8">
    <tr>
        <th>Movie</th>
        <th>Date</th>
        <th>Time</th>
        <th>Seats</th>
        <th>Action</th>
    </tr>

    <?php foreach ($bookings as $booking): ?>
<tr>
    <td><?= htmlspecialchars($booking['title']) ?></td>
    <td><?= $booking['show_date'] ?></td>
    <td><?= $booking['show_time'] ?></td>
    <td><?= $booking['seats'] ?></td>

    <td>
        <form method="POST" action="cancel_booking.php"
              onsubmit="return confirm('Cancel this booking?');">

            <input type="hidden" name="show_id"
                   value="<?= $booking['show_id'] ?>">

            <input type="hidden" name="csrf_token"
                   value="<?= $_SESSION['csrf_token'] ?>">

            <button class="btn-cancel">Cancel</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>

</table>

<?php endif; ?>

</body>
</html>
