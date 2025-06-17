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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous" defer></script>
    <script src="https://kit.fontawesome.com/259d69c0e0.js" crossorigin="anonymous" defer></script>
</head>
<body>
    <div class="preloader" id="preloader">
        <div class="preloader__spinner"></div>
        <div class="preloader__text">Загрузка...</div>
    </div>   
    <header>
        <nav>
            <a href="archive.php"> Архив</a>
            <a href="scrum.php"> Скрам-доска</a>
            <a href="profile.php">Профиль</a>
            <a href="logout.php"> Выйти</a>
        </nav>
    </header>
    <div class="navigation open">
        <div class="menu-toggle"></div>
        <ul class="list">
            <li class="list-item active" style="--color: #BED057">
                <a href="index.php">
                    <span class="icon"><i class="fa-solid fa-list"></i></span>
                    <span class="text">Задачи</span>
                </a>
            </li>
            <li class="list-item" style="--color: #D54A27">
                <a href="archive.php">
                    <span class="icon"><i class="fa-solid fa-box-archive"></i></span>
                    <span class="text">Архив</span>
                </a>
            </li>
            <li class="list-item" style="--color: #D54A27">
                <a href="scrum.php">
                    <span class="icon"><i class="fa-solid fa-box-archive"></i></span>
                    <span class="text">Скрам-доска</span>
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
        <form method="post" action="add_task.php">
            <h2>Добавить задачу</h2>
            <input type="text" name="title" placeholder="Название" required>
            <textarea name="description" placeholder="Описание"></textarea>
            <label for="datetime-local">Введите дату старта задачи</label>
            <input type="datetime-local" name="due_date" required>
            <label for="deadline">Дедлайн задачи</label>
            <input type="datetime-local" name="deadline">
            <button type="submit">Добавить</button>
        </form>
        <?php if (count($tasks)): ?>
            <h2>Активные задачи</h2>
            <?php foreach ($tasks as $task): ?>
                <div class="active-tasks row justify-content-center align-items-center">
                    <div class="active-task-title col-lg-6 active-task-item">
                        <span>Начать</span> - <?php echo $task['due_date']; ?><br>
                        <span>Закончить</span> - <?php echo $task['deadline']; ?>
                    </div>
                    <div class="active-task-desc col-lg-6 active-task-item">
                        <?php echo htmlspecialchars($task['title']); ?>
                    </div>
                    <!-- <div class="active-task-date col-lg-6 active-task-item">
                        <?php echo htmlspecialchars($task['description']); ?>
                    </div> -->
                    <div class="active-task-doin col-lg-12 active-task-item">
                        <a href="complete.php?id=<?php echo $task['id']; ?>">Выполнить</a>
                        <a href="delete.php?id=<?php echo $task['id']; ?>" onclick="return confirm('Удалить задачу?');">Удалить</a> 
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Нет активных задач</p>
        <?php endif; ?>
    </div>
    <div class="video">
        <video id="nubexVideo" loop="" muted="" autoplay="autoplay" playsinline="" preload="none"> 
            <source src="media/wat.mp4"></source>
        </video>
    </div>
    <script src="js/script.js"></script>
</body>
</html>
