<?php
require "../includes/header.php";
require "../config/db.php";

if (!isset($_GET['movie_id'])) {
    die("Movie not selected");
}

$movie_id = $_GET['movie_id'];

/* Fetch movie */
$stmt = $pdo->prepare("SELECT title FROM movies WHERE movie_id = ?");
$stmt->execute([$movie_id]);
$movie = $stmt->fetch();

/* Fetch shows */
$stmt = $pdo->prepare(
    "SELECT * FROM shows 
     WHERE movie_id = ? 
     ORDER BY show_date, show_time"
);
$stmt->execute([$movie_id]);
$shows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Shows for: <?= htmlspecialchars($movie['title']) ?></h2>
<a href="index.php" style="display:inline-block; margin-bottom:15px;">
    ← Back to Movies
</a>


<?php if (empty($shows)): ?>
    <p>No shows available.</p>
<?php else: ?>

<table border="1" cellpadding="8">
    <tr>
        <th>Date</th>
        <th>Time</th>
        <th>Price</th>
        <th>Seats</th>
        <th>Action</th>
    </tr>

    <?php foreach ($shows as $show): ?>
    <tr>
        <td><?= $show['show_date'] ?></td>
        <td><?= $show['show_time'] ?></td>
        <td>₹<?= $show['price'] ?></td>
        <td><?= $show['available_seats'] ?></td>
        <td>
            <a href="book.php?show_id=<?= $show['show_id'] ?>">
                Select Seats
            </a>
        </td>
    </tr>
    <?php endforeach; ?>

</table>

<?php endif; ?>
