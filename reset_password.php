<?php
require 'db.php';

if (!isset($_GET['token'])) {
    die("Неверный токен.");
}

$token = $_GET['token'];
$stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW()");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    die("Токен недействителен или истёк.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($new_password !== $confirm) {
        $error = "Пароли не совпадают.";
    } elseif (strlen($new_password) < 6) {
        $error = "Пароль слишком короткий.";
    } else {
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?")
    ->execute([$hash, $user['id']]);
        header("Location: login.php?reset=success");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Новый пароль</title>
    <meta name="viewport"content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Введите новый пароль</h2>
        <form method="POST">
            <input name="password" type="password" placeholder="Новый пароль" required><br>
            <input name="confirm" type="password" placeholder="Повторите пароль" required><br>
            <button type="submit">Сменить пароль</button>
        </form>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
    </div>
    <div class="video">
        <video id="nubexVideo" loop="" muted="" autoplay="autoplay" playsinline=""> 
            <source src="media/stars3.mp4"></source>
        </video>
    </div>
</body>
</html>




