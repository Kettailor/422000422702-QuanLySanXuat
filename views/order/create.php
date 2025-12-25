<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Tạo đơn hàng mới</h3>
        <p class="text-muted mb-0">Nhập thông tin khách hàng, cấu hình sản phẩm và hệ thống sẽ tự ghi nhận ngày lập.</p>
    </div>
    <a href="?controller=order&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php
$customers = $customers ?? [];
$orderStatuses = $orderStatuses ?? [];
$orderDetails = $orderDetails ?? [];
$products = $products ?? [];
$configurations = $configurations ?? [];
?>

<div class="card border-0 shadow-sm p-4">
    <form action="?controller=order&action=store" method="post" class="row g-4">
        <input type="hidden" name="IdDonHang" value="">
        <?php
        $selectedCustomerId = null;
$customerMode = 'existing';
$customerFormData = ['name' => '', 'phone' => '', 'email' => '', 'address' => '', 'type' => ''];
include __DIR__ . '/partials/customer_selector.php';
?>
        <div class="col-lg-4 col-md-6">
            <label class="form-label">Email liên hệ</label>
            <input type="email" name="EmailLienHe" class="form-control" value="<?= htmlspecialchars($_POST['EmailLienHe'] ?? '') ?>" placeholder="Ví dụ: customer@example.com" required>
        </div>
        <div class="col-12">
            <label class="form-label">Yêu cầu chung</label>
            <textarea name="YeuCau" rows="3" class="form-control" placeholder="Ghi chú chung cho toàn bộ đơn hàng"></textarea>
        </div>
        <?php $isEditMode = false; ?>
        <?php include __DIR__ . '/partials/detail_form.php'; ?>
        <div class="col-12 text-end">
            <button class="btn btn-primary px-4" type="submit">Lưu đơn hàng</button>
        </div>
    </form>
</div>
