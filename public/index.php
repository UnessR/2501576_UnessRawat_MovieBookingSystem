<?php
require "../includes/header.php";
require "../config/db.php";

$stmt = $pdo->prepare("SELECT * FROM movies");
$stmt->execute();
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Movie Booking System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header>
    <h1>🎬 Movie Booking System</h1>
</header>

<form class="search-box" method="GET" action="search.php">
    <input
        type="text"
        name="q"
        placeholder="Search movies by title or genre..."
        required
    >
    <button type="submit">Search</button>
</form>

<div class="movie-container">

<?php foreach ($movies as $movie): ?>

    <div class="movie-card">

        <div class="poster-wrapper">
            <img
                src="../assets/images/posters/<?= htmlspecialchars($movie['poster']) ?>"
                class="poster"
                alt="<?= htmlspecialchars($movie['title']) ?>"
            >

            <a href="shows.php?movie_id=<?= $movie['movie_id'] ?>" class="hover-book-btn">
                Book Movie
            </a>
        </div>

        <div class="movie-details">
            <h3><?= htmlspecialchars($movie['title']) ?></h3>
            <p>Genre: <?= htmlspecialchars($movie['genre']) ?></p>
            <p>Duration: <?= htmlspecialchars($movie['duration']) ?> mins</p>
        </div>

    </div>

<?php endforeach; ?>

</div>

</body>
</html>


