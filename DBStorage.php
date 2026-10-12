<?php
class DBStorage {
    private static ?PDO $instance = null;
    
    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $host = 'localhost';
            $db   = 'restaurant_db';
            $user = 'root';
            $pass = '';
            
            try {
                self::$instance = new PDO(
                    "mysql:host=$host;dbname=$db;charset=utf8mb4",
                    $user,
                    $pass,
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES   => false,
                    ]
                );
            } catch (PDOException $e) {
                die("❌ Ошибка подключения к БД: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}