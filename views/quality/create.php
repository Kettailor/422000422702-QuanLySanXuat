<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Lập biên bản đánh giá</h3>
        <p class="text-muted mb-0">Ghi nhận kết quả kiểm tra chất lượng thành phẩm.</p>
    </div>
    <a href="?controller=quality&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<div class="card p-4">
    <form action="?controller=quality&action=store" method="post" class="row g-4">
        <div class="col-md-4">
            <label class="form-label">Mã biên bản</label>
            <input type="text" name="IdBienBanDanhGiaSP" class="form-control" placeholder="Tự sinh nếu để trống">
        </div>
        <div class="col-md-4">
            <label class="form-label">Mã lô</label>
            <input type="text" name="IdLo" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Kết quả</label>
            <select name="KetQua" class="form-select">
                <option value="Đạt">Đạt</option>
                <option value="Không đạt">Không đạt</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Tổng tiêu chí đạt</label>
            <input type="number" name="TongTCD" class="form-control" min="0">
        </div>
        <div class="col-md-6">
            <label class="form-label">Tổng tiêu chí không đạt</label>
            <input type="number" name="TongTCKD" class="form-control" min="0">
        </div>
        <div class="col-md-6">
            <label class="form-label">Thời gian đánh giá</label>
            <input type="datetime-local" name="ThoiGian" class="form-control" value="<?= date('Y-m-d\\TH:i') ?>">
        </div>
        <div class="col-12 text-end">
            <button class="btn btn-primary px-4" type="submit">Lưu biên bản</button>
        </div>
    </form>
</div>
