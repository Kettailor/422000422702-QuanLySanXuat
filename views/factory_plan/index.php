<?php
$groupedPlans = $groupedPlans ?? [];
$workshops = $workshops ?? [];
$selectedWorkshop = $selectedWorkshop ?? null;
$employeeFilter = $employeeFilter ?? null;

$totalWorkshops = count($groupedPlans);
$totalItems = array_reduce($groupedPlans, static function (int $carry, array $group): int {
    return $carry + ($group['stats']['total'] ?? count($group['items'] ?? []));
}, 0);
$completedItems = array_reduce($groupedPlans, static function (int $carry, array $group): int {
    return $carry + ($group['stats']['completed'] ?? 0);
}, 0);

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

$statusBadge = static function (string $status): string {
    $normalized = strtolower(trim($status));
    if ($normalized === '') {
        return 'badge bg-light text-muted';
    }
    if (str_contains($normalized, 'hoàn thành')) {
        return 'badge bg-success-subtle text-success';
    }
    if (str_contains($normalized, 'đang')) {
        return 'badge bg-primary-subtle text-primary';
    }
    if (str_contains($normalized, 'chờ')) {
        return 'badge bg-warning-subtle text-warning';
    }
    if (str_contains($normalized, 'tạm dừng')) {
        return 'badge bg-secondary-subtle text-secondary';
    }
    return 'badge bg-info-subtle text-info';
};
?>

<div class="d-flex flex-wrap justify-content-between align-items-start mb-4 gap-3">
    <div class="flex-grow-1">
        <p class="text-uppercase text-muted small mb-1">Kế hoạch xưởng</p>
        <div class="d-flex align-items-center gap-3">
            <h2 class="fw-bold mb-0">Tổng quan triển khai</h2>
            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                <?= htmlspecialchars((string) $totalItems) ?> hạng mục
            </span>
        </div>
        <p class="text-muted mb-0 mt-1">Theo dõi sức khỏe kế hoạch, tiến độ và nhu cầu vật tư theo từng xưởng.</p>
    </div>
    <form class="d-flex align-items-center gap-2 bg-white shadow-sm border rounded-3 px-3 py-2" method="get">
        <input type="hidden" name="controller" value="factory_plan">
        <input type="hidden" name="action" value="index">
        <?php if ($employeeFilter): ?>
            <input type="hidden" name="employee_id" value="<?= htmlspecialchars($employeeFilter['IdNhanVien'] ?? '') ?>">
        <?php endif; ?>
        <label class="text-muted small mb-0">Lọc xưởng</label>
        <select name="workshop_id" class="form-select" onchange="this.form.submit()">
            <option value="">Tất cả</option>
            <?php foreach ($workshops as $workshop): ?>
                <?php $value = $workshop['IdXuong'] ?? ''; ?>
                <option value="<?= htmlspecialchars($value) ?>" <?= ($value === $selectedWorkshop) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($workshop['TenXuong'] ?? 'Xưởng sản xuất') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<?php if ($employeeFilter): ?>
    <div class="alert alert-info d-flex align-items-center" role="alert">
        <i class="bi bi-person-badge me-2"></i>
        <div>
            Đang theo dõi kế hoạch của <strong><?= htmlspecialchars($employeeFilter['HoTen'] ?? '') ?></strong>
            (<?= htmlspecialchars($employeeFilter['IdNhanVien'] ?? '') ?>).
            <a href="?controller=factory_plan&action=index" class="alert-link ms-2">Xóa bộ lọc</a>
        </div>
    </div>
<?php endif; ?>

<div class="row g-3 mb-4">
    <div class="col-lg-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small text-uppercase">Xưởng đang theo dõi</div>
                        <div class="fs-4 fw-semibold mt-1"><?= htmlspecialchars((string) $totalWorkshops) ?></div>
                    </div>
                    <div class="badge bg-primary-subtle text-primary rounded-circle p-3">
                        <i class="bi bi-buildings"></i>
                    </div>
                </div>
                <div class="text-muted small mt-2">Chỉ tính xưởng có nhiệm vụ.</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small text-uppercase">Đang xử lý</div>
                        <div class="fs-4 fw-semibold mt-1 text-primary"><?= htmlspecialchars((string) ($totalItems - $completedItems)) ?></div>
                    </div>
                    <div class="badge bg-info-subtle text-info rounded-circle p-3">
                        <i class="bi bi-lightning"></i>
                    </div>
                </div>
                <div class="text-muted small mt-2">Công đoạn chưa hoàn thành.</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small text-uppercase">Hoàn thành</div>
                        <div class="fs-4 fw-semibold mt-1 text-success"><?= htmlspecialchars((string) $completedItems) ?></div>
                    </div>
                    <div class="badge bg-success-subtle text-success rounded-circle p-3">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                </div>
                <div class="text-muted small mt-2">Đã nghiệm thu.</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small text-uppercase">Tỉ lệ hoàn thành</div>
                        <?php $completionRate = $totalItems > 0 ? round(($completedItems / $totalItems) * 100, 1) : 0; ?>
                        <div class="fs-4 fw-semibold mt-1"><?= htmlspecialchars((string) $completionRate) ?>%</div>
                    </div>
                    <div class="badge bg-warning-subtle text-warning rounded-circle p-3">
                        <i class="bi bi-graph-up"></i>
                    </div>
                </div>
                <div class="text-muted small mt-2">Tỷ lệ hoàn tất theo số hạng mục.</div>
            </div>
        </div>
    </div>
