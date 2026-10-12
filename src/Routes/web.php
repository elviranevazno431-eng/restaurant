<?php
/**
 * Рефакторинг маршрутизатора
 * Конфигурация маршрутов для расширения
 */
return [
    'home' => [
        'controller' => 'HomeController',
        'method' => 'index',
        'auth' => false,
        'view' => 'home'
    ],
    'menu' => [
        'controller' => 'MenuController',
        'method' => 'index',
        'auth' => false,
        'view' => 'menu'
    ],
    'cart' => [
        'controller' => 'CartController',
        'method' => 'index',
        'auth' => true,
        'view' => 'cart'
    ],
    'profile' => [
        'controller' => 'ProfileController',
        'method' => 'index',
        'auth' => true,
        'view' => 'profile'
    ],
    'orders' => [
        'controller' => 'OrderHistoryController',
        'method' => 'index',
        'auth' => true,
        'view' => 'orders'
    ],
    'login' => [
        'controller' => 'AuthController',
        'method' => 'login',
        'auth' => false,
        'view' => 'form'
    ],
    'register' => [
        'controller' => 'AuthController',
        'method' => 'register',
        'auth' => false,
        'view' => 'form'
    ]
];