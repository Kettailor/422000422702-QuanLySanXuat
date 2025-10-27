<?php
$user = $_SESSION['user'] ?? null;
$actualRole = null;
$isImpersonating = false;
$notifications = $notifications ?? [];
$unreadNotifications = 0;

if ($user) {
    $user = Impersonation::applyToUser($user);
    $actualRole = $user['ActualIdVaiTro'] ?? ($user['OriginalIdVaiTro'] ?? ($user['IdVaiTro'] ?? null));
    $isImpersonating = !empty($user['IsImpersonating']);
} else {
    $user = [];
}

foreach ($notifications as $notification) {
    if (!is_array($notification)) {
        continue;
    }

    $isRead = !empty($notification['is_read']) || !empty($notification['read_at']);

    if (!$isRead) {
        $unreadNotifications++;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>
</head>
<body>
<div class="layout-container">
