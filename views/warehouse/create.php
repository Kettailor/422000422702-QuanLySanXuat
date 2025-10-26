<?php
$workshops = $workshops ?? [];
$employees = $employees ?? [];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Thêm kho SV5TOT mới</h3>
        <p class="text-muted mb-0">Khai báo kho SV5TOT và thông tin người phụ trách.</p>
    </div>
    <a href="?controller=warehouse&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php
$workshops = $workshops ?? [];
$managers = $managers ?? [];
$statuses = $statuses ?? ['Đang sử dụng', 'Tạm dừng', 'Bảo trì'];
?>

<div class="card p-4">
    <form action="?controller=warehouse&action=store" method="post" class="row g-4">
        <input type="hidden" name="IdKho" value="">
        <div class="col-md-4">
            <div class="form-floating-label">Mã kho</div>
            <div class="form-hint">Mã kho sẽ được SV5TOT tự sinh khi lưu.</div>
        </div>
        <div class="col-md-4">
            <label class="form-label">Tên kho <span class="text-danger">*</span></label>
            <input type="text" name="TenKho" class="form-control" required>
        </div>
        <div class="col-md-4">
<<<<<<< HEAD
            <label class="form-label">Loại kho</label>
            <input type="text" name="TenLoaiKho" class="form-control" placeholder="Ví dụ: Nguyên liệu, Thành phẩm">
=======
            <label class="form-label">Loại kho SV5TOT</label>
            <input type="text" name="TenLoaiKho" class="form-control">
>>>>>>> 65075f83681f452199a37e66bf195847eea6c888
        </div>
        <div class="col-md-6">
            <label class="form-label">Địa chỉ</label>
            <input type="text" name="DiaChi" class="form-control" placeholder="Địa chỉ kho">
        </div>
        <div class="col-md-3">
<<<<<<< HEAD
            <label class="form-label">Tổng số lô</label>
            <input type="number" name="TongSLLo" class="form-control" min="0" value="0">
        </div>
        <div class="col-md-3">
            <label class="form-label">Tổng sức chứa</label>
            <input type="number" name="TongSL" class="form-control" min="0" value="0">
        </div>
        <div class="col-md-4">
            <label class="form-label">Tổng giá trị hàng tồn (đ)</label>
            <input type="number" name="ThanhTien" class="form-control" min="0" value="0">
=======
            <label class="form-label">Tổng số lô SV5TOT</label>
            <input type="number" name="TongSLLo" class="form-control" min="0">
        </div>
        <div class="col-md-3">
            <label class="form-label">Tổng số lượng SV5TOT</label>
            <input type="number" name="TongSL" class="form-control" min="0">
        </div>
        <div class="col-md-4">
            <label class="form-label">Giá trị hàng tồn (VND)</label>
            <input type="number" name="ThanhTien" class="form-control" min="0">
>>>>>>> 65075f83681f452199a37e66bf195847eea6c888
        </div>
        <div class="col-md-4">
            <label class="form-label">Trạng thái</label>
            <select name="TrangThai" class="form-select">
                <?php foreach ($statuses as $status): ?>
                    <option value="<?= htmlspecialchars($status) ?>"><?= htmlspecialchars($status) ?></option>
                <?php endforeach; ?>
                <option value="Đang sử dụng">Đang sử dụng</option>
                <option value="Tạm dừng">Tạm dừng</option>
                <option value="Bảo trì">Bảo trì</option>
            </select>
        </div>
        <div class="col-md-4">
<<<<<<< HEAD
            <label class="form-label">Xưởng phụ trách <span class="text-danger">*</span></label>
            <select name="IdXuong" class="form-select" required>
                <option value="" disabled selected>Chọn xưởng phụ trách</option>
                <?php foreach ($workshops as $workshop): ?>
                    <option value="<?= htmlspecialchars($workshop['IdXuong']) ?>">
                        <?= htmlspecialchars($workshop['TenXuong']) ?> (<?= htmlspecialchars($workshop['IdXuong']) ?>)
=======
            <label class="form-label">Xưởng SV5TOT liên kết</label>
            <select name="IdXuong" class="form-select">
                <option value="">-- Chọn xưởng --</option>
                <?php foreach ($workshops as $workshop): ?>
                    <option value="<?= htmlspecialchars($workshop['IdXuong']) ?>">
                        <?= htmlspecialchars($workshop['TenXuong'] ?? $workshop['IdXuong']) ?>
>>>>>>> 65075f83681f452199a37e66bf195847eea6c888
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
<<<<<<< HEAD
            <label class="form-label">Nhân viên quản kho <span class="text-danger">*</span></label>
            <select name="NHAN_VIEN_KHO_IdNhanVien" class="form-select" required>
                <option value="" disabled selected>Chọn nhân viên quản kho</option>
                <?php foreach ($managers as $manager): ?>
                    <option value="<?= htmlspecialchars($manager['IdNhanVien']) ?>">
                        <?= htmlspecialchars($manager['HoTen']) ?><?= !empty($manager['ChucVu']) ? ' · ' . htmlspecialchars($manager['ChucVu']) : '' ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label class="form-label">Mã nhân viên quản kho</label>
            <input type="text" name="IdQuanKho" class="form-control" placeholder="VD: NV004">
=======
            <label class="form-label">Nhân viên quản kho</label>
            <select name="IdQuanKho" class="form-select">
                <option value="">-- Chọn nhân sự --</option>
                <?php foreach ($employees as $employee): ?>
                    <option value="<?= htmlspecialchars($employee['IdNhanVien']) ?>">
                        <?= htmlspecialchars($employee['HoTen']) ?> (<?= htmlspecialchars($employee['IdNhanVien']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
>>>>>>> 65075f83681f452199a37e66bf195847eea6c888
        </div>
        <div class="col-12 text-end">
            <button class="btn btn-primary px-4" type="submit">Lưu kho SV5TOT</button>
        </div>
    </form>
</div>
