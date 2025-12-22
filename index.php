<?php

$envFile = __DIR__.'/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, "\"'");
        if (!getenv($key)) {
            putenv("{$key}={$value}");
        }
    }
}

session_start();

// Load Composer's autoloader if it exists
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

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
    'auth' => ['login', 'forgotPassword'],
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
