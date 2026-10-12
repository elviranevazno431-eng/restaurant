<?php
require_once __DIR__ . '/MockStorage.php';
require_once __DIR__ . '/../src/Models/UserRegistrationModel.php';

/**
 * Тесты валидации формы регистрации
 */
class ValidateRegisterDataTest {
    private static int $passed = 0;
    private static int $failed = 0;
    
    public static function run(): void {
        echo "\n🧪 Запуск тестов валидации регистрации...\n";
        echo str_repeat('=', 50) . "\n\n";
        
        // Тест 1: Валидные данные
        self::test('Валидные данные', [
            'name' => 'Иван Петров',
            'email' => 'ivan@example.com',
            'password' => 'secure123'
        ], true);
        
        // Тест 2: Пустое имя
        self::test('Пустое имя', [
            'name' => '',
            'email' => 'test@test.com',
            'password' => '123456'
        ], false);
        
        // Тест 3: Имя менее 2 символов
        self::test('Короткое имя', [
            'name' => 'А',
            'email' => 'test@test.com',
            'password' => '123456'
        ], false);
        
        // Тест 4: Некорректный email
        self::test('Некорректный email', [
            'name' => 'Иван',
            'email' => 'invalid-email',
            'password' => '123456'
        ], false);
        
        // Тест 5: Короткий пароль
        self::test('Короткий пароль', [
            'name' => 'Иван',
            'email' => 'ivan@test.com',
            'password' => '123'
        ], false);
        
        // Тест 6: Дубликат email
        MockStorage::$users = [['email' => 'existing@test.com']];
        self::test('Дубликат email', [
            'name' => 'Иван',
            'email' => 'existing@test.com',
            'password' => '123456'
        ], false);
        
        // Итоги
        echo "\n" . str_repeat('=', 50) . "\n";
        echo "✅ Пройдено: " . self::$passed . "\n";
        echo "❌ Провалено: " . self::$failed . "\n";
        echo "📊 Всего: " . (self::$passed + self::$failed) . "\n\n";
    }
    
    private static function test(string $name, array $data, bool $expected): void {
        $errors = App\Models\UserRegistrationModel::validate($data);
        $isValid = empty($errors);
        $passed = ($isValid === $expected);
        
        if ($passed) {
            echo "✅ $name: PASS\n";
            self::$passed++;
        } else {
            echo "❌ $name: FAIL (ожидалось " . ($expected ? 'валидно' : 'невалидно') . ")\n";
            if (!empty($errors)) {
                echo "   Ошибки: " . implode(', ', $errors) . "\n";
            }
            self::$failed++;
        }
    }
}

ValidateRegisterDataTest::run();