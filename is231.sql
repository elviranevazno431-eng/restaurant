-- Полностью очищаем и создаём БД заново
DROP DATABASE IF EXISTS restaurant_db;
CREATE DATABASE restaurant_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE restaurant_db;

-- Таблица пользователей
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    address TEXT DEFAULT NULL,
    email_token VARCHAR(255) DEFAULT NULL,
    token_expires DATETIME DEFAULT NULL,
    is_verified TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Таблица категорий
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Таблица товаров
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(255) DEFAULT NULL,
    is_new TINYINT(1) DEFAULT 0,
    rating DECIMAL(3,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Таблица заказов
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('new', 'confirmed', 'cooking', 'ready', 'delivered', 'cancelled') DEFAULT 'new',
    delivery_address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Таблица позиций заказа
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Индексы (стандартный синтаксис, работает в любом XAMPP)
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_token ON users(email_token);
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(status);

-- Тестовые данные: Категории
INSERT INTO categories (name, description, sort_order) VALUES
('Закуски', 'Лёгкие блюда для начала трапезы', 1),
('Основные блюда', 'Сытные горячие блюда', 2),
('Супы', 'Первые блюда', 3),
('Десерты', 'Сладкие завершающие штрихи', 4),
('Напитки', 'Горячие и холодные напитки', 5);

-- Тестовые данные: Продукты
INSERT INTO products (category_id, name, description, price, image_url, is_new, rating) VALUES
(1, 'Креветки в чесночном соусе', 'Свежие тигровые креветки, обжаренные в ароматном чесночном масле', 680, '/assets/img/shrimp.jpg', 1, 4.8),
(1, 'Тартар из говядины', 'Классический французский тартар с каперсами и луком', 1250, '/assets/img/tartar.jpg', 0, 4.9),
(2, 'Утка по-пекински', 'Хрустящая утка с блинчиками и соусом хойсин', 980, '/assets/img/duck.jpg', 1, 4.7),
(2, 'Стейк Рибай', 'Мраморная говядина, приготовленная medium rare', 2450, '/assets/img/steak.jpg', 0, 5.0),
(3, 'Борщ', 'Классический украинский борщ со сметаной', 450, '/assets/img/borscht.jpg', 0, 4.6),
(3, 'Том Ям', 'Острый тайский суп с креветками', 720, '/assets/img/tomyam.jpg', 1, 4.8),
(4, 'Шоколадный фондан', 'Тёплый шоколадный кекс с жидкой начинкой', 720, '/assets/img/fondant.jpg', 0, 4.9),
(4, 'Тирамису', 'Итальянский десерт с маскарпоне и кофе', 650, '/assets/img/tiramisu.jpg', 0, 4.7),
(5, 'Зелёный чай', 'Китайский зелёный чай высшего сорта', 320, '/assets/img/tea.jpg', 0, 4.5),
(5, 'Мохито', 'Освежающий коктейль с мятой и лаймом', 550, '/assets/img/mojito.jpg', 1, 4.6);