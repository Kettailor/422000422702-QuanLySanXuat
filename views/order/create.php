<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Tạo đơn hàng mới</h3>
        <p class="text-muted mb-0">Nhập thông tin chi tiết cho đơn hàng của khách hàng.</p>
    </div>
    <a href="?controller=order&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<div class="card p-4">
    <form action="?controller=order&action=store" method="post" class="row g-4">
        <div class="col-md-6">
            <label class="form-label">Mã đơn hàng</label>
            <input type="text" name="IdDonHang" class="form-control" placeholder="Tự sinh nếu để trống">
        </div>
        <div class="col-md-6">
            <label class="form-label">Khách hàng</label>
            <select name="IdKhachHang" class="form-select" required>
                <option value="">-- Chọn khách hàng --</option>
                <?php foreach ($customers as $customer): ?>
                    <option value="<?= htmlspecialchars($customer['IdKhachHang']) ?>"><?= htmlspecialchars($customer['HoTen']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Ngày lập</label>
            <input type="date" name="NgayLap" class="form-control" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Trạng thái</label>
            <select name="TrangThai" class="form-select">
                <option value="Mới tạo">Mới tạo</option>
                <option value="Đang xử lý">Đang xử lý</option>
                <option value="Hoàn thành">Hoàn thành</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Tổng tiền</label>
            <input type="number" name="TongTien" class="form-control" placeholder="0">
        </div>
        <div class="col-12">
            <label class="form-label">Yêu cầu chi tiết</label>
            <textarea name="YeuCau" rows="4" class="form-control" placeholder="Mô tả yêu cầu sản xuất của khách hàng..."></textarea>
        </div>
        <div class="col-12 text-end">
            <button class="btn btn-primary px-4" type="submit">Lưu đơn hàng</button>
        </div>
    </form>
</div>
