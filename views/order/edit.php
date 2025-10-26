<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Chỉnh sửa đơn hàng</h3>
        <p class="text-muted mb-0">Cập nhật thông tin khách hàng, sản phẩm và cấu hình theo yêu cầu mới.</p>
    </div>
    <a href="?controller=order&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php
$order = $order ?? null;
$customers = $customers ?? [];
$orderStatuses = $orderStatuses ?? [];
$orderDetails = $orderDetails ?? [];
$products = $products ?? [];
$configurations = $configurations ?? [];

$selectedCustomerId = $order['IdKhachHang'] ?? null;
$customerMode = 'existing';
$customerFormData = ['name' => '', 'phone' => '', 'address' => '', 'type' => ''];
?>

<div class="card p-4">
    <form action="?controller=order&action=update" method="post" class="row g-4">
        <input type="hidden" name="IdDonHang" value="<?= htmlspecialchars($order['IdDonHang'] ?? '') ?>">
        <?php include __DIR__ . '/partials/customer_selector.php'; ?>
        <div class="col-lg-3 col-md-6">
            <label class="form-label">Ngày lập</label>
            <input type="date" name="NgayLap" class="form-control" value="<?= htmlspecialchars($order['NgayLap'] ?? date('Y-m-d')) ?>">
        </div>
        <div class="col-lg-3 col-md-6">
            <label class="form-label">Trạng thái</label>
            <select name="TrangThai" class="form-select">
                <?php foreach ($orderStatuses as $status): ?>
                    <option value="<?= htmlspecialchars($status) ?>" <?= ($order['TrangThai'] ?? '') === $status ? 'selected' : '' ?>><?= htmlspecialchars($status) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-12">
            <label class="form-label">Yêu cầu chung</label>
            <textarea name="YeuCau" rows="3" class="form-control" placeholder="Ghi chú chung cho toàn bộ đơn hàng"><?= htmlspecialchars($order['YeuCau'] ?? '') ?></textarea>
        </div>
        <?php include __DIR__ . '/partials/detail_form.php'; ?>
        <div class="col-12 text-end">
            <button class="btn btn-primary px-4" type="submit">Cập nhật đơn hàng</button>
        </div>
    </form>
</div>
