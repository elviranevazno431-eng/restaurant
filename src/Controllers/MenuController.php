<?php
namespace App\Controllers;
use DBStorage;

class MenuController {
    public function index() {
        $pdo = DBStorage::getInstance();
        
        // 1. Категории
        $categories = $pdo->query("SELECT * FROM categories ORDER BY id")->fetchAll();
        
        // 2. Блюда
        $stmt = $pdo->query("
            SELECT p.*, c.id as cat_id, c.name as cat_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            ORDER BY c.id, p.name
        ");
        $products = $stmt->fetchAll();
        
        // 3. 🔥 ГРУППИРОВКА (ЭТОГО БЛОКА МОГЛО НЕ БЫТЬ!)
        $groupedProducts = [];
        foreach ($products as $p) {
            $cid = $p['cat_id'] ?? 0;
            $cname = $p['cat_name'] ?: 'Другое';
            if (!isset($groupedProducts[$cid])) {
                $groupedProducts[$cid] = ['name' => $cname, 'items' => []];
            }
            $groupedProducts[$cid]['items'][] = $p;
        }
        
        // 4. Передача в шаблон
        $page = 'menu';
        require_once __DIR__ . '/../Views/menu_view.php'; // 🔴 Путь должен быть верным!
    }
}