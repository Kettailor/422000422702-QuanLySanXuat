<?php
$orders = $orders ?? [];
$plans = $plans ?? [];
$hasPlans = !empty($plans);
$initialPlan = null;
$initialOrderId = null;

if ($hasPlans) {
    if (!empty($initialPlanId) && isset($plans[$initialPlanId])) {
        $initialPlan = $plans[$initialPlanId];
    } else {
        $initialPlan = reset($plans) ?: null;
    }

    $initialOrderId = $initialPlan['IdDonHang'] ?? ($orders[0]['IdDonHang'] ?? null);
}

$initialOrderPlans = [];
if ($initialOrderId) {
    foreach ($orders as $order) {
        if (($order['IdDonHang'] ?? null) === $initialOrderId) {
            $initialOrderPlans = $order['plans'] ?? [];
            break;
        }
    }
}

$planningData = [
    'orders' => $orders,
    'plans' => $plans,
    'initialPlanId' => $initialPlan['IdKeHoachSanXuat'] ?? null,
];
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h3 class="fw-bold mb-1">Kế hoạch sản xuất SV5TOT</h3>
        <p class="text-muted mb-0">Theo dõi toàn bộ kế hoạch tổng và phân bổ cho từng xưởng.</p>
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

<div class="card p-4">
    <?php if (empty($plans)): ?>
        <div class="alert alert-light border mb-0">
            Chưa có kế hoạch nào được tạo. Nhấn "Thêm kế hoạch SV5TOT" để bắt đầu.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>Mã kế hoạch</th>
                    <th>Đơn hàng</th>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Tiến độ xưởng</th>
                    <th>Trạng thái</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($plans as $plan): ?>
                    <?php
                    $totalSteps = (int) ($plan['TongCongDoan'] ?? 0);
                    $doneSteps = (int) ($plan['CongDoanHoanThanh'] ?? 0);
                    $statusClass = 'badge-soft-warning';
                    if (($plan['TrangThai'] ?? '') === 'Hoàn thành') {
                        $statusClass = 'badge-soft-success';
                    } elseif (($plan['TrangThai'] ?? '') === 'Đang thực hiện') {
                        $statusClass = 'badge-soft-primary';
                    } elseif (($plan['TrangThai'] ?? '') === 'Đã hủy') {
                        $statusClass = 'badge-soft-danger';
                    }
                    ?>
                    <tr>
                        <td class="fw-semibold">#<?= htmlspecialchars($plan['IdKeHoachSanXuat']) ?></td>
                        <td>
                            <div class="fw-semibold">ĐH <?= htmlspecialchars($plan['IdDonHang']) ?></div>
                            <div class="text-muted small"><?= htmlspecialchars($plan['YeuCau'] ?? 'Không có ghi chú') ?></div>
                        </td>
                        <td>
                            <div class="fw-semibold"><?= htmlspecialchars($plan['TenSanPham'] ?? '') ?></div>
                            <div class="text-muted small"><?= htmlspecialchars($plan['TenCauHinh'] ?? 'Cấu hình chuẩn') ?></div>
                            <?php if (!empty($plan['TenQuanLy'])): ?>
                                <div class="text-muted small">Phụ trách: <?= htmlspecialchars($plan['TenQuanLy']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div><?= htmlspecialchars($plan['SoLuong']) ?> / <?= htmlspecialchars($plan['SoLuongDonHang'] ?? $plan['SoLuong']) ?> <?= htmlspecialchars($plan['DonVi'] ?? 'sản phẩm') ?></div>
                            <div class="text-muted small">Bắt đầu: <?= $plan['ThoiGianBD'] ? date('d/m/Y', strtotime($plan['ThoiGianBD'])) : '-' ?></div>
                            <div class="text-muted small">Kết thúc: <?= $plan['ThoiGianKetThuc'] ? date('d/m/Y', strtotime($plan['ThoiGianKetThuc'])) : '-' ?></div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">
                                <?= $totalSteps ? $doneSteps . ' / ' . $totalSteps . ' công đoạn' : 'Chưa phân xưởng' ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge <?= $statusClass ?>">
                                <?= htmlspecialchars($plan['TrangThai'] ?? 'Chưa cập nhật') ?>
                            </span>
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
