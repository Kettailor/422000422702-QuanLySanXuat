<?php
$plans = $plans ?? [];
$pendingOrders = $pendingOrders ?? [];
$stats = array_merge([
    'total_plans' => 0,
    'active_plans' => 0,
    'completed_plans' => 0,
    'pending_orders' => 0,
    'pending_details' => 0,
    'workshop_tasks' => 0,
], $stats ?? []);

$lowerCase = static function (string $value): string {
    if ($value === '') {
        return '';
    }
    if (function_exists('mb_strtolower')) {
        return mb_strtolower($value, 'UTF-8');
    }

    return strtolower($value);
};

$contains = static function (string $haystack, string $needle): bool {
    if ($needle === '') {
        return true;
    }

    return strpos($haystack, $needle) !== false;
};
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h3 class="fw-bold mb-1">Kế hoạch sản xuất SV5TOT</h3>
        <p class="text-muted mb-0">Theo dõi tiến độ tổng và chủ động lập kế hoạch cho các đơn hàng chưa xử lý.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="?controller=plan&action=create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Tạo kế hoạch mới
        </a>
        <a href="?controller=factory_plan&action=index" class="btn btn-outline-primary">
            <i class="bi bi-diagram-3 me-2"></i>Kế hoạch xưởng
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Tổng số kế hoạch</div>
                <div class="fs-4 fw-semibold mb-1"><?= htmlspecialchars((string) $stats['total_plans']) ?></div>
                <div class="text-muted small">Hoàn thành: <?= htmlspecialchars((string) $stats['completed_plans']) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Đang triển khai</div>
                <div class="fs-4 fw-semibold mb-1 text-primary"><?= htmlspecialchars((string) $stats['active_plans']) ?></div>
                <div class="text-muted small">Bao gồm kế hoạch mới tạo & đang thực hiện</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Đơn chờ lập kế hoạch</div>
                <div class="fs-4 fw-semibold mb-1 text-warning"><?= htmlspecialchars((string) $stats['pending_orders']) ?></div>
                <div class="text-muted small">Chi tiết sản phẩm: <?= htmlspecialchars((string) $stats['pending_details']) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Hạng mục xưởng</div>
                <div class="fs-4 fw-semibold mb-1"><?= htmlspecialchars((string) $stats['workshop_tasks']) ?></div>
                <div class="text-muted small">Tổng số công đoạn đã phân bổ</div>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($pendingOrders)): ?>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="mb-0">Đơn hàng chờ lập kế hoạch</h5>
                    <p class="text-muted small mb-0">Chọn nhanh chi tiết đơn hàng để chuyển sang bước lập kế hoạch.</p>
                </div>
                <a href="?controller=plan&action=create" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-magic me-1"></i>Tạo kế hoạch từ danh sách này
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="vstack gap-3">
                <?php foreach ($pendingOrders as $order): ?>
                    <div class="border rounded-3 p-3">
                        <div class="d-flex justify-content-between flex-wrap gap-2 align-items-center">
                            <div>
                                <div class="fw-semibold">Đơn hàng <?= htmlspecialchars($order['IdDonHang'] ?? '') ?></div>
                                <?php if (!empty($order['NgayLap'])): ?>
                                    <div class="text-muted small">Ngày lập: <?= date('d/m/Y', strtotime($order['NgayLap'])) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="table-responsive mt-3">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Số lượng</th>
                                    <th>Ngày giao dự kiến</th>
                                    <th>Yêu cầu/Ghi chú</th>
                                    <th class="text-end"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($order['details'] as $detail): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-semibold"><?= htmlspecialchars($detail['TenSanPham'] ?? '-') ?></div>
                                            <div class="text-muted small"><?= htmlspecialchars($detail['TenCauHinh'] ?? 'Cấu hình tiêu chuẩn') ?></div>
                                        </td>
                                        <td class="text-center">
                                            <?= htmlspecialchars((string) ($detail['SoLuong'] ?? 0)) ?>
                                            <div class="text-muted small"><?= htmlspecialchars($detail['DonVi'] ?? 'sản phẩm') ?></div>
                                        </td>
                                        <td>
                                            <?php if (!empty($detail['NgayGiao'])): ?>
                                                <?= date('d/m/Y H:i', strtotime($detail['NgayGiao'])) ?>
                                            <?php else: ?>
                                                <span class="text-muted">Chưa cập nhật</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($detail['YeuCauChiTiet'])): ?>
                                                <div><?= htmlspecialchars($detail['YeuCauChiTiet']) ?></div>
                                            <?php elseif (!empty($detail['YeuCauDonHang'])): ?>
                                                <div><?= htmlspecialchars($detail['YeuCauDonHang']) ?></div>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <a class="btn btn-sm btn-outline-primary" href="?controller=plan&action=create&order_detail_id=<?= urlencode($detail['IdTTCTDonHang']) ?>">
                                                Lập kế hoạch
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <?php if (empty($plans)): ?>
        <div class="card-body">
            <div class="alert alert-light border mb-0">
                Chưa có kế hoạch nào được tạo. Nhấn "Tạo kế hoạch mới" để bắt đầu phân bổ đơn hàng.
            </div>
        </div>
    <?php else: ?>
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Danh sách kế hoạch hiện tại</h5>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>Mã kế hoạch</th>
                    <th>Đơn hàng</th>
                    <th>Sản phẩm</th>
                    <th>Khối lượng</th>
                    <th>Phân xưởng</th>
                    <th>Trạng thái</th>
                    <th class="text-end"></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($plans as $plan): ?>
                    <?php
                    $planStatus = $plan['TrangThai'] ?? '';
                    $statusNormalized = $lowerCase($planStatus);
                    $statusClass = 'badge-soft-secondary';
                    if ($contains($statusNormalized, 'hoàn thành') || $contains($statusNormalized, 'hoan thanh')) {
                        $statusClass = 'badge-soft-success';
                    } elseif ($contains($statusNormalized, 'đang') || $contains($statusNormalized, 'dang')) {
                        $statusClass = 'badge-soft-primary';
                    } elseif ($contains($statusNormalized, 'chờ') || $contains($statusNormalized, 'cho')) {
                        $statusClass = 'badge-soft-warning';
                    } elseif ($contains($statusNormalized, 'hủy') || $contains($statusNormalized, 'huy')) {
                        $statusClass = 'badge-soft-danger';
                    }

                    $workshopPlans = $plan['workshopPlans'] ?? [];
                    $workshopCount = count($workshopPlans);
                    $completedWorkshops = 0;
                    foreach ($workshopPlans as $workshopPlan) {
                        $wsStatus = $lowerCase($workshopPlan['TrangThai'] ?? '');
                        if ($contains($wsStatus, 'hoàn thành') || $contains($wsStatus, 'hoan thanh')) {
                            $completedWorkshops++;
                        }
                    }

                    $start = $plan['ThoiGianBD'] ?? null;
                    $end = $plan['ThoiGianKetThuc'] ?? null;
                    ?>
                    <tr>
                        <td class="fw-semibold">#<?= htmlspecialchars($plan['IdKeHoachSanXuat']) ?></td>
                        <td>
                            <div class="fw-semibold">ĐH <?= htmlspecialchars($plan['IdDonHang'] ?? '-') ?></div>
                            <?php if (!empty($plan['YeuCau'])): ?>
                                <div class="text-muted small">Yêu cầu: <?= htmlspecialchars($plan['YeuCau']) ?></div>
                            <?php endif; ?>
                            <?php if ($start || $end): ?>
                                <div class="text-muted small">BĐ: <?= $start ? date('d/m/Y H:i', strtotime($start)) : '-' ?> • KT: <?= $end ? date('d/m/Y H:i', strtotime($end)) : '-' ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="fw-semibold"><?= htmlspecialchars($plan['TenSanPham'] ?? '-') ?></div>
                            <div class="text-muted small"><?= htmlspecialchars($plan['TenCauHinh'] ?? 'Cấu hình chuẩn') ?></div>
                            <?php if (!empty($plan['TenQuanLy'])): ?>
                                <div class="text-muted small">BGĐ phụ trách: <?= htmlspecialchars($plan['TenQuanLy']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div><?= htmlspecialchars((string) ($plan['SoLuong'] ?? 0)) ?> / <?= htmlspecialchars((string) ($plan['SoLuongDonHang'] ?? $plan['SoLuong'] ?? 0)) ?> <?= htmlspecialchars($plan['DonVi'] ?? 'sản phẩm') ?></div>
                            <div class="text-muted small">Hoàn thành xưởng: <?= $workshopCount ? ($completedWorkshops . '/' . $workshopCount) : 'Chưa phân công' ?></div>
                        </td>
                        <td>
                            <?php if ($workshopCount === 0): ?>
                                <span class="badge bg-light text-muted">Chưa phân xưởng</span>
                            <?php else: ?>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php foreach (array_slice($workshopPlans, 0, 3) as $workshopPlan): ?>
                                        <span class="badge bg-light text-dark"><?= htmlspecialchars($workshopPlan['TenXuong'] ?? $workshopPlan['IdXuong'] ?? '-') ?></span>
                                    <?php endforeach; ?>
                                    <?php if ($workshopCount > 3): ?>
                                        <span class="badge bg-light text-dark">+<?= $workshopCount - 3 ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge <?= $statusClass ?>"><?= htmlspecialchars($planStatus ?: 'Chưa cập nhật') ?></span>
                        </td>
                        <td class="text-end">
                            <div class="table-actions">
                                <a class="btn btn-sm btn-outline-secondary" href="?controller=plan&action=read&id=<?= urlencode($plan['IdKeHoachSanXuat']) ?>">Chi tiết</a>
                                <a class="btn btn-sm btn-outline-primary" href="?controller=plan&action=edit&id=<?= urlencode($plan['IdKeHoachSanXuat']) ?>">Sửa</a>
                                <a class="btn btn-sm btn-outline-danger" href="?controller=plan&action=delete&id=<?= urlencode($plan['IdKeHoachSanXuat']) ?>" onclick="return confirm('Xác nhận xóa kế hoạch này?');">Xóa</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
