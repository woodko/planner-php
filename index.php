<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? AND completed = 0 ORDER BY due_date");
$stmt->execute([$user_id]);
$tasks = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мои задачи</title>
    <meta name="viewport"content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <header>
        <h1>📋 Мои задачи</h1>
        <nav>
            <a href="profile.php">👤 Профиль</a> |
            <a href="archive.php">📁 Архив</a> |
            <a href="logout.php">🚪 Выйти</a>
        </nav>
    </header>

    <form method="post" action="add_task.php">
        <h2>Добавить задачу</h2>
        <input type="text" name="title" placeholder="Название" required>
        <textarea name="description" placeholder="Описание"></textarea>
        <input type="datetime-local" name="due_date" required>
        <button type="submit">Добавить</button>
    </form>

    <h2>📌 Активные задачи</h2>
    <?php if (count($tasks)): ?>
        <table>
            <tr>
                <th>Название</th>
                <th>Описание</th>
                <th>Срок</th>
                <th>Действия</th>
            </tr>
            <?php foreach ($tasks as $task): ?>
                <tr>
                    <td><?php echo htmlspecialchars($task['title']); ?></td>
                    <td><?php echo htmlspecialchars($task['description']); ?></td>
                    <td><?php echo $task['due_date']; ?></td>
                    <td>
                        <a href="complete.php?id=<?php echo $task['id']; ?>">✅ Выполнить</a> |
                        <a href="delete.php?id=<?php echo $task['id']; ?>" onclick="return confirm('Удалить задачу?');">🗑 Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Нет активных задач.</p>
    <?php endif; ?>
</div>
</body>
</html>
