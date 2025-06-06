<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Проверяем, существует ли пользователь с таким username или email
    $checkStmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $checkStmt->execute([$username, $email]);
    if ($checkStmt->fetch()) {
        $error = "Пользователь с таким именем или email уже существует.";
    } else {
        // Если нет - вставляем нового пользователя
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $password]);
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Регистрация</title></head>
<link rel="stylesheet" href="css/style.css">
<body>
    <div class="container">
        <h2>Регистрация</h2>
        <form method="POST">
            <input name="username" type="text" placeholder="Имя пользователя" required><br>
            <input name="email" type="email" placeholder="Email" required><br>
            <input name="password" type="password" placeholder="Пароль" required><br><br><br>
            <button type="submit">Зарегистрироваться</button>
        </form>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <a href="login.php">Вход</a>
    </div>
    <div class="video">
        <video id="nubexVideo" loop="" muted="" autoplay="autoplay" playsinline=""> 
            <source src="media/stars3.mp4"></source>
        </video>
    </div>
</body>
</html>
