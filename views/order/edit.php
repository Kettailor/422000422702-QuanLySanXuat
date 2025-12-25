<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Chỉnh sửa đơn hàng</h3>
        <p class="text-muted mb-0">Chỉ cập nhật email liên hệ, tăng số lượng và dời ngày giao muộn hơn.</p>
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
$customerFormData = ['name' => '', 'phone' => '', 'email' => '', 'address' => '', 'type' => ''];
$isCustomerLocked = true;
?>

<div class="card border-0 shadow-sm p-4">
    <form action="?controller=order&action=update" method="post" class="row g-4">
        <input type="hidden" name="IdDonHang" value="<?= htmlspecialchars($order['IdDonHang'] ?? '') ?>">
        <div class="col-12">
            <div class="alert alert-light border mb-0">
                Bạn chỉ có thể cập nhật email liên hệ, tăng số lượng và dời ngày giao muộn hơn.
            </div>
        </div>
        <?php include __DIR__ . '/partials/customer_selector.php'; ?>
        <div class="col-lg-4 col-md-6">
            <label class="form-label">Email liên hệ</label>
            <input type="email" name="EmailLienHe" class="form-control" value="<?= htmlspecialchars($order['EmailLienHe'] ?? '') ?>" placeholder="Ví dụ: customer@example.com" required>
        </div>
        <div class="col-12">
            <label class="form-label">Yêu cầu chung</label>
            <textarea name="YeuCau" rows="3" class="form-control" placeholder="Ghi chú chung cho toàn bộ đơn hàng" readonly><?= htmlspecialchars($order['YeuCau'] ?? '') ?></textarea>
        </div>
        <?php $isEditMode = true; ?>
        <?php include __DIR__ . '/partials/detail_form.php'; ?>
        <div class="col-12 text-end">
            <button class="btn btn-primary px-4" type="submit">Cập nhật đơn hàng</button>
        </div>
    </form>
</div>
