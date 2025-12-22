<?php
$summary = $summary ?? ['total' => 0, 'pending' => 0, 'approved' => 0, 'paid' => 0, 'total_amount' => 0.0];
$pending = $pending ?? [];
$permissions = $permissions ?? ['canManage' => false, 'canApprove' => false];
$employeeFilter = $employeeFilter ?? null;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Bảng lương</h3>
        <p class="text-muted mb-0">Theo dõi quá trình tính toán, phê duyệt và chi trả lương cho nhân viên.</p>
    </div>
    <?php if ($permissions['canManage']): ?>
        <div class="d-flex gap-2">
            <a href="?controller=salary&action=create" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Thêm bảng lương</a>
            <form action="?controller=salary&action=recalculateAll" method="post" onsubmit="return confirm('Bạn có chắc muốn tính lại toàn bộ bảng lương?');">
                <button type="submit" class="btn btn-outline-info"><i class="bi bi-cpu me-2"></i>Tính toàn bộ lương</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php if ($employeeFilter): ?>
    <div class="alert alert-info d-flex align-items-center" role="alert">
        <i class="bi bi-person-badge me-2"></i>
        <div>
            Đang xem bảng lương của <strong><?= htmlspecialchars($employeeFilter['HoTen'] ?? '') ?></strong>
            (<?= htmlspecialchars($employeeFilter['IdNhanVien'] ?? '') ?>).
            <a href="?controller=salary&action=index" class="alert-link ms-2">Xóa bộ lọc</a>
        </div>
    </div>
<?php endif; ?>

<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-primary bg-opacity-10 text-primary"><i class="bi bi-cash-stack"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Tổng bảng lương</div>
                <div class="fs-3 fw-bold"><?= number_format($summary['total']) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-warning bg-opacity-10 text-warning"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Chờ duyệt</div>
                <div class="fs-3 fw-bold"><?= number_format($summary['pending']) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-success bg-opacity-10 text-success"><i class="bi bi-check2-circle"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Đã duyệt</div>
                <div class="fs-3 fw-bold"><?= number_format($summary['approved']) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-info bg-opacity-10 text-info"><i class="bi bi-wallet"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Đã chi</div>
                <div class="fs-3 fw-bold"><?= number_format($summary['paid']) ?></div>
                <div class="small text-muted">Tổng chi: <?= number_format($summary['total_amount'], 0, ',', '.') ?> đ</div>
            </div>
        </div>
    </div>
</div>

<?php if ($pending): ?>
    <div class="alert alert-warning d-flex align-items-center" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <div>Có <?= count($pending) ?> bảng lương đang chờ duyệt. Hãy xử lý kịp thời để đảm bảo tiến độ chi trả.</div>
    </div>
<?php endif; ?>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr>
                <th>Nhân viên</th>
                <th>Tháng/Năm</th>
                <th>Lương cơ bản</th>
                <th>Thực nhận</th>
                <th>Trạng thái</th>
                <th>Ngày lập</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($payrolls as $payroll): ?>
                <?php
                $status = $payroll['TrangThai'];
                $badgeClass = 'badge bg-secondary';
                if ($status === 'Chờ duyệt') {
                    $badgeClass = 'badge badge-soft-warning';
                } elseif ($status === 'Đã duyệt') {
                    $badgeClass = 'badge badge-soft-success';
                } elseif ($status === 'Đã chi') {
                    $badgeClass = 'badge bg-info text-white';
                }
                ?>
                <?php
                $monthLabel = $payroll['ThangNam'] ?? '';
                if (preg_match('/^(\d{4})-(\d{2})$/', (string) $monthLabel, $monthMatches)) {
                    $monthLabel = $monthMatches[2] . '/' . $monthMatches[1];
                } elseif (preg_match('/^(\d{4})(\d{2})$/', (string) $monthLabel, $monthMatches)) {
                    $monthLabel = $monthMatches[2] . '/' . $monthMatches[1];
                } elseif (preg_match('/^(\d{2})\/(\d{4})$/', (string) $monthLabel, $monthMatches)) {
                    $monthLabel = $monthMatches[1] . '/' . $monthMatches[2];
                }
                ?>
                <tr>
                    <td><?= htmlspecialchars($payroll['HoTen']) ?></td>
                    <td><?= htmlspecialchars($monthLabel) ?></td>
                    <td><?= number_format($payroll['LuongCoBan'], 0, ',', '.') ?> đ</td>
                    <td class="fw-semibold text-primary"><?= number_format($payroll['TongThuNhap'], 0, ',', '.') ?> đ</td>
                    <td><span class="<?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span></td>
                    <td><?= $payroll['NgayLap'] ? date('d/m/Y', strtotime($payroll['NgayLap'])) : '-' ?></td>
                    <td class="text-end">
                        <div class="table-actions">
                            <a class="btn btn-sm btn-outline-secondary" href="?controller=salary&action=read&id=<?= urlencode($payroll['IdBangLuong']) ?>">Chi tiết</a>
                            <?php if ($permissions['canManage']): ?>
                                <a class="btn btn-sm btn-outline-primary" href="?controller=salary&action=edit&id=<?= urlencode($payroll['IdBangLuong']) ?>">Sửa</a>
                            <?php endif; ?>
                            <?php if ($permissions['canApprove'] && $status === 'Chờ duyệt'): ?>
                                <a class="btn btn-sm btn-outline-success" href="?controller=salary&action=approve&id=<?= urlencode($payroll['IdBangLuong']) ?>">Phê duyệt</a>
                            <?php endif; ?>
                            <?php if ($permissions['canApprove'] && $status === 'Đã duyệt'): ?>
                                <a class="btn btn-sm btn-outline-success" href="?controller=salary&action=finalize&id=<?= urlencode($payroll['IdBangLuong']) ?>">Đã chi</a>
                            <?php endif; ?>
                            <?php if ($permissions['canApprove'] && $status !== 'Chờ duyệt'): ?>
                                <a class="btn btn-sm btn-outline-warning" href="?controller=salary&action=revert&id=<?= urlencode($payroll['IdBangLuong']) ?>">Hoàn trạng thái</a>
                            <?php endif; ?>
                            <?php if ($permissions['canManage']): ?>
                                <a class="btn btn-sm btn-outline-danger" href="?controller=salary&action=delete&id=<?= urlencode($payroll['IdBangLuong']) ?>" onclick="return confirm('Xác nhận xóa bảng lương này?');">Xóa</a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
