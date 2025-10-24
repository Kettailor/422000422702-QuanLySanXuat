<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Thêm xưởng sản xuất</h3>
        <p class="text-muted mb-0">Khai báo thông tin cơ bản, công suất và người phụ trách xưởng.</p>
    </div>
    <a href="?controller=workshop&action=index" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
</div>

<div class="card p-4">
    <form action="?controller=workshop&action=store" method="post" class="row g-4">
        <div class="col-md-4">
            <label class="form-label">Mã xưởng</label>
            <input type="text" name="IdXuong" class="form-control" placeholder="Tự sinh nếu để trống">
        </div>
        <div class="col-md-4">
            <label class="form-label">Tên xưởng</label>
            <input type="text" name="TenXuong" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Ngày thành lập</label>
            <input type="date" name="NgayThanhLap" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Địa điểm</label>
            <input type="text" name="DiaDiem" class="form-control" placeholder="Khu công nghiệp, tỉnh/thành...">
        </div>
        <div class="col-md-6">
            <label class="form-label">Trưởng xưởng (Mã nhân viên)</label>
            <input type="text" name="IdTruongXuong" class="form-control" placeholder="Ví dụ: NV001">
        </div>
        <div class="col-md-4">
            <label class="form-label">Công suất tối đa (giờ máy / tháng)</label>
            <input type="number" name="CongSuatToiDa" class="form-control" min="0" step="0.01">
        </div>
        <div class="col-md-4">
            <label class="form-label">Công suất đang sử dụng</label>
            <input type="number" name="CongSuatDangSuDung" class="form-control" min="0" step="0.01">
        </div>
        <div class="col-md-4">
            <label class="form-label">Số lượng công nhân</label>
            <input type="number" name="SoLuongCongNhan" class="form-control" min="0">
        </div>
        <div class="col-md-4">
            <label class="form-label">Trạng thái</label>
            <select name="TrangThai" class="form-select">
                <option value="Đang hoạt động">Đang hoạt động</option>
                <option value="Bảo trì">Bảo trì</option>
                <option value="Tạm dừng">Tạm dừng</option>
            </select>
        </div>
        <div class="col-12">
            <label class="form-label">Mô tả</label>
            <textarea name="MoTa" class="form-control" rows="4" placeholder="Ghi chú tình trạng thiết bị, hạng mục bảo trì..."></textarea>
        </div>
        <div class="col-12 text-end">
            <button type="submit" class="btn btn-primary px-4">Lưu thông tin xưởng</button>
        </div>
    </form>
</div>
