<?php
require "../config/db.php";

header("Content-Type: application/json");

if (!isset($_GET['q'])) {
    echo json_encode([]);
    exit;
}

$q = trim($_GET['q']);

if (strlen($q) < 2) {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare(
    "SELECT title FROM movies WHERE title LIKE ? LIMIT 5"
);
$stmt->execute(["%$q%"]);

$results = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo json_encode($results);
