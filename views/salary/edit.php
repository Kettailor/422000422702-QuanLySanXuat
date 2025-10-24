<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Cập nhật bảng lương</h3>
        <p class="text-muted mb-0">Chỉnh sửa thông tin lương, tự động tính lại lương thực nhận và điều chỉnh trạng thái chi trả.</p>
    </div>
    <a href="?controller=salary&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php if (!$payroll): ?>
    <div class="alert alert-warning">Không tìm thấy bảng lương.</div>
<?php else: ?>
    <div class="card p-4">
        <form action="?controller=salary&action=update" method="post" class="row g-4" id="payroll-form">
            <input type="hidden" name="IdBangLuong" value="<?= htmlspecialchars($payroll['IdBangLuong']) ?>">
            <div class="col-md-4">
                <label class="form-label">Nhân viên</label>
                <input type="text" name="IdNhanVien" class="form-control" value="<?= htmlspecialchars($payroll[Salary::EMPLOYEE_COLUMN]) ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Kế toán phụ trách</label>
                <input type="text" name="KeToan" class="form-control" value="<?= htmlspecialchars($payroll[Salary::ACCOUNTANT_COLUMN] ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tháng/Năm</label>
                <input type="number" name="ThangNam" class="form-control" value="<?= htmlspecialchars($payroll['ThangNam']) ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Lương cơ bản</label>
                <input type="number" name="LuongCoBan" class="form-control payroll-input" min="0" step="0.01" value="<?= htmlspecialchars($payroll['LuongCoBan']) ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Phụ cấp</label>
                <input type="number" name="PhuCap" class="form-control payroll-input" min="0" step="0.01" value="<?= htmlspecialchars($payroll['PhuCap']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Khấu trừ</label>
                <input type="number" name="KhauTru" class="form-control payroll-input" min="0" step="0.01" value="<?= htmlspecialchars($payroll['KhauTru']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Thuế TNCN</label>
                <input type="number" name="ThueTNCN" class="form-control payroll-input" min="0" step="0.01" value="<?= htmlspecialchars($payroll['ThueTNCN']) ?>">
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
                <input type="date" name="NgayLap" class="form-control" value="<?= htmlspecialchars($payroll['NgayLap']) ?>">
            </div>
            <div class="col-md-8">
                <label class="form-label">Ghi chú / Chữ ký</label>
                <input type="text" name="ChuKy" class="form-control" value="<?= htmlspecialchars($payroll['ChuKy'] ?? '') ?>" placeholder="Thông tin phê duyệt">
            </div>
            <div class="col-12">
                <div class="alert alert-info d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold">Lương thực nhận hiện tại</div>
                        <div class="small text-muted">Hệ thống tự tính = Lương cơ bản + Phụ cấp - Khấu trừ - Thuế TNCN</div>
                    </div>
                    <div class="fs-3 fw-bold text-primary mb-0" id="net-income"><?= number_format($payroll['TongThuNhap'], 0, ',', '.') ?> đ</div>
                </div>
            </div>
            <div class="col-12 text-end">
                <button class="btn btn-primary px-4" type="submit">Cập nhật bảng lương</button>
                <a class="btn btn-outline-info px-4" href="?controller=salary&action=recalculate&id=<?= urlencode($payroll['IdBangLuong']) ?>">Tính lại theo công thức</a>
            </div>
        </form>
    </div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('payroll-form');
    if (!form) return;

    const inputs = form.querySelectorAll('.payroll-input');
    const netIncome = document.getElementById('net-income');
    const formatCurrency = value => Number(value).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });

    const recalc = () => {
        let base = parseFloat(form.querySelector('[name="LuongCoBan"]').value || 0);
        let allowance = parseFloat(form.querySelector('[name="PhuCap"]').value || 0);
        let deduction = parseFloat(form.querySelector('[name="KhauTru"]').value || 0);
        let tax = parseFloat(form.querySelector('[name="ThueTNCN"]').value || 0);
        const net = Math.max(base + allowance - deduction - tax, 0);
        netIncome.textContent = formatCurrency(net);
    };

    inputs.forEach(input => input.addEventListener('input', recalc));
    recalc();
});
</script>
