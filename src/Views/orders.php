<?php $page = 'orders'; 

// 🔔 Показываем уведомление после редиректа из письма
if (isset($_GET['confirmed']) || isset($_GET['already_confirmed']) || isset($_GET['verify_error'])): ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const msg = 
        '<?= isset($_GET['confirmed']) ? '✅ Заказ успешно подтверждён!' : '' ?>' ||
        '<?= isset($_GET['already_confirmed']) ? 'ℹ️ Этот заказ уже был подтверждён.' : '' ?>' ||
        '❌ Ошибка подтверждения заказа.';
    
    if (typeof showToast === 'function') {
        showToast(msg, '<?= isset($_GET['verify_error']) ? 'error' : 'success' ?>');
    } else {
        alert(msg);
    }
});
</script>
<?php endif; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>История заказов | Gourmet</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <?php require_once __DIR__ . '/header.php'; ?>
    
    <main class="container">
        <h1 class="page-title">История заказов</h1>
        
        <?php if (isset($_GET['pending'])): ?>
        <div class="alert alert-success" style="margin-bottom: 20px;">
            <strong>✅ Заказ оформлен!</strong><br>
            📧 Проверьте почту и перейдите по ссылке для подтверждения.
        </div>
        <?php endif; ?>
        
        <?php if (empty($orders)): ?>
            <div class="empty-orders">
                <div class="empty-orders-icon">📋</div>
                <h2>У вас пока нет заказов</h2>
                <p>Самое время попробовать наши блюда!</p>
                <a href="/?page=menu" class="btn btn-primary">Перейти в меню</a>
            </div>
        <?php else: ?>
            <div class="orders-list">
                <?php foreach ($orders as $order): ?>
                <?php
                // ===== БЛОК СТАТУСА (простой и надёжный) =====
                $s = isset($order['status']) ? (string)$order['status'] : '';
                
                if ($s === 'pending') {
                    $t = '⏳ Ожидает подтверждения'; $c = 'status-pending';
                } elseif ($s === 'confirmed') {
                    $t = '✅ Подтверждён'; $c = 'status-confirmed';
                } elseif ($s === 'cooking') {
                    $t = '👨‍🍳 Готовится'; $c = 'status-cooking';
                } elseif ($s === 'ready') {
                    $t = '✨ Готов к выдаче'; $c = 'status-ready';
                } elseif ($s === 'delivered') {
                    $t = '🚚 Доставлен'; $c = 'status-delivered';
                } elseif ($s === 'cancelled') {
                    $t = '❌ Отменён'; $c = 'status-cancelled';
                } elseif ($s === 'new') {
                    $t = '📦 Новый'; $c = 'status-new';
                } else {
                    $t = '❓ [' . htmlspecialchars($s) . ']'; $c = 'status-unknown';
                }
                ?>
                
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-id">Заказ #<?= $order['id'] ?></div>
                        <div class="order-date"><?= date('d.m.Y H:i', strtotime($order['created_at'])) ?></div>
                        <span class="order-status <?= $c ?>"><?= $t ?></span>
                    </div>
                    <div class="order-items">
                        <span class="items-count"><?= $order['items_count'] ?? 0 ?> позиций</span>
                    </div>
                    <div class="order-footer">
                        <div class="order-total"><?= number_format($order['total_amount']) ?> ₽</div>
                        <div class="order-footer">
                            <div class="order-total"><?= number_format($order['total_amount']) ?> ₽</div>
                            
                            <?php if ($s === 'confirmed'): ?>
                                <button class="btn btn-secondary" onclick="repeatOrder(<?= $order['id'] ?>)">
                                    🔄 Повторить заказ
                                </button>
                            <?php endif; ?>
                            <!-- Для pending ничего не выводим - клиент подтверждает через почту -->
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
    
    <?php require_once __DIR__ . '/footer.php'; ?>
    <script src="/assets/js/main.js"></script>
</body>
</html>