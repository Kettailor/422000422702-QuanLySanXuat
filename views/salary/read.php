<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Chi tiết bảng lương</h3>
        <p class="text-muted mb-0">Thông tin chi tiết về thu nhập, khấu trừ và trạng thái phê duyệt của bảng lương.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="?controller=salary&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
        <?php if ($payroll): ?>
            <a href="?controller=salary&action=recalculate&id=<?= urlencode($payroll['IdBangLuong']) ?>" class="btn btn-outline-info">Tính lại</a>
        <?php endif; ?>
    </div>
</div>

<?php if (!$payroll): ?>
    <div class="alert alert-warning">Không tìm thấy bảng lương.</div>
<?php else: ?>
    <div class="card p-4">
        <div class="row g-4">
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Mã bảng lương</dt>
                    <dd class="col-sm-8 fw-semibold"><?= htmlspecialchars($payroll['IdBangLuong']) ?></dd>
                    <dt class="col-sm-4">Mã nhân viên</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($payroll[Salary::EMPLOYEE_COLUMN]) ?></dd>
                    <dt class="col-sm-4">Tháng/Năm</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($payroll['ThangNam']) ?></dd>
                    <dt class="col-sm-4">Trạng thái</dt>
                    <dd class="col-sm-8"><span class="badge bg-light text-dark"><?= htmlspecialchars($payroll['TrangThai']) ?></span></dd>
                    <dt class="col-sm-4">Ngày lập</dt>
                    <dd class="col-sm-8"><?= $payroll['NgayLap'] ? date('d/m/Y', strtotime($payroll['NgayLap'])) : '-' ?></dd>
                    <dt class="col-sm-4">Kế toán phụ trách</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($payroll[Salary::ACCOUNTANT_COLUMN] ?? '-') ?></dd>
                </dl>
            </div>
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-6">Lương cơ bản</dt>
                    <dd class="col-sm-6"><?= number_format($payroll['LuongCoBan'], 0, ',', '.') ?> đ</dd>
                    <dt class="col-sm-6">Phụ cấp</dt>
                    <dd class="col-sm-6"><?= number_format($payroll['PhuCap'], 0, ',', '.') ?> đ</dd>
                    <dt class="col-sm-6">Khấu trừ</dt>
                    <dd class="col-sm-6"><?= number_format($payroll['KhauTru'], 0, ',', '.') ?> đ</dd>
                    <dt class="col-sm-6">Thuế TNCN</dt>
                    <dd class="col-sm-6"><?= number_format($payroll['ThueTNCN'], 0, ',', '.') ?> đ</dd>
                    <dt class="col-sm-6">Thu nhập gộp</dt>
                    <dd class="col-sm-6 text-success fw-semibold"><?= number_format(($figures['gross'] ?? 0), 0, ',', '.') ?> đ</dd>
                    <dt class="col-sm-6">Thực nhận</dt>
                    <dd class="col-sm-6 text-primary fw-bold"><?= number_format($payroll['TongThuNhap'], 0, ',', '.') ?> đ</dd>
                </dl>
            </div>
        </div>
        <?php if (!empty($payroll['ChuKy'])): ?>
            <div class="mt-4">
                <h6 class="fw-semibold">Ghi chú / Chữ ký</h6>
                <p class="mb-0"><?= nl2br(htmlspecialchars($payroll['ChuKy'])) ?></p>
            </div>
        <?php endif; ?>
        <div class="mt-4 d-flex gap-2">
            <a class="btn btn-outline-primary" href="?controller=salary&action=edit&id=<?= urlencode($payroll['IdBangLuong']) ?>">Chỉnh sửa</a>
            <?php if ($payroll['TrangThai'] === 'Chờ duyệt'): ?>
                <a class="btn btn-success" href="?controller=salary&action=approve&id=<?= urlencode($payroll['IdBangLuong']) ?>">Phê duyệt</a>
            <?php elseif ($payroll['TrangThai'] === 'Đã duyệt'): ?>
                <a class="btn btn-success" href="?controller=salary&action=finalize&id=<?= urlencode($payroll['IdBangLuong']) ?>">Đánh dấu đã chi</a>
            <?php endif; ?>
            <?php if ($payroll['TrangThai'] !== 'Chờ duyệt'): ?>
                <a class="btn btn-outline-warning" href="?controller=salary&action=revert&id=<?= urlencode($payroll['IdBangLuong']) ?>">Hoàn trạng thái</a>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
