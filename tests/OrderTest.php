<?php
require_once __DIR__ . '/MockStorage.php';

/**
 * Тесты валидации заказов
 */
class OrderTest {
    private static int $passed = 0;
    private static int $failed = 0;
    
    public static function run(): void {
        echo "\n🧪 Запуск тестов валидации заказов...\n";
        echo str_repeat('=', 50) . "\n\n";
        
        // Тест 1: Валидный заказ
        self::test('Валидный заказ', [
            'user_id' => 1,
            'items' => [['id' => 5, 'qty' => 2, 'price' => 450]],
            'total_amount' => 900
        ], true);
        
        // Тест 2: Пустые items
        self::test('Пустые items', [
            'user_id' => 1,
            'items' => [],
            'total_amount' => 0
        ], false);
        
        // Тест 3: Отсутствует user_id
        self::test('Отсутствует user_id', [
            'items' => [['id' => 5, 'qty' => 1]],
            'total_amount' => 450
        ], false);
        
        // Тест 4: Отрицательная сумма
        self::test('Отрицательная сумма', [
            'user_id' => 1,
            'items' => [['id' => 5, 'qty' => 1]],
            'total_amount' => -100
        ], false);
        
        // Тест 5: Превышение максимальной суммы
        self::test('Превышение max суммы', [
            'user_id' => 1,
            'items' => [['id' => 5, 'qty' => 100]],
            'total_amount' => 60000
        ], false);
        
        // Итоги
        echo "\n" . str_repeat('=', 50) . "\n";
        echo "✅ Пройдено: " . self::$passed . "\n";
        echo " Провалено: " . self::$failed . "\n";
        echo "📊 Всего: " . (self::$passed + self::$failed) . "\n\n";
    }
    
    private static function validateOrder(array $order): bool {
        $config = json_decode(file_get_contents(__DIR__ . '/../storage/order.json'), true);
        
        if (empty($order['user_id'])) return false;
        if (empty($order['items'])) return false;
        if ($order['total_amount'] <= 0) return false;
        if ($order['total_amount'] > $config['validation_rules']['max_total']) return false;
        
        return true;
    }
    
    private static function test(string $name, array $data, bool $expected): void {
        $isValid = self::validateOrder($data);
        $passed = ($isValid === $expected);
        
        if ($passed) {
            echo "✅ $name: PASS\n";
            self::$passed++;
        } else {
            echo " $name: FAIL\n";
            self::$failed++;
        }
    }
}

OrderTest::run();