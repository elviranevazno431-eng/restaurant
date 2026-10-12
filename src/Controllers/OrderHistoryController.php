<?php
namespace App\Controllers;

use App\Services\OrderHistoryService;

class OrderHistoryController {
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /?page=login');
            exit;
        }
        
        $service = new OrderHistoryService();
        $orders = $service->getUserOrders($_SESSION['user_id']);
        
        $success = isset($_GET['success']);
        
        require_once __DIR__ . '/../Views/orders.php';
    }
}