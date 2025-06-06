<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$now = date('Y-m-d H:i:s');

// Выполненные задачи
$done_stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? AND completed = 1 ORDER BY due_date DESC");
$done_stmt->execute([$user_id]);
$done_tasks = $done_stmt->fetchAll();

// Просроченные задачи (не выполнены и срок истёк)
$overdue_stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? AND completed = 0 AND due_date < ? ORDER BY due_date DESC");
$overdue_stmt->execute([$user_id, $now]);
$overdue_tasks = $overdue_stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Архив задач</title>
    <!-- <meta name="viewport"content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"> -->
    <link rel="stylesheet" href="css/style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 32px;
        }
        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #eaeaea;
        }
        th {
            background: #f1f1f3;
        }
        a.delete-btn {
            background-color: #ff3b30;
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            transition: background-color 0.2s ease;
        }
        a.delete-btn:hover {
            background-color: #e22b24;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 18px;
            background: #f1f1f3;
            color: #0a84ff;
            text-decoration: none;
            border-radius: 12px;
            font-size: 16px;
            margin-bottom: 24px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
        }
        .back-link:hover {
            background: #e5e5ea;
        }
    </style>
</head>
<body>
    <div class="container">
         <?php if (basename($_SERVER['PHP_SELF']) != 'index.php'): ?>
            <a href="index.php" style="
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 10px 18px;
                background: transparent;
                color: #fff;
                border: 1px solid rgba(255, 255, 255, 0.5);
                text-decoration: none;
                border-radius: 5px;
                font-size: 14px;
                box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
                transition: all 0.2s ease-in-out;
                margin: 16px 0;
            " onmouseover="this.style.background='#35393d'" onmouseout="this.style.background='transparent'">
                ← Назад
            </a>
        <?php endif; ?>

        <h2>Архив задач</h2>

        <h3>Выполненные задачи</h3>
        <?php if (count($done_tasks)): ?>
            <table>
                <tr>
                    <th>Задача</th>
                    <th>Описание</th>
                    <th>Дата</th>
                    <th>Удалить</th>
                </tr>
                <?php foreach ($done_tasks as $task): ?>
                    <tr>
                        <td><?= htmlspecialchars($task['title']) ?></td>
                        <td><?= htmlspecialchars($task['description']) ?></td>
                        <td><?= htmlspecialchars($task['due_date']) ?></td>
                        <td>
                            <a class="delete-btn" href="delete.php?id=<?= $task['id'] ?>" onclick="return confirm('Удалить задачу?')">Удалить</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Нет выполненных задач.</p>
        <?php endif; ?>

        <h3>В работе / Просроченные задачи</h3>
        <?php if (count($overdue_tasks)): ?>
            <table>
                <tr>
                    <th>Задача</th>
                    <th>Описание</th>
                    <th>Дата</th>
                    <th>Удалить</th>
                </tr>
                <?php foreach ($overdue_tasks as $task): ?>
                    <tr>
                        <td><?= htmlspecialchars($task['title']) ?></td>
                        <td><?= htmlspecialchars($task['description']) ?></td>
                        <td><?= htmlspecialchars($task['due_date']) ?></td>
                        <td>
                            <a class="delete-btn" href="delete.php?id=<?= $task['id'] ?>" onclick="return confirm('Удалить задачу?')">Удалить</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Нет просроченных задач.</p>
        <?php endif; ?>
    </div>
    <div class="video">
        <video id="nubexVideo" width="100%" height="auto" loop="" muted="" autoplay="autoplay" playsinline=""> 
            <source src="media/stars.mp4"></source>
        </video>
    </div>
</body>
</html>
