<?php
$employee = $employee ?? [];
$payrolls = $payrolls ?? [];
$summary = $summary ?? ['total' => 0, 'total_amount' => 0, 'pending' => 0, 'approved' => 0, 'paid' => 0];

$formatCurrency = static function ($value): string {
    return number_format((float) $value, 0, ',', '.') . ' đ';
};
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h3 class="fw-bold mb-1">Bảng lương cá nhân</h3>
        <p class="text-muted mb-0">Theo dõi thu nhập, bảo hiểm và trạng thái chi trả của bạn.</p>
    </div>
    <a href="?controller=dashboard&action=index" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại tổng quan
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small text-uppercase">Nhân viên</div>
                <div class="fs-5 fw-semibold"><?= htmlspecialchars($employee['HoTen'] ?? 'Chưa cập nhật') ?></div>
                <div class="text-muted small">Mã: <?= htmlspecialchars($employee['IdNhanVien'] ?? '-') ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small text-uppercase">Tổng thu nhập</div>
                <div class="fs-4 fw-semibold text-primary"><?= $formatCurrency($summary['total_amount'] ?? 0) ?></div>
                <div class="text-muted small">Tổng <?= number_format((int) ($summary['total'] ?? 0)) ?> bảng lương</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small text-uppercase">Trạng thái</div>
                <div class="d-flex flex-wrap gap-2 mt-2">
                    <span class="badge bg-warning-subtle text-warning">Chờ duyệt: <?= number_format((int) ($summary['pending'] ?? 0)) ?></span>
                    <span class="badge bg-info-subtle text-info">Đã duyệt: <?= number_format((int) ($summary['approved'] ?? 0)) ?></span>
                    <span class="badge bg-success-subtle text-success">Đã chi: <?= number_format((int) ($summary['paid'] ?? 0)) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0">
        <h5 class="mb-0">Danh sách bảng lương</h5>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Kỳ lương</th>
                    <th>Ngày lập</th>
                    <th>Lương cơ bản</th>
                    <th>Ngày công</th>
                    <th>Phụ cấp + Thưởng</th>
                    <th>Bảo hiểm</th>
                    <th>Thực nhận</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($payrolls)): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Chưa có bảng lương nào.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($payrolls as $payroll): ?>
                        <?php
                        $period = $payroll['ThangNam'] ?? '';
                        $periodLabel = $period ? substr((string) $period, 4, 2) . '/' . substr((string) $period, 0, 4) : '-';
                        $bonusTotal = (float) ($payroll['PhuCap'] ?? 0) + (float) ($payroll['Thuong'] ?? 0);
                        ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($periodLabel) ?></td>
                            <td><?= !empty($payroll['NgayLap']) ? date('d/m/Y', strtotime($payroll['NgayLap'])) : '-' ?></td>
                            <td><?= $formatCurrency($payroll['LuongCoBan'] ?? 0) ?></td>
                            <td><?= number_format((float) ($payroll['SoNgayCong'] ?? 0), 1, ',', '.') ?> công</td>
                            <td><?= $formatCurrency($bonusTotal) ?></td>
                            <td class="text-danger">-<?= $formatCurrency($payroll['TongBaoHiem'] ?? ($payroll['KhauTru'] ?? 0)) ?></td>
                            <td class="fw-semibold text-success"><?= $formatCurrency($payroll['TongThuNhap'] ?? 0) ?></td>
                            <td>
                                <span class="badge bg-light text-dark"><?= htmlspecialchars($payroll['TrangThai'] ?? 'Đang cập nhật') ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
