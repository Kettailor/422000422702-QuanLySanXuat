<?php
session_start();

spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/core/' . $class . '.php',
        __DIR__ . '/controllers/' . $class . '.php',
        __DIR__ . '/models/' . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

$config = require __DIR__ . '/config/config.php';

$controller = $_GET['controller'] ?? 'dashboard';
$action = $_GET['action'] ?? 'index';

ImpersonationMiddleware::handle();

$publicRoutes = [
    'auth' => ['login'],
];

$isAuthenticated = !empty($_SESSION['user']);
if (!$isAuthenticated) {
    if (!isset($publicRoutes[$controller]) || !in_array($action, $publicRoutes[$controller], true)) {
        header('Location: ?controller=auth&action=login');
        exit;
    }
}

$controllerClass = ucfirst($controller) . 'Controller';

if (!class_exists($controllerClass)) {
    http_response_code(404);
    echo '<h1>404 - Controller not found</h1>';
    exit;
}

$controllerInstance = new $controllerClass();

if (!method_exists($controllerInstance, $action)) {
    http_response_code(404);
    echo '<h1>404 - Action not found</h1>';
    exit;
}

$controllerInstance->$action();
