<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Chi tiết kế hoạch xưởng Aurora</h3>
        <p class="text-muted mb-0">Thông tin phân công hạng mục bàn phím Aurora tại xưởng.</p>
    </div>
    <a href="?controller=factory_plan&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php if (!$plan): ?>
    <div class="alert alert-warning">Không tìm thấy kế hoạch.</div>
<?php else: ?>
    <div class="card p-4">
        <div class="row g-4">
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Mã kế hoạch xưởng</dt>
                    <dd class="col-sm-7 fw-semibold"><?= htmlspecialchars($plan['IdKeHoachSanXuatXuong']) ?></dd>
                    <dt class="col-sm-5">Mã kế hoạch tổng</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($plan['IdKeHoachSanXuat']) ?></dd>
                    <dt class="col-sm-5">Mã xưởng Aurora</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($plan['IdXuong']) ?></dd>
                </dl>
            </div>
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Hạng mục Aurora</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($plan['TenThanhThanhPhanSP']) ?></dd>
                    <dt class="col-sm-5">Số lượng linh kiện/bàn phím</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($plan['SoLuong']) ?></dd>
                    <dt class="col-sm-5">Trạng thái</dt>
                    <dd class="col-sm-7"><span class="badge bg-info bg-opacity-25 text-primary"><?= htmlspecialchars($plan['TrangThai']) ?></span></dd>
                </dl>
            </div>
        </div>
        <hr>
        <div class="row text-center">
            <div class="col">
                <div class="text-muted">Bắt đầu</div>
                <div class="fw-semibold"><?= $plan['ThoiGianBatDau'] ? date('d/m/Y H:i', strtotime($plan['ThoiGianBatDau'])) : '-' ?></div>
            </div>
            <div class="col">
                <div class="text-muted">Kết thúc</div>
                <div class="fw-semibold"><?= $plan['ThoiGianKetThuc'] ? date('d/m/Y H:i', strtotime($plan['ThoiGianKetThuc'])) : '-' ?></div>
            </div>
        </div>
    </div>
<?php endif; ?>
