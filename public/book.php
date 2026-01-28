<?php
require "../config/db.php";

if (!isset($_GET['show_id'])) {
    die("Show not selected");
}

$show_id = $_GET['show_id'];

/* Fetch show + movie */
$stmt = $pdo->prepare(
    "SELECT s.*, m.title 
     FROM shows s
     JOIN movies m ON s.movie_id = m.movie_id
     WHERE s.show_id = ?"
);
$stmt->execute([$show_id]);
$show = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$show) {
    die("Show not found");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seat Booking</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header>
    <h1>🎟 Select Your Seats</h1>
    <h3><?= htmlspecialchars($show['title']) ?></h3>
    <p>
        Date: <?= $show['show_date'] ?> |
        Time: <?= $show['show_time'] ?>
    </p>
</header>

<div class="theatre">

    <div class="screen-label">SCREEN</div>
    <div class="screen"></div>

    <div class="seats">
        <?php
        // Rows A–M
        $rows = range('A', 'M');
        $cols = 14;

        // TEMP: booked seats (later load from DB using show_id)
        $bookedSeats = [];

        foreach ($rows as $row) {
            echo "<div class='row-label'>Row $row</div>";

            for ($i = 1; $i <= $cols; $i++) {
                $seatId = $row . $i;

                if (in_array($seatId, $bookedSeats)) {
                    echo "<div class='seat booked' data-seat='$seatId'></div>";
                } else {
                    echo "<div class='seat' data-seat='$seatId'></div>";
                }
            }
        }
        ?>
    </div>

    <div class="legend">
        <span><div class="box" style="background:#14b8a6"></div> Available</span>
        <span><div class="box" style="background:#facc15"></div> Selected</span>
        <span><div class="box" style="background:#ef4444"></div> Booked</span>
    </div>

</div>

<script>
/* Seat selection */
document.querySelectorAll('.seat:not(.booked)').forEach(seat => {
    seat.addEventListener('click', () => {
        seat.classList.toggle('selected');
    });
});
</script>

</body>
</html>
