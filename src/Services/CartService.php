<?php
namespace App\Services;

use DBStorage;

class CartService {
    /**
     * Получение корзины из сессии
     */
    public function getCart(): array {
        $cart = $_SESSION['cart'] ?? [];
        
        if (empty($cart)) {
            return [];
        }
        
        $pdo = DBStorage::getInstance();
        $ids = array_keys($cart);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        $products = $stmt->fetchAll();
        
        // Добавляем количество из корзины
        foreach ($products as &$product) {
            $product['qty'] = $cart[$product['id']] ?? 1;
            $product['subtotal'] = $product['price'] * $product['qty'];
        }
        
        return $products;
    }
    
    /**
     * Добавление товара в корзину
     */
    public function addToCart(int $productId, int $quantity = 1): void {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
    }
    
    /**
     * Удаление товара из корзины
     */
    public function removeFromCart(int $productId): void {
        unset($_SESSION['cart'][$productId]);
    }
    
    /**
     * Обновление количества
     */
    public function updateQuantity(int $productId, int $quantity): void {
        if ($quantity <= 0) {
            $this->removeFromCart($productId);
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
    }
    
    /**
     * Получение общей суммы
     */
    public function getTotal(): float {
        $items = $this->getCart();
        return array_sum(array_column($items, 'subtotal'));
    }
    
    /**
     * Очистка корзины
     */
    public function clearCart(): void {
        $_SESSION['cart'] = [];
    }
    
    /**
     * Количество товаров в корзине
     */
    public function getCount(): int {
        return array_sum($_SESSION['cart'] ?? []);
    }
}