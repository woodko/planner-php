<?php
require 'session.php';
require 'db.php';

$title = $_POST['title'];
$desc = $_POST['description'];
$due = $_POST['due_date'];

$stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, description, due_date) VALUES (?, ?, ?, ?)");
$stmt->execute([$_SESSION['user_id'], $title, $desc, $due]);

header("Location: index.php");
