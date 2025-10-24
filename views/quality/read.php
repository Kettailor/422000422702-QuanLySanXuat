<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Chi tiết biên bản chất lượng</h3>
        <p class="text-muted mb-0">Xem thông tin đánh giá và kết quả kiểm tra.</p>
    </div>
    <a href="?controller=quality&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php if (!$report): ?>
    <div class="alert alert-warning">Không tìm thấy biên bản.</div>
<?php else: ?>
    <div class="card p-4">
        <div class="row g-4">
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Mã biên bản</dt>
                    <dd class="col-sm-7 fw-semibold"><?= htmlspecialchars($report['IdBienBanDanhGiaSP']) ?></dd>
                    <dt class="col-sm-5">Mã lô</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($report['IdLo']) ?></dd>
                    <dt class="col-sm-5">Kết quả</dt>
                    <dd class="col-sm-7"><span class="badge <?= $report['KetQua'] === 'Đạt' ? 'badge-soft-success' : 'badge-soft-danger' ?>"><?= htmlspecialchars($report['KetQua']) ?></span></dd>
                </dl>
            </div>
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Thời gian</dt>
                    <dd class="col-sm-7"><?= $report['ThoiGian'] ? date('d/m/Y H:i', strtotime($report['ThoiGian'])) : '-' ?></dd>
                    <dt class="col-sm-5">Tiêu chí đạt</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($report['TongTCD']) ?></dd>
                    <dt class="col-sm-5">Tiêu chí không đạt</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($report['TongTCKD']) ?></dd>
                </dl>
            </div>
        </div>
    </div>
<?php endif; ?>
