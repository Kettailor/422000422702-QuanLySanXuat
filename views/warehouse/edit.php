<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Cập nhật kho SV5TOT</h3>
        <p class="text-muted mb-0">Điều chỉnh thông tin kho SV5TOT và người phụ trách.</p>
    </div>
    <a href="?controller=warehouse&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php
$workshops = $workshops ?? [];
$employees = $employees ?? [];
?>

<?php if (!$warehouse): ?>
    <div class="alert alert-warning">Không tìm thấy kho.</div>
<?php else: ?>
    <div class="card p-4">
        <form action="?controller=warehouse&action=update" method="post" class="row g-4">
            <input type="hidden" name="IdKho" value="<?= htmlspecialchars($warehouse['IdKho']) ?>">
            <div class="col-md-4">
                <label class="form-label">Tên kho</label>
                <input type="text" name="TenKho" class="form-control" value="<?= htmlspecialchars($warehouse['TenKho']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Loại kho SV5TOT</label>
                <input type="text" name="TenLoaiKho" class="form-control" value="<?= htmlspecialchars($warehouse['TenLoaiKho']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Trạng thái</label>
                <select name="TrangThai" class="form-select">
                    <?php foreach (['Đang hoạt động', 'Tạm dừng'] as $status): ?>
                        <option value="<?= $status ?>" <?= $status === $warehouse['TrangThai'] ? 'selected' : '' ?>><?= $status ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Địa chỉ</label>
                <input type="text" name="DiaChi" class="form-control" value="<?= htmlspecialchars($warehouse['DiaChi']) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tổng lô SV5TOT</label>
                <input type="number" name="TongSLLo" class="form-control" value="<?= $warehouse['TongSLLo'] ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tổng SL SV5TOT</label>
                <input type="number" name="TongSL" class="form-control" value="<?= $warehouse['TongSL'] ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Giá trị hàng tồn (VND)</label>
                <input type="number" name="ThanhTien" class="form-control" value="<?= $warehouse['ThanhTien'] ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Xưởng SV5TOT liên kết</label>
                <select name="IdXuong" class="form-select">
                    <option value="">-- Chọn xưởng --</option>
                    <?php foreach ($workshops as $workshop): ?>
                        <option value="<?= htmlspecialchars($workshop['IdXuong']) ?>" <?= $workshop['IdXuong'] === ($warehouse['IdXuong'] ?? '') ? 'selected' : '' ?>>
                            <?= htmlspecialchars($workshop['TenXuong'] ?? $workshop['IdXuong']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Nhân viên quản kho</label>
                <select name="IdQuanKho" class="form-select">
                    <option value="">-- Chọn nhân sự --</option>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?= htmlspecialchars($employee['IdNhanVien']) ?>" <?= $employee['IdNhanVien'] === ($warehouse['`NHAN_VIEN_KHO_IdNhanVien`'] ?? '') ? 'selected' : '' ?>>
                            <?= htmlspecialchars($employee['HoTen']) ?> (<?= htmlspecialchars($employee['IdNhanVien']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 text-end">
                <button class="btn btn-primary px-4" type="submit">Cập nhật kho SV5TOT</button>
            </div>
        </form>
    </div>
<?php endif; ?>
