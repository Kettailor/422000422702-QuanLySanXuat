<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Chi tiết xưởng sản xuất</h3>
        <p class="text-muted mb-0">Tổng hợp thông tin nhân sự, công suất và trạng thái vận hành.</p>
    </div>
    <a href="?controller=workshop&action=index" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
</div>

<?php if (!$workshop): ?>
    <div class="alert alert-warning">Không tìm thấy thông tin xưởng.</div>
<?php else: ?>
    <?php
    $assignments = $assignments ?? [];
    $managerNames = array_column($assignments['truong_xuong'] ?? [], 'HoTen');
    $warehouseNames = array_column($assignments['nhan_vien_kho'] ?? [], 'HoTen');
    $productionNames = array_column($assignments['nhan_vien_san_xuat'] ?? [], 'HoTen');
    ?>
    <div class="card p-4">
        <div class="row g-4">
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Mã xưởng</dt>
                    <dd class="col-sm-8 fw-semibold"><?= htmlspecialchars($workshop['IdXuong']) ?></dd>
                    <dt class="col-sm-4">Tên xưởng</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($workshop['TenXuong']) ?></dd>
                    <dt class="col-sm-4">Địa điểm</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($workshop['DiaDiem'] ?? '-') ?></dd>
                    <dt class="col-sm-4">Trạng thái</dt>
                    <dd class="col-sm-8"><span class="badge bg-light text-dark"><?= htmlspecialchars($workshop['TrangThai'] ?? 'Không xác định') ?></span></dd>
                    <dt class="col-sm-4">Trưởng xưởng</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars(implode(', ', $managerNames) ?: '-') ?></dd>
                    <dt class="col-sm-4">Ngày thành lập</dt>
                    <dd class="col-sm-8"><?= !empty($workshop['NgayThanhLap']) ? date('d/m/Y', strtotime($workshop['NgayThanhLap'])) : '-' ?></dd>
                </dl>
            </div>
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-6">Công suất tối đa</dt>
                    <dd class="col-sm-6 fw-semibold text-primary"><?= number_format($workshop['CongSuatToiDa'] ?? 0, 0, ',', '.') ?></dd>
                    <dt class="col-sm-6">Công suất đang dùng</dt>
                    <dd class="col-sm-6"><?= number_format($workshop['CongSuatDangSuDung'] ?? $workshop['CongSuatHienTai'] ?? 0, 0, ',', '.') ?></dd>
                    <dt class="col-sm-6">Nhân sự tối đa</dt>
                    <dd class="col-sm-6"><?= number_format($workshop['SlNhanVien'] ?? 0) ?></dd>
                    <dt class="col-sm-6">Nhân sự hiện tại</dt>
                    <dd class="col-sm-6"><?= number_format($workshop['SoLuongCongNhan'] ?? 0) ?></dd>
                    <dt class="col-sm-6">Tỷ lệ sử dụng</dt>
                    <?php
                    $usage = 0;
                    if (!empty($workshop['CongSuatToiDa'])) {
                        $usage = round((($workshop['CongSuatDangSuDung'] ?? $workshop['CongSuatHienTai'] ?? 0) / $workshop['CongSuatToiDa']) * 100, 2);
                    }
                    ?>
                    <dd class="col-sm-6"><?= $usage ?>%</dd>
                </dl>
            </div>
        </div>
        <?php if (!empty($workshop['MoTa'])): ?>
            <div class="mt-4">
                <h6 class="fw-semibold">Ghi chú</h6>
                <p class="mb-0"><?= nl2br(htmlspecialchars($workshop['MoTa'])) ?></p>
            </div>
        <?php endif; ?>
        <div class="mt-4 row g-3">
            <div class="col-md-6">
                <h6 class="fw-semibold">Nhân viên kho</h6>
                <p class="mb-0">
                    <?= $warehouseNames ? htmlspecialchars(implode(', ', $warehouseNames)) : 'Chưa phân công' ?>
                </p>
            </div>
            <div class="col-md-6">
                <h6 class="fw-semibold">Nhân viên sản xuất</h6>
                <p class="mb-0">
                    <?= $productionNames ? htmlspecialchars(implode(', ', $productionNames)) : 'Chưa phân công' ?>
                </p>
            </div>
        </div>
    </div>
<?php endif; ?>
