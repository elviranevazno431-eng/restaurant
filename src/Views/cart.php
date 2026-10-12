<?php $page = 'cart'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина | Gourmet</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <?php require_once __DIR__ . '/header.php'; ?>
    
    <main class="container">
        <h1 class="page-title">Корзина</h1>
        
        <?php if (empty($cartItems)): ?>
            <div class="empty-cart">
                <div class="empty-cart-icon">🛒</div>
                <h2>Ваша корзина пуста</h2>
                <p>Добавьте что-нибудь вкусное из нашего меню!</p>
                <a href="/?page=menu" class="btn btn-primary">Перейти в меню</a>
            </div>
        <?php else: ?>
            <div class="cart-layout">
                <!-- Cart Items -->
                <div class="cart-items">
                    <?php foreach ($cartItems as $item): ?>
                    <div class="cart-item">
                        <img src="<?= htmlspecialchars($item['image_url'] ?: '/assets/img/default.jpg') ?>" 
                             alt="<?= htmlspecialchars($item['name']) ?>" class="cart-item-img">
                        <div class="cart-item-info">
                            <h3><?= htmlspecialchars($item['name']) ?></h3>
                            <p class="cart-item-price"><?= number_format($item['price']) ?> ₽ за шт.</p>
                        </div>
                        <div class="cart-item-controls">
                            <form method="post" action="/?page=cart&action=update" class="qty-form">
                                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                <button type="submit" name="quantity" value="<?= $item['qty'] - 1 ?>" 
                                        <?= $item['qty'] <= 1 ? 'disabled' : '' ?>>−</button>
                                <span class="qty"><?= $item['qty'] ?></span>
                                <button type="submit" name="quantity" value="<?= $item['qty'] + 1 ?>">+</button>
                            </form>
                        </div>
                        <div class="cart-item-subtotal">
                            <?= number_format($item['subtotal']) ?> ₽
                        </div>
                        <form method="post" action="/?page=cart&action=remove" class="remove-form">
                            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                            <button type="submit" class="btn-remove" title="Удалить">️</button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Cart Summary -->
                <div class="cart-summary">
                    <h2>Итого</h2>
                    <div class="summary-row">
                        <span>Стоимость товаров:</span>
                        <span><?= number_format($total) ?> ₽</span>
                    </div>
                    <div class="summary-row">
                        <span>Доставка:</span>
                        <span><?= $total > 0 ? number_format($delivery) . ' ₽' : 'Бесплатно' ?></span>
                    </div>
                    <div class="summary-row total">
                        <span>Итоговая сумма:</span>
                        <span><?= number_format($grandTotal) ?> ₽</span>
                    </div>
                    <!-- Стало (добавьте этот блок после формы оформления) -->
                        <form method="post" action="/?page=cart&action=checkout">
                            <button type="submit" class="btn btn-primary btn-block btn-large">
                                Оформить заказ
                            </button>
                        </form>

                        <!-- 🔴 Кнопка очистки корзины -->
                        <div style="margin-top: 15px;">
                            <button type="button" id="clear-cart-btn" class="btn btn-secondary btn-block" 
                                    style="background: #e74c3c; border: none;">
                                🗑️ Очистить корзину
                            </button>
                        </div>
                        <a href="/?page=menu" class="continue-shopping">← Продолжить покупки</a>
                </div>
            </div>
        <?php endif; ?>
    </main>
    
    <?php require_once __DIR__ . '/footer.php'; ?>
    <script src="/assets/js/main.js"></script>
</body>
</html>