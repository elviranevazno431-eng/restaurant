<?php
namespace App\Services;

use DBStorage;

class ProfileService {
    public function getUserProfile(int $userId): array {
        $pdo = DBStorage::getInstance();
        $stmt = $pdo->prepare("SELECT id, name, email, phone, address, created_at FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch() ?: [];
    }
}