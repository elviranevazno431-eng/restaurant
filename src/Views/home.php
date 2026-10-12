<?php $page = 'home'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gourmet - Ресторан изысканной кухни</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <?php require_once __DIR__ . '/header.php'; ?>
    
    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-content">
                <h1>Вкус, который вдохновляет</h1>
                <p>Изысканные блюда от лучших шеф-поваров с доставкой на дом</p>
                <a href="/?page=menu" class="btn btn-primary btn-large">Смотреть меню</a>
            </div>
        </section>
        
        <!-- Popular Dishes -->
        <section class="popular-dishes container">
            <h2 class="section-title">Популярные блюда</h2>
            <div class="dishes-grid">
                <?php
                $pdo = DBStorage::getInstance();
                $stmt = $pdo->query("SELECT * FROM products ORDER BY rating DESC LIMIT 3");
                while ($dish = $stmt->fetch()):
                ?>
                <div class="dish-card">
                    <img src="<?= htmlspecialchars($dish['image_url'] ?: '/assets/img/default.jpg') ?>" 
                         alt="<?= htmlspecialchars($dish['name']) ?>">
                    <div class="dish-info">
                        <h3><?= htmlspecialchars($dish['name']) ?></h3>
                        <p class="dish-price">от <?= number_format($dish['price']) ?> ₽</p>
                        <button class="btn btn-secondary" onclick="addToCart(<?= $dish['id'] ?>)">
                            В корзину
                        </button>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </section>
        
        <!-- Features -->
        <section class="features">
            <div class="container">
                <div class="features-grid">
                    <div class="feature">
                        <div class="feature-icon">🚚</div>
                        <h3>Быстрая доставка</h3>
                        <p>Доставим за 30-60 минут</p>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">👨‍🍳</div>
                        <h3>Лучшие повара</h3>
                        <p>Мастера своего дела</p>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">⭐</div>
                        <h3>Высокое качество</h3>
                        <p>Только свежие продукты</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <?php require_once __DIR__ . '/footer.php'; ?>
    <script src="/assets/js/main.js"></script>
</body>
</html>