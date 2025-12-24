<?php
$orders = $orders ?? [];
$statusCounts = array_reduce($orders, static function (array $carry, array $order): array {
    $status = $order['TrangThai'] ?? 'Chưa có kế hoạch';
    $carry[$status] = ($carry[$status] ?? 0) + 1;
    return $carry;
}, []);
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1">Quản lý đơn hàng</h3>
        <p class="text-muted mb-0">Theo dõi tiến độ sản xuất, trạng thái kế hoạch và giao hàng theo từng khách hàng.</p>
    </div>
    <a href="?controller=order&action=create" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Tạo đơn hàng</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Tổng đơn hàng</div>
                <div class="fs-4 fw-semibold"><?= count($orders) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Chưa có kế hoạch</div>
                <div class="fs-4 fw-semibold text-warning"><?= $statusCounts['Chưa có kế hoạch'] ?? 0 ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Đang xử lý</div>
                <div class="fs-4 fw-semibold text-primary"><?= $statusCounts['Đang xử lý'] ?? 0 ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Hoàn thành / Đã hoàn thành</div>
                <div class="fs-4 fw-semibold text-success">
                    <?= ($statusCounts['Hoàn thành'] ?? 0) + ($statusCounts['Đã hoàn thành'] ?? 0) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
            <tr>
                <th>Mã đơn</th>
                <th>Khách hàng</th>
                <th>Ngày lập</th>
                <th>Trạng thái</th>
                <th>Yêu cầu</th>
                <th class="text-end">Tổng tiền</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td class="fw-semibold">#<?= htmlspecialchars($order['IdDonHang']) ?></td>
                    <td>
                        <div class="fw-medium"><?= htmlspecialchars($order['TenKhachHang']) ?></div>
                        <?php if (!empty($order['TenCongTy'])): ?>
                            <div class="text-muted small">Công ty: <?= htmlspecialchars($order['TenCongTy']) ?></div>
                        <?php endif; ?>
                        <div class="text-muted small"><?= htmlspecialchars($order['SoDienThoai'] ?? '') ?></div>
                        <?php if (!empty($order['EmailLienHe'] ?? '')): ?>
                            <div class="text-muted small"><?= htmlspecialchars($order['EmailLienHe']) ?></div>
                        <?php elseif (!empty($order['EmailKhachHang'] ?? '')): ?>
                            <div class="text-muted small"><?= htmlspecialchars($order['EmailKhachHang']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td><?= !empty($order['NgayLap']) ? date('d/m/Y', strtotime($order['NgayLap'])) : '--' ?></td>
                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($order['TrangThai'] ?? '---') ?></span></td>
                    <td class="text-muted small">
                        <?php if (!empty($order['YeuCau'])): ?>
                            <?= htmlspecialchars(mb_strimwidth($order['YeuCau'], 0, 80, '…', 'UTF-8')) ?>
                        <?php else: ?>
                            <span class="text-muted">Không có</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end fw-semibold text-primary"><?= number_format((float) ($order['TongTien'] ?? 0), 0, ',', '.') ?> đ</td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm" role="group">
                            <a class="btn btn-outline-secondary" href="?controller=order&action=read&id=<?= urlencode($order['IdDonHang']) ?>">Chi tiết</a>
                            <?php if (($order['TrangThai'] ?? '') !== 'Hủy'): ?>
                                <a class="btn btn-outline-primary" href="?controller=order&action=edit&id=<?= urlencode($order['IdDonHang']) ?>">Sửa</a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
