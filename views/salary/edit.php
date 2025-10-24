<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Cập nhật bảng lương</h3>
        <p class="text-muted mb-0">Chỉnh sửa thông tin lương và trạng thái chi trả.</p>
    </div>
    <a href="?controller=salary&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php if (!$payroll): ?>
    <div class="alert alert-warning">Không tìm thấy bảng lương.</div>
<?php else: ?>
    <div class="card p-4">
        <form action="?controller=salary&action=update" method="post" class="row g-4">
            <input type="hidden" name="IdBangLuong" value="<?= htmlspecialchars($payroll['IdBangLuong']) ?>">
            <div class="col-md-4">
                <label class="form-label">Nhân viên</label>
                <input type="text" name="IdNhanVien" class="form-control" value="<?= htmlspecialchars($payroll['NHAN_VIENIdNhanVien']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Kế toán duyệt</label>
                <input type="text" name="KeToan" class="form-control" value="<?= htmlspecialchars($payroll['`KETOAN IdNhanVien2`']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tháng/Năm</label>
                <input type="number" name="ThangNam" class="form-control" value="<?= $payroll['ThangNam'] ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Lương cơ bản</label>
                <input type="number" name="LuongCoBan" class="form-control" value="<?= $payroll['LuongCoBan'] ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Phụ cấp</label>
                <input type="number" name="PhuCap" class="form-control" value="<?= $payroll['PhuCap'] ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Khấu trừ</label>
                <input type="number" name="KhauTru" class="form-control" value="<?= $payroll['KhauTru'] ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Thuế TNCN</label>
                <input type="number" name="ThueTNCN" class="form-control" value="<?= $payroll['ThueTNCN'] ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tổng thu nhập</label>
                <input type="number" name="TongThuNhap" class="form-control" value="<?= $payroll['TongThuNhap'] ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Trạng thái</label>
                <select name="TrangThai" class="form-select">
                    <?php foreach (['Chờ duyệt', 'Đã duyệt', 'Đã chi'] as $status): ?>
                        <option value="<?= $status ?>" <?= $status === $payroll['TrangThai'] ? 'selected' : '' ?>><?= $status ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Ngày lập</label>
                <input type="date" name="NgayLap" class="form-control" value="<?= $payroll['NgayLap'] ?>">
            </div>
            <div class="col-12 text-end">
                <button class="btn btn-primary px-4" type="submit">Cập nhật bảng lương</button>
            </div>
        </form>
    </div>
<?php endif; ?>
