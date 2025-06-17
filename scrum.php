<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY due_date ASC");
$stmt->execute([$user_id]);
$tasks = $stmt->fetchAll();

$columns = [
    'todo' => [],
    'in_progress' => [],
    'done' => [],
];

foreach ($tasks as $task) {
    $status = $task['status'] ?? 'todo';
    if (!isset($columns[$status])) $status = 'todo';
    $columns[$status][] = $task;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Scrum доска</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body { background: #f5f5f5; font-family: sans-serif; }
        .scrum-board { display: flex; gap: 20px; margin: 20px; }
        .scrum-column { flex: 1; background: #fff; border-radius: 10px; padding: 10px; box-shadow: 0 0 6px rgba(0,0,0,0.1); }
        .scrum-column h3 { text-align: center; margin-bottom: 10px; }
        .tasks { min-height: 200px; }
        .task { display: flex; align-items: center; justify-content: space-between; background: #e1e4e8; margin-bottom: 10px; padding: 10px; border-radius: 6px; cursor: grab; }
        .task-title { flex: 1; margin-right: 8px; outline: none; }
        .task-actions a { color: #ff3b30; text-decoration: none; font-size: 1.2rem; }
        .task-actions a:hover { color: #d12f28; }
        .scrum-column.drag-over { background: #d0ebff; }
    </style>
    <script src="js/scrum.js" defer></script>
</head>
<body>
<header class="p-3 bg-dark text-white">
    <div class="container d-flex justify-content-between">
        <h1 class="h4">Scrum доска</h1>
        <nav>
            <a href="index.php" class="text-white me-3">Задачи</a>
            <a href="archive.php" class="text-white me-3">Архив</a>
            <a href="logout.php" class="text-white">Выйти</a>
        </nav>
    </div>
</header>

<div class="scrum-board">
    <?php foreach ([
        'todo' => 'Нужно сделать',
        'in_progress' => 'В процессе',
        'done' => 'Готово'
    ] as $status => $label): ?>
        <div class="scrum-column" data-status="<?= $status ?>">
            <h3><?= $label ?></h3>
            <div class="tasks">
                <?php foreach ($columns[$status] as $task): ?>
                    <div class="task" draggable="true" data-id="<?= $task['id'] ?>">
                        <div class="task-title" contenteditable="true"><?= htmlspecialchars($task['title']) ?></div>
                        <div class="task-actions">
                            <a href="delete.php?id=<?= $task['id'] ?>" onclick="return confirm('Удалить задачу?');">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
