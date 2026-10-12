<?php 
$page = 'menu';
require_once __DIR__ . '/../Controllers/MenuController.php';
$controller = new App\Controllers\MenuController();
$controller->index();
?>