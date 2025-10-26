<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Tạo đơn hàng mới</h3>
        <p class="text-muted mb-0">Nhập thông tin khách hàng, sản phẩm và cấu hình theo yêu cầu sản xuất.</p>
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

<div class="card p-4">
    <form action="?controller=order&action=store" method="post" class="row g-4">
        <input type="hidden" name="IdDonHang" value="">
        <?php
        $selectedCustomerId = null;
        $customerMode = 'existing';
$customerFormData = ['name' => '', 'phone' => '', 'email' => '', 'address' => '', 'type' => ''];
        include __DIR__ . '/partials/customer_selector.php';
        ?>
        <div class="col-lg-3 col-md-6">
            <label class="form-label">Ngày lập</label>
            <input type="date" name="NgayLap" class="form-control" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="col-lg-3 col-md-6">
            <label class="form-label">Trạng thái</label>
            <select name="TrangThai" class="form-select">
                <?php foreach ($orderStatuses as $status): ?>
                    <option value="<?= htmlspecialchars($status) ?>" <?= $status === ($orderStatuses[0] ?? '') ? 'selected' : '' ?>><?= htmlspecialchars($status) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-lg-3 col-md-6">
            <label class="form-label">Email liên hệ</label>
            <input type="email" name="EmailLienHe" class="form-control" value="<?= htmlspecialchars($_POST['EmailLienHe'] ?? '') ?>" placeholder="Ví dụ: customer@example.com">
        </div>
        <div class="col-12">
            <label class="form-label">Yêu cầu chung</label>
            <textarea name="YeuCau" rows="3" class="form-control" placeholder="Ghi chú chung cho toàn bộ đơn hàng"></textarea>
        </div>
        <?php include __DIR__ . '/partials/detail_form.php'; ?>
        <div class="col-12 text-end">
            <button class="btn btn-primary px-4" type="submit">Lưu đơn hàng</button>
        </div>
    </form>
</div>
