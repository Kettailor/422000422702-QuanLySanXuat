<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Tạo hóa đơn</h3>
        <p class="text-muted mb-0">Ghi nhận thông tin hóa đơn liên quan đến đơn hàng.</p>
    </div>
    <a href="?controller=bill&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<div class="card p-4">
    <?php $eligibleOrders = $eligibleOrders ?? []; ?>
    <form action="?controller=bill&action=store" method="post" class="row g-4">
        <div class="col-md-4">
            <label class="form-label">Mã hóa đơn</label>
            <input type="text" name="IdHoaDon" class="form-control" placeholder="Tự sinh nếu để trống" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Ngày lập</label>
            <input type="date" name="NgayLap" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Loại hóa đơn</label>
            <input type="text" name="LoaiHD" class="form-control" value="Bán hàng" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Trạng thái</label>
            <select name="TrangThai" class="form-select" required>
                <option value="Chưa thanh toán">Chưa thanh toán</option>
                <option value="Đã thanh toán">Đã thanh toán</option>
                <option value="Hủy">Hủy</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Đơn hàng đã hoàn thành kế hoạch</label>
            <select name="IdDonHang" class="form-select" required>
                <option value="" disabled selected>Chọn đơn hàng</option>
                <?php foreach ($eligibleOrders as $order): ?>
                    <option value="<?= htmlspecialchars($order['IdDonHang'] ?? '') ?>">
                        <?= htmlspecialchars($order['IdDonHang'] ?? '') ?> · <?= htmlspecialchars($order['TenKhachHang'] ?? 'Khách hàng') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Mã đơn hàng</label>
            <input type="text" name="IdDonHang" class="form-control" required>
        </div>
        <div class="col-12 text-end">
            <button class="btn btn-primary px-4" type="submit">Lưu hóa đơn</button>
        </div>
    </form>
</div>
