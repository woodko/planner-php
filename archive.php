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
    <meta name="viewport"content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous" defer></script>
    <script src="https://kit.fontawesome.com/259d69c0e0.js" crossorigin="anonymous" defer></script>
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
            display: block;
            max-width: 300px;
            margin: 0 auto;
            background-color: #ff3b30;
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            transition: background-color 0.2s ease;
            margin: 30px auto 20px auto;
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
    <div class="preloader" id="preloader">
        <div class="preloader__spinner"></div>
        <div class="preloader__text">Загрузка...</div>
    </div>  
    <header>
        <nav>
            <a href="index.php">Задачи</a>
            <a href="archive.php"> Архив</a>
            <a href="profile.php">Профиль</a>
            <a href="logout.php"> Выйти</a>
        </nav>
    </header>
   <div class="navigation open">
        <div class="menu-toggle"></div>
        <ul class="list">
            <li class="list-item" style="--color: #BED057">
                <a href="index.php">
                    <span class="icon"><i class="fa-solid fa-list"></i></span>
                    <span class="text">Задачи</span>
                </a>
            </li>
            <li class="list-item active" style="--color: #D54A27">
                <a href="archive.php">
                    <span class="icon"><i class="fa-solid fa-box-archive"></i></span>
                    <span class="text">Архив</span>
                </a>
            </li>
            <li class="list-item" style="--color: #1AA313">
                <a href="profile.php">
                    <span class="icon"><i class="fa-solid fa-circle-user"></i></span>
                    <span class="text">Профиль</span>
                </a>
            </li>
            <li class="list-item" style="--color: #5437F5">
                <a href="logout.php">
                    <span class="icon"><i class="fa-solid fa-door-open"></i></span>
                    <span class="text">Выйти</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="container">
         <!-- <?php if (basename($_SERVER['PHP_SELF']) != 'index.php'): ?>
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
        <?php endif; ?> -->
        <h2>Архив задач</h2>
        <h3>В работе / Просроченные задачи</h3>
        <?php if (count($overdue_tasks)): ?>
             <?php foreach ($overdue_tasks as $task): ?>
                <div class="active-tasks row justify-content-center align-items-center">
                    <div class="active-task-title col-lg-6 active-task-item">
                        <span>Начало</span> - <?= htmlspecialchars($task['due_date']) ?><br>
                        <span>Дедлайн</span> - <?= htmlspecialchars($task['deadline']) ?>
                    </div>
                    <div class="active-task-title col-lg-6 active-task-item">
                        <?= htmlspecialchars($task['title']) ?>
                    </div>
                    <a class="delete-btn" href="delete.php?id=<?= $task['id'] ?>" onclick="return confirm('Удалить задачу?')">Удалить</a>
                </div>      
                <?php endforeach; ?>
        <?php else: ?>
            <p>Нет просроченных задач.</p>
        <?php endif; ?>
        <h3>Выполненные задачи</h3>
        <?php if (count($done_tasks)): ?>
                <?php foreach ($done_tasks as $task): ?>
                <div class="active-tasks row justify-content-center align-items-center">
                    <div class="active-task-title col-lg-6 active-task-item">
                        <?= htmlspecialchars($task['due_date']) ?>
                    </div>
                    <div class="active-task-title col-lg-6 active-task-item">
                        <?= htmlspecialchars($task['title']) ?>
                    </div>
                    <a class="delete-btn" href="delete.php?id=<?= $task['id'] ?>" onclick="return confirm('Удалить задачу?')">Удалить</a>
                </div>      
                <?php endforeach; ?>
        <?php else: ?>
            <p>Нет выполненных задач.</p>
        <?php endif; ?>
    </div>
    <div class="video">
        <video id="nubexVideo" width="100%" height="auto" loop="" muted="" autoplay="autoplay" playsinline=""> 
            <source src="media/wat.mp4"></source>
        </video>
    </div>
    <script src="js/script.js"></script>
</body>
</html>
