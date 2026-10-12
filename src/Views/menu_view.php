<?php $page = 'menu'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Меню | Gourmet</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <!-- 🔒 Стили для фильтра (перебьют любой внешний CSS) -->
    <style>
        .menu-category.filter-hidden { 
            display: none !important; 
            visibility: hidden !important;
        }
    </style>
</head>
<body>
    <?php require_once __DIR__ . '/header.php'; ?>

    <main>
        <section class="hero" style="padding: 60px 20px; min-height: auto;">
            <h1>Наше Меню</h1>
            <p>Выбирайте блюда по категориям</p>
        </section>

        <div class="container">
            <!-- Фильтры (передаём this для точной работы active) -->
            <div class="category-filters">
                <button class="filter-btn active" onclick="filterMenu('all', this)">Все блюда</button>
                <?php foreach ($categories as $c): ?>
                    <button class="filter-btn" onclick="filterMenu(<?= $c['id'] ?>, this)">
                        <?= htmlspecialchars($c['name']) ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- Вывод по категориям -->
            <?php foreach ($groupedProducts as $catId => $catData): ?>
                <section class="menu-category" data-cat-id="<?= $catId ?>">
                    <h2 class="section-title"><?= htmlspecialchars($catData['name']) ?></h2>
                    <div class="products-grid">
                        <?php foreach ($catData['items'] as $p): ?>
                            <div class="product-card">
                                <div class="product-image">
                                    <img src="<?= htmlspecialchars($p['image_url'] ?: '/assets/img/default.jpg') ?>" 
                                         alt="<?= htmlspecialchars($p['name']) ?>"
                                         onerror="this.src='/assets/img/default.jpg'">
                                    <?php if (!empty($p['is_new'])): ?>
                                        <span class="badge-new">NEW</span>
                                    <?php endif; ?>
                                </div>
                                <div class="product-info">
                                    <h3><?= htmlspecialchars($p['name']) ?></h3>
                                    <p class="product-desc"><?= htmlspecialchars(mb_strimwidth($p['description'] ?? '', 0, 80, '...')) ?></p>
                                    <div class="product-footer">
                                        <span class="product-price"><?= number_format($p['price']) ?> ₽</span>
                                        <button type="button" class="btn-cart add-to-cart-btn" data-id="<?= $p['id'] ?>">🛒 В корзину</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        </div>
    </main>

    <?php require_once __DIR__ . '/footer.php'; ?>

    <script src="/assets/js/main.js"></script>
    <script>
    // 🔹 Железобетонный фильтр категорий
    window.filterMenu = function(id, clickedBtn) {
        // 1. Обновляем активную кнопку
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        if (clickedBtn) clickedBtn.classList.add('active');

        // 2. Показываем/скрываем категории
        document.querySelectorAll('.menu-category').forEach(sec => {
            const catId = sec.dataset.catId;
            // Сравниваем как строки, чтобы избежать проблем с типами
            const isMatch = (id === 'all' || String(catId) === String(id));
            
            if (isMatch) {
                sec.classList.remove('filter-hidden');
            } else {
                sec.classList.add('filter-hidden');
            }
        });
    };

    // 🔹 Корзина (защита от ×2, не конфликтует с main.js)
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.add-to-cart-btn');
        if (!btn || btn.disabled || btn.dataset.busy === '1') return;
        
        e.preventDefault(); 
        e.stopImmediatePropagation();

        btn.dataset.busy = '1';
        const oldHtml = btn.innerHTML;
        btn.innerHTML = '⏳';
        btn.disabled = true;

        fetch('/?page=cart&action=add', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/x-www-form-urlencoded', 
                'X-Requested-With': 'XMLHttpRequest' 
            },
            body: 'product_id=' + btn.dataset.id + '&quantity=1'
        })
        .then(res => res.json())
        .then(data => { 
            if (typeof updateCartBadge === 'function' && data.count !== undefined) {
                updateCartBadge(data.count); 
            }
        })
        .catch(console.warn)
        .finally(() => {
            setTimeout(() => {
                btn.innerHTML = oldHtml;
                btn.disabled = false;
                delete btn.dataset.busy;
            }, 600);
        });
    });
    </script>
</body>
</html>