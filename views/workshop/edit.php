<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Cập nhật thông tin xưởng</h3>
        <p class="text-muted mb-0">Điều chỉnh lại thông tin, công suất vận hành và trạng thái hoạt động.</p>
    </div>
    <a href="?controller=workshop&action=index" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
</div>

<?php if (!$workshop): ?>
    <div class="alert alert-warning">Không tìm thấy thông tin xưởng.</div>
<?php else: ?>
    <div class="card p-4">
        <form action="?controller=workshop&action=update" method="post" class="row g-4">
            <input type="hidden" name="IdXuong" value="<?= htmlspecialchars($workshop['IdXuong']) ?>">
            <div class="col-md-4">
                <label class="form-label">Tên xưởng</label>
                <input type="text" name="TenXuong" class="form-control" value="<?= htmlspecialchars($workshop['TenXuong']) ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Ngày thành lập</label>
                <input type="date" name="NgayThanhLap" class="form-control" value="<?= htmlspecialchars($workshop['NgayThanhLap'] ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Trưởng xưởng (Mã nhân viên)</label>
                <input type="text" name="IdTruongXuong" class="form-control" value="<?= htmlspecialchars($workshop['IdTruongXuong'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Địa điểm</label>
                <input type="text" name="DiaDiem" class="form-control" value="<?= htmlspecialchars($workshop['DiaDiem'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Công suất tối đa</label>
                <input type="number" name="CongSuatToiDa" class="form-control" min="0" step="0.01" value="<?= htmlspecialchars($workshop['CongSuatToiDa'] ?? 0) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Công suất đang sử dụng</label>
                <input type="number" name="CongSuatDangSuDung" class="form-control" min="0" step="0.01" value="<?= htmlspecialchars($workshop['CongSuatDangSuDung'] ?? $workshop['CongSuatHienTai'] ?? 0) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Số lượng công nhân</label>
                <input type="number" name="SoLuongCongNhan" class="form-control" min="0" value="<?= htmlspecialchars($workshop['SoLuongCongNhan'] ?? 0) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Trạng thái</label>
                <select name="TrangThai" class="form-select">
                    <?php foreach (['Đang hoạt động', 'Bảo trì', 'Tạm dừng'] as $status): ?>
                        <option value="<?= $status ?>" <?= ($workshop['TrangThai'] ?? '') === $status ? 'selected' : '' ?>><?= $status ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Mô tả</label>
                <textarea name="MoTa" class="form-control" rows="4"><?= htmlspecialchars($workshop['MoTa'] ?? '') ?></textarea>
            </div>
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary px-4">Cập nhật</button>
            </div>
        </form>
    </div>
<?php endif; ?>
