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

        $link = "https://planner.tw1.ru/reset_password.php?token=$token";
        $subject = "Восстановление пароля Планировщик задач";
        $message = "Перейдите по ссылке для восстановления пароля: $link";

        mail($email, $subject, $message); // Подключи SMTP, если нужно

        $msg = "Инструкции отправлены на email. Проверьте папаку СПАМ";
    } else {
        $msg = "Email не найден.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Восстановление пароля</title>
    <meta name="viewport"content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Восстановить пароль</h2>
        <span style="display: block; text-align: center">Введите свой E-mail, и мы отправим вам ссылку на страницу восстановления</span>
        <form method="POST">
            <input name="email" type="email" placeholder="Ваш Email" required>
            <button type="submit">Отправить ссылку</button>
        </form>
        <?php if (isset($msg)) echo "<p>$msg</p>"; ?>
        <a href="login.php">Назад к входу</a>
    </div>
    <div class="video">
        <video id="nubexVideo" loop="" muted="" autoplay="autoplay" playsinline=""> 
            <source src="media/stars.mp4"></source>
        </video>
    </div>
</body>
</html>
