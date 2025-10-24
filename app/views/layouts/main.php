<?php
$config = require __DIR__ . '/../../../config/config.php';
?><!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? $config['app_name']) ?></title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <script defer src="/assets/js/app.js"></script>
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <div class="sidebar__header">
            <span class="sidebar__logo">ERP</span>
            <span class="sidebar__title"><?= htmlspecialchars($config['app_name']) ?></span>
        </div>
        <nav class="sidebar__nav">
            <a class="sidebar__link is-active" href="/">Dashboard</a>
            <a class="sidebar__link" href="#">Đơn hàng</a>
            <a class="sidebar__link" href="#">Định mức</a>
            <a class="sidebar__link" href="#">Hoạch định</a>
            <a class="sidebar__link" href="#">Kho</a>
            <a class="sidebar__link" href="#">Sản xuất</a>
            <a class="sidebar__link" href="#">Chất lượng</a>
            <a class="sidebar__link" href="#">Nhân sự</a>
            <a class="sidebar__link" href="#">Báo cáo</a>
        </nav>
    </aside>
    <main class="main">
        <header class="main__header">
            <div class="search-box">
                <input type="text" placeholder="Tìm kiếm thông tin..." />
            </div>
            <div class="user-box">
                <span class="user-box__name">Trần Lê Kiệt</span>
                <div class="user-box__avatar">TLK</div>
            </div>
        </header>
        <div class="main__content">
            <?= $content ?>
        </div>
    </main>
</div>
</body>
</html>
