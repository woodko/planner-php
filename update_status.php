<?php
require 'db.php';
session_start();
$data = json_decode(file_get_contents('php://input'), true);

// Ожидаем task_id и status
if (!isset($_SESSION['user_id'], $data['task_id'], $data['status'])) {
    http_response_code(400);
    echo json_encode(['error'=>'Invalid input']);
    exit;
}

$task_id = (int)$data['task_id'];
$status  = $data['status'];
$valid   = ['todo','in_progress','done'];
if (!in_array($status, $valid, true)) {
    http_response_code(400);
    echo json_encode(['error'=>'Invalid status']);
    exit;
}

// Если статус done — помечаем completed
$completed = $status==='done' ? 1 : 0;
$stmt = $pdo->prepare("UPDATE tasks SET status=?, completed=? WHERE id=? AND user_id=?");
$stmt->execute([$status, $completed, $task_id, $_SESSION['user_id']]);
echo json_encode(['success'=>true]);
