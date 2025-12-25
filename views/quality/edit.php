<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Cập nhật biên bản</h3>
        <p class="text-muted mb-0">Chỉnh sửa thông tin đánh giá chất lượng.</p>
    </div>
    <a href="?controller=quality&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php if (!$report): ?>
    <div class="alert alert-warning">Không tìm thấy biên bản.</div>
<?php else: ?>
    <div class="card p-4">
        <form action="?controller=quality&action=update" method="post" class="row g-4">
            <input type="hidden" name="IdBienBanDanhGiaSP" value="<?= htmlspecialchars($report['IdBienBanDanhGiaSP']) ?>">
            <div class="col-md-4">
                <label class="form-label">Mã lô</label>
                <input type="text" name="IdLo" class="form-control" value="<?= htmlspecialchars($report['IdLo']) ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Kết quả</label>
                <select name="KetQua" class="form-select" required>
                    <?php foreach (['Đạt', 'Không đạt'] as $status): ?>
                        <option value="<?= $status ?>" <?= $status === $report['KetQua'] ? 'selected' : '' ?>><?= $status ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Thời gian đánh giá</label>
                <input type="datetime-local" name="ThoiGian" class="form-control" value="<?= $report['ThoiGian'] ? date('Y-m-d\\TH:i', strtotime($report['ThoiGian'])) : '' ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tổng tiêu chí đạt</label>
                <input type="number" name="TongTCD" class="form-control" value="<?= $report['TongTCD'] ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tổng tiêu chí không đạt</label>
                <input type="number" name="TongTCKD" class="form-control" value="<?= $report['TongTCKD'] ?>" required>
            </div>
            <div class="col-12 text-end">
                <button class="btn btn-primary px-4" type="submit">Cập nhật biên bản</button>
            </div>
        </form>
    </div>
<?php endif; ?>
