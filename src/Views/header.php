<?php
use App\Services\CartService;
$cartService = new CartService();
$cartCount = $cartService->getCount();
?>
<header class="site-header">
    <div class="container">
        <nav class="main-nav">
            <a href="/" class="nav-logo">🍽️ Пир горой</a>
            
            <div class="nav-links">
                <a href="/?page=home" class="<?= $page === 'home' ? 'active' : '' ?>">Главная</a>
                <a href="/?page=menu" class="<?= $page === 'menu' ? 'active' : '' ?>">Меню</a>
                <a href="/?page=cart" class="<?= $page === 'cart' ? 'active' : '' ?>">
                    Корзина 
                    <?php if ($cartCount > 0): ?>
                        <span id="cart-count" class="cart-badge"><?= $cartCount ?></span>
                    <?php else: ?>
                        <span id="cart-count" class="cart-badge" style="display:none;">0</span>
                    <?php endif; ?>
                </a>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/?page=profile" class="<?= $page === 'profile' ? 'active' : '' ?>">Профиль</a>
                    <a href="/?page=orders" class="<?= $page === 'orders' ? 'active' : '' ?>">История заказов</a>
                    <a href="/?page=logout" class="nav-logout">Выход</a>
                    <span class="user-name">👤 <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                <?php else: ?>
                    <a href="/?page=login" class="nav-login">Вход</a>
                    <a href="/?page=register" class="nav-register">Регистрация</a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</header>