<?php
require 'db.php';
require 'telegram.php';

$now = new DateTime();
$window = clone $now;
$window->modify('+30 seconds');

$stmt = $pdo->prepare("SELECT t.*, u.telegram_chat_id FROM tasks t
                      JOIN users u ON t.user_id = u.id
                      WHERE t.due_date <= ? AND t.completed = 0 AND t.notification_sent = 0");
$stmt->execute([$window->format('Y-m-d H:i:s')]);

$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($tasks as $task) {
    if (!$task['telegram_chat_id']) continue;

    $msg = "🔔 <b>{$task['title']}</b>\n\n"
         . "📝 {$task['description']}\n\n"
         . "⏰ Срок: {$task['due_date']}";

    sendTelegram($task['telegram_chat_id'], $msg);

    $update = $pdo->prepare("UPDATE tasks SET notification_sent = 1 WHERE id = ?");
    $update->execute([$task['id']]);
}
