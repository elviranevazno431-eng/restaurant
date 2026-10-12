<?php
namespace App\Models;

use DBStorage;
use App\Config\EmailTokenConfig;

class UserRegistrationModel {
    /**
     * Валидация данных (проверка ошибок)
     */
    public static function validate(array $data): array {
        $errors = [];
        
        if (empty(trim($data['name']))) $errors[] = 'Введите имя';
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Некорректный Email';
        if (strlen($data['password']) < 6) $errors[] = 'Пароль минимум 6 символов';
        
        // Проверка, не занят ли Email
        if (empty($errors)) {
            $pdo = DBStorage::getInstance();
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$data['email']]);
            if ($stmt->fetch()) {
                $errors[] = 'Такой Email уже зарегистрирован';
            }
        }
        
        return $errors;
    }

    /**
     * Регистрация пользователя
     */
    public static function register(string $name, string $email, string $password): bool {
        $pdo = DBStorage::getInstance();
        
        // Шифруем пароль (НИКОГДА не храните пароли в открытом виде!)
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Генерируем токен (для подтверждения email)
        $token = bin2hex(random_bytes(32));
        
        // Вставляем в БД
        // is_verified = 1 для теста (чтобы можно было сразу войти). 
        // В реальном проекте ставьте 0 и требуйте перехода по ссылке.
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, email_token, is_verified) VALUES (?, ?, ?, ?, 1)");
        
        return $stmt->execute([$name, $email, $hash, $token]);
    }
}