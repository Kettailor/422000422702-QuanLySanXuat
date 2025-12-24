<?php
$currentController = $_GET['controller'] ?? 'dashboard';
$currentAction = $_GET['action'] ?? 'index';
$role = is_array($user) ? ($user['IdVaiTro'] ?? null) : null;
$actualRole = is_array($user) ? ($user['ActualIdVaiTro'] ?? ($user['OriginalIdVaiTro'] ?? $role)) : null;
$isImpersonating = is_array($user) && !empty($user['IsImpersonating']);
$adminFlow = $_SESSION['admin_flow'] ?? 'main';
$adminFullAccess = $actualRole === 'VT_ADMIN' && !$isImpersonating && $adminFlow === 'test';
$canAccess = function (array $roles) use ($role, $actualRole, $adminFullAccess): bool {
    if (!$role) {
        return false;
    }

    if ($adminFullAccess) {
        return true;
    }

    if (array_intersect(['VT_ADMIN'], $roles) && ($actualRole === 'VT_ADMIN')) {
        return true;
    }

    return in_array($role, $roles, true);
};

$workshopManagerRoles = [
    'VT_TRUONG_XUONG_KIEM_DINH',
    'VT_TRUONG_XUONG_LAP_RAP_DONG_GOI',
    'VT_TRUONG_XUONG_SAN_XUAT',
    'VT_TRUONG_XUONG_LUU_TRU',
];

$showOrders = $canAccess(['VT_KINH_DOANH', 'VT_BAN_GIAM_DOC']);
$showPlan = $canAccess(['VT_BAN_GIAM_DOC']);
$showWorkshopPlan = $canAccess(array_merge($workshopManagerRoles, ['VT_NHANVIEN_SANXUAT']));
$showWorkshopPlanPersonal = in_array($role, ['VT_NHANVIEN_SANXUAT', 'VT_NHANVIEN_KHO'], true);
$showWorkshop = $canAccess(array_merge(['VT_BAN_GIAM_DOC'], $workshopManagerRoles));
$showTimekeeping = $canAccess(array_merge(['VT_BAN_GIAM_DOC'], $workshopManagerRoles));
$showSelfTimekeeping = !empty($role);
$showQuality = $canAccess(array_merge(['VT_KIEM_SOAT_CL'], $workshopManagerRoles));
$showWarehouse = $canAccess(array_merge(['VT_NHANVIEN_KHO'], $workshopManagerRoles));
$showWarehouseSheet = $canAccess(['VT_NHANVIEN_KHO']);
$showHumanResources = $canAccess(['VT_BAN_GIAM_DOC']);
$showReports = $canAccess(['VT_BAN_GIAM_DOC']);
$showBill = $canAccess(['VT_KETOAN']);
$showSalary = $canAccess(['VT_KETOAN', 'VT_BAN_GIAM_DOC']);
$showNotifications = !empty($role);
$showSupport = $actualRole !== 'VT_ADMIN';
$showAdminTools = $actualRole === 'VT_ADMIN';
$isAdminMain = $actualRole === 'VT_ADMIN' && !$adminFullAccess;
$showOperationsSection = $isAdminMain ? false : ($showOrders || $showPlan || $showWorkshopPlan || $showWorkshop || $showTimekeeping || $showHumanResources || $showReports);
$showQualitySection = $isAdminMain ? false : $showQuality;
$showWarehouseSection = $isAdminMain ? false : ($showWarehouse || $showWarehouseSheet);
$showFinanceSection = $isAdminMain ? false : ($showBill || $showSalary);

