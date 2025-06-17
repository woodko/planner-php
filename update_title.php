<?php
require 'db.php';
session_start();
$data = json_decode(file_get_contents('php://input'), true);

// Ожидаем task_id и title
if (!isset($_SESSION['user_id'], $data['task_id'], $data['title'])) {
    http_response_code(400);
    echo json_encode(['error'=>'Invalid input']);
    exit;
}

$task_id = (int)$data['task_id'];
$title   = trim($data['title']);
if ($title==='') {
    http_response_code(400);
    echo json_encode(['error'=>'Title cannot be empty']);
    exit;
}

$stmt = $pdo->prepare("UPDATE tasks SET title=? WHERE id=? AND user_id=?");
$stmt->execute([$title, $task_id, $_SESSION['user_id']]);
echo json_encode(['success'=>true]);
