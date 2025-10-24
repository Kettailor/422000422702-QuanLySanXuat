<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Thêm kế hoạch xưởng</h3>
        <p class="text-muted mb-0">Phân rã kế hoạch sản xuất cho từng xưởng.</p>
    </div>
    <a href="?controller=factory_plan&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<div class="card p-4">
    <form action="?controller=factory_plan&action=store" method="post" class="row g-4">
        <div class="col-md-4">
            <label class="form-label">Mã kế hoạch xưởng</label>
            <input type="text" name="IdKeHoachSanXuatXuong" class="form-control" placeholder="Tự sinh nếu để trống">
        </div>
        <div class="col-md-4">
            <label class="form-label">Mã kế hoạch tổng</label>
            <input type="text" name="IdKeHoachSanXuat" class="form-control" placeholder="Nhập mã kế hoạch tổng">
        </div>
        <div class="col-md-4">
            <label class="form-label">Mã xưởng</label>
            <input type="text" name="IdXuong" class="form-control" placeholder="Nhập mã xưởng">
        </div>
        <div class="col-md-6">
            <label class="form-label">Tên thành phần sản phẩm</label>
            <input type="text" name="TenThanhThanhPhanSP" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Số lượng</label>
            <input type="number" name="SoLuong" class="form-control" min="0">
        </div>
        <div class="col-md-6">
            <label class="form-label">Thời gian bắt đầu</label>
            <input type="datetime-local" name="ThoiGianBatDau" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Thời gian kết thúc</label>
            <input type="datetime-local" name="ThoiGianKetThuc" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Trạng thái</label>
            <select name="TrangThai" class="form-select">
                <option value="Đang chuẩn bị">Đang chuẩn bị</option>
                <option value="Đang sản xuất">Đang sản xuất</option>
                <option value="Hoàn thành">Hoàn thành</option>
            </select>
        </div>
        <div class="col-12 text-end">
            <button class="btn btn-primary px-4" type="submit">Lưu kế hoạch</button>
        </div>
    </form>
</div>