if ($isAdminMain) {
    $showOrders = true;
    $showPlan = true;
    $showWorkshopPlan = true;
    $showWorkshop = true;
    $showTimekeeping = true;
    $showSelfTimekeeping = true;
    $showWorkshopPlanPersonal = true;
    $showQuality = true;
    $showWarehouse = true;
    $showWarehouseSheet = true;
    $showHumanResources = true;
    $showReports = true;
    $showBill = true;
    $showSalary = true;
}
?>
<nav class="sidebar">
    <div class="logo">
        <a href="?controller=dashboard&action=index" class="logo-link" style="text-decoration: none; color: white; font-weight: 700;">
            <span class="logo-mark">SV5TOT</span>
            <span class="logo-subtitle">Production Hub</span>
        </a>
    </div>
    <div class="nav flex-column d-none d-lg-flex">
        <div class="text-uppercase text-muted small px-3 mt-3">Cá nhân</div>
        <a class="nav-link <?= $currentController === 'auth' && $currentAction === 'profile' ? 'active' : '' ?>" href="?controller=auth&action=profile">
            <i class="bi bi-person-circle"></i> Hồ sơ cá nhân
        </a>
        <?php if ($showSelfTimekeeping): ?>
            <a class="nav-link <?= $currentController === 'self_timekeeping' && $currentAction === 'index' ? 'active' : '' ?>" href="?controller=self_timekeeping&action=index">
                <i class="bi bi-fingerprint"></i> Tự chấm công
            </a>
            <a class="nav-link <?= $currentController === 'self_timekeeping' && $currentAction === 'history' ? 'active' : '' ?>" href="?controller=self_timekeeping&action=history">
                <i class="bi bi-calendar2-check"></i> Lịch sử chấm công
            </a>
        <?php endif; ?>
        <?php if ($showWorkshopPlanPersonal): ?>
            <a class="nav-link <?= $currentController === 'workshop_plan_personal' ? 'active' : '' ?>" href="?controller=workshop_plan_personal&action=index">
                <i class="bi bi-clipboard-check"></i> Kế hoạch được giao
            </a>
        <?php endif; ?>
        <a class="nav-link <?= $currentController === 'setting' ? 'active' : '' ?>" href="?controller=setting&action=index">
            <i class="bi bi-gear"></i> Cài đặt
        </a>
        <?php if ($showNotifications): ?>
            <a class="nav-link <?= $currentController === 'notifications' ? 'active' : '' ?>" href="?controller=notifications&action=index">
                <i class="bi bi-bell"></i> Thông báo
            </a>
        <?php endif; ?>
        <?php if ($showSupport): ?>
            <a class="nav-link <?= $currentController === 'support' ? 'active' : '' ?>" href="?controller=support&action=index">
                <i class="bi bi-life-preserver"></i> Yêu cầu hỗ trợ
            </a>
        <?php endif; ?>

        <?php if ($showOperationsSection): ?>
            <div class="text-uppercase text-muted small px-3 mt-3">Vận hành</div>
        <?php endif; ?>
        <?php if ($showOrders): ?>
            <a class="nav-link <?= $currentController === 'order' ? 'active' : '' ?>" href="?controller=order&action=index">
                <i class="bi bi-receipt"></i> Đơn hàng
            </a>
        <?php endif; ?>
        <?php if ($showPlan): ?>
            <a class="nav-link <?= $currentController === 'plan' ? 'active' : '' ?>" href="?controller=plan&action=index">
                <i class="bi bi-kanban"></i> Kế hoạch sản xuất
            </a>
        <?php endif; ?>
        <?php if ($showWorkshopPlan): ?>
            <a class="nav-link <?= $currentController === 'factory_plan' ? 'active' : '' ?>" href="?controller=factory_plan&action=index">
                <i class="bi bi-building"></i> Kế hoạch xưởng
            </a>
        <?php endif; ?>
        <?php if ($showWorkshop): ?>
            <a class="nav-link <?= $currentController === 'workshop' ? 'active' : '' ?>" href="?controller=workshop&action=index">
                <i class="bi bi-houses"></i> Quản lý xưởng
            </a>
        <?php endif; ?>
        <?php if ($showTimekeeping): ?>
            <a class="nav-link <?= $currentController === 'timekeeping' ? 'active' : '' ?>" href="?controller=timekeeping&action=index">
                <i class="bi bi-stopwatch"></i> Phân công &amp; chấm công
            </a>
        <?php endif; ?>
        <?php if ($showHumanResources): ?>
            <a class="nav-link <?= $currentController === 'human_resources' ? 'active' : '' ?>" href="?controller=human_resources&action=index">
                <i class="bi bi-people"></i> Nhân sự
            </a>
        <?php endif; ?>
        <?php if ($showReports): ?>
            <a class="nav-link <?= $currentController === 'report' ? 'active' : '' ?>" href="?controller=report&action=index">
                <i class="bi bi-file-earmark-bar-graph"></i> Thống kê báo cáo
            </a>
        <?php endif; ?>

        <?php if ($showQualitySection): ?>
        <div class="text-uppercase text-muted small px-3 mt-3">Chất lượng</div>
        <div class="nav-group">
            <a class="nav-link <?= in_array($currentController, ['quality', 'suddenly']) ? 'active' : '' ?>"
            data-bs-toggle="collapse"
            href="#submenu-quality"
            role="button"
            aria-expanded="<?= in_array($currentController, ['quality', 'suddenly']) ? 'true' : 'false' ?>"
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
                    <a href="?controller=quality&action=criterias"
                    class="nav-link <?= ($currentController === 'quality' && $currentAction === 'criterias') ? 'active' : '' ?>">
                    <i class="bi bi-list-ul me-1"></i>Tiêu chí đánh giá</a>
                </li>
                </ul>
                </div>
        </div>
        <?php endif; ?>

        <?php if ($showWarehouseSection): ?>
            <div class="text-uppercase text-muted small px-3 mt-3">Kho & vật tư</div>
        <?php endif; ?>
        <?php if ($showWarehouse): ?>
            <a class="nav-link <?= $currentController === 'warehouse' ? 'active' : '' ?>" href="?controller=warehouse&action=index">
                <i class="bi bi-boxes"></i> Kho hàng
            </a>
        <?php endif; ?>
        <?php if ($showWarehouseSheet): ?>
            <a class="nav-link <?= $currentController === 'warehouse_sheet' ? 'active' : '' ?>" href="?controller=warehouse_sheet&action=index">
                <i class="bi bi-journal-text"></i> Phiếu kho
            </a>
        <?php endif; ?>

        <?php if ($showFinanceSection): ?>
            <div class="text-uppercase text-muted small px-3 mt-3">Tài chính</div>
        <?php endif; ?>
        <?php if ($showBill): ?>
            <a class="nav-link <?= $currentController === 'bill' ? 'active' : '' ?>" href="?controller=bill&action=index">
                <i class="bi bi-file-earmark-text"></i> Hóa đơn
            </a>
        <?php endif; ?>
        <?php if ($showSalary): ?>
            <a class="nav-link <?= $currentController === 'salary' ? 'active' : '' ?>" href="?controller=salary&action=index">
                <i class="bi bi-cash-stack"></i> Bảng lương
            </a>
        <?php endif; ?>

        <?php if ($showAdminTools): ?>
            <div class="text-uppercase text-muted small px-3 mt-3">Quản trị</div>
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
        <?php endif; ?>
    </div>
    <div class="nav flex-column d-lg-none">
        <a class="nav-link <?= $currentController === 'auth' && $currentAction === 'profile' ? 'active' : '' ?>" href="?controller=auth&action=profile">
            <i class="bi bi-person-circle"></i> Hồ sơ cá nhân
        </a>
        <?php if ($showSelfTimekeeping): ?>
            <a class="nav-link <?= $currentController === 'self_timekeeping' && $currentAction === 'index' ? 'active' : '' ?>" href="?controller=self_timekeeping&action=index">
                <i class="bi bi-fingerprint"></i> Tự chấm công
            </a>
            <a class="nav-link <?= $currentController === 'self_timekeeping' && $currentAction === 'history' ? 'active' : '' ?>" href="?controller=self_timekeeping&action=history">
                <i class="bi bi-calendar2-check"></i> Lịch sử chấm công
            </a>
        <?php endif; ?>
        <?php if ($showWorkshopPlanPersonal): ?>
            <a class="nav-link <?= $currentController === 'workshop_plan_personal' ? 'active' : '' ?>" href="?controller=workshop_plan_personal&action=index">
                <i class="bi bi-clipboard-check"></i> Kế hoạch được giao
            </a>
        <?php endif; ?>
        <a class="nav-link <?= $currentController === 'setting' ? 'active' : '' ?>" href="?controller=setting&action=index">
            <i class="bi bi-gear"></i> Cài đặt
        </a>
        <?php if ($showSupport): ?>
            <a class="nav-link <?= $currentController === 'support' ? 'active' : '' ?>" href="?controller=support&action=index">
                <i class="bi bi-life-preserver"></i> Yêu cầu hỗ trợ
            </a>
        <?php endif; ?>
        <?php if ($showOrders): ?>
            <a class="nav-link <?= $currentController === 'order' ? 'active' : '' ?>" href="?controller=order&action=index">
                <i class="bi bi-receipt"></i> Đơn hàng
            </a>
        <?php endif; ?>
        <?php if ($showPlan): ?>
            <a class="nav-link <?= $currentController === 'plan' ? 'active' : '' ?>" href="?controller=plan&action=index">
                <i class="bi bi-kanban"></i> Kế hoạch sản xuất
            </a>
        <?php endif; ?>
        <?php if ($showWorkshopPlan): ?>
            <a class="nav-link <?= $currentController === 'factory_plan' ? 'active' : '' ?>" href="?controller=factory_plan&action=index">
                <i class="bi bi-building"></i> Kế hoạch xưởng
            </a>
        <?php endif; ?>
        <?php if ($showSalary): ?>
            <a class="nav-link <?= $currentController === 'salary' ? 'active' : '' ?>" href="?controller=salary&action=index">
                <i class="bi bi-cash-stack"></i> Bảng lương
            </a>
        <?php endif; ?>
        <?php if ($showNotifications): ?>
            <a class="nav-link <?= $currentController === 'notifications' ? 'active' : '' ?>" href="?controller=notifications&action=index">
                <i class="bi bi-bell"></i> Thông báo
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
            <a href="?controller=dashboard&action=index" class="topbar-brand" style="text-decoration: none; color: var(--text-dark); font-weight: 700;">SV5TOT</a>
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
                                $time = $notification['created_at'] ?? ($notification['time'] ?? null);
                                $link = $notification['link'] ?? null;
                                $isRead = !empty($notification['is_read']) || !empty($notification['read_at']);
                                ?>
                                <div class="notification-item px-3 py-2 <?= $isRead ? '' : 'notification-unread' ?>">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <span class="fw-semibold text-truncate flex-grow-1"><?= htmlspecialchars($title) ?></span>
                                        <?php if ($time): ?>
                                            <small class="text-muted flex-shrink-0"><?= htmlspecialchars(date('d/m/Y H:i', strtotime($time))) ?></small>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($message): ?>
                                        <div class="text-muted small mt-1"><?= htmlspecialchars($message) ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($notification['id'])): ?>
                                        <?php
                                        $redirect = $link ?: '?controller=notifications&action=index';
                                        $readLink = '?controller=notifications&action=read&id=' . urlencode($notification['id']) . '&redirect=' . urlencode($redirect);
                                        ?>
                                        <a href="<?= htmlspecialchars($readLink) ?>" class="stretched-link"></a>
                                    <?php elseif ($link): ?>
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
