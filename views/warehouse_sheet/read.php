<?php
$document = $document ?? null;
$details = $details ?? [];
$warehouse = $warehouse ?? null;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Chi tiết phiếu kho</h3>
        <p class="text-muted mb-0">Theo dõi đầy đủ thông tin chứng từ và các lô phát sinh.</p>
    </div>
    <a href="?controller=warehouse_sheet&action=index" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
    </a>
</div>

<?php if (!$document): ?>
    <div class="alert alert-warning mb-0">Không tìm thấy phiếu kho.</div>
<?php else: ?>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Mã phiếu</dt>
                        <dd class="col-sm-7 fw-semibold"><?= htmlspecialchars($document['IdPhieu']) ?></dd>
                        <dt class="col-sm-5">Loại phiếu</dt>
                        <dd class="col-sm-7"><span class="badge badge-soft-primary"><?= htmlspecialchars($document['LoaiPhieu']) ?></span></dd>
                        <dt class="col-sm-5">Kho</dt>
                        <dd class="col-sm-7">
                            <?= htmlspecialchars($document['TenKho'] ?? ($document['IdKho'] ?? '-')) ?>
                            <?php if (!empty($warehouse['TenLoaiKho'])): ?>
                                <div class="text-muted small"><?= htmlspecialchars($warehouse['TenLoaiKho']) ?></div>
                            <?php endif; ?>
                        </dd>
                        <dt class="col-sm-5">Ngày lập</dt>
                        <dd class="col-sm-7"><?= $document['NgayLP'] ? date('d/m/Y', strtotime($document['NgayLP'])) : '-' ?></dd>
                        <dt class="col-sm-5">Ngày xác nhận</dt>
                        <dd class="col-sm-7"><?= $document['NgayXN'] ? date('d/m/Y', strtotime($document['NgayXN'])) : '-' ?></dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Người lập</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($document['NguoiLap'] ?? '-') ?></dd>
                        <dt class="col-sm-5">Người xác nhận</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($document['NguoiXacNhan'] ?? '-') ?></dd>
                        <dt class="col-sm-5">Tổng tiền</dt>
                        <dd class="col-sm-7 text-primary fw-semibold"><?= number_format($document['TongTien'] ?? 0, 0, ',', '.') ?> đ</dd>
                        <dt class="col-sm-5">Tổng số lượng</dt>
                        <dd class="col-sm-7"><?= number_format($document['TongSoLuong'] ?? $document['TongMatHang'] ?? 0) ?></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Danh sách lô</h5>
                <span class="text-muted small">Tổng cộng: <?= count($details) ?> dòng</span>
            </div>
            <?php if (empty($details)): ?>
                <p class="text-muted mb-0">Phiếu chưa có chi tiết.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>Mã lô</th>
                            <th>Tên lô</th>
                            <th>Mặt hàng</th>
                            <th class="text-nowrap">Số lượng</th>
                            <th class="text-nowrap">Thực nhận</th>
                            <th class="text-nowrap">Loại lô</th>
                            <th class="text-nowrap">Đơn vị</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($details as $detail): ?>
                            <tr>
                                <td class="fw-semibold"><?= htmlspecialchars($detail['IdLo'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($detail['TenLo'] ?? '-') ?></td>
                                <td>
                                    <div class="fw-semibold mb-0"><?= htmlspecialchars($detail['TenSanPham'] ?? '-') ?></div>
                                    <small class="text-muted">Mã SP: <?= htmlspecialchars($detail['IdSanPham'] ?? '-') ?></small>
                                </td>
                                <td><?= number_format($detail['SoLuong'] ?? 0) ?></td>
                                <td><?= number_format($detail['ThucNhan'] ?? 0) ?></td>
                                <td><?= htmlspecialchars($detail['LoaiLo'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($detail['DonViTinh'] ?? $detail['DonVi'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
