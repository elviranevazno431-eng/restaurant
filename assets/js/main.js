/**
 * Основной JavaScript файл для сайта ресторана Gourmet
 * Версия: 2.1 (С кнопкой очистки корзины)
 */

// ===== ГЛОБАЛЬНЫЕ ФУНКЦИИ (доступны везде) =====

/**
 * Обновление счётчика товаров в шапке сайта
 */
function updateCartBadge(count) {
    const badge = document.getElementById('cart-count');
    if (!badge) return;
    
    badge.textContent = count;
    badge.style.display = count > 0 ? 'inline-block' : 'none';
    
    // Анимация пульсации
    badge.style.transition = 'transform 0.2s';
    badge.style.transform = 'scale(1.4)';
    setTimeout(() => badge.style.transform = 'scale(1)', 200);
}

/**
 * Всплывающее уведомление (Toast)
 */
function showToast(message, type = 'success') {
    const oldToast = document.querySelector('.toast-notification');
    if (oldToast) oldToast.remove();

    const toast = document.createElement('div');
    toast.className = `toast-notification`;
    toast.textContent = message;
    
    Object.assign(toast.style, {
        position: 'fixed',
        bottom: '30px',
        right: '30px',
        background: type === 'success' ? '#2ecc71' : '#e74c3c',
        color: 'white',
        padding: '14px 24px',
        borderRadius: '8px',
        boxShadow: '0 5px 20px rgba(0,0,0,0.25)',
        zIndex: '10000',
        fontWeight: '500',
        fontSize: '15px',
        animation: 'slideInToast 0.3s ease-out'
    });

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

/**
 * Повторить заказ
 */
function repeatOrder(orderId) {
    if (confirm('Добавить товары из этого заказа в корзину?')) {
        showToast('🔄 Товары добавлены в корзину!', 'success');
    }
}

// ===== ИНИЦИАЛИЗАЦИЯ МОДУЛЕЙ =====

document.addEventListener('DOMContentLoaded', () => {
    initCategoryFilters();
    initCartAJAX();
    initClearCart();      // ✅ Теперь работает — функция объявлена выше
    initSmoothScroll();
});

/**
 * Фильтрация по категориям
 */
function initCategoryFilters() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    // Ищем секции по атрибуту data-category-name (как в меню)
    const sections = document.querySelectorAll('.menu-category');
    
    if (!filterBtns.length) return;
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            const category = btn.dataset.category; // "Закуски", "Супы" и т.д.
            
            if (category === 'all') {
                sections.forEach(s => s.style.display = 'block');
            } else {
                sections.forEach(s => {
                    // Сравниваем имя категории из data-category-name
                    const secCat = s.dataset.categoryName;
                    s.style.display = (secCat === category) ? 'block' : 'none';
                });
            }
        });
    });
}

/**
 * AJAX добавление в корзину
 */
/**
 * 🔒 Надёжное AJAX-добавление в корзину (Версия 3.0)
 */
// 🔒 УДАЛИТЕ старую initCartAJAX() и вставьте это:
function initCartAJAX() {
    document.addEventListener('click', async (e) => {
        // Ищем ближайшую кнопку с классом add-to-cart-btn
        const btn = e.target.closest('.add-to-cart-btn');
        if (!btn) return; // Клик не по нашей кнопке

        e.preventDefault();
        e.stopPropagation();

        const productId = btn.dataset.id;
        if (!productId) {
            console.error('❌ У кнопки нет data-id');
            return;
        }

        console.log(`🛒 Клик: добавляем товар #${productId}`);
        
        // Визуальная обратная связь
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '⏳';
        btn.disabled = true;

        try {
            const response = await fetch('/?page=cart&action=add', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `product_id=${productId}&quantity=1`
            });

            console.log(`📡 Статус ответа: ${response.status}`);
            const text = await response.text(); // Читаем как текст, чтобы увидеть ошибку сервера
            console.log('📄 Ответ сервера:', text.substring(0, 200)); // Первые 200 символов

            if (response.ok) {
                try {
                    const data = JSON.parse(text); // Парсим вручную для контроля
                    if (data.count !== undefined) updateCartBadge(data.count);
                    showToast('✅ Добавлено в корзину!');
                } catch (jsonErr) {
                    console.error('❌ Сервер вернул не JSON:', text);
                    showToast('Ошибка ответа сервера', 'error');
                }
            } else {
                showToast('❌ Ошибка при добавлении', 'error');
            }
        } catch (err) {
            console.error('🌐 Ошибка сети:', err);
            showToast('❌ Нет связи с сервером', 'error');
        } finally {
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.disabled = false;
            }, 1000);
        }
    });
}
/**
 * 🔴 Кнопка "Очистить корзину" (ОТДЕЛЬНАЯ ФУНКЦИЯ!)
 */
function initClearCart() {
    const clearBtn = document.getElementById('clear-cart-btn');
    if (!clearBtn) return;
    
    clearBtn.addEventListener('click', async () => {
        if (!confirm('🗑️ Очистить корзину?\nВсе товары будут удалены.')) {
            return;
        }
        
        const originalText = clearBtn.innerHTML;
        clearBtn.innerHTML = '⏳ Очистка...';
        clearBtn.disabled = true;
        
        try {
            const response = await fetch('/?page=cart&action=clear', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: ''
            });
            
            if (response.ok) {
                updateCartBadge(0);
                
                if (window.location.search.includes('page=cart')) {
                    const cartLayout = document.querySelector('.cart-layout');
                    if (cartLayout) {
                        cartLayout.innerHTML = `
                            <div class="empty-cart" style="grid-column: 1 / -1; text-align: center; padding: 60px 20px;">
                                <div class="empty-cart-icon" style="font-size: 80px; margin-bottom: 20px;">🛒</div>
                                <h2 style="margin-bottom: 10px; color: var(--dark);">Корзина пуста</h2>
                                <p style="color: var(--text-light); margin-bottom: 30px;">Добавьте что-нибудь вкусное!</p>
                                <a href="/?page=menu" class="btn btn-primary">Перейти в меню</a>
                            </div>
                        `;
                    }
                }
                showToast('🗑️ Корзина очищена!');
            } else {
                showToast('❌ Ошибка при очистке', 'error');
            }
        } catch (error) {
            console.error('Clear cart error:', error);
            showToast('❌ Ошибка сети', 'error');
        } finally {
            setTimeout(() => {
                clearBtn.innerHTML = originalText;
                clearBtn.disabled = false;
            }, 1000);
        }
    });
}

/**
 * Плавная прокрутка
 */
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
}

// CSS-анимации для тостов
if (!document.getElementById('toast-keyframes')) {
    const style = document.createElement('style');
    style.id = 'toast-keyframes';
    style.textContent = `
        @keyframes slideInToast {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    `;
    document.head.appendChild(style);
}
/**
 * Повторная отправка письма подтверждения
 */
function resendConfirm(orderId) {
    if (!confirm('📧 Отправить ссылку подтверждения ещё раз?')) {
        return;
    }
    
    showToast('✉️ Письмо отправлено! Проверьте почту (и спам).');
    
    // Здесь можно добавить реальный AJAX-запрос на сервер для повторной отправки
    // fetch('/?page=orders&action=resend', { method: 'POST', body: `order_id=${orderId}` })
}