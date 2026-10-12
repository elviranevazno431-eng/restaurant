<?php
namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

class EmailService {
    private const SMTP_HOST = 'smtp.mail.ru';
    private const SMTP_PORT = 465;
    private const SMTP_USER = 'coopteh231@mail.ru';
    private const SMTP_PASS = 'oBdxSwM2AWnco7ALXUk5'; // Пароль приложения
    private const FROM_EMAIL = 'coopteh231@mail.ru';
    private const FROM_NAME = 'Пир горой';

    public static function sendOrderConfirmation(string $toEmail, string $userName, int $orderId, float $totalAmount, string $confirmLink): bool {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = self::SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = self::SMTP_USER;
            $mail->Password   = self::SMTP_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = self::SMTP_PORT;
            $mail->CharSet    = 'UTF-8';
            $mail->SMTPDebug  = 0; // Поставьте 2 для отладки

            $mail->setFrom(self::FROM_EMAIL, self::FROM_NAME);
            $mail->addAddress($toEmail, $userName);
            $mail->addReplyTo(self::FROM_EMAIL, self::FROM_NAME);

            $mail->isHTML(true);
            $mail->Subject = "✅ Подтвердите заказ #{$orderId} | Gourmet";

            $mail->Body = self::getOrderHtml($userName, $orderId, $totalAmount, $confirmLink);
            $mail->AltBody = "Здравствуйте, {$userName}!\nВаш заказ #{$orderId} на сумму {$totalAmount} ₽ создан.\nПодтвердите его: {$confirmLink}";

            return $mail->send();
        } catch (Exception $e) {
            error_log("[EMAIL ERROR] Order #{$orderId}: {$mail->ErrorInfo}");
            return false;
        }
    }

    private static function getOrderHtml(string $name, int $orderId, float $amount, string $link): string {
        $fmt = number_format($amount, 2, '.', ' ');
        return "
        <html><body style='font-family:Arial,sans-serif;line-height:1.6;color:#333;max-width:600px;margin:0 auto;background:#f9f9f9;'>
            <div style='background:#170404;color:#fff;padding:25px;text-align:center;border-radius:10px 10px 0 0;'>
                <h1 style='margin:0;'>🍽️ Пир горой</h1>
            </div>
            <div style='background:#fff;padding:30px;border:1px solid #eee;border-radius:0 0 10px 10px;'>
                <p>Здравствуйте, <strong>{$name}</strong>!</p>
                <p>✅ Ваш заказ <strong>#{$orderId}</strong> на сумму <strong>{$fmt} ₽</strong> успешно создан.</p>
                <p>📦 Статус: <em>Ожидает подтверждения</em></p>
                <hr style='border:0;border-top:1px solid #eee;margin:25px 0;'>
                <p style='text-align:center;margin:30px 0;'>
                    <a href='{$link}' style='background:#e74c3c;color:#fff;padding:14px 35px;text-decoration:none;border-radius:8px;font-weight:bold;font-size:16px;display:inline-block;'>
                        ✅ Подтвердить заказ
                    </a>
                </p>
                <p style='font-size:13px;color:#777;text-align:center;'>Или скопируйте ссылку в браузер:<br><a href='{$link}'>{$link}</a></p>
                <hr style='border:0;border-top:1px solid #eee;margin:25px 0;'>
                <p style='font-size:12px;color:#999;'>⏰ Ссылка действительна 24 часа.<br>Если вы не оформляли заказ, просто проигнорируйте это письмо.</p>
            </div>
        </body></html>";
    }
}