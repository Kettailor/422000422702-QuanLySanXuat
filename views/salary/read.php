<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Chi tiết bảng lương</h3>
        <p class="text-muted mb-0">Thông tin tính lương cho nhân viên.</p>
    </div>
    <a href="?controller=salary&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php if (!$payroll): ?>
    <div class="alert alert-warning">Không tìm thấy bảng lương.</div>
<?php else: ?>
    <div class="card p-4">
        <dl class="row mb-0">
            <dt class="col-sm-4">Mã bảng lương</dt>
            <dd class="col-sm-8 fw-semibold"><?= htmlspecialchars($payroll['IdBangLuong']) ?></dd>
            <dt class="col-sm-4">Mã nhân viên</dt>
            <dd class="col-sm-8"><?= htmlspecialchars($payroll['NHAN_VIENIdNhanVien']) ?></dd>
            <dt class="col-sm-4">Tháng/Năm</dt>
            <dd class="col-sm-8"><?= htmlspecialchars($payroll['ThangNam']) ?></dd>
            <dt class="col-sm-4">Lương cơ bản</dt>
            <dd class="col-sm-8"><?= number_format($payroll['LuongCoBan'], 0, ',', '.') ?> đ</dd>
            <dt class="col-sm-4">Phụ cấp</dt>
            <dd class="col-sm-8"><?= number_format($payroll['PhuCap'], 0, ',', '.') ?> đ</dd>
            <dt class="col-sm-4">Khấu trừ</dt>
            <dd class="col-sm-8"><?= number_format($payroll['KhauTru'], 0, ',', '.') ?> đ</dd>
            <dt class="col-sm-4">Thuế TNCN</dt>
            <dd class="col-sm-8"><?= number_format($payroll['ThueTNCN'], 0, ',', '.') ?> đ</dd>
            <dt class="col-sm-4">Tổng thu nhập</dt>
            <dd class="col-sm-8 fw-semibold text-primary"><?= number_format($payroll['TongThuNhap'], 0, ',', '.') ?> đ</dd>
            <dt class="col-sm-4">Trạng thái</dt>
            <dd class="col-sm-8"><span class="badge bg-info bg-opacity-25 text-primary"><?= htmlspecialchars($payroll['TrangThai']) ?></span></dd>
            <dt class="col-sm-4">Ngày lập</dt>
            <dd class="col-sm-8"><?= $payroll['NgayLap'] ? date('d/m/Y', strtotime($payroll['NgayLap'])) : '-' ?></dd>
        </dl>
    </div>
<?php endif; ?>
