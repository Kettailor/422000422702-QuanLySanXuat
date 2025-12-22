<?php
$currentController = $_GET['controller'] ?? 'dashboard';
$role = is_array($user) ? ($user['IdVaiTro'] ?? null) : null;
$actualRole = is_array($user) ? ($user['ActualIdVaiTro'] ?? ($user['OriginalIdVaiTro'] ?? $role)) : null;
$isImpersonating = is_array($user) && !empty($user['IsImpersonating']);
$canAccess = function (array $roles) use ($role, $actualRole, $isImpersonating): bool {
    if (!$role) {
        return false;
    }

    if (in_array($actualRole, ['VT_ADMIN', 'VT_BAN_GIAM_DOC'], true) && !$isImpersonating) {
        return true;
    }

    if (in_array($actualRole, ['VT_ADMIN', 'VT_BAN_GIAM_DOC'], true) && array_intersect(['VT_ADMIN', 'VT_BAN_GIAM_DOC'], $roles)) {
        return true;
    }

    return in_array($role, $roles, true);
};
?>
<nav class="sidebar">
    <div class="logo">
        <span class="bi bi-grid-1x2-fill"></span>
        <span>SV5TOT Keyboard Ops</span>
    </div>
    <div class="nav flex-column">
        <a class="nav-link <?= $currentController === 'dashboard' ? 'active' : '' ?>" href="?controller=dashboard&action=index">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <?php if ($canAccess(['VT_KINH_DOANH', 'VT_BAN_GIAM_DOC'])): ?>
            <a class="nav-link <?= $currentController === 'order' ? 'active' : '' ?>" href="?controller=order&action=index">
                <i class="bi bi-receipt"></i> Đơn hàng
            </a>
        <?php endif; ?>
        <?php if ($canAccess(['VT_BAN_GIAM_DOC', 'VT_QUANLY_XUONG', 'VT_KHO_TRUONG'])): ?>
            <a class="nav-link <?= $currentController === 'plan' ? 'active' : '' ?>" href="?controller=plan&action=index">
                <i class="bi bi-kanban"></i> Kế hoạch sản xuất
            </a>
        <?php endif; ?>
        <?php if ($canAccess(['VT_QUANLY_XUONG', 'VT_NHANVIEN_SANXUAT'])): ?>
            <a class="nav-link <?= $currentController === 'factory_plan' ? 'active' : '' ?>" href="?controller=factory_plan&action=index">
                <i class="bi bi-building"></i> Kế hoạch xưởng
            </a>
        <?php endif; ?>
        <?php if ($canAccess(['VT_QUANLY_XUONG', 'VT_BAN_GIAM_DOC'])): ?>
            <a class="nav-link <?= $currentController === 'workshop' ? 'active' : '' ?>" href="?controller=workshop&action=index">
                <i class="bi bi-houses"></i> Quản lý xưởng
            </a>
        <?php endif; ?>
        <?php if ($canAccess(['VT_QUANLY_XUONG', 'VT_BAN_GIAM_DOC', 'VT_KHO_TRUONG'])): ?>
            <a class="nav-link <?= $currentController === 'timekeeping' ? 'active' : '' ?>" href="?controller=timekeeping&action=index">
                <i class="bi bi-stopwatch"></i> Chấm công
            </a>
        <?php endif; ?>
        <?php if ($canAccess(['VT_NHANVIEN_SANXUAT', 'VT_NHANVIEN_KHO', 'VT_KHO_TRUONG'])): ?>
            <a class="nav-link <?= $currentController === 'self_timekeeping' ? 'active' : '' ?>" href="?controller=self_timekeeping&action=index">
                <i class="bi bi-fingerprint"></i> Tự chấm công
            </a>
        <?php endif; ?>
        <?php if ($canAccess(['VT_BAN_GIAM_DOC', 'VT_KHO_TRUONG'])): ?>
            <a class="nav-link <?= $currentController === 'human_resources' ? 'active' : '' ?>" href="?controller=human_resources&action=index">
                <i class="bi bi-people"></i> Nhân sự
            </a>
        <?php endif; ?>
        <?php if ($canAccess(['VT_BAN_GIAM_DOC'])): ?>
            <a class="nav-link <?= $currentController === 'report' ? 'active' : '' ?>" href="?controller=report&action=index">
                <i class="bi bi-file-earmark-bar-graph"></i> Thống Kê Báo Cáo
            </a>
        <?php endif; ?>
        <?php if ($canAccess(['VT_KIEM_SOAT_CL', 'VT_QUANLY_XUONG', 'VT_BAN_GIAM_DOC'])): ?>
        <div class="nav-group">
            <a class="nav-link <?= in_array($currentController, ['quality']) ? 'active' : '' ?>"
            data-bs-toggle="collapse"
            href="#submenu-quality"
            role="button"
            aria-expanded="<?= in_array($currentController, ['quality']) ? 'true' : 'false' ?>"
            aria-controls="submenu-quality">
            <i class="bi bi-shield-check me-2"></i> Chất lượng
            </a>
                <div class="collapse <?= in_array($currentController, ['quality', 'suddenly']) ? 'show' : '' ?>" id="submenu-quality">
                <ul class="btn-toggle-nav list-unstyled fw-normal small ms-3">
                <li>
                    <a href="?controller=quality&action=index"
                    class="nav-link <?= ($currentController === 'quality' && $currentAction === 'index') ? 'active' : '' ?>">
                    <i class="bi bi-clipboard-data me-1"></i>Đánh giá thành phẩm</a>
                    <a href="?controller=suddenly&action=index"
                    class="nav-link <?= ($currentController === 'suddenly' && $currentAction === 'index') ? 'active' : '' ?>">
                    <i class="bi bi-lightning-charge me-1"></i>Kiểm tra đột xuất</a>
                </li>
                </ul>
                </div>
        <?php endif; ?>
        <?php if ($canAccess(['VT_NHANVIEN_KHO', 'VT_KHO_TRUONG', 'VT_QUANLY_XUONG'])): ?>
            <a class="nav-link <?= $currentController === 'warehouse' ? 'active' : '' ?>" href="?controller=warehouse&action=index">
                <i class="bi bi-boxes"></i> Kho hàng
            </a>
        <?php endif; ?>
        <?php if ($canAccess(['VT_NHANVIEN_KHO', 'VT_KHO_TRUONG'])): ?>
            <a class="nav-link <?= $currentController === 'warehouse_sheet' ? 'active' : '' ?>" href="?controller=warehouse_sheet&action=index">
                <i class="bi bi-journal-text"></i> Phiếu kho
            </a>
        <?php endif; ?>
        <?php if ($canAccess(['VT_KETOAN', 'VT_KINH_DOANH'])): ?>
            <a class="nav-link <?= $currentController === 'bill' ? 'active' : '' ?>" href="?controller=bill&action=index">
                <i class="bi bi-file-earmark-text"></i> Hóa đơn
            </a>
        <?php endif; ?>
        <?php if ($canAccess(['VT_KETOAN', 'VT_BAN_GIAM_DOC'])): ?>
            <a class="nav-link <?= $currentController === 'salary' ? 'active' : '' ?>" href="?controller=salary&action=index">
                <i class="bi bi-cash-stack"></i> Bảng lương
            </a>
        <?php endif; ?>
        <?php if ($actualRole === 'VT_ADMIN'): ?>
            <a class="nav-link <?= $currentController === 'adminImpersonation' ? 'active' : '' ?>" href="?controller=adminImpersonation&action=index">
                <i class="bi bi-person-badge"></i> Giả lập vai trò
            </a>
            <a class="nav-link" href="?controller=account&action=index">
                <i class="bi bi-person-circle"></i> Tài khoản
            </a>
            <a class="nav-link" href="?controller=account&action=auditLog">
                <i class="bi bi-journal-check"></i> Nhật ký hoạt động
            </a>
            <a class="nav-link" href="?controller=admin&action=ticket">
                <i class="bi bi-ticket-detailed"></i> Yêu cầu hỗ trợ
            </a>
            <a class="nav-link" href="?controller=setting&action=index">
                <i class="bi bi-gear"></i> Cài đặt
            </a>
        <?php endif; ?>
    </div>
    <button class="btn-close position-absolute top-0 end-0 m-3 text-white d-lg-none" data-toggle="sidebar" aria-label="Đóng menu"></button>
