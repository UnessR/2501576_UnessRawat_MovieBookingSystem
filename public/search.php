<?php
require "../config/db.php";

$query = $_GET['q'] ?? '';
$query = trim($query);

$stmt = $pdo->prepare(
    "SELECT * FROM movies 
     WHERE title LIKE ? OR genre LIKE ?"
);

$searchTerm = "%$query%";
$stmt->execute([$searchTerm, $searchTerm]);

$movies = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header>
  <h1>Search Results</h1>
</header>

<form class="search-box" method="GET" action="search.php">
    <input type="text" name="q" value="<?= htmlspecialchars($query) ?>" required>
    <button type="submit">Search</button>
</form>

<div class="movie-container">

<?php if ($movies): ?>
  <?php foreach ($movies as $movie): ?>
    <div class="movie-card">

      <img 
        src="../assets/images/posters/<?= htmlspecialchars($movie['poster']) ?>"
        class="poster"
      >

      <div class="movie-details">
        <h3><?= htmlspecialchars($movie['title']) ?></h3>
        <p>Genre: <?= htmlspecialchars($movie['genre']) ?></p>
        <p>Duration: <?= htmlspecialchars($movie['duration']) ?> mins</p>

        <a href="#" class="btn">Book Now</a>
      </div>

    </div>
  <?php endforeach; ?>
<?php else: ?>
  <p style="text-align:center;">No movies found.</p>
<?php endif; ?>

</div>

</body>
</html>
