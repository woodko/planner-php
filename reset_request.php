<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + 3600); // 1 час

        $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?")
            ->execute([$token, $expires, $user['id']]);

        $link = "https://example.com/reset_password.php?token=$token";
        $subject = "Восстановление пароля";
        $message = "Перейдите по ссылке для восстановления пароля: $link";

        mail($email, $subject, $message); // Подключи SMTP, если нужно

        $msg = "Инструкции отправлены на email.";
    } else {
        $msg = "Email не найден.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Восстановление пароля</title></head>
<body>
<div class="container">
<h2>Восстановить пароль</h2>
<form method="POST">
    <input name="email" type="email" placeholder="Ваш Email" required>
    <button type="submit">Отправить ссылку</button>
</form>
<?php if (isset($msg)) echo "<p>$msg</p>"; ?>
<a href="login.php">Назад к входу</a>
</div>
</body>
</html>
