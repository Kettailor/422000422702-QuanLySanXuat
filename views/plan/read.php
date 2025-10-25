<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Chi tiết kế hoạch Aurora</h3>
        <p class="text-muted mb-0">Thông tin chi tiết kế hoạch sản xuất bàn phím Aurora.</p>
    </div>
    <a href="?controller=plan&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php if (!$plan): ?>
    <div class="alert alert-warning">Không tìm thấy kế hoạch.</div>
<?php else: ?>
    <div class="card p-4">
        <div class="row g-4">
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Mã kế hoạch</dt>
                    <dd class="col-sm-7 fw-semibold"><?= htmlspecialchars($plan['IdKeHoachSanXuat']) ?></dd>
                    <dt class="col-sm-5">Mã chi tiết đơn hàng</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($plan['IdTTCTDonHang']) ?></dd>
                    <dt class="col-sm-5">Số lượng bàn phím</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($plan['SoLuong']) ?></dd>
                </dl>
            </div>
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Trạng thái</dt>
                    <dd class="col-sm-7"><span class="badge bg-info bg-opacity-25 text-primary"><?= htmlspecialchars($plan['TrangThai']) ?></span></dd>
                    <dt class="col-sm-5">Bắt đầu</dt>
                    <dd class="col-sm-7"><?= $plan['ThoiGianBD'] ? date('d/m/Y H:i', strtotime($plan['ThoiGianBD'])) : '-' ?></dd>
                    <dt class="col-sm-5">Kết thúc</dt>
                    <dd class="col-sm-7"><?= $plan['ThoiGianKetThuc'] ? date('d/m/Y H:i', strtotime($plan['ThoiGianKetThuc'])) : '-' ?></dd>
                </dl>
            </div>
        </div>
    </div>
<?php endif; ?>
