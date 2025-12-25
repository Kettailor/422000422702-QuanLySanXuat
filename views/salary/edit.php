<?php
$employees = $employees ?? [];
$accountants = $accountants ?? [];
$payroll = $payroll ?? null;
$monthValue = '';

if ($payroll) {
    $rawMonth = trim((string) ($payroll['ThangNam'] ?? ''));
    if ($rawMonth !== '') {
        if (preg_match('/^(\d{4})(\d{2})$/', $rawMonth, $matches)) {
            $monthValue = sprintf('%s-%s', $matches[1], $matches[2]);
        } elseif (preg_match('/^(\d{4})-(\d{2})$/', $rawMonth)) {
            $monthValue = $rawMonth;
        } elseif (preg_match('/^(\d{2})\/(\d{4})$/', $rawMonth, $matches)) {
            $monthValue = sprintf('%s-%s', $matches[2], $matches[1]);
        } else {
            $monthValue = $rawMonth;
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Cập nhật bảng lương</h3>
        <p class="text-muted mb-0">Điều chỉnh thông tin lương, phụ cấp, thưởng và các khoản khấu trừ trước khi phê duyệt.</p>
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
                <select name="IdNhanVien" class="form-select" required>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?= htmlspecialchars($employee['IdNhanVien']) ?>" <?= $employee['IdNhanVien'] === $payroll[Salary::EMPLOYEE_COLUMN] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($employee['HoTen']) ?> (<?= htmlspecialchars($employee['IdNhanVien']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Kế toán phụ trách</label>
                <select name="KeToan" class="form-select">
                    <option value="">-- Chọn kế toán --</option>
                    <?php foreach ($accountants as $accountant): ?>
                        <option value="<?= htmlspecialchars($accountant['IdNhanVien']) ?>" <?= ($accountant['IdNhanVien'] === ($payroll[Salary::ACCOUNTANT_COLUMN] ?? '')) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($accountant['HoTen']) ?> (<?= htmlspecialchars($accountant['IdNhanVien']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tháng/Năm</label>
                <input type="month" name="ThangNam" class="form-control" value="<?= htmlspecialchars($monthValue ?: date('Y-m')) ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Lương cơ bản</label>
                <input type="number" name="LuongCoBan" class="form-control" min="0" step="0.01" value="<?= htmlspecialchars($payroll['LuongCoBan'] ?? 0) ?>" readonly>
                <div class="form-text text-muted">Lương cơ bản được tính theo chức vụ.</div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Đơn giá ngày công</label>
                <input type="number" name="DonGiaNgayCong" class="form-control" min="0" step="0.01" value="<?= htmlspecialchars($payroll['DonGiaNgayCong'] ?? 0) ?>" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label">Số ngày công</label>
                <input type="number" name="SoNgayCong" class="form-control" min="0" step="0.01" value="<?= htmlspecialchars($payroll['SoNgayCong'] ?? 0) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tổng lương theo ngày công</label>
                <input type="number" class="form-control" value="<?= htmlspecialchars($payroll['TongLuongNgayCong'] ?? 0) ?>" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label">Phụ cấp</label>
                <input type="number" name="PhuCap" class="form-control" min="0" step="0.01" value="<?= htmlspecialchars($payroll['PhuCap'] ?? 0) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Thưởng</label>
                <input type="number" name="Thuong" class="form-control" min="0" step="0.01" value="<?= htmlspecialchars($payroll['Thuong'] ?? 0) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Khấu trừ (BHXH)</label>
                <input type="number" name="KhauTru" class="form-control" min="0" step="0.01" value="<?= htmlspecialchars($payroll['KhauTru'] ?? 0) ?>" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tổng bảo hiểm</label>
                <input type="number" name="TongBaoHiem" class="form-control" min="0" step="0.01" value="<?= htmlspecialchars($payroll['TongBaoHiem'] ?? ($payroll['KhauTru'] ?? 0)) ?>" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label">Thuế TNCN</label>
                <input type="number" name="ThueTNCN" class="form-control" min="0" step="0.01" value="<?= htmlspecialchars($payroll['ThueTNCN'] ?? 0) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Trạng thái</label>
                <select name="TrangThai" class="form-select">
                    <?php foreach (['Chờ duyệt', 'Đã duyệt', 'Đã chi'] as $status): ?>
                        <option value="<?= $status ?>" <?= $status === $payroll['TrangThai'] ? 'selected' : '' ?>><?= $status ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Ngày lập</label>
                <input type="date" name="NgayLap" class="form-control" value="<?= htmlspecialchars($payroll['NgayLap']) ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Ghi chú / Chữ ký</label>
                <input type="text" name="ChuKy" class="form-control" value="<?= htmlspecialchars($payroll['ChuKy'] ?? '') ?>" placeholder="Thông tin phê duyệt">
            </div>
            <div class="col-12">
                <div class="alert alert-info mb-0">
                    <div class="fw-semibold">Công thức tham khảo</div>
                    <div class="small">Bảo hiểm = (Lương cơ bản + Lương ngày công) × 10.5%. Thực nhận = Lương cơ bản + Lương ngày công + Phụ cấp + Thưởng - Bảo hiểm - Thuế TNCN.</div>
                </div>
            </div>
            <div class="col-12 text-end">
                <button class="btn btn-primary px-4" type="submit">Cập nhật bảng lương</button>
                <a class="btn btn-outline-info px-4" href="?controller=salary&action=recalculate&id=<?= urlencode($payroll['IdBangLuong']) ?>">Tính lại theo công thức</a>
            </div>
        </form>
    </div>
<?php endif; ?>
