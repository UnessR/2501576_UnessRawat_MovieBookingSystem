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
<script>
document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("searchInput");
    const box = document.getElementById("suggestions");

    if (!input || !box) {
        console.error("Search input or suggestions box not found");
        return;
    }

    input.addEventListener("keyup", async () => {
        const query = input.value.trim();

        if (query.length < 2) {
            box.innerHTML = "";
            return;
        }

        const response = await fetch("ajax_search.php?q=" + encodeURIComponent(query));
        const results = await response.json();

        box.innerHTML = "";

        results.forEach(title => {
            const div = document.createElement("div");
            div.textContent = title;
            div.onclick = () => {
                input.value = title;
                box.innerHTML = "";
            };
            box.appendChild(div);
        });
    });
});
</script>


<body>

<header>
    <h1>🎬 Movie Booking System</h1>
    <div style="margin-top:10px;">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="booking_history.php">
                <button>🎟 My Bookings</button>
            </a>

            <a href="logout.php">
                <button>Logout</button>
            </a>
        <?php else: ?>
            <a href="login.php">
                <button>Login</button>
            </a>
        <?php endif; ?>
    </div>
</header>

<form class="search-box" method="GET" action="search.php">
    <input
    type="text"
    name="q"
    id="searchInput"
    placeholder="Search movies..."
    autocomplete="off"
    required
>
<div id="suggestions" class="suggestions-box"></div>

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


