<?php
$payroll = $payroll ?? [];
$figures = $figures ?? ['gross' => 0, 'net' => 0];
$monthLabel = $payroll['ThangNam'] ?? '-';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Chi tiết bảng lương</h3>
        <p class="text-muted mb-0">Thông tin chi tiết kỳ lương của bạn.</p>
    </div>
    <a href="?controller=self_salary&action=index" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card p-4 h-100">
            <h5 class="mb-3">Tóm tắt thu nhập</h5>
            <ul class="list-unstyled mb-0">
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span>Tháng</span>
                    <span class="fw-semibold"><?= htmlspecialchars($monthLabel) ?></span>
                </li>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span>Tổng thu nhập</span>
                    <span class="fw-semibold"><?= number_format($payroll['TongThuNhap'] ?? 0, 0, ',', '.') ?> đ</span>
                </li>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span>Thu nhập gộp</span>
                    <span class="fw-semibold"><?= number_format($figures['gross'] ?? 0, 0, ',', '.') ?> đ</span>
                </li>
                <li class="d-flex justify-content-between py-2">
                    <span>Thực nhận</span>
                    <span class="fw-semibold text-success"><?= number_format($figures['net'] ?? 0, 0, ',', '.') ?> đ</span>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card p-4 h-100">
            <h5 class="mb-3">Chi tiết</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="text-muted small">Lương cơ bản</div>
                    <div class="fw-semibold"><?= number_format($payroll['LuongCoBan'] ?? 0, 0, ',', '.') ?> đ</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Phụ cấp</div>
                    <div class="fw-semibold"><?= number_format($payroll['PhuCap'] ?? 0, 0, ',', '.') ?> đ</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Tổng lương ngày công</div>
                    <div class="fw-semibold"><?= number_format($payroll['TongLuongNgayCong'] ?? 0, 0, ',', '.') ?> đ</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Số ngày công</div>
                    <div class="fw-semibold"><?= htmlspecialchars($payroll['SoNgayCong'] ?? '-') ?></div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Thưởng</div>
                    <div class="fw-semibold"><?= number_format($payroll['Thuong'] ?? 0, 0, ',', '.') ?> đ</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Khấu trừ</div>
                    <div class="fw-semibold"><?= number_format($payroll['KhauTru'] ?? 0, 0, ',', '.') ?> đ</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Thuế TNCN</div>
                    <div class="fw-semibold"><?= number_format($payroll['ThueTNCN'] ?? 0, 0, ',', '.') ?> đ</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Trạng thái</div>
                    <div class="fw-semibold"><?= htmlspecialchars($payroll['TrangThai'] ?? 'Đang cập nhật') ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
