<?php
namespace App\Config;

class EmailTokenConfig {
    /**
     * Генерация уникального токена подтверждения
     */
    public static function generateToken(): string {
        return bin2hex(random_bytes(32));
    }
    
    /**
     * Email отправителя
     */
    public static function fromEmail(): string {
        return 'noreply@gourmet-restaurant.local';
    }
    
    /**
     * Симуляция отправки email (в продакшене заменить на PHPMailer/SwiftMailer)
     */
    public static function simulateEmailSend(string $to, string $token): bool {
        $subject = 'Подтверждение регистрации в Gourmet';
        $link = "http://localhost/verify.php?token=$token";
        $message = "Здравствуйте!\n\nДля подтверждения регистрации перейдите по ссылке:\n$link\n\nСпасибо, что выбрали Gourmet!";
        
        // Логируем "отправку" (в реальном проекте здесь будет mail() или SMTP)
        error_log("[EMAIL] To: $to | Subject: $subject | Link: $link");
        
        return true;
    }
    
    /**
     * Время жизни токена в секундах (24 часа)
     */
    public static function getTokenLifetime(): int {
        return 86400;
    }
}