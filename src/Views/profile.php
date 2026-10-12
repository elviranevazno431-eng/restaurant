<?php $page = 'profile'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль | Gourmet</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <?php require_once __DIR__ . '/header.php'; ?>
    
    <main class="container">
        <div class="profile-layout">
            <!-- Sidebar -->
            <aside class="profile-sidebar">
                <div class="profile-avatar">
                    <div class="avatar-circle">👤</div>
                    <h3><?= htmlspecialchars($user['name']) ?></h3>
                    <p class="user-email"><?= htmlspecialchars($user['email']) ?></p>
                </div>
                <nav class="profile-nav">
                    <a href="/?page=profile" class="active">Личные данные</a>
                    <a href="/?page=orders">История заказов</a>
                    <a href="/?page=logout">Выход</a>
                </nav>
            </aside>
            
            <!-- Main Content -->
            <div class="profile-content">
                <h1>Личные данные</h1>
                
                <div class="profile-stats">
                    <div class="stat-card">
                        <div class="stat-value"><?= $stats['total_orders'] ?? 0 ?></div>
                        <div class="stat-label">Заказов</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?= number_format($stats['total_spent'] ?? 0) ?> ₽</div>
                        <div class="stat-label">Потрачено</div>
                    </div>
                    <div class="stat-card bonus">
                        <div class="stat-value"><?= $bonusPoints ?></div>
                        <div class="stat-label">Бонусных баллов</div>
                    </div>
                </div>
                
                <form method="post" action="/?page=profile&action=update" class="profile-form">
                    <div class="form-group">
                        <label>Имя</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                        <small>Email нельзя изменить</small>
                    </div>
                    <div class="form-group">
                        <label>Телефон</label>
                        <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" 
                               placeholder="+7 (999) 123-45-67">
                    </div>
                    <div class="form-group">
                        <label>Адрес доставки</label>
                        <textarea name="address" rows="3" placeholder="Город, улица, дом, квартира"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                </form>
            </div>
        </div>
    </main>
    
    <?php require_once __DIR__ . '/footer.php'; ?>
    <script src="/assets/js/main.js"></script>
</body>
</html>