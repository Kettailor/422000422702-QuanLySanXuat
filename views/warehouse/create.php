<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Thêm kho Aurora mới</h3>
        <p class="text-muted mb-0">Khai báo kho Aurora và thông tin người phụ trách.</p>
    </div>
    <a href="?controller=warehouse&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<div class="card p-4">
    <form action="?controller=warehouse&action=store" method="post" class="row g-4">
        <div class="col-md-4">
            <label class="form-label">Mã kho</label>
            <input type="text" name="IdKho" class="form-control" placeholder="Tự sinh nếu để trống">
        </div>
        <div class="col-md-4">
            <label class="form-label">Tên kho</label>
            <input type="text" name="TenKho" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Loại kho Aurora</label>
            <input type="text" name="TenLoaiKho" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Địa chỉ</label>
            <input type="text" name="DiaChi" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">Tổng số lô Aurora</label>
            <input type="number" name="TongSLLo" class="form-control" min="0">
        </div>
        <div class="col-md-3">
            <label class="form-label">Tổng số lượng Aurora</label>
            <input type="number" name="TongSL" class="form-control" min="0">
        </div>
        <div class="col-md-4">
            <label class="form-label">Giá trị hàng tồn (VND)</label>
            <input type="number" name="ThanhTien" class="form-control" min="0">
        </div>
        <div class="col-md-4">
            <label class="form-label">Trạng thái</label>
            <select name="TrangThai" class="form-select">
                <option value="Đang hoạt động">Đang hoạt động</option>
                <option value="Tạm dừng">Tạm dừng</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Xưởng Aurora liên kết</label>
            <input type="text" name="IdXuong" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Mã nhân viên quản kho Aurora</label>
            <input type="text" name="IdQuanKho" class="form-control">
        </div>
        <div class="col-12 text-end">
            <button class="btn btn-primary px-4" type="submit">Lưu kho Aurora</button>
        </div>
    </form>
</div>
