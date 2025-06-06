<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $telegram_id = $_POST['telegram_chat_id'];

    $update = $pdo->prepare("UPDATE users SET telegram_chat_id = ? WHERE id = ?");
    $update->execute([$telegram_id, $user_id]);

    $message = "Профиль обновлён";
    $user['telegram_chat_id'] = $telegram_id;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Профиль</title>
    <meta name="viewport"content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <?php if (basename($_SERVER['PHP_SELF']) != 'index.php'): ?>
            <a href="index.php" style="
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 10px 18px;
                background: transparent;
                color: #fff;
                border: 1px solid rgba(255, 255, 255, 0.5);
                text-decoration: none;
                border-radius: 5px;
                font-size: 14px;
                box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
                transition: all 0.2s ease-in-out;
                margin: 16px 0;
            " onmouseover="this.style.background='#35393d'" onmouseout="this.style.background='transparent'">
                ← Назад
            </a>
        <?php endif; ?>
        <h2>Профиль пользователя</h2>
        <?php if (isset($message)) echo "<div class='alert alert-success'>$message</div>"; ?>
        <form method="post">
            <label>Логин: <?php echo htmlspecialchars($user['username']); ?></label><br><br>
            <label>Email: <?php echo htmlspecialchars($user['email']); ?></label><br><br>
            <label for="telegram_chat_id">Telegram Chat ID для уведомлений:</label><br><br>
            <input type="text" name="telegram_chat_id" value="<?php echo htmlspecialchars($user['telegram_chat_id'] ?? ''); ?>"><br><br>
            <spam>Свой Chat ID вы можете узнать у бота <strong><a href="tg://resolve?domain=userinfobot">@userinfobot</a></strong> в Телеграм</spam><br><br><br>
            <button type="submit">Сохранить</button>
        </form>
    </div>
    <div class="video">
        <video id="nubexVideo" width="100%" height="auto" loop="" muted="" autoplay="autoplay" playsinline=""> 
            <source src="media/stars.mp4"></source>
        </video>
    </div>
</body>
</html>
