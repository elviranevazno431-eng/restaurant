<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Включаем отображение ВСЕХ ошибок для отладки
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Проверяем автозагрузку Composer
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    die('❌ Ошибка: vendor/autoload.php не найден. Выполните: composer install');
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/DBStorage.php';

use App\Services\CartService;

// Получаем действие из URL
$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? null;

// Аутентификация - logout
if ($page === 'logout') {
    session_destroy();
    header('Location: /');
    exit;
}

// Проверка авторизации для защищённых страниц
$authRequired = ['profile', 'orders', 'cart'];
if (in_array($page, $authRequired) && !isset($_SESSION['user_id'])) {
    header('Location: /?page=login');
    exit;
}

// Простая маршрутизация
try {
    switch ($page) {
        case 'home':
            require_once __DIR__ . '/src/Views/home.php';
            break;
            
        case 'menu':
            require_once __DIR__ . '/src/Controllers/MenuController.php';
            $controller = new App\Controllers\MenuController();
            $controller->index();
            break;
            
        case 'cart':
            require_once __DIR__ . '/src/Controllers/CartController.php';
            $controller = new App\Controllers\CartController();
            
            if ($action === 'add') {
                $controller->add();
            } elseif ($action === 'remove') {
                $controller->remove();
            } elseif ($action === 'update') {
                $controller->update();
            } elseif ($action === 'checkout') {
                $controller->checkout();
            } elseif ($action === 'clear') {  // 🔴 НОВОЕ
                $controller->clear();
            } else {
                $controller->index();
            }
            break;
            
        case 'profile':
            require_once __DIR__ . '/src/Controllers/ProfileController.php';
            $controller = new App\Controllers\ProfileController();
            
            if ($action === 'update') {
                $controller->update();
            } else {
                $controller->index();
            }
            break;
            
        case 'orders':
            require_once __DIR__ . '/src/Controllers/OrderHistoryController.php';
            $controller = new App\Controllers\OrderHistoryController();
            $controller->index();
            break;
            
        case 'login':
        case 'register':
            require_once __DIR__ . '/form.php';
            break;
            
        case 'verify':
            require_once __DIR__ . '/verify.php';
            break;
            
        default:
            echo "<h1>❌ Страница не найдена</h1>";
            echo "<p>Запрошенная страница: <strong>" . htmlspecialchars($page) . "</strong></p>";
            echo "<p><a href='/'>← На главную</a></p>";
            echo "<h3>Доступные страницы:</h3>";
            echo "<ul>";
            echo "<li><a href='/?page=home'>Главная</a></li>";
            echo "<li><a href='/?page=menu'>Меню</a></li>";
            echo "<li><a href='/?page=cart'>Корзина</a></li>";
            echo "<li><a href='/?page=login'>Вход</a></li>";
            echo "<li><a href='/?page=register'>Регистрация</a></li>";
            echo "</ul>";
    }
} catch (Exception $e) {
    echo "<h1>🔥 Ошибка приложения</h1>";
    echo "<pre>" . $e->getMessage() . "</pre>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}   