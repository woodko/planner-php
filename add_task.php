<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Ошибка: пользователь не авторизован.");
}

$user_id = $_SESSION['user_id'];

// Получаем данные из формы
$title = trim($_POST['title']);
$description = trim($_POST['description']);
$due_date = $_POST['due_date'];
$deadline = !empty($_POST['deadline']) ? $_POST['deadline'] : null;

// Проверка обязательных полей
if (!$title || !$due_date) {
    die("Ошибка: не все обязательные поля заполнены.");
}

// Выполняем запрос
$stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, description, due_date, deadline) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$user_id, $title, $description, $due_date, $deadline]);

header("Location: index.php");
exit;
?>
