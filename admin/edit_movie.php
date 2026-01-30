<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}


if (!isset($_GET['id'])) {
    die("Movie ID missing");
}

$movie_id = $_GET['id'];


$stmt = $pdo->prepare("SELECT * FROM movies WHERE movie_id = ?");
$stmt->execute([$movie_id]);
$movie = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$movie) {
    die("Movie not found");
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title       = $_POST['title'];
    $genre       = $_POST['genre'];
    $duration    = $_POST['duration'];
    $rating      = $_POST['rating'];
    $description = $_POST['description'];

    /* Keep old poster by default */
    $posterName = $movie['poster'];

    if (!empty($_FILES['poster']['name'])) {

        $allowedTypes = ['image/jpeg', 'image/png'];
        $fileType = mime_content_type($_FILES['poster']['tmp_name']);

        if (!in_array($fileType, $allowedTypes)) {
            die("Only JPG and PNG images allowed");
        }

        if ($_FILES['poster']['size'] > 2 * 1024 * 1024) {
            die("Image must be under 2MB");
        }

        /* Delete old image */
        $oldImagePath = "../assets/images/posters/" . $movie['poster'];
        if (file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }

        /* Upload new image */
        $extension = pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION);
        $posterName = uniqid("poster_", true) . "." . $extension;
        $uploadPath = "../assets/images/posters/" . $posterName;

        move_uploaded_file($_FILES['poster']['tmp_name'], $uploadPath);
    }

    /* Update database */
    $stmt = $pdo->prepare(
        "UPDATE movies 
         SET title=?, genre=?, duration=?, rating=?, description=?, poster=?
         WHERE movie_id=?"
    );

    $stmt->execute([
        $title,
        $genre,
        $duration,
        $rating,
        $description,
        $posterName,
        $movie_id
    ]);

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Movie</title>
    <style>
        body { font-family: Arial; background:#f4f4f4; }
        .box {
            width: 400px;
            margin: 40px auto;
            padding: 20px;
            background: white;
            border-radius: 6px;
        }
        input, textarea { width: 100%; padding: 8px; margin: 6px 0; }
        img { width: 120px; margin-bottom: 10px; }
        button { padding: 10px; width: 100%; }
    </style>
</head>
<body>

<div class="box">
    <h2>Edit Movie</h2>

    <p><strong>Current Poster:</strong></p>
    <img src="../assets/images/posters/<?= htmlspecialchars($movie['poster']) ?>">

    <form method="POST" enctype="multipart/form-data">

        <label>Title</label>
        <input type="text" name="title" value="<?= htmlspecialchars($movie['title']) ?>" required>

        <label>Genre</label>
        <input type="text" name="genre" value="<?= htmlspecialchars($movie['genre']) ?>" required>

        <label>Duration (minutes)</label>
        <input type="number" name="duration" value="<?= htmlspecialchars($movie['duration']) ?>" required>

        <label>Rating</label>
        <input type="text" name="rating" value="<?= htmlspecialchars($movie['rating']) ?>" required>

        <label>Description</label>
        <textarea name="description" required><?= htmlspecialchars($movie['description']) ?></textarea>

        <label>Replace Poster (optional)</label>
        <input type="file" name="poster" accept="image/jpeg,image/png">
        <small>Leave empty to keep current poster</small>

        <br><br>
        <button type="submit">Update Movie</button>
    </form>
</div>

</body>
</html>

