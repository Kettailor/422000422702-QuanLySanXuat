<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Thông tin cá nhân</h3>
        <p class="text-muted mb-0">Cập nhật hồ sơ người dùng và thông tin liên quan.</p>
    </div>
    <a href="?controller=dashboard&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card p-4 text-center">
            <div class="display-5 text-primary mb-3"><i class="bi bi-person-circle"></i></div>
            <h5 class="fw-bold mb-1"><?= htmlspecialchars($user['TenDangNhap'] ?? 'Khách') ?></h5>
            <p class="text-muted">
                Vai trò: <?= htmlspecialchars($user['TenVaiTro'] ?? 'N/A') ?>
                <?php if (!empty($user['IdVaiTro'])): ?>
                    <span class="badge bg-light text-muted border ms-1">#<?= htmlspecialchars($user['IdVaiTro']) ?></span>
                <?php endif; ?>
            </p>
            <a href="?controller=auth&action=logout" class="btn btn-outline-danger">Đăng xuất</a>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card p-4">
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
    </div>
</div>
