<?php
$config = require __DIR__ . '/../../../config/config.php';
?><!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? $config['app_name']) ?></title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body class="auth-body">
<div class="auth-card">
    <?= $content ?>
</div>
</body>
</html>
