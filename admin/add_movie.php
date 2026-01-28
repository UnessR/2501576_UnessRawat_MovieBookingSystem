<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require "../config/db.php";

/*
 OPTIONAL (but recommended):
 Only admin can add movies
 Uncomment if you already have login & roles

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Invalid CSRF token");
}
    $title       = $_POST['title'];
    $genre       = $_POST['genre'];
    $duration    = $_POST['duration'];
    $rating      = $_POST['rating'];
    $description = $_POST['description'];

    if (!isset($_FILES['poster']) || $_FILES['poster']['error'] !== 0) {
        die("Poster image is required");
    }

    $allowedTypes = ['image/jpeg', 'image/png'];
    $fileType = mime_content_type($_FILES['poster']['tmp_name']);

    if (!in_array($fileType, $allowedTypes)) {
        die("Only JPG and PNG images are allowed");
    }

    if ($_FILES['poster']['size'] > 2 * 1024 * 1024) {
        die("Image size must be less than 2MB");
    }

    // ----------------------------
    // 3. Upload image
    // ----------------------------
    $extension = pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION);
    $imageName = uniqid("poster_", true) . "." . $extension;

    $uploadPath = "../assets/images/posters/" . $imageName;

    if (!move_uploaded_file($_FILES['poster']['tmp_name'], $uploadPath)) {
        die("Failed to upload image");
    }

    $stmt = $pdo->prepare(
        "INSERT INTO movies (title, genre, duration, rating, description, poster)
         VALUES (?, ?, ?, ?, ?, ?)"
    );

    $stmt->execute([
        $title,
        $genre,
        $duration,
        $rating,
        $description,
        $imageName
    ]);

    header("Location: ../public/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Movie</title>
</head>
<body>

<h2>Add Movie</h2>

<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

    <label>Movie Title</label><br>
    <input type="text" name="title" required><br><br>

    <label>Genre</label><br>
    <input type="text" name="genre" required><br><br>

    <label>Duration (minutes)</label><br>
    <input type="number" name="duration" required><br><br>

    <label>Rating</label><br>
    <input type="text" name="rating" required><br><br>

    <label>Description</label><br>
    <textarea name="description" required></textarea><br><br>

    <label>Movie Poster</label><br>
    <input type="file" name="poster" accept="image/jpeg,image/png" required><br>
    <small>JPG or PNG | Max 2MB | Recommended 300×450</small><br><br>

    <button type="submit">Add Movie</button>
</form>

</body>
</html>

