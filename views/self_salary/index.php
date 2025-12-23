<?php
$summary = $summary ?? ['total' => 0, 'pending' => 0, 'approved' => 0, 'paid' => 0, 'total_amount' => 0];
$payrolls = $payrolls ?? [];
$employee = $employee ?? [];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Bảng lương cá nhân</h3>
        <p class="text-muted mb-0">Theo dõi các kỳ lương và trạng thái chi trả của bạn.</p>
    </div>
    <a href="?controller=auth&action=profile" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Hồ sơ cá nhân
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card p-4 h-100">
            <h5 class="mb-3">Tổng quan lương</h5>
            <ul class="list-unstyled mb-0">
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span>Tổng kỳ lương</span>
                    <span class="fw-semibold"><?= number_format($summary['total'] ?? 0) ?></span>
                </li>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span>Đang chờ duyệt</span>
                    <span class="fw-semibold"><?= number_format($summary['pending'] ?? 0) ?></span>
                </li>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span>Đã duyệt</span>
                    <span class="fw-semibold"><?= number_format($summary['approved'] ?? 0) ?></span>
                </li>
                <li class="d-flex justify-content-between py-2">
                    <span>Tổng thu nhập</span>
                    <span class="fw-semibold"><?= number_format($summary['total_amount'] ?? 0, 0, ',', '.') ?> đ</span>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card p-4 h-100">
            <h5 class="mb-3">Danh sách bảng lương</h5>
            <?php if (empty($payrolls)): ?>
                <div class="text-muted">Chưa có dữ liệu bảng lương.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>Tháng</th>
                            <th>Tổng thu nhập</th>
                            <th>Trạng thái</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($payrolls as $payroll): ?>
                            <tr>
                                <td><?= htmlspecialchars($payroll['ThangNam'] ?? '-') ?></td>
                                <td><?= number_format($payroll['TongThuNhap'] ?? 0, 0, ',', '.') ?> đ</td>
                                <td><?= htmlspecialchars($payroll['TrangThai'] ?? 'Đang cập nhật') ?></td>
                                <td>
                                    <a class="btn btn-sm btn-outline-primary" href="?controller=self_salary&action=read&id=<?= urlencode($payroll['IdBangLuong'] ?? '') ?>">Chi tiết</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
