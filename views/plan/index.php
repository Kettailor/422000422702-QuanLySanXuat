<?php
$plans = $plans ?? [];
$pendingOrders = $pendingOrders ?? [];
$canManagePlan = $canManagePlan ?? false;
$stats = array_merge([
    'total_plans' => 0,
    'pending_orders' => 0,
    'pending_details' => 0,
    'workshop_tasks' => 0,
], $stats ?? []);

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

$badgeForStatus = static function (string $status): string {
    $normalized = strtolower(trim($status));

    if ($normalized === '') {
        return 'badge bg-light text-muted';
    }

    if (str_contains($normalized, 'hoàn thành')) {
        return 'badge bg-success-subtle text-success';
    }

    if (str_contains($normalized, 'hủy')) {
        return 'badge bg-danger-subtle text-danger';
    }

    if (str_contains($normalized, 'đang')) {
        return 'badge bg-primary-subtle text-primary';
    }

    if (str_contains($normalized, 'chờ')) {
        return 'badge bg-warning-subtle text-warning';
    }

    return 'badge bg-secondary-subtle text-secondary';
};
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1">Quy trình lập kế hoạch sản xuất</h2>
        <p class="text-muted mb-0">Theo dõi tiến độ các kế hoạch hiện có và bắt đầu giao việc cho các xưởng dựa trên đơn hàng mới.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <?php if ($canManagePlan): ?>
            <a href="?controller=plan&action=create" class="btn btn-primary">
                <i class="bi bi-magic me-2"></i>Lập kế hoạch mới
            </a>
        <?php endif; ?>
        <a href="?controller=factory_plan&action=index" class="btn btn-outline-primary">
            <i class="bi bi-building-gear me-2"></i>Tiến độ xưởng
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small text-uppercase">Kế hoạch đã tạo</div>
                <div class="fs-3 fw-semibold mb-1"><?= htmlspecialchars((string) $stats['total_plans']) ?></div>
                <div class="text-muted small">Tổng hợp toàn bộ kế hoạch sản xuất.</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small text-uppercase">Đơn hàng chờ xử lý</div>
                <div class="fs-3 fw-semibold mb-1 text-warning"><?= htmlspecialchars((string) $stats['pending_orders']) ?></div>
                <div class="text-muted small"><?= htmlspecialchars((string) $stats['pending_details']) ?> dòng sản phẩm cần lập kế hoạch.</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small text-uppercase">Hạng mục xưởng</div>
                <div class="fs-3 fw-semibold mb-1 text-primary"><?= htmlspecialchars((string) $stats['workshop_tasks']) ?></div>
                <div class="text-muted small">Đã giao cho các xưởng phụ trách.</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small text-uppercase">Hướng dẫn nhanh</div>
                <ul class="list-unstyled mb-0 small">
                    <li class="d-flex gap-2 align-items-start mb-1"><i class="bi bi-1-circle text-primary"></i><span>Chọn đơn hàng chờ trong danh sách.</span></li>
                    <li class="d-flex gap-2 align-items-start mb-1"><i class="bi bi-2-circle text-primary"></i><span>Xác nhận thông tin và cấu hình sản phẩm.</span></li>
                    <li class="d-flex gap-2 align-items-start"><i class="bi bi-3-circle text-primary"></i><span>Phân công xưởng, giao số lượng & hạn chót.</span></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">Đơn hàng chờ lập kế hoạch</h5>
                <span class="text-muted small">Lựa chọn một dòng sản phẩm để bắt đầu lập kế hoạch.</span>
            </div>
            <div class="card-body p-0">
                <?php if (!$canManagePlan): ?>
                    <div class="p-4 text-center text-muted">
                        Vai trò của bạn chỉ được phép theo dõi kế hoạch sản xuất.
                    </div>
                <?php elseif (empty($pendingOrders)): ?>
                    <div class="p-4 text-center text-muted">
                        Tất cả đơn hàng đều đã có kế hoạch.
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($pendingOrders as $order): ?>
                            <div class="list-group-item list-group-item-action py-3">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <div>
                                        <div class="fw-semibold">Đơn hàng <?= htmlspecialchars($order['IdDonHang'] ?? '-') ?></div>
                                        <?php if (!empty($order['TenKhachHang'])): ?>
                                            <div class="text-muted small">Khách hàng: <?= htmlspecialchars($order['TenKhachHang']) ?></div>
                                        <?php endif; ?>
                                        <?php
                                        $orderEmail = $order['EmailLienHe'] ?? null;
                            if (!$orderEmail && !empty($order['details'][0]['Email'])) {
                                $orderEmail = $order['details'][0]['Email'];
                            }
                            ?>
                                        <?php if (!empty($orderEmail)): ?>
                                            <div class="text-muted small">Email: <?= htmlspecialchars($orderEmail) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($order['NgayLap'])): ?>
                                            <div class="text-muted small">Ngày lập: <?= $formatDate($order['NgayLap'], 'd/m/Y') ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <a class="btn btn-sm btn-outline-primary" href="?controller=plan&action=create&order_detail_id=<?= urlencode($order['details'][0]['IdTTCTDonHang'] ?? '') ?>">
                                        Lập kế hoạch
                                    </a>
                                </div>
                                <div class="mt-3">
                                    <?php foreach ($order['details'] as $detail): ?>
                                        <div class="border rounded-3 p-3 mb-2">
                                            <div class="d-flex justify-content-between align-items-center gap-2">
                                                <div>
                                                    <div class="fw-semibold"><?= htmlspecialchars($detail['TenSanPham'] ?? 'Sản phẩm') ?></div>
                                                    <div class="text-muted small"><?= htmlspecialchars($detail['TenCauHinh'] ?? 'Cấu hình tiêu chuẩn') ?></div>
                                                </div>
                                                <div class="text-end">
                                                    <div class="fw-semibold"><?= htmlspecialchars((string) ($detail['SoLuong'] ?? 0)) ?> <?= htmlspecialchars($detail['DonVi'] ?? 'sp') ?></div>
                                                    <?php if (!empty($detail['NgayGiao'])): ?>
                                                        <div class="text-muted small">Giao: <?= $formatDate($detail['NgayGiao']) ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <div class="text-muted small flex-grow-1 me-3">
                                                    <?= htmlspecialchars($detail['YeuCauChiTiet'] ?? $detail['YeuCauDonHang'] ?? 'Không có yêu cầu thêm') ?>
                                                </div>
                                                <a class="btn btn-sm btn-outline-secondary" href="?controller=plan&action=create&order_detail_id=<?= urlencode($detail['IdTTCTDonHang'] ?? '') ?>">
                                                    Chọn dòng này
                                                </a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php if ($canManagePlan && !empty($pendingOrders)): ?>
                <div class="card-footer bg-white border-0 text-center">
                    <a class="btn btn-outline-primary w-100" href="?controller=plan&action=create">
                        Xem tất cả để lập kế hoạch
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Kế hoạch đang quản lý</h5>
                        <span class="text-muted small">Theo dõi tiến độ sản xuất của từng đơn hàng.</span>
                    </div>
                </div>
            </div>
            <?php if (empty($plans)): ?>
                <div class="card-body">
                    <div class="alert alert-light border text-center mb-0">
                        Chưa có kế hoạch nào. Hãy nhấn "Lập kế hoạch mới" để bắt đầu.
                    </div>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>Mã kế hoạch</th>
                            <th>Đơn hàng</th>
                            <th>Sản phẩm</th>
                            <th>Khối lượng</th>
                            <th>Tiến độ</th>
                            <th class="text-end"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($plans as $plan): ?>
                            <?php
                            $statusValue = (string) ($plan['TrangThai'] ?? '');
                            $canCancel = $canManagePlan && in_array($statusValue, ['Đang chuẩn bị', 'Đang sản xuất'], true);
                            $cancelModalId = 'cancel-plan-' . preg_replace('/[^a-zA-Z0-9_-]/', '_', (string) ($plan['IdKeHoachSanXuat'] ?? ''));
                            ?>
                            <tr>
                                <td class="fw-semibold"><?= htmlspecialchars($plan['IdKeHoachSanXuat'] ?? '-') ?></td>
                                <td>
                                    <div class="fw-semibold"><?= htmlspecialchars($plan['IdDonHang'] ?? '-') ?></div>
                                    <?php if (!empty($plan['NgayLapDonHang'])): ?>
                                        <div class="text-muted small"><?= $formatDate($plan['NgayLapDonHang'], 'd/m/Y') ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-semibold"><?= htmlspecialchars($plan['TenSanPham'] ?? 'Sản phẩm') ?></div>
                                    <div class="text-muted small"><?= htmlspecialchars($plan['TenCauHinh'] ?? 'Cấu hình tiêu chuẩn') ?></div>
                                </td>
                                <td>
                                    <div class="fw-semibold"><?= htmlspecialchars((string) ($plan['SoLuong'] ?? 0)) ?> <?= htmlspecialchars($plan['DonVi'] ?? 'sp') ?></div>
                                    <?php if (!empty($plan['ThoiGianKetThuc'])): ?>
                                        <div class="text-muted small">Hạn: <?= $formatDate($plan['ThoiGianKetThuc']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="<?= $badgeForStatus((string) ($plan['TrangThai'] ?? '')) ?>">
                                        <?= htmlspecialchars($plan['TrangThai'] ?? 'Chưa cập nhật') ?>
                                    </span>
                                    <div class="text-muted small mt-1">
                                        <?= htmlspecialchars((string) ($plan['CongDoanHoanThanh'] ?? 0)) ?> / <?= htmlspecialchars((string) ($plan['TongCongDoan'] ?? 0)) ?> hạng mục
                                    </div>
                                </td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="?controller=plan&action=read&id=<?= urlencode($plan['IdKeHoachSanXuat'] ?? '') ?>">
                                        Chi tiết
                                    </a>
                                    <?php if ($canCancel): ?>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#<?= htmlspecialchars($cancelModalId) ?>">
                                            Hủy
                                        </button>
                                        <div class="modal fade" id="<?= htmlspecialchars($cancelModalId) ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="post" action="?controller=plan&action=cancel">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Hủy kế hoạch <?= htmlspecialchars($plan['IdKeHoachSanXuat'] ?? '') ?></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                                        </div>
                                                        <div class="modal-body text-start">
                                                            <input type="hidden" name="IdKeHoachSanXuat" value="<?= htmlspecialchars($plan['IdKeHoachSanXuat'] ?? '') ?>">
                                                            <label class="form-label">Ghi chú hủy kế hoạch</label>
                                                            <textarea name="cancel_note" class="form-control" rows="3" placeholder="Lý do hủy kế hoạch..." required></textarea>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
                                                            <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
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
