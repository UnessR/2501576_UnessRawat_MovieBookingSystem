<?php
require "../config/db.php";

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM movies WHERE movie_id=?");
$stmt->execute([$id]);

header("Location: ../public/index.php");
