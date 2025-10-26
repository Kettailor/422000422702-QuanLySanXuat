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

<div class="card p-4">
    <form action="?controller=warehouse&action=store" method="post" class="row g-4">
        <input type="hidden" name="IdKho" value="">
        <div class="col-md-4">
            <div class="form-floating-label">Mã kho</div>
            <div class="form-hint">Mã kho sẽ được SV5TOT tự sinh khi lưu.</div>
        </div>
        <div class="col-md-4">
            <label class="form-label">Tên kho</label>
            <input type="text" name="TenKho" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Loại kho SV5TOT</label>
            <input type="text" name="TenLoaiKho" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Địa chỉ</label>
            <input type="text" name="DiaChi" class="form-control">
        </div>
        <div class="col-md-3">
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
        </div>
        <div class="col-md-4">
            <label class="form-label">Trạng thái</label>
            <select name="TrangThai" class="form-select">
                <option value="Đang hoạt động">Đang hoạt động</option>
                <option value="Tạm dừng">Tạm dừng</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Xưởng SV5TOT liên kết</label>
            <select name="IdXuong" class="form-select">
                <option value="">-- Chọn xưởng --</option>
                <?php foreach ($workshops as $workshop): ?>
                    <option value="<?= htmlspecialchars($workshop['IdXuong']) ?>">
                        <?= htmlspecialchars($workshop['TenXuong'] ?? $workshop['IdXuong']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Nhân viên quản kho</label>
            <select name="IdQuanKho" class="form-select">
                <option value="">-- Chọn nhân sự --</option>
                <?php foreach ($employees as $employee): ?>
                    <option value="<?= htmlspecialchars($employee['IdNhanVien']) ?>">
                        <?= htmlspecialchars($employee['HoTen']) ?> (<?= htmlspecialchars($employee['IdNhanVien']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-12 text-end">
            <button class="btn btn-primary px-4" type="submit">Lưu kho SV5TOT</button>
        </div>
    </form>
</div>
