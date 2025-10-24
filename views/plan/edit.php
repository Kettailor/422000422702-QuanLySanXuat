<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Cập nhật kế hoạch</h3>
        <p class="text-muted mb-0">Điều chỉnh thông tin kế hoạch sản xuất.</p>
    </div>
    <a href="?controller=plan&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php if (!$plan): ?>
    <div class="alert alert-warning">Không tìm thấy kế hoạch.</div>
<?php else: ?>
    <div class="card p-4">
        <form action="?controller=plan&action=update" method="post" class="row g-4">
            <input type="hidden" name="IdKeHoachSanXuat" value="<?= htmlspecialchars($plan['IdKeHoachSanXuat']) ?>">
            <div class="col-md-4">
                <label class="form-label">Mã chi tiết đơn hàng</label>
                <input type="text" name="IdTTCTDonHang" class="form-control" value="<?= htmlspecialchars($plan['IdTTCTDonHang']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Ban giám đốc</label>
                <input type="text" name="BanGiamDoc" class="form-control" value="<?= htmlspecialchars($plan['`BANIAMDOC IdNhanVien`']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Số lượng</label>
                <input type="number" name="SoLuong" class="form-control" value="<?= $plan['SoLuong'] ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Bắt đầu</label>
                <input type="datetime-local" name="ThoiGianBD" class="form-control" value="<?= $plan['ThoiGianBD'] ? date('Y-m-d\\TH:i', strtotime($plan['ThoiGianBD'])) : '' ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Kết thúc</label>
                <input type="datetime-local" name="ThoiGianKetThuc" class="form-control" value="<?= $plan['ThoiGianKetThuc'] ? date('Y-m-d\\TH:i', strtotime($plan['ThoiGianKetThuc'])) : '' ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Trạng thái</label>
                <select name="TrangThai" class="form-select">
                    <?php foreach (['Mới tạo', 'Đang thực hiện', 'Hoàn thành'] as $status): ?>
                        <option value="<?= $status ?>" <?= $status === $plan['TrangThai'] ? 'selected' : '' ?>><?= $status ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 text-end">
                <button class="btn btn-primary px-4" type="submit">Cập nhật kế hoạch</button>
            </div>
        </form>
    </div>
<?php endif; ?>
