<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Tạo kế hoạch sản xuất</h3>
        <p class="text-muted mb-0">Thiết lập kế hoạch mới dựa trên yêu cầu đơn hàng.</p>
    </div>
    <a href="?controller=plan&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<div class="card p-4">
    <form action="?controller=plan&action=store" method="post" class="row g-4">
        <div class="col-md-4">
            <label class="form-label">Mã kế hoạch</label>
            <input type="text" name="IdKeHoachSanXuat" class="form-control" placeholder="Tự sinh nếu để trống">
        </div>
        <div class="col-md-4">
            <label class="form-label">Mã chi tiết đơn hàng</label>
            <input type="text" name="IdTTCTDonHang" class="form-control" placeholder="Nhập mã chi tiết đơn hàng">
        </div>
        <div class="col-md-4">
            <label class="form-label">Ban giám đốc phụ trách</label>
            <input type="text" name="BanGiamDoc" class="form-control" placeholder="Mã nhân viên ban giám đốc">
        </div>
        <div class="col-md-4">
            <label class="form-label">Số lượng</label>
            <input type="number" name="SoLuong" class="form-control" min="0">
        </div>
        <div class="col-md-4">
            <label class="form-label">Thời gian bắt đầu</label>
            <input type="datetime-local" name="ThoiGianBD" class="form-control">
        </div>
        <div class="col-md-4">
            <label class="form-label">Thời gian kết thúc</label>
            <input type="datetime-local" name="ThoiGianKetThuc" class="form-control">
        </div>
        <div class="col-md-4">
            <label class="form-label">Trạng thái</label>
            <select name="TrangThai" class="form-select">
                <option value="Mới tạo">Mới tạo</option>
                <option value="Đang thực hiện">Đang thực hiện</option>
                <option value="Hoàn thành">Hoàn thành</option>
            </select>
        </div>
        <div class="col-12 text-end">
            <button class="btn btn-primary px-4" type="submit">Lưu kế hoạch</button>
        </div>
    </form>
</div>
