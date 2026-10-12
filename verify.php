<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/DBStorage.php';

use App\Models\UserRegistrationModel;

$token = $_GET['token'] ?? '';
$success = false;
$message = '';

if (empty($token)) {
    $message = '❌ Токен подтверждения не указан в ссылке.';
} else {
    try {
        // ✅ Важно: \Exception с обратным слешем (глобальный класс)
        if (UserRegistrationModel::verifyToken($token)) {
            $message = '✅ Email успешно подтверждён! Теперь вы можете войти.';
            $success = true;
        } else {
            $message = '❌ Токен недействителен, уже использован или истёк.';
        }
    } catch (\Exception $e) {  // ✅ И здесь тоже \Exception
        $message = '❌ Ошибка системы: ' . $e->getMessage();
        error_log("Verify email error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Подтверждение email</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="alert <?= $success ? 'alert-success' : 'alert-error' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
        <a href="/?form=login" class="btn btn-primary">Войти в аккаунт</a>
    </div>
</body>
</html>