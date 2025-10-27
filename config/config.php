<?php

return [
    'db' => [
        'host' => getenv("MYSQL_HOST") ?: '127.0.0.1', // Khi deploy bằng Docker, sử dụng tên dịch vụ `db` làm host
        'port' => 3306,
        'database' => getenv("MYSQL_DATABASE") ?: '422000422702-quanlysanxuat',
        'username' => getenv("MYSQL_USER") ?: 'root',
        'password' => getenv("MYSQL_PASSWORD") ?: '',
        'charset' => 'utf8mb4',
    ],
    'app' => [
        'name' => 'Quản lý sản xuất',
    ],
    'auth' => [
        'default_password' => '1111'
    ]
];
