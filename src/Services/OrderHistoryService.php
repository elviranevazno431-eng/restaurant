<?php
namespace App\Services;
use DBStorage;

class OrderHistoryService {
    public function getUserOrders(int $userId): array {
        $pdo = DBStorage::getInstance();
        
        // ✅ Запрос без GROUP BY — гарантирует возврат status
        $stmt = $pdo->prepare("
            SELECT o.id, o.total_amount, o.status, o.created_at, o.confirm_token,
                   (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as items_count
            FROM orders o
            WHERE o.user_id = ?
            ORDER BY o.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}