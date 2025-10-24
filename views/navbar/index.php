<?php
$currentController = $_GET['controller'] ?? 'dashboard';
$role = $user['IdVaiTro'] ?? null;
$canAccess = function (array $roles) use ($role): bool {
    if (!$role) {
        return false;
    }

    if ($role === 'VT_ADMIN') {
        return true;
    }

    return in_array($role, $roles, true);
};
?>
<nav class="sidebar">
    <div class="logo">
        <span class="bi bi-grid-1x2-fill"></span>
        <span>Sản xuất ERP</span>
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
        <?php if ($canAccess(['VT_BAN_GIAM_DOC', 'VT_QUANLY_XUONG'])): ?>
            <a class="nav-link <?= $currentController === 'plan' ? 'active' : '' ?>" href="?controller=plan&action=index">
                <i class="bi bi-kanban"></i> Kế hoạch sản xuất
            </a>
        <?php endif; ?>
        <?php if ($canAccess(['VT_QUANLY_XUONG', 'VT_NHANVIEN_SANXUAT'])): ?>
            <a class="nav-link <?= $currentController === 'factory_plan' ? 'active' : '' ?>" href="?controller=factory_plan&action=index">
                <i class="bi bi-building"></i> Kế hoạch xưởng
            </a>
        <?php endif; ?>
        <?php if ($canAccess(['VT_BAN_GIAM_DOC', 'VT_NHAN_SU'])): ?>
            <a class="nav-link <?= $currentController === 'human_resources' ? 'active' : '' ?>" href="?controller=human_resources&action=index">
                <i class="bi bi-people"></i> Nhân sự
            </a>
        <?php endif; ?>
        <?php if ($canAccess(['VT_KIEM_SOAT_CL', 'VT_QUANLY_XUONG', 'VT_BAN_GIAM_DOC'])): ?>
            <a class="nav-link <?= $currentController === 'quality' ? 'active' : '' ?>" href="?controller=quality&action=index">
                <i class="bi bi-shield-check"></i> Chất lượng
            </a>
        <?php endif; ?>
        <?php if ($canAccess(['VT_NHANVIEN_KHO', 'VT_QUANLY_XUONG'])): ?>
            <a class="nav-link <?= $currentController === 'warehouse' ? 'active' : '' ?>" href="?controller=warehouse&action=index">
                <i class="bi bi-boxes"></i> Kho hàng
            </a>
        <?php endif; ?>
        <?php if ($canAccess(['VT_NHANVIEN_KHO'])): ?>
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
        <?php if ($role === 'VT_ADMIN'): ?>
            <a class="nav-link" href="#">
                <i class="bi bi-gear"></i> Cài đặt
            </a>
        <?php endif; ?>
    </div>
</nav>
<div>
    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-outline-primary d-lg-none" data-toggle="sidebar"><i class="bi bi-list"></i></button>
            <div class="fw-semibold text-secondary">Sinh viên 5 Tốt ERP</div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="search-bar d-none d-md-block">
                <input type="search" class="form-control" placeholder="Tìm kiếm nhanh...">
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
