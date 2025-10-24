<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Cập nhật hóa đơn</h3>
        <p class="text-muted mb-0">Chỉnh sửa thông tin hóa đơn hiện có.</p>
    </div>
    <a href="?controller=bill&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

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
                    <?php foreach (['Chưa thanh toán', 'Đã thanh toán'] as $status): ?>
                        <option value="<?= $status ?>" <?= $status === $bill['TrangThai'] ? 'selected' : '' ?>><?= $status ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Mã đơn hàng</label>
                <input type="text" name="IdDonHang" class="form-control" value="<?= htmlspecialchars($bill['IdDonHang']) ?>">
            </div>
            <div class="col-12 text-end">
                <button class="btn btn-primary px-4" type="submit">Cập nhật hóa đơn</button>
            </div>
        </form>
    </div>
<?php endif; ?>
