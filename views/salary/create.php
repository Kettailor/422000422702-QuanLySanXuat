<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Tạo bảng lương</h3>
        <p class="text-muted mb-0">Thiết lập các khoản thu nhập, khấu trừ và hệ thống sẽ tự tính lương thực nhận.</p>
    </div>
    <a href="?controller=salary&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<div class="card p-4">
    <form action="?controller=salary&action=store" method="post" class="row g-4" id="payroll-form">
        <div class="col-md-4">
            <label class="form-label">Mã bảng lương</label>
            <input type="text" name="IdBangLuong" class="form-control" placeholder="Tự sinh nếu để trống">
        </div>
        <div class="col-md-4">
            <label class="form-label">Nhân viên</label>
            <input type="text" name="IdNhanVien" class="form-control" placeholder="Mã nhân viên" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Kế toán phụ trách</label>
            <input type="text" name="KeToan" class="form-control" placeholder="Mã nhân viên kế toán">
        </div>
        <div class="col-md-4">
            <label class="form-label">Tháng/Năm</label>
            <input type="number" name="ThangNam" class="form-control" placeholder="Ví dụ: 202405" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Lương cơ bản</label>
            <input type="number" name="LuongCoBan" class="form-control payroll-input" min="0" step="0.01" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Phụ cấp</label>
            <input type="number" name="PhuCap" class="form-control payroll-input" min="0" step="0.01" value="0">
        </div>
        <div class="col-md-4">
            <label class="form-label">Khấu trừ</label>
            <input type="number" name="KhauTru" class="form-control payroll-input" min="0" step="0.01" value="0">
        </div>
        <div class="col-md-4">
            <label class="form-label">Thuế TNCN</label>
            <input type="number" name="ThueTNCN" class="form-control payroll-input" min="0" step="0.01" value="0">
        </div>
        <div class="col-md-4">
            <label class="form-label">Trạng thái</label>
            <select name="TrangThai" class="form-select">
                <option value="Chờ duyệt" selected>Chờ duyệt</option>
                <option value="Đã duyệt">Đã duyệt</option>
                <option value="Đã chi">Đã chi</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Ngày lập</label>
            <input type="date" name="NgayLap" class="form-control" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="col-md-8">
            <label class="form-label">Ghi chú</label>
            <input type="text" name="ChuKy" class="form-control" placeholder="Ký hiệu phê duyệt hoặc mô tả ngắn">
        </div>
        <div class="col-12">
            <div class="alert alert-info d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-semibold">Lương thực nhận dự kiến</div>
                    <div class="small text-muted">Hệ thống tự tính = Lương cơ bản + Phụ cấp - Khấu trừ - Thuế TNCN</div>
                </div>
                <div class="fs-3 fw-bold text-primary mb-0" id="net-income">0 đ</div>
            </div>
        </div>
        <div class="col-12 text-end">
            <button class="btn btn-primary px-4" type="submit">Lưu bảng lương</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputs = document.querySelectorAll('#payroll-form .payroll-input');
    const netIncome = document.getElementById('net-income');

    const formatCurrency = value => Number(value).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });

    const recalc = () => {
        let base = parseFloat(document.querySelector('[name="LuongCoBan"]').value || 0);
        let allowance = parseFloat(document.querySelector('[name="PhuCap"]').value || 0);
        let deduction = parseFloat(document.querySelector('[name="KhauTru"]').value || 0);
        let tax = parseFloat(document.querySelector('[name="ThueTNCN"]').value || 0);
        const net = Math.max(base + allowance - deduction - tax, 0);
        netIncome.textContent = formatCurrency(net);
    };

    inputs.forEach(input => input.addEventListener('input', recalc));
    recalc();
});
</script>
