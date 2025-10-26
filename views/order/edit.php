<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Chỉnh sửa đơn SV5TOT</h3>
        <p class="text-muted mb-0">Điều chỉnh thông tin cấu hình bàn phím SV5TOT cho đơn này.</p>
    </div>
    <a href="?controller=order&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php if (!$order): ?>
    <div class="alert alert-warning">Không tìm thấy đơn hàng.</div>
<?php else: ?>
    <div class="card p-4">
        <form action="?controller=order&action=update" method="post" class="row g-4">
            <input type="hidden" name="IdDonHang" value="<?= htmlspecialchars($order['IdDonHang']) ?>">
            <?php
            $selectedCustomerId = $order['IdKhachHang'] ?? null;
            $customerMode = 'existing';
            $customerFormData = ['name' => '', 'phone' => '', 'address' => '', 'type' => ''];
            include __DIR__ . '/partials/customer_selector.php';
            ?>
            <div class="col-lg-3 col-md-6">
                <label class="form-label">Ngày lập</label>
                <input type="date" name="NgayLap" class="form-control" value="<?= $order['NgayLap'] ?>">
            </div>
            <div class="col-lg-3 col-md-6">
                <label class="form-label">Trạng thái đơn</label>
                <select name="TrangThai" class="form-select">
                    <?php foreach ($orderStatuses as $status): ?>
                        <option value="<?= htmlspecialchars($status) ?>" <?= $status === $order['TrangThai'] ? 'selected' : '' ?>><?= htmlspecialchars($status) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Yêu cầu tổng quan</label>
                <textarea name="YeuCau" rows="3" class="form-control"><?= htmlspecialchars($order['YeuCau']) ?></textarea>
            </div>
            <?php include __DIR__ . '/partials/detail_form.php'; ?>
            <div class="col-12 text-end">
                <button class="btn btn-primary px-4" type="submit">Cập nhật đơn SV5TOT</button>
            </div>
        </form>
    </div>
<?php endif; ?>