</div>

<?php if (empty($groupedPlans)): ?>
    <div class="alert alert-light border">Chưa có nhiệm vụ nào được giao cho các xưởng trong khoảng thời gian này.</div>
<?php else: ?>
    <div class="vstack gap-4">
        <?php foreach ($groupedPlans as $group): ?>
            <?php
            $info = $group['workshop'] ?? [];
            $stats = $group['stats'] ?? [];
            $items = $group['items'] ?? [];
            ?>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h5 class="mb-1">Xưởng <?= htmlspecialchars($info['TenXuong'] ?? 'Chưa xác định') ?></h5>
                        <?php if (!empty($info['TruongXuong'])): ?>
                            <span class="text-muted small">Trưởng xưởng: <?= htmlspecialchars($info['TruongXuong']) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex flex-wrap gap-3 align-items-center">
                            <div class="text-muted small">Tổng <span class="fw-semibold"><?= htmlspecialchars((string) ($stats['total'] ?? count($items))) ?></span></div>
                            <div class="text-muted small text-success">Hoàn thành <span class="fw-semibold"><?= htmlspecialchars((string) ($stats['completed'] ?? 0)) ?></span></div>
                            <div class="text-muted small text-primary">Đang làm <span class="fw-semibold"><?= htmlspecialchars((string) ($stats['in_progress'] ?? 0)) ?></span></div>
                        </div>
                        <?php if (!empty($stats['upcoming_deadline'])): ?>
                            <div class="text-muted small">Hạn gần nhất: <?= $formatDate($stats['upcoming_deadline']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if (empty($items)): ?>
                    <div class="card-body">
                        <div class="alert alert-light border mb-0">Không có nhiệm vụ nào cho xưởng này.</div>
                    </div>
                <?php else: ?>
                    <div class="card-body">
                        <div class="vstack gap-3">
                            <?php foreach ($items as $item): ?>
                                <div class="border rounded-3 p-3">
                                    <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold mb-1"><?= htmlspecialchars($item['TenThanhThanhPhanSP'] ?? 'Hạng mục') ?></div>
                                            <div class="text-muted small">Đơn hàng <?= htmlspecialchars($item['IdDonHang'] ?? '-') ?> • <?= htmlspecialchars($item['TenSanPham'] ?? 'Sản phẩm') ?></div>
                                            <?php if (!empty($item['TenCauHinh'])): ?>
                                                <div class="text-muted small">Cấu hình: <?= htmlspecialchars($item['TenCauHinh']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-semibold"><?= htmlspecialchars((string) ($item['SoLuong'] ?? 0)) ?> <?= htmlspecialchars($item['DonVi'] ?? 'sp') ?></div>
                                            <div class="text-muted small">Bắt đầu: <?= $formatDate($item['ThoiGianBatDau'] ?? null) ?></div>
                                            <div class="text-muted small">Hạn: <?= $formatDate($item['ThoiGianKetThuc'] ?? null) ?></div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                                        <div class="d-flex flex-wrap align-items-center gap-2">
                                            <span class="<?= $statusBadge((string) ($item['TrangThai'] ?? '')) ?>">
                                                <?= htmlspecialchars($item['TrangThai'] ?? 'Chưa cập nhật') ?>
                                            </span>
                                            <?php if (!empty($item['TinhTrangVatTu'])): ?>
                                                <span class="badge bg-light text-muted">Vật tư: <?= htmlspecialchars($item['TinhTrangVatTu']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <a class="btn btn-sm btn-primary" href="?controller=factory_plan&action=read&id=<?= urlencode($item['IdKeHoachSanXuatXuong'] ?? '') ?>">
                                            Xem chi tiết
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
