<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Chỉnh sửa đơn hàng</h3>
        <p class="text-muted mb-0">Cập nhật thông tin cần thiết cho đơn hàng.</p>
    </div>
    <a href="?controller=order&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php if (!$order): ?>
    <div class="alert alert-warning">Không tìm thấy đơn hàng.</div>
<?php else: ?>
    <div class="card p-4">
        <form action="?controller=order&action=update" method="post" class="row g-4">
            <input type="hidden" name="IdDonHang" value="<?= htmlspecialchars($order['IdDonHang']) ?>">
            <div class="col-md-6">
                <label class="form-label">Khách hàng</label>
                <select name="IdKhachHang" class="form-select" required>
                    <?php foreach ($customers as $customer): ?>
                        <option value="<?= htmlspecialchars($customer['IdKhachHang']) ?>" <?= $customer['IdKhachHang'] === $order['IdKhachHang'] ? 'selected' : '' ?>><?= htmlspecialchars($customer['HoTen']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Ngày lập</label>
                <input type="date" name="NgayLap" class="form-control" value="<?= $order['NgayLap'] ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Trạng thái</label>
                <select name="TrangThai" class="form-select">
                    <?php foreach (['Mới tạo', 'Đang xử lý', 'Hoàn thành'] as $status): ?>
                        <option value="<?= $status ?>" <?= $status === $order['TrangThai'] ? 'selected' : '' ?>><?= $status ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tổng tiền</label>
                <input type="number" name="TongTien" class="form-control" value="<?= $order['TongTien'] ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Yêu cầu</label>
                <textarea name="YeuCau" rows="4" class="form-control"><?= htmlspecialchars($order['YeuCau']) ?></textarea>
            </div>
            <div class="col-12 text-end">
                <button class="btn btn-primary px-4" type="submit">Cập nhật</button>
            </div>
        </form>
    </div>
<?php endif; ?>
