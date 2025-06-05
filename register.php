<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    try {
        $stmt->execute([$username, $email, $password]);
        header("Location: login.php");
    } catch (Exception $e) {
        $error = "Пользователь уже существует.";
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
<?php if (isset($error)) echo "<p>$error</p>"; ?>
<a href="login.php">Вход</a>
</div>
</body>
</html>
