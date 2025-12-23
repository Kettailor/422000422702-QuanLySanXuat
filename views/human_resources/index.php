<?php
$employeeCount = count($employees);
$roleMap = $roleMap ?? [];
$scopeNote = $scopeNote ?? null;
$showSalaryLink = $showSalaryLink ?? true;
$showTimekeepingLink = $showTimekeepingLink ?? true;
$activeEmployees = array_filter($employees, static function ($employee) {
    return ($employee['TrangThai'] ?? '') === 'Đang làm việc';
});
$activeCount = count($activeEmployees);
$inactiveCount = max($employeeCount - $activeCount, 0);
$avgCoefficient = 0.0;
$latestWorkingTime = null;

if ($employeeCount > 0) {
    $sumCoefficient = 0.0;
    foreach ($employees as $employee) {
        $sumCoefficient += (float) ($employee['HeSoLuong'] ?? 0);
        $workingTime = $employee['ThoiGianLamViec'] ?? null;
        if ($workingTime) {
            if (!$latestWorkingTime || strtotime($workingTime) > strtotime($latestWorkingTime)) {
                $latestWorkingTime = $workingTime;
            }
        }
    }
    $avgCoefficient = $sumCoefficient / $employeeCount;
}

$avgCoefficientLabel = number_format($avgCoefficient, 2, ',', '.');
$latestWorkingLabel = $latestWorkingTime ? date('d/m/Y', strtotime($latestWorkingTime)) : 'Chưa cập nhật';
$activeRate = $employeeCount > 0 ? round(($activeCount / $employeeCount) * 100, 1) : 0;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Quản lý nhân sự</h3>
        <p class="text-muted mb-0">Tổng quan lực lượng lao động, biến động nhân sự và các chỉ số vận hành liên quan.</p>
    </div>
    <div class="d-flex gap-2">
        <?php if ($showSalaryLink): ?>
            <a href="?controller=salary&action=index" class="btn btn-outline-info"><i class="bi bi-cash-stack me-2"></i>Bảng lương</a>
        <?php endif; ?>
        <?php if ($showTimekeepingLink): ?>
            <a href="?controller=timekeeping&action=index" class="btn btn-outline-secondary"><i class="bi bi-calendar-check me-2"></i>Phân công &amp; chấm công</a>
        <?php endif; ?>
        <a href="?controller=human_resources&action=create" class="btn btn-primary"><i class="bi bi-person-plus me-2"></i>Thêm nhân sự</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-primary bg-opacity-10 text-primary"><i class="bi bi-people"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Tổng nhân sự</div>
                <div class="fs-3 fw-bold"><?= number_format($employeeCount) ?></div>
                <div class="small text-muted">Tỷ lệ hoạt động: <?= number_format($activeRate, 1, ',', '.') ?>%</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-success bg-opacity-10 text-success"><i class="bi bi-person-check"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Đang làm việc</div>
                <div class="fs-3 fw-bold"><?= number_format($activeCount) ?></div>
                <div class="small text-muted">Tập trung nguồn lực chính</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-warning bg-opacity-10 text-warning"><i class="bi bi-person-dash"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Tạm nghỉ</div>
                <div class="fs-3 fw-bold"><?= number_format($inactiveCount) ?></div>
                <div class="small text-muted">Theo dõi điều chỉnh kế hoạch</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-info bg-opacity-10 text-info"><i class="bi bi-graph-up"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Hệ số lương TB</div>
                <div class="fs-3 fw-bold"><?= $avgCoefficientLabel ?></div>
                <div class="small text-muted">Cập nhật: <?= htmlspecialchars($latestWorkingLabel) ?></div>
            </div>
        </div>
    </div>
</div>

<div class="alert alert-info d-flex align-items-center" role="alert">
    <i class="bi bi-lightning-charge me-2"></i>
    <div><?= htmlspecialchars($scopeNote ?? 'Đồng bộ dữ liệu lương, kế hoạch sản xuất và chấm công để tối ưu chi phí nhân sự và năng suất vận hành.') ?></div>
</div>

<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-semibold mb-1">Danh sách nhân sự</h5>
            <p class="text-muted mb-0">Quản lý hồ sơ, trạng thái và liên kết nghiệp vụ của từng nhân viên.</p>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-light text-dark">Tổng <?= number_format($employeeCount) ?></span>
            <span class="badge badge-soft-success">Đang làm việc <?= number_format($activeCount) ?></span>
            <span class="badge badge-soft-warning">Tạm nghỉ <?= number_format($inactiveCount) ?></span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr>
                <th>Mã NV</th>
                <th>Họ tên</th>
                <th>Chức vụ</th>
                <th>Ngày sinh</th>
                <th>Ngày vào làm</th>
                <th>Trạng thái</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($employees as $employee): ?>
                <tr>
                    <td class="fw-semibold"><?= htmlspecialchars($employee['IdNhanVien']) ?></td>
                    <td>
                        <div class="fw-medium"><?= htmlspecialchars($employee['HoTen']) ?></div>
                        <div class="text-muted small">Hệ số: <?= htmlspecialchars($employee['HeSoLuong']) ?></div>
                    </td>
                    <td>
                        <div class="fw-medium"><?= htmlspecialchars($employee['ChucVu']) ?></div>
                        <div class="text-muted small">
                            <?php
                            $roleId = $employee['IdVaiTro'] ?? '';
                            $roleName = $roleMap[$roleId]['TenVaiTro'] ?? $roleId;
                            ?>
                            <?= $roleName ? htmlspecialchars($roleName) : 'Chưa phân quyền' ?>
                        </div>
                    </td>
                    <td><?= $employee['NgaySinh'] ? date('d/m/Y', strtotime($employee['NgaySinh'])) : '-' ?></td>
                    <td><?= $employee['ThoiGianLamViec'] ? date('d/m/Y', strtotime($employee['ThoiGianLamViec'])) : '-' ?></td>
                    <td>
                        <span class="badge <?= ($employee['TrangThai'] ?? '') === 'Đang làm việc' ? 'badge-soft-success' : 'badge-soft-warning' ?>">
                            <?= htmlspecialchars($employee['TrangThai']) ?>
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="table-actions">
                            <a class="btn btn-sm btn-outline-secondary" href="?controller=human_resources&action=read&id=<?= urlencode($employee['IdNhanVien']) ?>">Chi tiết</a>
                            <a class="btn btn-sm btn-outline-primary" href="?controller=human_resources&action=edit&id=<?= urlencode($employee['IdNhanVien']) ?>">Sửa</a>
                            <a class="btn btn-sm btn-outline-info" href="?controller=human_resources&action=read&id=<?= urlencode($employee['IdNhanVien']) ?>">Chi tiết</a>
                            <a class="btn btn-sm btn-outline-danger" href="?controller=human_resources&action=delete&id=<?= urlencode($employee['IdNhanVien']) ?>" onclick="return confirm('Xác nhận xóa nhân sự này?');">Xóa</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
