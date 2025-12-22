<?php
$employee = $employee ?? null;
$payrolls = $payrolls ?? [];
$salarySummary = $salarySummary ?? ['total' => 0, 'total_amount' => 0, 'pending' => 0, 'approved' => 0, 'paid' => 0];
$timekeepingEntries = $timekeepingEntries ?? [];
$plans = $plans ?? [];

$formatDate = static function (?string $value, string $format = 'd/m/Y H:i'): string {
    if (!$value) {
        return '-';
    }
    $timestamp = strtotime($value);
    if ($timestamp === false) {
        return '-';
    }
    return date($format, $timestamp);
};

$completionBadge = static function (?string $status): string {
    $normalized = strtolower(trim((string) $status));
    if ($normalized === '') {
        return 'badge bg-light text-muted';
    }
    if (str_contains($normalized, 'hoàn thành')) {
        return 'badge badge-soft-success';
    }
    if (str_contains($normalized, 'đang')) {
        return 'badge badge-soft-primary';
    }
    if (str_contains($normalized, 'chờ')) {
        return 'badge badge-soft-warning';
    }
    return 'badge bg-light text-dark';
};
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Liên kết nhân sự</h3>
        <p class="text-muted mb-0">Trung tâm điều phối dữ liệu nhân sự, lương, chấm công và kế hoạch sản xuất.</p>
    </div>
    <a href="?controller=human_resources&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php if (!$employee): ?>
    <div class="alert alert-warning">Không tìm thấy nhân sự.</div>
