<?php
namespace App\Controllers;

use App\Services\ProfileService;
use DBStorage;

class ProfileController {
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /?page=login');
            exit;
        }
        
        $service = new ProfileService();
        $user = $service->getUserProfile($_SESSION['user_id']);
        
        // Получаем статистику заказов
        $pdo = DBStorage::getInstance();
        $stmt = $pdo->prepare("SELECT COUNT(*) as total_orders, SUM(total_amount) as total_spent FROM orders WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $stats = $stmt->fetch();
        
        // Бонусные баллы (10% от потраченной суммы)
        $bonusPoints = floor(($stats['total_spent'] ?? 0) * 0.1);
        
        require_once __DIR__ . '/../Views/profile.php';
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pdo = DBStorage::getInstance();
            $stmt = $pdo->prepare("UPDATE users SET name = ?, phone = ?, address = ? WHERE id = ?");
            $stmt->execute([
                $_POST['name'],
                $_POST['phone'] ?? '',
                $_POST['address'] ?? '',
                $_SESSION['user_id']
            ]);
        }
        
        header('Location: /?page=profile');
    }
}