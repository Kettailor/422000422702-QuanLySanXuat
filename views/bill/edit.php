<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Cập nhật hóa đơn</h3>
        <p class="text-muted mb-0">Chỉnh sửa thông tin hóa đơn hiện có.</p>
    </div>
    <a href="?controller=bill&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php
$eligibleOrders = $eligibleOrders ?? [];
$eligibleOrderMap = array_fill_keys(array_column($eligibleOrders, 'IdDonHang'), true);
?>
<?php if (!$bill): ?>
    <div class="alert alert-warning">Không tìm thấy hóa đơn.</div>
<?php else: ?>
    <div class="card p-4">
        <form action="?controller=bill&action=update" method="post" class="row g-4">
            <input type="hidden" name="IdHoaDon" value="<?= htmlspecialchars($bill['IdHoaDon']) ?>">
            <div class="col-md-4">
                <label class="form-label">Ngày lập</label>
                <input type="date" name="NgayLap" class="form-control" value="<?= $bill['NgayLap'] ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Loại hóa đơn</label>
                <input type="text" name="LoaiHD" class="form-control" value="<?= htmlspecialchars($bill['LoaiHD']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Trạng thái</label>
                <select name="TrangThai" class="form-select">
                    <?php foreach (['Chưa thanh toán', 'Đã thanh toán', 'Hủy'] as $status): ?>
                        <option value="<?= $status ?>" <?= $status === $bill['TrangThai'] ? 'selected' : '' ?>><?= $status ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Đơn hàng</label>
                <select name="IdDonHang" class="form-select" required>
                    <option value="" disabled>Chọn đơn hàng</option>
                    <?php foreach ($eligibleOrders as $order): ?>
                        <option value="<?= htmlspecialchars($order['IdDonHang'] ?? '') ?>" <?= ($order['IdDonHang'] ?? '') === ($bill['IdDonHang'] ?? '') ? 'selected' : '' ?>>
                            <?= htmlspecialchars($order['IdDonHang'] ?? '') ?> · <?= htmlspecialchars($order['TenKhachHang'] ?? 'Khách hàng') ?>
                        </option>
                    <?php endforeach; ?>
                    <?php if (!empty($bill['IdDonHang']) && !isset($eligibleOrderMap[$bill['IdDonHang']])): ?>
                        <option value="<?= htmlspecialchars($bill['IdDonHang']) ?>" selected>
                            <?= htmlspecialchars($bill['IdDonHang']) ?> · Đã hủy hoặc chưa đủ điều kiện
                        </option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Thuế (%)</label>
                <input type="number" name="Thue" class="form-control" min="0" step="0.01" value="<?= htmlspecialchars($bill['Thue'] ?? 0) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Mã bưu điện</label>
                <input type="text" name="MaBuuDien" class="form-control" value="<?= htmlspecialchars($bill['MaBuuDien'] ?? '') ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Ghi chú</label>
                <textarea name="GhiChu" class="form-control" rows="3"><?= htmlspecialchars($bill['GhiChu'] ?? '') ?></textarea>
            </div>
            <div class="col-12 text-end">
                <button class="btn btn-primary px-4" type="submit">Cập nhật hóa đơn</button>
            </div>
        </form>
    </div>
<?php endif; ?>
