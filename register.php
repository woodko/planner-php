<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!$username || !$email || !$password) {
        $error = "Пожалуйста, заполните все поля.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            $error = "Пользователь с таким именем или email уже существует.";
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
            try {
                $stmt->execute([$username, $email, $hash]);
                header("Location: login.php");
                exit;
            } catch (Exception $e) {
                $error = "Ошибка при регистрации: " . $e->getMessage();
            }
        }
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
</body>
</html>
