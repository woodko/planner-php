<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
    } else {
        $error = "Неверный логин или пароль";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Вход</title></head>
<meta name="viewport"content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="css/style.css">
<body>
    <div class="container">
        <h2>Вход</h2>
        <form method="POST">
            <input name="username" type="text" placeholder="Имя пользователя" required><br>
            <input name="password" type="password" placeholder="Пароль" required><br>
            <button type="submit">Войти</button>
        </form>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
        <a href="register.php">Регистрация</a> | 
        <a href="reset_request.php">Забыли пароль?</a>
    </div>
    <div class="video">
        <video id="nubexVideo" loop="" muted="" autoplay="autoplay" playsinline=""> 
            <source src="media/wat.mp4"></source>
        </video>
    </div>
</body>
</html>
