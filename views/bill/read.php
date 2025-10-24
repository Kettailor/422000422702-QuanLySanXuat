<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Chi tiết hóa đơn</h3>
        <p class="text-muted mb-0">Thông tin hóa đơn và liên kết với đơn hàng.</p>
    </div>
    <a href="?controller=bill&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php if (!$bill): ?>
    <div class="alert alert-warning">Không tìm thấy hóa đơn.</div>
<?php else: ?>
    <div class="card p-4">
        <dl class="row mb-0">
            <dt class="col-sm-3">Mã hóa đơn</dt>
            <dd class="col-sm-9 fw-semibold"><?= htmlspecialchars($bill['IdHoaDon']) ?></dd>
            <dt class="col-sm-3">Loại hóa đơn</dt>
            <dd class="col-sm-9"><?= htmlspecialchars($bill['LoaiHD']) ?></dd>
            <dt class="col-sm-3">Ngày lập</dt>
            <dd class="col-sm-9"><?= $bill['NgayLap'] ? date('d/m/Y', strtotime($bill['NgayLap'])) : '-' ?></dd>
            <dt class="col-sm-3">Trạng thái</dt>
            <dd class="col-sm-9"><span class="badge bg-info bg-opacity-25 text-primary"><?= htmlspecialchars($bill['TrangThai']) ?></span></dd>
            <dt class="col-sm-3">Đơn hàng</dt>
            <dd class="col-sm-9"><?= htmlspecialchars($bill['IdDonHang']) ?></dd>
        </dl>
    </div>
<?php endif; ?>
