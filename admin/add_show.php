<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}

$stmt = $pdo->query("SELECT movie_id, title FROM movies");
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $movie_id = $_POST['movie_id'];
    $date     = $_POST['show_date'];
    $time     = $_POST['show_time'];
    $price    = $_POST['price'];
    $seats    = $_POST['available_seats'];

    $stmt = $pdo->prepare(
        "INSERT INTO shows (movie_id, show_date, show_time, price, available_seats)
         VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->execute([$movie_id, $date, $time, $price, $seats]);

    $success = "Show added successfully";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Show</title>
</head>
<a href="dashboard.php">← Back to Dashboard</a>
<br><br>

<body>

<h2>Add Movie Show</h2>

<?php if (!empty($success)): ?>
    <p style="color:green;"><?= $success ?></p>
<?php endif; ?>

<form method="POST">

    <label>Movie</label><br>
    <select name="movie_id" required>
        <option value="">Select Movie</option>
        <?php foreach ($movies as $movie): ?>
            <option value="<?= $movie['movie_id'] ?>">
                <?= htmlspecialchars($movie['title']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <label>Show Date</label><br>
    <input type="date" name="show_date" required>
    <br><br>

    <label>Show Time</label><br>
    <input type="time" name="show_time" required>
    <br><br>

    <label>Ticket Price</label><br>
    <input type="number" name="price" step="0.01" required>
    <br><br>

    <label>Available Seats</label><br>
    <input type="number" name="available_seats" required>
    <br><br>

    <button type="submit">Add Show</button>

</form>

</body>
</html>
