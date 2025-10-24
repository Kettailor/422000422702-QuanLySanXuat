<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Chi tiết nhân sự</h3>
        <p class="text-muted mb-0">Thông tin chi tiết của nhân viên trong hệ thống.</p>
    </div>
    <a href="?controller=human_resources&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php if (!$employee): ?>
    <div class="alert alert-warning">Không tìm thấy nhân sự.</div>
<?php else: ?>
    <div class="card p-4">
        <div class="row g-4">
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Mã nhân viên</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($employee['IdNhanVien']) ?></dd>
                    <dt class="col-sm-5">Họ tên</dt>
                    <dd class="col-sm-7 fw-semibold"><?= htmlspecialchars($employee['HoTen']) ?></dd>
                    <dt class="col-sm-5">Ngày sinh</dt>
                    <dd class="col-sm-7"><?= date('d/m/Y', strtotime($employee['NgaySinh'])) ?></dd>
                    <dt class="col-sm-5">Giới tính</dt>
                    <dd class="col-sm-7"><?= $employee['GioiTinh'] ? 'Nam' : 'Nữ' ?></dd>
                    <dt class="col-sm-5">Chức vụ</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($employee['ChucVu']) ?></dd>
                </dl>
            </div>
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Trạng thái</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($employee['TrangThai']) ?></dd>
                    <dt class="col-sm-5">Hệ số lương</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($employee['HeSoLuong']) ?></dd>
                    <dt class="col-sm-5">Địa chỉ</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($employee['DiaChi']) ?></dd>
                    <dt class="col-sm-5">Thời gian làm việc</dt>
                    <dd class="col-sm-7"><?= date('d/m/Y H:i', strtotime($employee['ThoiGianLamViec'])) ?></dd>
                </dl>
            </div>
        </div>
    </div>
<?php endif; ?>