</nav>
<div class="sidebar-backdrop d-lg-none" data-toggle="sidebar"></div>
<div class="main-wrapper">
    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-outline-primary d-lg-none" data-toggle="sidebar"><i class="bi bi-list"></i></button>
            <div class="fw-semibold text-secondary">SV5TOT Keyboard Manufacturing Hub</div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <?php if ($isImpersonating): ?>
                <span class="badge bg-warning text-dark">
                    Đang giả lập: <?= htmlspecialchars($user['TenVaiTro'] ?? $role ?? 'Không xác định') ?>
                </span>
                <a href="?impersonation=stop" class="btn btn-outline-warning btn-sm">Hủy giả lập</a>
            <?php elseif ($actualRole === 'VT_ADMIN'): ?>
                <a href="?controller=adminImpersonation&action=index" class="btn btn-outline-secondary btn-sm">Giả lập vai trò</a>
            <?php endif; ?>
            <div class="search-bar d-none d-md-block">
                <input type="search" class="form-control" placeholder="Tìm nhanh đơn hàng, kế hoạch SV5TOT...">
            </div>
            <div class="dropdown notification-dropdown">
                <button class="btn btn-light border position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Xem thông báo">
                    <i class="bi <?= $unreadNotifications > 0 ? 'bi-bell-fill text-primary' : 'bi-bell' ?> fs-5"></i>
                    <?php if ($unreadNotifications > 0): ?>
                        <span class="badge rounded-pill bg-danger notification-badge"><?= $unreadNotifications ?></span>
                    <?php endif; ?>
                </button>
                <div class="dropdown-menu dropdown-menu-end shadow notification-menu p-0">
                    <div class="notification-header px-3 py-2 border-bottom">
                        <div class="fw-semibold">Thông báo</div>
                        <small class="text-muted">
                            <?= $unreadNotifications > 0 ? $unreadNotifications . ' thông báo chưa đọc' : 'Tất cả đã được xem' ?>
                        </small>
                    </div>
                    <div class="notification-list">
                        <?php if (!empty($notifications)): ?>
                            <?php foreach ($notifications as $notification): ?>
                                <?php if (!is_array($notification)) {
                                    continue;
                                } ?>
                                <?php
                                    $title = $notification['title'] ?? 'Thông báo hệ thống';
                                $message = $notification['message'] ?? null;
                                $time = $notification['time'] ?? null;
                                $link = $notification['link'] ?? null;
                                $isRead = !empty($notification['is_read']) || !empty($notification['read_at']);
                                ?>
                                <div class="notification-item px-3 py-2 <?= $isRead ? '' : 'notification-unread' ?>">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <span class="fw-semibold text-truncate flex-grow-1"><?= htmlspecialchars($title) ?></span>
                                        <?php if ($time): ?>
                                            <small class="text-muted flex-shrink-0"><?= htmlspecialchars($time) ?></small>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($message): ?>
                                        <div class="text-muted small mt-1"><?= htmlspecialchars($message) ?></div>
                                    <?php endif; ?>
                                    <?php if ($link): ?>
                                        <a href="<?= htmlspecialchars($link) ?>" class="stretched-link"></a>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="notification-empty text-center text-muted py-4">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                Chưa có thông báo mới
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="notification-footer border-top px-3 py-2">
                        <a href="?controller=notifications&action=index" class="text-decoration-none small">Xem tất cả thông báo</a>
                    </div>
                </div>
            </div>
            <a href="?controller=auth&action=profile" class="btn btn-light border d-flex align-items-center gap-2">
                <i class="bi bi-person-circle"></i>
                <span>
                    <?= htmlspecialchars($user['TenDangNhap'] ?? 'Khách') ?>
                    <?php if (!empty($user['TenVaiTro'])): ?>
                        <small class="text-muted d-block" style="line-height: 1;">
                            <?= htmlspecialchars($user['TenVaiTro']) ?>
                        </small>
                    <?php endif; ?>
                </span>
            </a>
            <a href="?controller=auth&action=logout" class="btn btn-outline-danger">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </header>
    <main class="content-wrapper">
        <?php if (!empty($flash)): ?>
            <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
                <div class="toast align-items-center text-bg-<?= $flash['type'] ?> border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            <?= htmlspecialchars($flash['message']) ?>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
