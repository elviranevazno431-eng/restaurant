<?php
namespace App\Controllers;

use App\Services\CartService;
use App\Services\EmailService;
use DBStorage;

class CartController {
    public function index() {
        $service = new CartService();
        $cartItems = $service->getCart();
        $total = $service->getTotal();
        $delivery = 250;
        $grandTotal = $total + ($total > 0 ? $delivery : 0);
        
        $page = 'cart';
        require_once __DIR__ . '/../Views/cart.php';
    }
    
    public function add() {
        $productId = $_POST['product_id'] ?? 0;
        $quantity = $_POST['quantity'] ?? 1;
        
        $service = new CartService();
        $service->addToCart($productId, $quantity);
        
        // 🔑 ОБЯЗАТЕЛЬНО: проверяем AJAX и отдаём JSON
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => true, 'count' => $service->getCount()]);
            exit; // 🔴 ВАЖНО: останавливаем выполнение, иначе добавится HTML страницы
        }
        
        header('Location: /?page=cart');
        exit;
    }
    
    public function remove() {
        $productId = $_POST['product_id'] ?? 0;
        $service = new CartService();
        $service->removeFromCart($productId);
        
        header('Location: /?page=cart');
        exit;
    }
    
    public function update() {
        $productId = $_POST['product_id'] ?? 0;
        $quantity = $_POST['quantity'] ?? 1;
        
        $service = new CartService();
        $service->updateQuantity($productId, max(1, $quantity));
        
        header('Location: /?page=cart');
        exit;
    }
    
        public function checkout() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /?page=login');
            exit;
        }
        
        $service = new CartService();
        $cartItems = $service->getCart();
        if (empty($cartItems)) {
            header('Location: /?page=cart');
            exit;
        }
        
        $total = $service->getTotal();
        $pdo = DBStorage::getInstance();
        
        try {
            $pdo->beginTransaction();
            $token = bin2hex(random_bytes(32));
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status, confirm_token, created_at) VALUES (?, ?, 'pending', ?, NOW())");
            $stmt->execute([$_SESSION['user_id'], $total, $token]);
            $orderId = $pdo->lastInsertId();
            foreach ($cartItems as $item) {
                $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->execute([$orderId, $item['id'], $item['qty'], $item['price']]);
            }
            $service->clearCart();
            $pdo->commit();
            $baseUrl = 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
            $confirmLink = "{$baseUrl}/verify-order.php?token={$token}&id={$orderId}";
            $userEmail = $_SESSION['user_email'] ?? '';
            $userName  = $_SESSION['user_name'] ?? 'Клиент';  
            \App\Services\EmailService::sendOrderConfirmation($userEmail, $userName, (int)$orderId, $total, $confirmLink);
            header('Location: /?page=orders&pending=1');
            exit;
        } catch (\Exception $e) {
            $pdo->rollBack();
            error_log("Checkout error: " . $e->getMessage());
            echo "<h1>❌ Ошибка оформления</h1><p><a href='/'>На главную</a></p>";
        }
    }
    public function clear() {
        $service = new CartService();
        $service->clearCart();
        
        // Если AJAX-запрос
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            http_response_code(200);
            echo json_encode(['success' => true, 'count' => 0]);
            exit;
        }
        
        // Обычный редирект
        header('Location: /?page=cart');
        exit;
    }
}