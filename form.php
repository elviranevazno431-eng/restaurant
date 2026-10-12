<?php
// Безопасный запуск сессии (не вызывает ошибку, если уже запущена)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/DBStorage.php';

use App\Models\UserRegistrationModel;

// Определяем режим: приоритет у ?form=, затем у ?page=
$mode = $_GET['form'] ?? ($_GET['page'] ?? 'login');
$message = '';
$msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = DBStorage::getInstance();

    // --- РЕГИСТРАЦИЯ ---
    if (isset($_POST['do_register'])) {
        $errors = UserRegistrationModel::validate([
            'name' => $_POST['name'] ?? '',
            'email' =>      $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? ''
        ]);

        if (empty($errors)) {
            try {
                UserRegistrationModel::register($_POST['name'], $_POST['email'], $_POST['password']);
                $message = '✅ Регистрация успешна! Теперь войдите.';
                $msgType = 'success';
                $mode = 'login'; // Автоматически переключаем на форму входа
            } catch (Exception $e) {
                $message = '❌ Ошибка: ' . $e->getMessage();
                $msgType = 'error';
            }
        } else {
            $message = '❌ ' . implode('<br>', $errors);
            $msgType = 'error';
        }
    } 
    // --- ВХОД ---
    elseif (isset($_POST['do_login'])) {
        $stmt = $pdo->prepare("SELECT id, name, email, password_hash, is_verified FROM users WHERE email = ?");
        $stmt->execute([$_POST['email']]);
        $user = $stmt->fetch();

        if ($user && password_verify($_POST['password'], $user['password_hash'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            header('Location: /');
            exit;
        } else {
            $message = '❌ Неверный Email или пароль';
            $msgType = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $mode === 'login' ? 'Вход' : 'Регистрация' ?> | Gourmet</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-header">
            <h1>🍽️ Пир горой
            </h1>
            <p><?= $mode === 'login' ? 'Войдите в аккаунт' : 'Создайте аккаунт' ?></p>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?= $msgType ?>"><?= $message ?></div>
        <?php endif; ?>

        <form method="post" action="" class="auth-form">
            <?php if ($mode === 'register'): ?>
                <input type="hidden" name="do_register">
                <div class="form-group">
                    <label>Ваше имя</label>
                    <input type="text" name="name" placeholder="Иван Иванов" required>
                </div>
            <?php else: ?>
                <input type="hidden" name="do_login">
            <?php endif; ?>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="example@mail.ru" required>
            </div>

            <div class="form-group">
                <label>Пароль</label>
        <input type="password" name="password" placeholder="Минимум 6 символов" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                <?= $mode === 'login' ? 'Войти' : 'Зарегистрироваться' ?>
            </button>
        </form>

        <div class="auth-footer">
            <?php if ($mode === 'login'): ?>
                <p>Нет аккаунта? <a href="?form=register">Зарегистрироваться</a></p>
            <?php else: ?>
                <p>Уже есть аккаунт? <a href="?form=login">Войти</a></p>
            <?php endif; ?>
            <a href="/">← На главную</a>
        </div>
    </div>
</body>
</html>