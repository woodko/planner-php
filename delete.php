<?php
require_once 'config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Неверный ID.');
}

$id = (int)$_GET['id'];

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
    $stmt->execute(['id' => $id]);

    // ⬅ Возврат на страницу, с которой пришёл пользователь
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
} catch (PDOException $e) {
    die("Ошибка удаления: " . $e->getMessage());
}
