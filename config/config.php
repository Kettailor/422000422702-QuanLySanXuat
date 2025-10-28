<?php

return [
    'db' => [
        'host' => getenv("MYSQL_HOST") ?: '127.0.0.1',
        'port' => getenv("MYSQL_PORT") ?: 3306,
        'database' => getenv("MYSQL_DATABASE") ?: '422000422702-quanlysanxuat',
        'username' => getenv("MYSQL_USER") ?: 'root',
        'password' => getenv("MYSQL_PASSWORD") ?: '',
        'charset' => 'utf8mb4',
    ],
    'app' => [
        'name' => getenv("APP_NAME") ?: 'Quản lý sản xuất',
    ],
    'auth' => [
        'default_password' => getenv("DEFAULT_PASSWORD") ?: '1111'
    ]
];
