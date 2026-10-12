<?php
/**
 * Мокирование DBStorage для тестов
 */
class MockStorage {
    public static $users = [];
    public static $products = [];
    public static $orders = [];
    
    public static function insertUser(array $data): bool {
        $data['id'] = count(self::$users) + 1;
        $data['created_at'] = date('Y-m-d H:i:s');
        self::$users[] = $data;
        return true;
    }
    
    public static function getUserByEmail(string $email): ?array {
        foreach (self::$users as $u) {
            if ($u['email'] === $email) return $u;
        }
        return null;
    }
    
    public static function reset(): void {
        self::$users = [];
        self::$products = [];
        self::$orders = [];
    }
}