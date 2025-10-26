<?php
$groupedPlans = $groupedPlans ?? [];
$workshops = $workshops ?? [];
$selectedWorkshop = $selectedWorkshop ?? null;

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

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1">Tiến độ sản xuất tại các xưởng</h2>
        <p class="text-muted mb-0">Tổng hợp các hạng mục được giao từ kế hoạch sản xuất và trạng thái thực hiện theo từng xưởng.</p>
    </div>
    <form class="d-flex align-items-center gap-2" method="get">
        <input type="hidden" name="controller" value="factory_plan">
        <input type="hidden" name="action" value="index">
        <select name="workshop_id" class="form-select" onchange="this.form.submit()">
            <option value="">Tất cả các xưởng</option>
            <?php foreach ($workshops as $workshop): ?>
                <?php $value = $workshop['IdXuong'] ?? ''; ?>
                <option value="<?= htmlspecialchars($value) ?>" <?= ($value === $selectedWorkshop) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($workshop['TenXuong'] ?? 'Xưởng sản xuất') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small text-uppercase">Xưởng đang theo dõi</div>
                <div class="fs-3 fw-semibold mb-1"><?= htmlspecialchars((string) $totalWorkshops) ?></div>
                <div class="text-muted small">Chỉ tính các xưởng có nhiệm vụ trong kế hoạch.</div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small text-uppercase">Hạng mục đang xử lý</div>
                <div class="fs-3 fw-semibold mb-1 text-primary"><?= htmlspecialchars((string) ($totalItems - $completedItems)) ?></div>
                <div class="text-muted small">Tổng số công đoạn chưa hoàn thành.</div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small text-uppercase">Hoàn thành</div>
                <div class="fs-3 fw-semibold mb-1 text-success"><?= htmlspecialchars((string) $completedItems) ?></div>
                <div class="text-muted small">Hạng mục đã hoàn tất và nghiệm thu.</div>
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
                        <div class="text-muted small">Tổng: <span class="fw-semibold"><?= htmlspecialchars((string) ($stats['total'] ?? count($items))) ?></span></div>
                        <div class="text-muted small">Hoàn thành: <span class="fw-semibold text-success"><?= htmlspecialchars((string) ($stats['completed'] ?? 0)) ?></span></div>
                        <div class="text-muted small">Đang làm: <span class="fw-semibold text-primary"><?= htmlspecialchars((string) ($stats['in_progress'] ?? 0)) ?></span></div>
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
                                        <div>
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
                                        <div>
                                            <span class="<?= $statusBadge((string) ($item['TrangThai'] ?? '')) ?>">
                                                <?= htmlspecialchars($item['TrangThai'] ?? 'Chưa cập nhật') ?>
                                            </span>
                                            <?php if (!empty($item['TinhTrangVatTu'])): ?>
                                                <span class="badge bg-light text-muted ms-2">Vật tư: <?= htmlspecialchars($item['TinhTrangVatTu']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <a class="btn btn-sm btn-outline-primary" href="?controller=factory_plan&action=read&id=<?= urlencode($item['IdKeHoachSanXuatXuong'] ?? '') ?>">
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
