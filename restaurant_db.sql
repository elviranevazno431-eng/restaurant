-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Апр 28 2026 г., 07:06
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `restaurant_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `sort_order`, `created_at`) VALUES
(1, 'Закуски', 'Лёгкие блюда для начала трапезы', 1, '2026-04-27 10:40:01'),
(2, 'Основные блюда', 'Сытные горячие блюда', 2, '2026-04-27 10:40:01'),
(3, 'Супы', 'Первые блюда', 3, '2026-04-27 10:40:01'),
(4, 'Десерты', 'Сладкие завершающие штрихи', 4, '2026-04-27 10:40:01'),
(5, 'Напитки', 'Горячие и холодные напитки', 5, '2026-04-27 10:40:01');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('new','pending','confirmed','cooking','ready','delivered','cancelled') DEFAULT 'new',
  `delivery_address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `confirm_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `delivery_address`, `created_at`, `confirm_token`) VALUES
(1, 1, 1360.00, 'new', NULL, '2026-04-27 11:52:01', NULL),
(2, 1, 2500.00, 'new', NULL, '2026-04-27 11:53:02', NULL),
(3, 1, 680.00, 'confirmed', NULL, '2026-04-27 11:55:58', NULL),
(4, 1, 3130.00, 'new', NULL, '2026-04-27 12:01:28', 'b085182be4fc20a6e2632cb2b252d56aa5f99a0b905bdb4296d60724f1e68e4f'),
(5, 1, 1920.00, 'new', NULL, '2026-04-27 12:22:30', 'ada905ad473d1481a1979e22aeda51e7b70430069166792c617980c108934ea5'),
(6, 1, 2350.00, 'new', NULL, '2026-04-27 12:31:29', 'ea07fd75a8a6e71b73f11388667b4e6cfd9a401fa6d3d535c5ece20176bc3b80'),
(7, 1, 1930.00, 'pending', NULL, '2026-04-27 12:34:02', 'c11898be4a0d2c2d2f7d4f6b3517ea818c6f4a2df00946d71b345ff91ac73417'),
(8, 1, 3430.00, 'pending', NULL, '2026-04-27 12:37:58', '22a9f0d4be9444b659565a15f072b06f234c27a2f760adbb3845ce43da3984cf'),
(9, 1, 2230.00, 'pending', NULL, '2026-04-27 12:40:31', '5772373e179e9d3ded1325876485f0dabc8c1159d5453917b99633eadde8bdfd'),
(10, 1, 4680.00, 'pending', NULL, '2026-04-27 12:54:15', '0bda7e70d7616338a1e4aae7385e8dc29efc9536cd33e1b183b822f07b0e8b6e'),
(11, 1, 3700.00, 'confirmed', NULL, '2026-04-27 13:44:27', NULL),
(12, 1, 1930.00, 'pending', NULL, '2026-04-27 14:35:47', '2242ee7c98e10d715bdf909abf7f0f63190d034a23cf055b6849f5e9434d2d96'),
(13, 1, 680.00, 'pending', NULL, '2026-04-27 15:22:21', '889c290d1e4dc1bf6441469dd6fc4ea817b67baae1cacd68a7007f4c9d9a99b5'),
(14, 1, 680.00, 'pending', NULL, '2026-04-27 15:23:42', '6cbd55301e3f0d379d662c85a228683efe5cb0d39421dda76ced5605787d2c4f'),
(15, 1, 1930.00, 'pending', NULL, '2026-04-27 15:26:41', 'b38985c577451cbcab002576013870e2d3078cd6edb4913f6f56432b21157b6d'),
(16, 1, 320.00, 'pending', NULL, '2026-04-28 04:30:28', '71029f949c21a7590667a5e95d659181f0f27fe492adcb0d26e4641f34c3b0fb'),
(17, 1, 1930.00, 'confirmed', NULL, '2026-04-28 04:36:48', NULL),
(18, 1, 3980.00, 'confirmed', NULL, '2026-04-28 04:37:38', NULL),
(19, 1, 320.00, 'confirmed', NULL, '2026-04-28 04:42:01', NULL),
(20, 1, 2900.00, 'confirmed', NULL, '2026-04-28 04:46:34', NULL),
(21, 1, 1930.00, 'confirmed', NULL, '2026-04-28 04:53:01', NULL),
(22, 1, 320.00, 'confirmed', NULL, '2026-04-28 04:54:36', NULL),
(23, 1, 770.00, 'confirmed', NULL, '2026-04-28 05:00:32', NULL),
(24, 1, 1330.00, 'confirmed', NULL, '2026-04-28 05:02:40', NULL),
(25, 1, 3430.00, 'confirmed', NULL, '2026-04-28 05:03:44', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 1, 2, 680.00),
(2, 2, 2, 2, 1250.00),
(3, 3, 1, 1, 680.00),
(4, 4, 1, 1, 680.00),
(5, 4, 4, 1, 2450.00),
(6, 5, 6, 1, 720.00),
(7, 5, 8, 1, 650.00),
(8, 5, 10, 1, 550.00),
(9, 6, 3, 1, 980.00),
(10, 6, 7, 1, 720.00),
(11, 6, 8, 1, 650.00),
(12, 7, 1, 1, 680.00),
(13, 7, 2, 1, 1250.00),
(14, 8, 3, 1, 980.00),
(15, 8, 4, 1, 2450.00),
(16, 9, 2, 1, 1250.00),
(17, 9, 3, 1, 980.00),
(18, 10, 2, 1, 1250.00),
(19, 10, 3, 1, 980.00),
(20, 10, 4, 1, 2450.00),
(21, 11, 2, 1, 1250.00),
(22, 11, 4, 1, 2450.00),
(23, 12, 1, 1, 680.00),
(24, 12, 2, 1, 1250.00),
(25, 13, 1, 1, 680.00),
(26, 14, 1, 1, 680.00),
(27, 15, 1, 1, 680.00),
(28, 15, 2, 1, 1250.00),
(29, 16, 9, 1, 320.00),
(30, 17, 1, 1, 680.00),
(31, 17, 2, 1, 1250.00),
(32, 18, 3, 1, 980.00),
(33, 18, 4, 1, 2450.00),
(34, 18, 10, 1, 550.00),
(35, 19, 9, 1, 320.00),
(36, 20, 4, 1, 2450.00),
(37, 20, 5, 1, 450.00),
(38, 21, 1, 1, 680.00),
(39, 21, 2, 1, 1250.00),
(40, 22, 9, 1, 320.00),
(41, 23, 5, 1, 450.00),
(42, 23, 9, 1, 320.00),
(43, 24, 1, 1, 680.00),
(44, 24, 8, 1, 650.00),
(45, 25, 3, 1, 980.00),
(46, 25, 4, 1, 2450.00);

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `is_new` tinyint(1) DEFAULT 0,
  `rating` decimal(3,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `price`, `image_url`, `is_new`, `rating`, `created_at`) VALUES
(1, 1, 'Креветки в чесночном соусе', 'Свежие тигровые креветки, обжаренные в ароматном чесночном масле', 680.00, 'assets/img/dish4.png', 1, 4.80, '2026-04-27 10:40:01'),
(2, 1, 'Тартар из говядины', 'Классический французский тартар с каперсами и луком', 1250.00, 'assets/img/dish1.png', 0, 4.90, '2026-04-27 10:40:01'),
(3, 2, 'Утка по-пекински', 'Хрустящая утка с блинчиками и соусом хойсин', 980.00, 'assets/img/dish5.png', 1, 4.70, '2026-04-27 10:40:01'),
(4, 2, 'Стейк Рибай', 'Мраморная говядина, приготовленная medium rare', 2450.00, 'assets/img/dish2.png', 0, 5.00, '2026-04-27 10:40:01'),
(5, 3, 'Борщ', 'Классический украинский борщ со сметаной', 450.00, 'assets/img/dish6.png', 0, 4.60, '2026-04-27 10:40:01'),
(6, 3, 'Том Ям', 'Острый тайский суп с креветками', 720.00, 'assets/img/dish4.png', 1, 4.80, '2026-04-27 10:40:01'),
(7, 4, 'Шоколадный фондан', 'Тёплый шоколадный кекс с жидкой начинкой', 720.00, 'assets/img/dish3.png', 0, 4.90, '2026-04-27 10:40:01'),
(8, 4, 'Тирамису', 'Итальянский десерт с маскарпоне и кофе', 650.00, 'assets/img/dish8.png', 0, 4.70, '2026-04-27 10:40:01'),
(9, 5, 'Зелёный чай', 'Китайский зелёный чай высшего сорта', 320.00, 'assets/img/dish9.png', 0, 4.50, '2026-04-27 10:40:01'),
(10, 5, 'Мохито', 'Освежающий коктейль с мятой и лаймом', 550.00, 'assets/img/dish10.png', 1, 4.60, '2026-04-27 10:40:01');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email_token` varchar(255) DEFAULT NULL,
  `token_expires` datetime DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `phone`, `address`, `email_token`, `token_expires`, `is_verified`, `created_at`, `updated_at`) VALUES
(1, 'Неважно Эльвира', 'alviranevazhno@yandex.ru', '$2y$10$9Bio.VEUycYr6vZERknOQ.rmRrocd3JtAvyE7CfzqCf0l2bvUPYuW', NULL, NULL, '830cb137b3b483ffbc3b8e8d26a976ac5880584b327848fb1793406dc6047725', NULL, 1, '2026-04-27 11:51:33', '2026-04-27 11:51:33');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_orders_user` (`user_id`),
  ADD KEY `idx_orders_status` (`status`);

--
-- Индексы таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_products_category` (`category_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_email` (`email`),
  ADD KEY `idx_users_token` (`email_token`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT для таблицы `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Ограничения внешнего ключа таблицы `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
