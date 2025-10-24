<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Cập nhật kế hoạch xưởng</h3>
        <p class="text-muted mb-0">Điều chỉnh tiến độ sản xuất tại xưởng.</p>
    </div>
    <a href="?controller=factory_plan&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php if (!$plan): ?>
    <div class="alert alert-warning">Không tìm thấy kế hoạch.</div>
<?php else: ?>
    <div class="card p-4">
        <form action="?controller=factory_plan&action=update" method="post" class="row g-4">
            <input type="hidden" name="IdKeHoachSanXuatXuong" value="<?= htmlspecialchars($plan['IdKeHoachSanXuatXuong']) ?>">
            <div class="col-md-4">
                <label class="form-label">Mã kế hoạch tổng</label>
                <input type="text" name="IdKeHoachSanXuat" class="form-control" value="<?= htmlspecialchars($plan['IdKeHoachSanXuat']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Mã xưởng</label>
                <input type="text" name="IdXuong" class="form-control" value="<?= htmlspecialchars($plan['IdXuong']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Thành phần sản phẩm</label>
                <input type="text" name="TenThanhThanhPhanSP" class="form-control" value="<?= htmlspecialchars($plan['TenThanhThanhPhanSP']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Số lượng</label>
                <input type="number" name="SoLuong" class="form-control" value="<?= $plan['SoLuong'] ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Bắt đầu</label>
                <input type="datetime-local" name="ThoiGianBatDau" class="form-control" value="<?= $plan['ThoiGianBatDau'] ? date('Y-m-d\\TH:i', strtotime($plan['ThoiGianBatDau'])) : '' ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Kết thúc</label>
                <input type="datetime-local" name="ThoiGianKetThuc" class="form-control" value="<?= $plan['ThoiGianKetThuc'] ? date('Y-m-d\\TH:i', strtotime($plan['ThoiGianKetThuc'])) : '' ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Trạng thái</label>
                <select name="TrangThai" class="form-select">
                    <?php foreach (['Đang chuẩn bị', 'Đang sản xuất', 'Hoàn thành'] as $status): ?>
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
