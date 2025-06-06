<?php
require 'session.php';
require 'db.php';

$title = $_POST['title'];
$desc = $_POST['description'];
$due = $_POST['due_date'];
$deadline = !empty($_POST['deadline']) ? $_POST['deadline'] : null;

$stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, description, due_date, deadline) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$user_id, $title, $description, $due_date, $deadline]);

header("Location: index.php");
