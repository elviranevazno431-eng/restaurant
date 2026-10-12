<?php
class MockPDOStatement {
    private $emailToCheck;
    public function execute($params) {
        $this->emailToCheck = $params[0] ?? '';
        return true;
    }
    public function fetch() {
        foreach (DBStorage::$users as $u) {
            if ($u['email'] === $this->emailToCheck) return $u;
        }
        return false;
    }
}

class MockPDO {
    public function prepare($sql) { return new MockPDOStatement(); }
}

class DBStorage {
    public static $users = [];
    public static $products = [];
    public static $orders = [];

    public static function getInstance() { return new MockPDO(); }

    public static function insertUser(array $data): bool {
        $data['id'] = count(self::$users) + 1;
        $data['created_at'] = date('Y-m-d H:i:s');
        self::$users[] = $data;
        return true;
    }

    public static function reset(): void {
        self::$users = []; self::$products = []; self::$orders = [];
    }
}