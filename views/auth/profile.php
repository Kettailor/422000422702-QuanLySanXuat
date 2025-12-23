<?php
$user = $user ?? [];
$employee = $employee ?? [];
$roleId = $user['IdVaiTro'] ?? null;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Trung tâm hồ sơ</h3>
        <p class="text-muted mb-0">Quản lý thông tin cá nhân và truy cập nhanh các tiện ích dành riêng cho bạn.</p>
    </div>
    <a href="?controller=dashboard&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card p-4 text-center h-100">
            <div class="display-5 text-primary mb-3"><i class="bi bi-person-circle"></i></div>
            <h5 class="fw-bold mb-1"><?= htmlspecialchars($user['TenDangNhap'] ?? 'Khách') ?></h5>
            <p class="text-muted mb-3">
                <?= htmlspecialchars($user['TenVaiTro'] ?? 'N/A') ?>
                <?php if (!empty($user['IdVaiTro'])): ?>
                    <span class="badge bg-light text-muted border ms-1">#<?= htmlspecialchars($user['IdVaiTro']) ?></span>
                <?php endif; ?>
            </p>
            <div class="d-grid gap-2">
                <a href="?controller=setting&action=index" class="btn btn-outline-primary">Cài đặt tài khoản</a>
                <a href="?controller=auth&action=logout" class="btn btn-outline-danger">Đăng xuất</a>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card p-4 mb-4">
            <h5 class="mb-3">Thông tin nhân viên</h5>
            <?php if ($employee): ?>
                <dl class="row mb-0">
                    <dt class="col-sm-4">Họ tên</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($employee['HoTen']) ?></dd>
                    <dt class="col-sm-4">Chức vụ</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($employee['ChucVu']) ?></dd>
                    <dt class="col-sm-4">Ngày sinh</dt>
                    <dd class="col-sm-8"><?= date('d/m/Y', strtotime($employee['NgaySinh'])) ?></dd>
                    <dt class="col-sm-4">Giới tính</dt>
                    <dd class="col-sm-8"><?= $employee['GioiTinh'] ? 'Nam' : 'Nữ' ?></dd>
                    <dt class="col-sm-4">Địa chỉ</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($employee['DiaChi']) ?></dd>
                </dl>
            <?php else: ?>
                <p class="text-muted">Chưa có thông tin nhân viên liên kết.</p>
            <?php endif; ?>
        </div>

        <div class="card p-4">
            <h5 class="mb-3">Tiện ích cá nhân</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <a href="?controller=self_timekeeping&action=history" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-calendar2-check me-2"></i>Lịch sử chấm công
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="?controller=self_salary&action=index" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-cash-coin me-2"></i>Bảng lương cá nhân
                    </a>
                </div>
                <?php if (in_array($roleId, ['VT_NHANVIEN_SANXUAT', 'VT_NHANVIEN_KHO'], true)): ?>
                    <div class="col-md-6">
                        <a href="?controller=workshop_plan_personal&action=index" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-clipboard-check me-2"></i>Kế hoạch xưởng được giao
                        </a>
                    </div>
                <?php endif; ?>
                <?php if ($roleId !== 'VT_ADMIN'): ?>
                    <div class="col-md-6">
                        <a href="?controller=support&action=index" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-life-preserver me-2"></i>Gửi yêu cầu hỗ trợ
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
