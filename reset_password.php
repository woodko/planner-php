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
        $pdo->prepare("UPDATE users SET password_hash = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?")
            ->execute([$hash, $user['id']]);
        header("Location: login.php?reset=success");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Новый пароль</title></head>
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
</body>
</html>
