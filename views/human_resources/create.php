<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Thêm nhân sự</h3>
        <p class="text-muted mb-0">Ghi nhận thông tin nhân viên mới.</p>
    </div>
    <a href="?controller=human_resources&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<div class="card p-4">
    <form action="?controller=human_resources&action=store" method="post" class="row g-4">
        <div class="col-md-6">
            <label class="form-label">Mã nhân viên</label>
            <input type="text" name="IdNhanVien" class="form-control" placeholder="Tự sinh nếu để trống">
        </div>
        <div class="col-md-6">
            <label class="form-label">Họ tên</label>
            <input type="text" name="HoTen" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Ngày sinh</label>
            <input type="date" name="NgaySinh" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Giới tính</label>
            <select name="GioiTinh" class="form-select">
                <option value="1">Nam</option>
                <option value="0">Nữ</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Chức vụ</label>
            <input type="text" name="ChucVu" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Hệ số lương</label>
            <input type="number" name="HeSoLuong" class="form-control" min="1" step="1" value="1">
        </div>
        <div class="col-md-6">
            <label class="form-label">Trạng thái</label>
            <select name="TrangThai" class="form-select">
                <option value="Đang làm việc">Đang làm việc</option>
                <option value="Tạm nghỉ">Tạm nghỉ</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Thời gian làm việc</label>
            <input type="datetime-local" name="ThoiGianLamViec" class="form-control" value="<?= date('Y-m-d\\TH:i') ?>">
        </div>
        <div class="col-12">
            <label class="form-label">Địa chỉ</label>
            <textarea name="DiaChi" rows="3" class="form-control"></textarea>
        </div>
        <div class="col-12 text-end">
            <button class="btn btn-primary px-4" type="submit">Lưu nhân sự</button>
        </div>
    </form>
</div>
