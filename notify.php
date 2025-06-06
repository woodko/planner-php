<?php
require 'db.php';
require 'telegram.php';

$now = new DateTime();
$window = clone $now;
$window->modify('+30 seconds');
$nowStr = $now->format('Y-m-d H:i:s');
$windowStr = $window->format('Y-m-d H:i:s');

// Уведомление о старте задачи
$stmt = $pdo->prepare("SELECT t.*, u.telegram_chat_id FROM tasks t
                      JOIN users u ON t.user_id = u.id
                      WHERE t.due_date <= ? AND t.completed = 0 AND t.notification_sent = 0");
$stmt->execute([$windowStr]);

$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($tasks as $task) {
    if (!$task['telegram_chat_id']) continue;

    $msg = "🔔 <b>{$task['title']}</b>\n\n"
         . "📝 {$task['description']}\n\n"
         . "▶️ Начало задачи: {$task['due_date']}";

    sendTelegram($task['telegram_chat_id'], $msg);

    $update = $pdo->prepare("UPDATE tasks SET notification_sent = 1 WHERE id = ?");
    $update->execute([$task['id']]);
}

// Уведомление о дедлайне задачи
$deadlineStmt = $pdo->prepare("SELECT t.*, u.telegram_chat_id FROM tasks t
                      JOIN users u ON t.user_id = u.id
                      WHERE t.deadline IS NOT NULL AND t.deadline <= ? AND t.completed = 0 AND t.deadline_notified = 0");
$deadlineStmt->execute([$windowStr]);

$deadlineTasks = $deadlineStmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($deadlineTasks as $task) {
    if (!$task['telegram_chat_id']) continue;

    $msg = "⚠️ <b>{$task['title']}</b>\n\n"
         . "📝 {$task['description']}\n\n"
         . "📌 Дедлайн задачи: {$task['deadline']}";

    sendTelegram($task['telegram_chat_id'], $msg);

    $update = $pdo->prepare("UPDATE tasks SET deadline_notified = 1 WHERE id = ?");
    $update->execute([$task['id']]);
}
