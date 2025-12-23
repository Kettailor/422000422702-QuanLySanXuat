<?php
$currentUser = $currentUser ?? [];
$role = $role ?? ($currentUser['ActualIdVaiTro'] ?? ($currentUser['IdVaiTro'] ?? null));
$employee = $employee ?? [];
$now = $now ?? date('Y-m-d H:i:s');
$shift = $shift ?? null;
$shiftList = $shiftList ?? [];
$workSummary = $workSummary ?? ['month' => date('Y-m'), 'total_hours' => 0, 'total_records' => 0];
$employeePayrollSummary = $employeePayrollSummary ?? ['total_amount' => 0, 'pending' => 0, 'approved' => 0, 'paid' => 0];
$latestPayrolls = $latestPayrolls ?? [];
$notifications = $notifications ?? [];
$importantNotifications = $importantNotifications ?? [];

$orderStats = $orderStats ?? ['total_orders' => 0, 'pending_orders' => 0, 'total_revenue' => 0, 'completed_orders' => 0];
$payrollSummary = $payrollSummary ?? ['total_amount' => 0, 'pending' => 0];
$workshopSummary = $workshopSummary ?? ['utilization' => 0, 'workforce' => 0];
$pendingPayrolls = $pendingPayrolls ?? [];
$warehouseSummary = $warehouseSummary ?? ['total_warehouses' => 0, 'total_lots' => 0, 'total_quantity' => 0, 'total_inventory_value' => 0];
$warehouses = $warehouses ?? [];
$workshopPlans = $workshopPlans ?? [];
$qualitySummary = $qualitySummary ?? [];
$qualityLots = $qualityLots ?? [];
$qualityReports = $qualityReports ?? [];
$tickets = $tickets ?? [];
$ticketSummary = $ticketSummary ?? ['total' => 0, 'open' => 0];
$activeUserCount = $activeUserCount ?? 0;
$roles = $roles ?? [];
$employees = $employees ?? [];

$roleLabels = [
    'VT_BAN_GIAM_DOC' => 'Ban giám đốc',
    'VT_KHO_TRUONG' => 'Kho trưởng',
    'VT_KINH_DOANH' => 'Nhân viên kinh doanh',
    'VT_QUANLY_XUONG' => 'Xưởng trưởng',
    'VT_NHANVIEN_KHO' => 'Nhân viên kho',
    'VT_NHANVIEN_SANXUAT' => 'Nhân viên sản xuất',
    'VT_KIEM_SOAT_CL' => 'Nhân viên kiểm soát chất lượng',
    'VT_KETOAN' => 'Kế toán',
    'VT_ADMIN' => 'Admin',
];

$roleLabel = $roleLabels[$role] ?? ($currentUser['TenVaiTro'] ?? 'Tổng quan');

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

$formatCurrency = static function (float $value): string {
    return number_format($value, 0, ',', '.') . ' đ';
};

$monthLabel = $workSummary['month'] ?? date('Y-m');
if (preg_match('/^(\d{4})-(\d{2})$/', $monthLabel, $monthMatches)) {
    $monthLabel = $monthMatches[2] . '/' . $monthMatches[1];
}