<?php else: ?>
    <div class="card p-4 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <p class="text-uppercase text-muted small mb-1">Hồ sơ nhân sự</p>
                <h4 class="fw-bold mb-1"><?= htmlspecialchars($employee['HoTen']) ?></h4>
                <div class="text-muted">Mã NV: <?= htmlspecialchars($employee['IdNhanVien']) ?> • <?= htmlspecialchars($employee['ChucVu'] ?? '-') ?></div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="#salary" class="btn btn-outline-info btn-sm">Bảng lương</a>
                <a href="#timekeeping" class="btn btn-outline-secondary btn-sm">Chấm công</a>
                <a href="#plans" class="btn btn-outline-primary btn-sm">Kế hoạch</a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4" id="salary">
        <div class="col-lg-3 col-sm-6">
            <div class="card metric-card">
                <div class="icon-wrap bg-primary bg-opacity-10 text-primary"><i class="bi bi-cash-stack"></i></div>
                <div>
                    <div class="text-muted text-uppercase small">Tổng kỳ lương</div>
                    <div class="fs-3 fw-bold"><?= number_format($salarySummary['total']) ?></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card metric-card">
                <div class="icon-wrap bg-success bg-opacity-10 text-success"><i class="bi bi-wallet"></i></div>
                <div>
                    <div class="text-muted text-uppercase small">Tổng thu nhập</div>
                    <div class="fs-3 fw-bold"><?= number_format($salarySummary['total_amount'], 0, ',', '.') ?> đ</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card metric-card">
                <div class="icon-wrap bg-warning bg-opacity-10 text-warning"><i class="bi bi-hourglass-split"></i></div>
                <div>
                    <div class="text-muted text-uppercase small">Chờ duyệt</div>
                    <div class="fs-3 fw-bold"><?= number_format($salarySummary['pending']) ?></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card metric-card">
                <div class="icon-wrap bg-info bg-opacity-10 text-info"><i class="bi bi-check2-circle"></i></div>
                <div>
                    <div class="text-muted text-uppercase small">Đã chi</div>
                    <div class="fs-3 fw-bold"><?= number_format($salarySummary['paid']) ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-semibold mb-1">Bảng lương theo kỳ</h5>
                <p class="text-muted mb-0">Theo dõi thu nhập và trạng thái phê duyệt.</p>
            </div>
            <span class="badge bg-light text-dark"><?= number_format($salarySummary['total']) ?> kỳ</span>
        </div>
        <?php if (!$payrolls): ?>
            <div class="alert alert-light border mb-0">Chưa có dữ liệu bảng lương.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>Tháng/Năm</th>
                        <th>Lương cơ bản</th>
                        <th>Thực nhận</th>
                        <th>Trạng thái</th>
                        <th>Ngày lập</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($payrolls as $payroll): ?>
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
                            <td><?= htmlspecialchars($monthLabel) ?></td>
                            <td><?= number_format($payroll['LuongCoBan'] ?? 0, 0, ',', '.') ?> đ</td>
                            <td class="fw-semibold text-primary"><?= number_format($payroll['TongThuNhap'] ?? 0, 0, ',', '.') ?> đ</td>
                            <td><span class="badge bg-light text-dark"><?= htmlspecialchars($payroll['TrangThai'] ?? '-') ?></span></td>
                            <td><?= $payroll['NgayLap'] ? date('d/m/Y', strtotime($payroll['NgayLap'])) : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <div class="card p-4 mb-4" id="timekeeping">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-semibold mb-1">Chấm công & giờ làm</h5>
                <p class="text-muted mb-0">Theo dõi ca làm, tăng ca và ghi chú quản lý.</p>
            </div>
            <span class="badge bg-light text-dark"><?= number_format(count($timekeepingEntries)) ?> bản ghi</span>
        </div>
        <?php if (!$timekeepingEntries): ?>
            <div class="alert alert-light border mb-0">Chưa có dữ liệu chấm công.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>Ngày làm việc</th>
                        <th>Ca</th>
                        <th>Giờ vào</th>
                        <th>Giờ ra</th>
                        <th>Xưởng</th>
                        <th>Ghi chú</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($timekeepingEntries as $entry): ?>
                        <tr>
                            <td><?= htmlspecialchars($entry['NgayLamViec'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($entry['TenCa'] ?? $entry['IdCaLamViec'] ?? '-') ?></td>
                            <td><?= $formatDate($entry['ThoiGianVao'] ?? null) ?></td>
                            <td><?= $formatDate($entry['ThoiGIanRa'] ?? null) ?></td>
                            <td><?= htmlspecialchars($entry['TenXuong'] ?? $entry['IdXuong'] ?? '-') ?></td>
                            <td class="text-muted small"><?= htmlspecialchars($entry['GhiChu'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <div class="card p-4" id="plans">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-semibold mb-1">Kế hoạch & phân công</h5>
                <p class="text-muted mb-0">Tổng hợp hạng mục sản xuất được phân công.</p>
            </div>
            <span class="badge bg-light text-dark"><?= number_format(count($plans)) ?> hạng mục</span>
        </div>
        <?php if (!$plans): ?>
            <div class="alert alert-light border mb-0">Chưa có kế hoạch được phân công.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>Hạng mục</th>
                        <th>Xưởng</th>
                        <th>Bắt đầu</th>
                        <th>Hạn</th>
                        <th>Trạng thái</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($plans as $plan): ?>
                        <tr>
                            <td>
                                <div class="fw-semibold"><?= htmlspecialchars($plan['TenThanhThanhPhanSP'] ?? '-') ?></div>
                                <div class="text-muted small">Kế hoạch tổng: <?= htmlspecialchars($plan['IdKeHoachSanXuat'] ?? '-') ?></div>
                            </td>
                            <td><?= htmlspecialchars($plan['TenXuong'] ?? '-') ?></td>
                            <td><?= $formatDate($plan['ThoiGianBatDau'] ?? null) ?></td>
                            <td><?= $formatDate($plan['ThoiGianKetThuc'] ?? null) ?></td>
                            <td><span class="<?= $completionBadge($plan['TrangThai'] ?? '') ?>"><?= htmlspecialchars($plan['TrangThai'] ?? '-') ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
