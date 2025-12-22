<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Chi tiết nhân sự</h3>
        <p class="text-muted mb-0">Bức tranh tổng thể về hồ sơ nhân sự, lương thưởng và liên kết nghiệp vụ.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="?controller=human_resources&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
        <?php if ($employee): ?>
            <a href="?controller=human_resources&action=edit&id=<?= urlencode($employee['IdNhanVien']) ?>" class="btn btn-outline-primary"><i class="bi bi-pencil-square"></i> Cập nhật</a>
        <?php endif; ?>
    </div>
</div>

<?php if (!$employee): ?>
    <div class="alert alert-warning">Không tìm thấy nhân sự.</div>
<?php else: ?>
    <?php
    $status = $employee['TrangThai'] ?? 'Chưa xác định';
    $statusBadge = $status === 'Đang làm việc' ? 'badge-soft-success' : 'badge-soft-warning';
    $genderLabel = ($employee['GioiTinh'] ?? 1) ? 'Nam' : 'Nữ';
    $startDate = $employee['ThoiGianLamViec'] ? date('d/m/Y H:i', strtotime($employee['ThoiGianLamViec'])) : '-';
    $birthDate = $employee['NgaySinh'] ? date('d/m/Y', strtotime($employee['NgaySinh'])) : '-';
    $salaryCoefficient = number_format((float) ($employee['HeSoLuong'] ?? 0), 2, ',', '.');
    $address = $employee['DiaChi'] ? htmlspecialchars($employee['DiaChi']) : '-';
    $roleName = $role['TenVaiTro'] ?? ($employee['IdVaiTro'] ?? 'Chưa phân quyền');
    ?>

    <div class="card p-4 mb-4">
        <div class="row align-items-center g-3">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-wrap bg-primary bg-opacity-10 text-primary"><i class="bi bi-person-badge"></i></div>
                    <div>
                        <h4 class="fw-bold mb-1"><?= htmlspecialchars($employee['HoTen']) ?></h4>
                        <div class="text-muted">Mã NV: <?= htmlspecialchars($employee['IdNhanVien']) ?> • <?= htmlspecialchars($employee['ChucVu']) ?></div>
                        <span class="badge <?= $statusBadge ?> mt-2"><?= htmlspecialchars($status) ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                    <a href="?controller=human_resources&action=links&id=<?= urlencode($employee['IdNhanVien']) ?>" class="btn btn-primary">
                        <i class="bi bi-link-45deg me-1"></i>Liên kết nhân sự
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="card h-100 p-4">
                <h6 class="fw-semibold mb-3">Thông tin cá nhân</h6>
                <dl class="row mb-0">
                    <dt class="col-sm-5">Ngày sinh</dt>
                    <dd class="col-sm-7"><?= $birthDate ?></dd>
                    <dt class="col-sm-5">Giới tính</dt>
                    <dd class="col-sm-7"><?= $genderLabel ?></dd>
                    <dt class="col-sm-5">Địa chỉ</dt>
                    <dd class="col-sm-7"><?= $address ?></dd>
                </dl>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100 p-4">
                <h6 class="fw-semibold mb-3">Tổng quan công việc</h6>
                <dl class="row mb-0">
                    <dt class="col-sm-5">Chức vụ</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($employee['ChucVu']) ?></dd>
                    <dt class="col-sm-5">Vai trò hệ thống</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($roleName) ?></dd>
                    <dt class="col-sm-5">Ngày vào làm</dt>
                    <dd class="col-sm-7"><?= $startDate ?></dd>
                    <dt class="col-sm-5">Trạng thái</dt>
                    <dd class="col-sm-7"><span class="badge <?= $statusBadge ?>"><?= htmlspecialchars($status) ?></span></dd>
                </dl>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100 p-4">
                <h6 class="fw-semibold mb-3">Lương & phúc lợi</h6>
                <dl class="row mb-0">
                    <dt class="col-sm-6">Hệ số lương</dt>
                    <dd class="col-sm-6 fw-semibold text-primary"><?= $salaryCoefficient ?></dd>
                    <dt class="col-sm-6">Gắn kết bảng lương</dt>
                    <dd class="col-sm-6">Theo dõi chi tiết</dd>
                    <dt class="col-sm-6">Đánh giá định kỳ</dt>
                    <dd class="col-sm-6">Cập nhật theo kỳ</dd>
                </dl>
                <a href="?controller=human_resources&action=links&id=<?= urlencode($employee['IdNhanVien']) ?>" class="btn btn-sm btn-outline-info mt-3">Xem liên kết nhân sự</a>
            </div>
        </div>
    </div>

    <div class="card p-4">
        <div class="row g-4">
            <div class="col-md-6">
                <h6 class="fw-semibold mb-3">Liên kết vận hành</h6>
                <div class="border rounded p-3 bg-light">
                    <p class="mb-2 text-muted">Tất cả thông tin lương, chấm công và kế hoạch được gom trong một giao diện riêng.</p>
                    <a href="?controller=human_resources&action=links&id=<?= urlencode($employee['IdNhanVien']) ?>" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-link-45deg me-1"></i>Mở liên kết nhân sự
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <h6 class="fw-semibold mb-3">Ghi chú nhân sự</h6>
                <div class="border rounded p-3 bg-light">
                    <p class="text-muted mb-2">Tập trung ghi nhận các sự kiện quan trọng, KPI, khen thưởng hoặc cảnh báo liên quan đến nhân sự.</p>
                    <ul class="mb-0">
                        <li>Trạng thái hồ sơ cần cập nhật định kỳ theo quy trình nhân sự.</li>
                        <li>Ưu tiên đồng bộ dữ liệu với chấm công và bảng lương mỗi kỳ.</li>
                        <li>Đề xuất kế hoạch đào tạo/điều chuyển khi thay đổi năng lực.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
