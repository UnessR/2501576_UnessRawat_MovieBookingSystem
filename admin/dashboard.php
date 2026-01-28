<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}

$stmt = $pdo->prepare("SELECT * FROM movies");
$stmt->execute();
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        img { width: 80px; }
        a { margin: 0 5px; }
    </style>
</head>
<body>

<h2>Admin Dashboard</h2>

<p>
    <a href="add_movie.php">➕ Add New Movie</a> |
    <a href="../public/index.php">🎬 View Site</a>
    <a href="add_show.php">Add Show (Date & Time)</a>
    <a href="show_lists.php">Manage Shows</a>
</p>

<table>
    <tr>
        <th>Poster</th>
        <th>Title</th>
        <th>Genre</th>
        <th>Rating</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($movies as $movie): ?>
    <tr>
        <td>
            <img src="../assets/images/posters/<?= htmlspecialchars($movie['poster']) ?>">
        </td>
        <td><?= htmlspecialchars($movie['title']) ?></td>
        <td><?= htmlspecialchars($movie['genre']) ?></td>
        <td><?= htmlspecialchars($movie['rating']) ?></td>
        <td>
            <a href="edit_movie.php?id=<?= $movie['movie_id'] ?>">✏️ Edit</a>
            |
            <a href="delete_movie.php?id=<?= $movie['movie_id'] ?>"
               onclick="return confirm('Are you sure you want to delete this movie?')">
               🗑 Delete
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>

