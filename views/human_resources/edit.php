<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Cập nhật nhân sự</h3>
        <p class="text-muted mb-0">Điều chỉnh thông tin nhân viên.</p>
    </div>
    <a href="?controller=human_resources&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php if (!$employee): ?>
    <div class="alert alert-warning">Không tìm thấy nhân viên.</div>
<?php else: ?>
    <div class="card p-4">
        <form action="?controller=human_resources&action=update" method="post" class="row g-4">
            <input type="hidden" name="IdNhanVien" value="<?= htmlspecialchars($employee['IdNhanVien']) ?>">
            <div class="col-md-6">
                <label class="form-label">Họ tên</label>
                <input type="text" name="HoTen" class="form-control" value="<?= htmlspecialchars($employee['HoTen']) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Ngày sinh</label>
                <input type="date" name="NgaySinh" class="form-control" value="<?= $employee['NgaySinh'] ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Giới tính</label>
                <select name="GioiTinh" class="form-select">
                    <option value="1" <?= $employee['GioiTinh'] == 1 ? 'selected' : '' ?>>Nam</option>
                    <option value="0" <?= $employee['GioiTinh'] == 0 ? 'selected' : '' ?>>Nữ</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Chức vụ</label>
                <input type="text" name="ChucVu" class="form-control" value="<?= htmlspecialchars($employee['ChucVu']) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Hệ số lương</label>
                <input type="number" name="HeSoLuong" class="form-control" value="<?= $employee['HeSoLuong'] ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Trạng thái</label>
                <select name="TrangThai" class="form-select">
                    <option value="Đang làm việc" <?= $employee['TrangThai'] === 'Đang làm việc' ? 'selected' : '' ?>>Đang làm việc</option>
                    <option value="Tạm nghỉ" <?= $employee['TrangThai'] === 'Tạm nghỉ' ? 'selected' : '' ?>>Tạm nghỉ</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Thời gian làm việc</label>
                <input type="datetime-local" name="ThoiGianLamViec" class="form-control" value="<?= date('Y-m-d\\TH:i', strtotime($employee['ThoiGianLamViec'])) ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Địa chỉ</label>
                <textarea name="DiaChi" rows="3" class="form-control"><?= htmlspecialchars($employee['DiaChi']) ?></textarea>
            </div>
            <div class="col-12 text-end">
                <button class="btn btn-primary px-4" type="submit">Cập nhật</button>
            </div>
        </form>
    </div>
<?php endif; ?>
