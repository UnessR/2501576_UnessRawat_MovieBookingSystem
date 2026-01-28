<?php
session_start();
require "../config/db.php";

/* Admin only */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}

$stmt = $pdo->query(
    "SELECT s.*, m.title 
     FROM shows s
     JOIN movies m ON s.movie_id = m.movie_id
     ORDER BY show_date, show_time"
);
$shows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Manage Shows</h2>

<a href="dashboard.php">← Back to Dashboard</a>
<br><br>

<table border="1" cellpadding="8">
    <tr>
        <th>Movie</th>
        <th>Date</th>
        <th>Time</th>
        <th>Price</th>
        <th>Seats</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($shows as $show): ?>
    <tr>
        <td><?= htmlspecialchars($show['title']) ?></td>
        <td><?= $show['show_date'] ?></td>
        <td><?= $show['show_time'] ?></td>
        <td>₹<?= $show['price'] ?></td>
        <td><?= $show['available_seats'] ?></td>
        <td>
            <a href="edit_show.php?id=<?= $show['show_id'] ?>">Edit</a> |
            <a href="delete_show.php?id=<?= $show['show_id'] ?>"
               onclick="return confirm('Delete this show?')">
               Delete
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
