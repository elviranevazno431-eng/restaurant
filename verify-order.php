<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/DBStorage.php';

$token  = $_GET['token'] ?? '';
$orderId = (int)($_GET['id'] ?? 0);
$message = '';
$success = false;

if (empty($token) || $orderId <= 0) {
    $message = "❌ Ссылка некорректна.";
} else {
    try {
        $pdo = DBStorage::getInstance();
        // Подтверждаем ТОЛЬКО если статус pending и токен совпадает
        $stmt = $pdo->prepare("UPDATE orders SET status = 'confirmed', confirm_token = NULL WHERE id = ? AND confirm_token = ? AND status = 'pending'");
        $stmt->execute([$orderId, $token]);

        if ($stmt->rowCount() > 0) {
            $message = "✅ Заказ #{$orderId} успешно подтверждён!";
            $success = true;
        } else {
            $check = $pdo->prepare("SELECT status FROM orders WHERE id = ?");
            $check->execute([$orderId]);
            if ($check->fetchColumn() === 'confirmed') {
                $message = "ℹ️ Этот заказ уже был подтверждён.";
                $success = true;
            } else {
                $message = "❌ Ссылка устарела или неверна.";
            }
        }
    } catch (Exception $e) {
        error_log("Verify Error: " . $e->getMessage());
        $message = "❌ Ошибка системы.";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Подтверждение заказа</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        .verify-wrap{display:flex;justify-content:center;align-items:center;min-height:80vh;background:#170404;padding:20px}
        .verify-card{background:#fff;padding:40px;border-radius:12px;max-width:500px;text-align:center}
        .verify-icon{font-size:64px;margin-bottom:20px}
        .verify-btn{display:inline-block;margin-top:20px;padding:12px 30px;background:#e74c3c;color:#fff;text-decoration:none;border-radius:8px}
    </style>
</head>
<body>
    <div class="verify-wrap">
        <div class="verify-card">
            <div class="verify-icon"><?= $success ? '🎉' : '⚠️' ?></div>
            <h2><?= $success ? 'Заказ подтверждён!' : 'Ошибка' ?></h2>
            <p style="margin:15px 0;color:#555"><?= nl2br(htmlspecialchars($message)) ?></p>
            <a href="/?page=orders" class="verify-btn">← Мои заказы</a>
        </div>
    </div>
</body>
</html>