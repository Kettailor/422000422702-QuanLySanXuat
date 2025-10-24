<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Tạo bảng lương</h3>
        <p class="text-muted mb-0">Thiết lập thông tin bảng lương cho nhân viên.</p>
    </div>
    <a href="?controller=salary&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<div class="card p-4">
    <form action="?controller=salary&action=store" method="post" class="row g-4">
        <div class="col-md-4">
            <label class="form-label">Mã bảng lương</label>
            <input type="text" name="IdBangLuong" class="form-control" placeholder="Tự sinh nếu để trống">
        </div>
        <div class="col-md-4">
            <label class="form-label">Nhân viên</label>
            <input type="text" name="IdNhanVien" class="form-control" placeholder="Mã nhân viên">
        </div>
        <div class="col-md-4">
            <label class="form-label">Kế toán duyệt</label>
            <input type="text" name="KeToan" class="form-control" placeholder="Mã nhân viên kế toán">
        </div>
        <div class="col-md-4">
            <label class="form-label">Tháng/Năm</label>
            <input type="number" name="ThangNam" class="form-control" placeholder="Ví dụ: 202405">
        </div>
        <div class="col-md-4">
            <label class="form-label">Lương cơ bản</label>
            <input type="number" name="LuongCoBan" class="form-control" min="0">
        </div>
        <div class="col-md-4">
            <label class="form-label">Phụ cấp</label>
            <input type="number" name="PhuCap" class="form-control" min="0">
        </div>
        <div class="col-md-4">
            <label class="form-label">Khấu trừ</label>
            <input type="number" name="KhauTru" class="form-control" min="0">
        </div>
        <div class="col-md-4">
            <label class="form-label">Thuế TNCN</label>
            <input type="number" name="ThueTNCN" class="form-control" min="0">
        </div>
        <div class="col-md-4">
            <label class="form-label">Tổng thu nhập</label>
            <input type="number" name="TongThuNhap" class="form-control" min="0">
        </div>
        <div class="col-md-4">
            <label class="form-label">Trạng thái</label>
            <select name="TrangThai" class="form-select">
                <option value="Chờ duyệt">Chờ duyệt</option>
                <option value="Đã duyệt">Đã duyệt</option>
                <option value="Đã chi">Đã chi</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Ngày lập</label>
            <input type="date" name="NgayLap" class="form-control" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="col-12 text-end">
            <button class="btn btn-primary px-4" type="submit">Lưu bảng lương</button>
        </div>
    </form>
</div>
