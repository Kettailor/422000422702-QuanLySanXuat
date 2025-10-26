<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Chi tiết kho SV5TOT</h3>
        <p class="text-muted mb-0">Thông tin kho SV5TOT và các lô switch/PCB/thành phẩm đang quản lý.</p>
    </div>
    <a href="?controller=warehouse&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php if (!$warehouse): ?>
    <div class="alert alert-warning">Không tìm thấy kho.</div>
<?php else: ?>
    <div class="card p-4 mb-4">
        <div class="row g-4">
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Mã kho</dt>
                    <dd class="col-sm-7 fw-semibold"><?= htmlspecialchars($warehouse['IdKho']) ?></dd>
                    <dt class="col-sm-5">Tên kho</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($warehouse['TenKho']) ?></dd>
                    <dt class="col-sm-5">Loại kho</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($warehouse['TenLoaiKho']) ?></dd>
                    <dt class="col-sm-5">Xưởng phụ trách</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($warehouse['TenXuong'] ?? '-') ?> (<?= htmlspecialchars($warehouse['IdXuong'] ?? '-') ?>)</dd>
                    <dt class="col-sm-5">Địa chỉ</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($warehouse['DiaChi']) ?></dd>
                    <dt class="col-sm-5">Giá trị tồn kho</dt>
                    <dd class="col-sm-7 text-primary fw-semibold"><?= number_format($warehouse['ThanhTien'], 0, ',', '.') ?> đ</dd>
                </dl>
            </div>
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Quản kho</dt>
                    <dd class="col-sm-7">
                        <?= htmlspecialchars($warehouse['TenQuanKho'] ?? '') ?>
                        <span class="text-muted d-block small">Mã NV: <?= htmlspecialchars($warehouse['NHAN_VIEN_KHO_IdNhanVien'] ?? '') ?></span>
                    </dd>
                    <dt class="col-sm-5">Tổng số lô</dt>
                    <dd class="col-sm-7"><?= number_format($warehouse['SoLoDangQuanLy']) ?> lô / <?= number_format($warehouse['TongSLLo']) ?> lô thiết kế</dd>
                    <dt class="col-sm-5">Tổng số lượng</dt>
                    <dd class="col-sm-7"><?= number_format($warehouse['TongSoLuongLo']) ?> / năng lực <?= number_format($warehouse['TongSL']) ?></dd>
                    <dt class="col-sm-5">Tỷ lệ sử dụng</dt>
                    <dd class="col-sm-7">
                        <span class="badge <?= ($warehouse['TyLeSuDung'] ?? 0) > 85 ? 'badge-soft-danger' : (( $warehouse['TyLeSuDung'] ?? 0) > 60 ? 'badge-soft-warning' : 'badge-soft-success') ?>">
                            <?= number_format($warehouse['TyLeSuDung'] ?? 0, 1) ?>%
                        </span>
                    </dd>
                    <dt class="col-sm-5">Trạng thái</dt>
                    <dd class="col-sm-7"><span class="badge bg-info bg-opacity-25 text-primary"><?= htmlspecialchars($warehouse['TrangThai']) ?></span></dd>
                    <dt class="col-sm-5">Phiếu gần nhất</dt>
                    <dd class="col-sm-7">
                        <?= $warehouse['LanNhapXuatGanNhat'] ? date('d/m/Y', strtotime($warehouse['LanNhapXuatGanNhat'])) : '-' ?>
                        <span class="text-muted d-block small">Tổng giá trị phiếu: <?= number_format($warehouse['TongGiaTriPhieu'], 0, ',', '.') ?> đ</span>
                        <span class="text-muted d-block small">Giá trị trong tháng: <?= number_format($warehouse['GiaTriPhieuThang'], 0, ',', '.') ?> đ</span>
                    </dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Danh sách lô SV5TOT</h5>
            <span class="text-muted small">Tổng cộng: <?= count($lots) ?> lô</span>
        </div>
        <?php if (empty($lots)): ?>
            <p class="text-muted">Chưa có lô hàng nào được ghi nhận.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>Mã lô</th>
                        <th>Tên lô</th>
                        <th>Mặt hàng</th>
                        <th>Số lượng</th>
                        <th>Loại lô SV5TOT</th>
                        <th>Ngày tạo</th>
                        <th>Phiếu liên quan</th>
                        <th>Số lượng xuất/nhập</th>
                        <th>Thực nhận</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($lots as $lot): ?>
                        <tr>
                            <td><?= htmlspecialchars($lot['IdLo']) ?></td>
                            <td><?= htmlspecialchars($lot['TenLo']) ?></td>
                            <td>
                                <div class="fw-semibold mb-0"><?= htmlspecialchars($lot['TenSanPham'] ?? '-') ?></div>
                                <small class="text-muted">Mã SP: <?= htmlspecialchars($lot['IdSanPham'] ?? '-') ?><?= !empty($lot['DonVi']) ? ' · Đơn vị: ' . htmlspecialchars($lot['DonVi']) : '' ?></small>
                            </td>
                            <td><?= number_format($lot['SoLuong']) ?></td>
                            <td><?= htmlspecialchars($lot['LoaiLo']) ?></td>
                            <td>
                                <?= $lot['NgayTao'] ? date('d/m/Y', strtotime($lot['NgayTao'])) : '-' ?>
                                <?php if (!empty($lot['LanPhatSinhGanNhat'])): ?>
                                    <div class="text-muted small">Phát sinh: <?= date('d/m/Y', strtotime($lot['LanPhatSinhGanNhat'])) ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?= number_format($lot['TongSoPhieuLienQuan'] ?? 0) ?></td>
                            <td><?= number_format($lot['TongSoLuongPhieu'] ?? 0) ?></td>
                            <td><?= number_format($lot['TongThucNhan'] ?? 0) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