$resolveNotificationScope = static function (array $notification): array {
    $recipient = $notification['recipient'] ?? null;
    $recipientRole = $notification['recipient_role'] ?? null;

    if ($recipient) {
        return ['label' => 'Cá nhân', 'class' => 'bg-info-subtle text-info'];
    }

    if ($recipientRole) {
        return ['label' => 'Theo vai trò', 'class' => 'bg-primary-subtle text-primary'];
    }

    return ['label' => 'Chung', 'class' => 'bg-secondary-subtle text-secondary'];
};
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <div class="text-muted small">Xin chào</div>
        <h3 class="fw-bold mb-1"><?= htmlspecialchars($currentUser['HoTen'] ?? 'Nhân viên') ?></h3>
        <div class="text-muted small">Vai trò: <?= htmlspecialchars($roleLabel) ?></div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="?controller=notifications&action=index" class="btn btn-outline-secondary">
            <i class="bi bi-bell me-1"></i>Thông báo
        </a>
        <a href="?controller=auth&action=profile" class="btn btn-primary">
            <i class="bi bi-person-circle me-1"></i>Hồ sơ
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-success bg-opacity-10 text-success"><i class="bi bi-clock-history"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Số giờ làm tháng <?= htmlspecialchars($monthLabel) ?></div>
                <div class="fs-3 fw-bold text-center"><?= number_format($workSummary['total_hours'] ?? 0, 1) ?>h</div>
                <div class="small text-muted"><?= number_format($workSummary['total_records'] ?? 0) ?> ca đã ghi nhận</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-info bg-opacity-10 text-info"><i class="bi bi-cash-coin"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Tổng lương đã ghi nhận</div>
                <div class="fs-3 fw-bold text-center"><?= $formatCurrency((float) ($employeePayrollSummary['total_amount'] ?? 0)) ?></div>
                <div class="small text-muted">Chờ duyệt: <?= number_format($employeePayrollSummary['pending'] ?? 0) ?> bảng</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-warning bg-opacity-10 text-warning"><i class="bi bi-bell"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Thông báo mới</div>
                <div class="fs-3 fw-bold text-center"><?= number_format(count($notifications)) ?></div>
                <div class="small text-muted">Quan trọng: <?= number_format(count($importantNotifications)) ?> mục</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-primary bg-opacity-10 text-primary"><i class="bi bi-calendar-check"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Ca làm việc hiện tại</div>
                <div class="fs-6 fw-bold text-center">
                    <?= $shift ? htmlspecialchars($shift['TenCa'] ?? $shift['IdCaLamViec']) : 'Chưa đến ca' ?>
                </div>
                <div class="small text-muted">
                    <?= $shift ? htmlspecialchars($formatDate($shift['ThoiGianBatDau'] ?? null, 'H:i'))
                        . ' → ' . htmlspecialchars($formatDate($shift['ThoiGianKetThuc'] ?? null, 'H:i'))
                        : 'Chưa có ca phù hợp' ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-xl-8">
        <div class="card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Ca làm việc trong ngày</h5>
                <a href="?controller=self_timekeeping&action=index" class="btn btn-sm btn-outline-primary">Tự chấm công</a>
            </div>
            <?php if (empty($shiftList)): ?>
                <div class="text-muted">Chưa có ca làm việc được lên lịch cho hôm nay.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>Ca</th>
                            <th>Loại ca</th>
                            <th>Thời gian</th>
                            <th>Trạng thái</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($shiftList as $item): ?>
                            <tr>
                                <td class="fw-semibold"><?= htmlspecialchars($item['TenCa'] ?? $item['IdCaLamViec']) ?></td>
                                <td><?= htmlspecialchars($item['LoaiCa'] ?? 'Sản xuất') ?></td>
                                <td>
                                    <?= htmlspecialchars($formatDate($item['ThoiGianBatDau'] ?? null, 'H:i')) ?>
                                    → <?= htmlspecialchars($formatDate($item['ThoiGianKetThuc'] ?? null, 'H:i')) ?>
                                </td>
                                <td>
                                    <?php
                                    $isCurrent = $shift && (($shift['IdCaLamViec'] ?? null) === ($item['IdCaLamViec'] ?? null));
                                    ?>
                                    <span class="badge <?= $isCurrent ? 'badge-soft-success' : 'badge-soft-secondary' ?>">
                                        <?= $isCurrent ? 'Đang diễn ra' : 'Sắp tới' ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card p-4 h-100">
            <h5 class="mb-3">Lịch sử lương gần nhất</h5>
            <?php if (empty($latestPayrolls)): ?>
                <div class="text-muted">Chưa có dữ liệu bảng lương.</div>
            <?php else: ?>
                <ul class="list-unstyled mb-0">
                    <?php foreach ($latestPayrolls as $payroll): ?>
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
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div>
                                <div class="fw-semibold">Tháng <?= htmlspecialchars($monthLabel) ?></div>
                                <div class="small text-muted">Trạng thái: <?= htmlspecialchars($payroll['TrangThai'] ?? 'Đang cập nhật') ?></div>
                            </div>
                            <span class="badge bg-light text-dark"><?= number_format($payroll['TongThuNhap'] ?? 0, 0, ',', '.') ?> đ</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if ($role === 'VT_BAN_GIAM_DOC'): ?>
    <div class="row g-4 mt-1">
        <div class="col-xl-3 col-sm-6">
            <div class="card metric-card">
                <div class="icon-wrap bg-primary bg-opacity-10 text-primary"><i class="bi bi-bag-check"></i></div>
                <div>
                    <div class="text-muted text-uppercase small">Tổng đơn hàng</div>
                    <div class="fs-3 fw-bold text-center"><?= number_format($orderStats['total_orders']) ?></div>
                    <div class="small text-muted">Chờ xử lý: <?= number_format($orderStats['pending_orders']) ?> đơn</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card metric-card">
                <div class="icon-wrap bg-success bg-opacity-10 text-success"><i class="bi bi-currency-exchange"></i></div>
                <div>
                    <div class="text-muted text-uppercase small">Doanh thu</div>
                    <div class="fs-3 fw-bold text-center"><?= number_format($orderStats['total_revenue'], 0, ',', '.') ?> đ</div>
                    <div class="small text-muted">Đơn hoàn thành: <?= number_format($orderStats['completed_orders']) ?></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card metric-card">
                <div class="icon-wrap bg-info bg-opacity-10 text-info"><i class="bi bi-wallet2"></i></div>
                <div>
                    <div class="text-muted text-uppercase small">Quỹ lương</div>
                    <div class="fs-3 fw-bold text-center"><?= number_format($payrollSummary['total_amount'], 0, ',', '.') ?> đ</div>
                    <div class="small text-muted">Bảng chờ duyệt: <?= number_format($payrollSummary['pending']) ?> bảng</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card metric-card">
                <div class="icon-wrap bg-warning bg-opacity-10 text-warning"><i class="bi bi-gear-wide-connected"></i></div>
                <div>
                    <div class="text-muted text-uppercase small">Hiệu suất xưởng</div>
                    <div class="fs-3 fw-bold text-center"><?= $workshopSummary['utilization'] ?>%</div>
                    <div class="small text-muted">Nhân sự: <?= number_format($workshopSummary['workforce']) ?> người</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-xl-4">
            <div class="card p-4 h-100">
                <h5 class="mb-3">Gửi thông báo nhanh</h5>
                <form method="post" action="?controller=dashboard&action=sendNotification">
                    <div class="mb-2">
                        <label class="form-label small text-muted">Phân loại thông báo</label>
                        <select name="scope" class="form-select form-select-sm" data-notification-scope>
                            <option value="general">Thông báo chung</option>
                            <option value="role">Theo vai trò</option>
                            <option value="personal">Cá nhân</option>
                        </select>
                    </div>
                    <div class="mb-2" data-scope-target="role" style="display:none;">
                        <label class="form-label small text-muted">Vai trò nhận thông báo</label>
                        <select name="recipient_role" class="form-select form-select-sm">
                            <option value="">-- Chọn vai trò --</option>
                            <?php foreach ($roles as $roleOption): ?>
                                <option value="<?= htmlspecialchars($roleOption['IdVaiTro'] ?? '') ?>">
                                    <?= htmlspecialchars($roleOption['TenVaiTro'] ?? $roleOption['IdVaiTro'] ?? '') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-2" data-scope-target="personal" style="display:none;">
                        <label class="form-label small text-muted">Nhân sự nhận thông báo</label>
                        <select name="recipient" class="form-select form-select-sm">
                            <option value="">-- Chọn nhân sự --</option>
                            <?php foreach ($employees as $employeeOption): ?>
                                <option value="<?= htmlspecialchars($employeeOption['IdNhanVien'] ?? '') ?>">
                                    <?= htmlspecialchars($employeeOption['HoTen'] ?? $employeeOption['IdNhanVien'] ?? '') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="title" class="form-control" placeholder="Tiêu đề thông báo">
                    </div>
                    <div class="mb-2">
                        <textarea name="message" rows="3" class="form-control" placeholder="Nội dung thông báo"></textarea>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <select name="priority" class="form-select form-select-sm w-auto">
                            <option value="normal">Bình thường</option>
                            <option value="important">Quan trọng</option>
                            <option value="urgent">Khẩn</option>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-send me-1"></i>Gửi thông báo
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Doanh thu & chi phí lương theo tháng</h5>
                    <span class="text-muted small">So sánh dòng tiền 6 tháng gần nhất</span>
                </div>
                <canvas id="financeChart" height="160"></canvas>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-xl-4">
            <div class="card p-4 h-100">
                <h5 class="mb-3">Thông báo vận hành</h5>
                <div class="timeline">
                    <?php foreach ($activities as $activity): ?>
                        <div class="timeline-item">
                            <div class="fw-semibold"><?= htmlspecialchars($activity['HanhDong']) ?></div>
                            <div class="text-muted small"><?= date('d/m/Y H:i', strtotime($activity['ThoiGian'])) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card p-4 h-100">
                <h5 class="mb-3">Thông báo quan trọng từ các bộ phận</h5>
                <?php if (empty($importantNotifications)): ?>
                    <div class="text-muted">Chưa có thông báo quan trọng.</div>
                <?php else: ?>
                    <?php foreach (array_slice($importantNotifications, 0, 5) as $notification): ?>
                        <?php
                        if (!is_array($notification)) {
                            continue;
                        }
                        $scope = $resolveNotificationScope($notification);
                        $message = $notification['message'] ?? $notification['title'] ?? 'Thông báo';
                        ?>
                        <div class="alert alert-warning d-flex justify-content-between align-items-start gap-3 mb-2">
                            <div>
                                <div class="fw-semibold"><?= htmlspecialchars($notification['title'] ?? 'Thông báo quan trọng') ?></div>
                                <div class="small text-muted"><?= htmlspecialchars($message) ?></div>
                            </div>
                            <span class="badge <?= $scope['class'] ?>"><?= htmlspecialchars($scope['label']) ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-xl-6">
            <div class="card p-4 h-100">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="mb-0">Kế hoạch sản xuất</h5>
                    <a href="?controller=plan&action=index" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>Mã kế hoạch</th>
                            <th>Hạng mục</th>
                            <th>Trạng thái</th>
                            <th>Thời hạn</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($plans as $plan): ?>
                            <tr>
                                <td class="fw-semibold"><?= htmlspecialchars($plan['IdKeHoachSanXuat']) ?></td>
                                <td><?= htmlspecialchars($plan['YeuCau'] ?? $plan['TenThanhThanhPhanSP'] ?? 'N/A') ?></td>
                                <td>
                                    <?php $status = $plan['TrangThai']; ?>
                                    <span class="badge <?= $status === 'Hoàn thành' ? 'badge-soft-success' : ($status === 'Đang thực hiện' ? 'badge-soft-warning' : 'badge-soft-danger') ?>">
                                        <?= htmlspecialchars($status) ?>
                                    </span>
                                </td>
                                <td><?= $plan['ThoiGianKetThuc'] ? date('d/m/Y', strtotime($plan['ThoiGianKetThuc'])) : '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card p-4 h-100">
                <h5 class="mb-3">Tiến độ & nhân sự</h5>
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span>Ngày làm việc chuẩn</span>
                        <span class="fw-semibold"><?= $stats['totalWorkingDays'] ?></span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span>Nhân sự đang hoạt động</span>
                        <span class="fw-semibold"><?= $stats['participationRate'] ?></span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span>Kế hoạch hoàn thành</span>
                        <span class="fw-semibold"><?= $stats['completedPlans'] ?></span>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span>Thông báo mới từ xưởng</span>
                        <span class="fw-semibold"><?= $stats['newNotifications'] ?></span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card p-4 h-100">
                <h5 class="mb-3">Bảng lương chờ xử lý</h5>
                <?php if (!$pendingPayrolls): ?>
                    <div class="text-muted">Không có bảng lương nào cần duyệt.</div>
                <?php else: ?>
                    <ul class="list-unstyled mb-0">
                        <?php foreach ($pendingPayrolls as $payroll): ?>
                            <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
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
                                <div>
                                    <div class="fw-semibold"><?= htmlspecialchars($payroll['HoTen']) ?></div>
                                    <div class="small text-muted">Tháng <?= htmlspecialchars($monthLabel) ?></div>
                                </div>
                                <span class="badge bg-light text-dark"><?= number_format($payroll['TongThuNhap'], 0, ',', '.') ?> đ</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <a href="?controller=salary&action=index" class="btn btn-sm btn-outline-primary mt-3">Quản lý bảng lương</a>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-xl-6">
            <div class="card p-4 h-100">
                <h5 class="mb-3">Đánh giá chất lượng</h5>
                <?php if ($qualitySummary): ?>
                    <div class="row text-center">
                        <div class="col">
                            <div class="fs-1 fw-bold text-primary"><?= $qualitySummary['tong_bien_ban'] ?? 0 ?></div>
                            <div class="text-muted">Biên bản QA</div>
                        </div>
                        <div class="col">
                            <div class="fs-1 fw-bold text-success"><?= $qualitySummary['so_dat'] ?? 0 ?></div>
                            <div class="text-muted">Đạt</div>
                        </div>
                        <div class="col">
                            <div class="fs-1 fw-bold text-danger"><?= $qualitySummary['so_khong_dat'] ?? 0 ?></div>
                            <div class="text-muted">Không đạt</div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-muted">Chưa có dữ liệu đánh giá chất lượng.</div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card p-4 h-100">
                <h5 class="mb-3">Đơn hàng gần đây</h5>
                <?php foreach ($orders as $order): ?>
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <div>
                            <div class="fw-semibold"><?= htmlspecialchars($order['IdDonHang']) ?> - <?= htmlspecialchars($order['TenKhachHang']) ?></div>
                            <div class="text-muted small">Ngày: <?= date('d/m/Y', strtotime($order['NgayLap'])) ?></div>
                        </div>
                        <span class="badge bg-light text-dark"><?= number_format($order['TongTien'], 0, ',', '.') ?> đ</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (in_array($role, ['VT_KHO_TRUONG', 'VT_NHANVIEN_KHO'], true)): ?>
    <div class="row g-4 mt-1">
        <div class="col-xl-4">
            <div class="card p-4 h-100">
                <h5 class="mb-3">Tổng quan kho</h5>
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span>Số kho quản lý</span>
                        <span class="fw-semibold"><?= number_format($warehouseSummary['total_warehouses'] ?? 0) ?></span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span>Số lô đang lưu</span>
                        <span class="fw-semibold"><?= number_format($warehouseSummary['total_lots'] ?? 0) ?></span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span>Tổng số lượng tồn</span>
                        <span class="fw-semibold"><?= number_format($warehouseSummary['total_quantity'] ?? 0) ?></span>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span>Giá trị tồn kho</span>
                        <span class="fw-semibold"><?= $formatCurrency((float) ($warehouseSummary['total_inventory_value'] ?? 0)) ?></span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Kho đang phụ trách</h5>
                    <a href="?controller=warehouse&action=index" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
                </div>
                <?php if (empty($warehouses)): ?>
                    <div class="text-muted">Chưa có kho được phân công.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                            <tr>
                                <th>Tên kho</th>
                                <th>Loại kho</th>
                                <th>Số lô</th>
                                <th>Giá trị tháng</th>
                                <th>Trạng thái</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach (array_slice($warehouses, 0, 6) as $warehouse): ?>
                                <tr>
                                    <td class="fw-semibold"><?= htmlspecialchars($warehouse['TenKho'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($warehouse['TenLoaiKho'] ?? '-') ?></td>
                                    <td><?= number_format($warehouse['SoLoDangQuanLy'] ?? 0) ?></td>
                                    <td><?= $formatCurrency((float) ($warehouse['GiaTriPhieuThang'] ?? 0)) ?></td>
                                    <td>
                                        <span class="badge bg-light text-dark"><?= htmlspecialchars($warehouse['TrangThai'] ?? 'Đang cập nhật') ?></span>
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

    <div class="row g-4 mt-1">
        <div class="col-xl-12">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Kế hoạch cần cấp vật tư</h5>
                    <a href="?controller=factory_plan&action=index" class="btn btn-sm btn-outline-primary">Kế hoạch xưởng</a>
                </div>
                <?php if (empty($workshopPlans)): ?>
                    <div class="text-muted">Chưa có kế hoạch cần theo dõi vật tư.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                            <tr>
                                <th>Hạng mục</th>
                                <th>Xưởng</th>
                                <th>Thời gian</th>
                                <th>Tình trạng vật tư</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach (array_slice($workshopPlans, 0, 8) as $plan): ?>
                                <tr>
                                    <td class="fw-semibold"><?= htmlspecialchars($plan['TenThanhThanhPhanSP'] ?? 'Kế hoạch xưởng') ?></td>
                                    <td><?= htmlspecialchars($plan['TenXuong'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($formatDate($plan['ThoiGianBatDau'] ?? null, 'd/m/Y')) ?></td>
                                    <td>
                                        <span class="badge <?= ($plan['TinhTrangVatTu'] ?? '') === 'Đủ' ? 'badge-soft-success' : 'badge-soft-warning' ?>">
                                            <?= htmlspecialchars($plan['TinhTrangVatTu'] ?? 'Chưa cập nhật') ?>
                                        </span>
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
<?php endif; ?>

<?php if ($role === 'VT_KINH_DOANH'): ?>
    <div class="row g-4 mt-1">
        <div class="col-xl-4">
            <div class="card p-4 h-100">
                <h5 class="mb-3">Tổng quan đơn hàng</h5>
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span>Tổng đơn hàng</span>
                        <span class="fw-semibold"><?= number_format($orderStats['total_orders']) ?></span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span>Đơn đang xử lý</span>
                        <span class="fw-semibold"><?= number_format($orderStats['pending_orders']) ?></span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span>Đơn hoàn thành</span>
                        <span class="fw-semibold"><?= number_format($orderStats['completed_orders']) ?></span>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span>Doanh thu dự kiến</span>
                        <span class="fw-semibold"><?= number_format($orderStats['total_revenue'], 0, ',', '.') ?> đ</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Đơn hàng cần theo dõi</h5>
                    <a href="?controller=order&action=index" class="btn btn-sm btn-outline-primary">Danh sách đơn hàng</a>
                </div>
                <?php if (empty($orders)): ?>
                    <div class="text-muted">Chưa có đơn hàng mới.</div>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>
                                <div class="fw-semibold">ĐH <?= htmlspecialchars($order['IdDonHang']) ?> - <?= htmlspecialchars($order['TenKhachHang']) ?></div>
                                <div class="text-muted small">Ngày lập: <?= htmlspecialchars($formatDate($order['NgayLap'] ?? null, 'd/m/Y')) ?></div>
                            </div>
                            <span class="badge bg-light text-dark"><?= number_format($order['TongTien'], 0, ',', '.') ?> đ</span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (in_array($role, ['VT_QUANLY_XUONG', 'VT_NHANVIEN_SANXUAT'], true)): ?>
    <div class="row g-4 mt-1">
        <div class="col-xl-12">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Kế hoạch xưởng đang phụ trách</h5>
                    <a href="?controller=factory_plan&action=index" class="btn btn-sm btn-outline-primary">Kế hoạch xưởng</a>
                </div>
                <?php if (empty($workshopPlans)): ?>
                    <div class="text-muted">Chưa có kế hoạch được phân công.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                            <tr>
                                <th>Kế hoạch</th>
                                <th>Xưởng</th>
                                <th>Số lượng</th>
                                <th>Trạng thái</th>
                                <th>Vật tư</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach (array_slice($workshopPlans, 0, 10) as $plan): ?>
                                <tr>
                                    <td class="fw-semibold"><?= htmlspecialchars($plan['TenThanhThanhPhanSP'] ?? 'Kế hoạch xưởng') ?></td>
                                    <td><?= htmlspecialchars($plan['TenXuong'] ?? '-') ?></td>
                                    <td><?= number_format($plan['SoLuong'] ?? 0) ?></td>
                                    <td>
                                        <span class="badge <?= ($plan['TrangThai'] ?? '') === 'Hoàn thành' ? 'badge-soft-success' : 'badge-soft-warning' ?>">
                                            <?= htmlspecialchars($plan['TrangThai'] ?? 'Đang thực hiện') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?= ($plan['TinhTrangVatTu'] ?? '') === 'Đủ' ? 'badge-soft-success' : 'badge-soft-warning' ?>">
                                            <?= htmlspecialchars($plan['TinhTrangVatTu'] ?? 'Chưa cập nhật') ?>
                                        </span>
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
<?php endif; ?>

<?php if ($role === 'VT_KIEM_SOAT_CL'): ?>
    <div class="row g-4 mt-1">
        <div class="col-xl-4">
            <div class="card p-4 h-100">
                <h5 class="mb-3">Tổng quan chất lượng</h5>
                <?php if ($qualitySummary): ?>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span>Tổng biên bản</span>
                            <span class="fw-semibold"><?= number_format($qualitySummary['tong_bien_ban'] ?? 0) ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span>Lô đạt</span>
                            <span class="fw-semibold text-success"><?= number_format($qualitySummary['so_dat'] ?? 0) ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span>Lô không đạt</span>
                            <span class="fw-semibold text-danger"><?= number_format($qualitySummary['so_khong_dat'] ?? 0) ?></span>
                        </li>
                    </ul>
                <?php else: ?>
                    <div class="text-muted">Chưa có dữ liệu đánh giá.</div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Lô cần đánh giá</h5>
                    <a href="?controller=quality&action=index" class="btn btn-sm btn-outline-primary">Quản lý chất lượng</a>
                </div>
                <?php if (empty($qualityLots)): ?>
                    <div class="text-muted">Không có lô chờ đánh giá.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                            <tr>
                                <th>Lô</th>
                                <th>Xưởng</th>
                                <th>Số lượng</th>
                                <th>Ngày tạo</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($qualityLots as $lot): ?>
                                <tr>
                                    <td class="fw-semibold"><?= htmlspecialchars($lot['TenLo'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($lot['TenXuong'] ?? '-') ?></td>
                                    <td><?= number_format($lot['SoLuong'] ?? 0) ?></td>
                                    <td><?= htmlspecialchars($formatDate($lot['NgayTao'] ?? null, 'd/m/Y')) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-xl-12">
            <div class="card p-4">
                <h5 class="mb-3">Biên bản chất lượng gần nhất</h5>
                <?php if (empty($qualityReports)): ?>
                    <div class="text-muted">Chưa có biên bản mới.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                            <tr>
                                <th>Biên bản</th>
                                <th>Lô</th>
                                <th>Kết quả</th>
                                <th>Thời gian</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($qualityReports as $report): ?>
                                <tr>
                                    <td class="fw-semibold"><?= htmlspecialchars($report['IdBienBanDanhGiaSP'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($report['TenLo'] ?? '-') ?></td>
                                    <td>
                                        <span class="badge <?= ($report['KetQua'] ?? '') === 'Đạt' ? 'badge-soft-success' : 'badge-soft-danger' ?>">
                                            <?= htmlspecialchars($report['KetQua'] ?? '-') ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($formatDate($report['ThoiGian'] ?? null)) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($role === 'VT_KETOAN'): ?>
    <div class="row g-4 mt-1">
        <div class="col-xl-4">
            <div class="card p-4 h-100">
                <h5 class="mb-3">Tổng quan hóa đơn</h5>
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span>Đơn cần lập hóa đơn</span>
                        <span class="fw-semibold"><?= number_format($orderStats['pending_orders']) ?></span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span>Tổng đơn hàng</span>
                        <span class="fw-semibold"><?= number_format($orderStats['total_orders']) ?></span>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span>Doanh thu dự kiến</span>
                        <span class="fw-semibold"><?= number_format($orderStats['total_revenue'], 0, ',', '.') ?> đ</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Đơn hàng cần lập hóa đơn</h5>
                    <a href="?controller=order&action=index" class="btn btn-sm btn-outline-primary">Danh sách đơn hàng</a>
                </div>
                <?php if (empty($orders)): ?>
                    <div class="text-muted">Không có đơn hàng cần xử lý.</div>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>
                                <div class="fw-semibold">ĐH <?= htmlspecialchars($order['IdDonHang']) ?> - <?= htmlspecialchars($order['TenKhachHang']) ?></div>
                                <div class="text-muted small">Ngày lập: <?= htmlspecialchars($formatDate($order['NgayLap'] ?? null, 'd/m/Y')) ?></div>
                            </div>
                            <span class="badge bg-light text-dark"><?= number_format($order['TongTien'], 0, ',', '.') ?> đ</span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-xl-12">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Bảng lương cần duyệt</h5>
                    <a href="?controller=salary&action=index" class="btn btn-sm btn-outline-primary">Quản lý bảng lương</a>
                </div>
                <?php if (empty($pendingPayrolls)): ?>
                    <div class="text-muted">Chưa có bảng lương cần xử lý.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                            <tr>
                                <th>Nhân viên</th>
                                <th>Tháng</th>
                                <th>Tổng thu nhập</th>
                                <th>Trạng thái</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($pendingPayrolls as $payroll): ?>
                                <?php
                                $monthLabel = $payroll['ThangNam'] ?? '';
                                if (preg_match('/^(\d{4})-(\d{2})$/', (string) $monthLabel, $monthMatches)) {
                                    $monthLabel = $monthMatches[2] . '/' . $monthMatches[1];
                                }
                                ?>
                                <tr>
                                    <td class="fw-semibold"><?= htmlspecialchars($payroll['HoTen']) ?></td>
                                    <td><?= htmlspecialchars($monthLabel) ?></td>
                                    <td><?= number_format($payroll['TongThuNhap'], 0, ',', '.') ?> đ</td>
                                    <td><span class="badge badge-soft-warning">Chờ duyệt</span></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($role === 'VT_ADMIN'): ?>
    <div class="row g-4 mt-1">
        <div class="col-xl-4">
            <div class="card p-4 h-100">
                <h5 class="mb-3">Tổng quan hỗ trợ</h5>
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span>Yêu cầu hỗ trợ</span>
                        <span class="fw-semibold"><?= number_format($ticketSummary['total']) ?></span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span>Đang mở</span>
                        <span class="fw-semibold text-danger"><?= number_format($ticketSummary['open']) ?></span>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span>Tài khoản hoạt động</span>
                        <span class="fw-semibold"><?= number_format($activeUserCount) ?></span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Yêu cầu hỗ trợ mới</h5>
                    <a href="?controller=admin&action=ticket" class="btn btn-sm btn-outline-primary">Quản lý hỗ trợ</a>
                </div>
                <?php if (empty($tickets)): ?>
                    <div class="text-muted">Chưa có yêu cầu hỗ trợ.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                            <tr>
                                <th>Thời gian</th>
                                <th>Tài khoản</th>
                                <th>Nội dung</th>
                                <th>Trạng thái</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach (array_slice($tickets, 0, 6) as $ticket): ?>
                                <tr>
                                    <td><?= htmlspecialchars($formatDate($ticket['date'] ?? null)) ?></td>
                                    <td class="fw-semibold"><?= htmlspecialchars($ticket['user'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($ticket['request'] ?? '-') ?></td>
                                    <td>
                                        <span class="badge <?= ($ticket['status'] ?? '') === 'open' ? 'badge-soft-warning' : 'badge-soft-success' ?>">
                                            <?= htmlspecialchars(($ticket['status'] ?? 'open') === 'open' ? 'Đang mở' : 'Đã đóng') ?>
                                        </span>
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
<?php endif; ?>

<div class="row g-4 mt-1">
    <div class="col-xl-6">
        <div class="card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Thông báo quan trọng</h5>
                <a href="?controller=notifications&action=index" class="btn btn-sm btn-outline-secondary">Xem tất cả</a>
            </div>
            <?php if (empty($importantNotifications)): ?>
                <div class="text-muted">Chưa có thông báo quan trọng.</div>
            <?php else: ?>
                <?php foreach (array_slice($importantNotifications, 0, 4) as $notification): ?>
                    <?php
                    if (!is_array($notification)) {
                        continue;
                    }
                    $scope = $resolveNotificationScope($notification);
                    $message = $notification['message'] ?? $notification['title'] ?? 'Thông báo';
                    ?>
                    <div class="alert alert-warning d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <div class="fw-semibold"><?= htmlspecialchars($notification['title'] ?? 'Thông báo quan trọng') ?></div>
                            <div class="small text-muted"><?= htmlspecialchars($message) ?></div>
                        </div>
                        <span class="badge <?= $scope['class'] ?>"><?= htmlspecialchars($scope['label']) ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Thông báo gần đây</h5>
                <a href="?controller=notifications&action=index" class="btn btn-sm btn-outline-secondary">Quản lý</a>
            </div>
            <?php if (empty($notifications)): ?>
                <div class="text-muted">Chưa có thông báo mới.</div>
            <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach (array_slice($notifications, 0, 6) as $notification): ?>
                        <?php
                        if (!is_array($notification)) {
                            continue;
                        }
                        $scope = $resolveNotificationScope($notification);
                        $title = $notification['title'] ?? 'Thông báo hệ thống';
                        $message = $notification['message'] ?? null;
                        $timeLabel = $notification['created_at'] ?? ($notification['time'] ?? null);
                        ?>
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div>
                                    <div class="fw-semibold"><?= htmlspecialchars($title) ?></div>
                                    <?php if ($message): ?>
                                        <div class="text-muted small mt-1"><?= htmlspecialchars($message) ?></div>
                                    <?php endif; ?>
                                    <?php if ($timeLabel): ?>
                                        <div class="text-muted small"><?= htmlspecialchars($formatDate($timeLabel)) ?></div>
                                    <?php endif; ?>
                                </div>
                                <span class="badge <?= $scope['class'] ?>"><?= htmlspecialchars($scope['label']) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if ($role === 'VT_BAN_GIAM_DOC'): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const financeCanvas = document.getElementById('financeChart');
    if (!financeCanvas) return;

    const revenueRaw = <?= json_encode($monthlyRevenue) ?>;
    const payrollRaw = <?= json_encode($payrollTrend) ?>;

    const monthSet = new Set();
    revenueRaw.forEach(item => monthSet.add(item.thang));
    payrollRaw.forEach(item => monthSet.add(item.thang));

    const labels = Array.from(monthSet).sort();
    const revenueMap = {};
    const payrollMap = {};

    revenueRaw.forEach(item => { revenueMap[item.thang] = parseFloat(item.tong_doanh_thu); });
    payrollRaw.forEach(item => { payrollMap[item.thang] = parseFloat(item.tong_chi); });

    if (!labels.length) {
        financeCanvas.parentElement.classList.add('d-flex', 'align-items-center', 'justify-content-center', 'text-muted');
        financeCanvas.replaceWith('Chưa có dữ liệu tài chính.');
        return;
    }

    const revenueData = labels.map(label => revenueMap[label] ?? 0);
    const payrollData = labels.map(label => payrollMap[label] ?? 0);

    new Chart(financeCanvas, {
        data: {
            labels,
            datasets: [
                {
                    type: 'line',
                    label: 'Doanh thu đơn hàng',
                    data: revenueData,
                    borderColor: '#1976d2',
                    backgroundColor: 'rgba(25,118,210,0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#0d47a1'
                },
                {
                    type: 'bar',
                    label: 'Chi phí lương',
                    data: payrollData,
                    backgroundColor: 'rgba(240, 98, 146, 0.45)',
                    borderRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => value.toLocaleString('vi-VN')
                    }
                }
            }
        }
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const scopeSelect = document.querySelector('[data-notification-scope]');
    if (!scopeSelect) {
        return;
    }

    const toggleTargets = (scope) => {
        document.querySelectorAll('[data-scope-target]').forEach((element) => {
            const target = element.getAttribute('data-scope-target');
            element.style.display = target === scope ? '' : 'none';
        });
    };

    toggleTargets(scopeSelect.value);
    scopeSelect.addEventListener('change', (event) => toggleTargets(event.target.value));
});
</script>
<?php endif; ?>
