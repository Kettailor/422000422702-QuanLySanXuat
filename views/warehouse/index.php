<?php
$summary = $summary ?? [];
$warehouses = $warehouses ?? [];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Quản lý kho</h3>
        <p class="text-muted mb-0">Theo dõi hiệu suất vận hành và chi tiết tồn kho theo từng kho.</p>
    </div>
    <a href="?controller=warehouse&action=create" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Thêm kho</a>
</div>

<?php if (!empty($summary)): ?>
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card metric-card">
                <div class="icon-wrap bg-primary bg-opacity-10 text-primary"><i class="bi bi-archive"></i></div>
                <div>
                    <div class="text-muted text-uppercase small">Tổng số kho</div>
                    <div class="fs-3 fw-bold"><?= number_format($summary['total_warehouses']) ?></div>
                    <div class="small text-success">Đang sử dụng: <?= number_format($summary['active_warehouses']) ?></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card metric-card">
                <div class="icon-wrap bg-info bg-opacity-10 text-info"><i class="bi bi-box-seam"></i></div>
                <div>
                    <div class="text-muted text-uppercase small">Sức chứa hệ thống</div>
                    <div class="fs-3 fw-bold"><?= number_format($summary['total_capacity']) ?></div>
                    <div class="small text-muted">Kho tạm ngưng: <?= number_format($summary['inactive_warehouses']) ?></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card metric-card">
                <div class="icon-wrap bg-warning bg-opacity-10 text-warning"><i class="bi bi-graph-up"></i></div>
                <div>
                    <div class="text-muted text-uppercase small">Giá trị hàng tồn</div>
                    <div class="fs-3 fw-bold"><?= number_format($summary['total_inventory_value'], 0, ',', '.') ?> đ</div>
                    <div class="small text-muted">Tổng lô: <?= number_format($summary['total_lots']) ?></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card metric-card">
                <div class="icon-wrap bg-success bg-opacity-10 text-success"><i class="bi bi-layers"></i></div>
                <div>
                    <div class="text-muted text-uppercase small">Tổng số lượng tồn</div>
                    <div class="fs-3 fw-bold"><?= number_format($summary['total_quantity']) ?></div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr>
                <th>Mã kho</th>
                <th>Tên kho</th>
                <th>Loại kho</th>
                <th>Xưởng phụ trách</th>
                <th>Quản kho</th>
                <th>Lô đang quản lý</th>
                <th>Số lượng lô</th>
                <th>Phiếu phát sinh</th>
                <th>Lần nhập/xuất gần nhất</th>
                <th>Giá trị phiếu</th>
                <th>Giá trị tháng</th>
                <th>Tỷ lệ sử dụng</th>
                <th>Trạng thái</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($warehouses as $warehouse): ?>
                <tr>
                    <td class="fw-semibold"><?= htmlspecialchars($warehouse['IdKho']) ?></td>
                    <td><?= htmlspecialchars($warehouse['TenKho']) ?></td>
                    <td><?= htmlspecialchars($warehouse['TenLoaiKho']) ?></td>
                    <td><?= htmlspecialchars($warehouse['TenXuong'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($warehouse['TenQuanKho'] ?? '') ?></td>
                    <td><?= number_format($warehouse['SoLoDangQuanLy']) ?></td>
                    <td><?= number_format($warehouse['TongSoLuongLo']) ?></td>
                    <td><?= number_format($warehouse['TongSoPhieu']) ?></td>
                    <td>
                        <?= $warehouse['LanNhapXuatGanNhat'] ? date('d/m/Y', strtotime($warehouse['LanNhapXuatGanNhat'])) : '-' ?>
                    </td>
                    <td class="fw-semibold text-primary">
                        <?= number_format($warehouse['TongGiaTriPhieu'], 0, ',', '.') ?> đ
                    </td>
                    <td class="text-muted">
                        <?= number_format($warehouse['GiaTriPhieuThang'], 0, ',', '.') ?> đ
                    </td>
                    <td>
                        <span class="badge <?= ($warehouse['TyLeSuDung'] ?? 0) > 85 ? 'badge-soft-danger' : (( $warehouse['TyLeSuDung'] ?? 0) > 60 ? 'badge-soft-warning' : 'badge-soft-success') ?>">
                            <?= number_format($warehouse['TyLeSuDung'] ?? 0, 1) ?>%
                        </span>
                    </td>
                    <td><span class="badge bg-light text-dark"><?= htmlspecialchars($warehouse['TrangThai']) ?></span></td>
                    <td class="text-end">
                        <div class="table-actions">
                            <a class="btn btn-sm btn-outline-secondary" href="?controller=warehouse&action=read&id=<?= urlencode($warehouse['IdKho']) ?>">Chi tiết</a>
                            <a class="btn btn-sm btn-outline-primary" href="?controller=warehouse&action=edit&id=<?= urlencode($warehouse['IdKho']) ?>">Sửa</a>
                            <a class="btn btn-sm btn-outline-danger" href="?controller=warehouse&action=delete&id=<?= urlencode($warehouse['IdKho']) ?>" onclick="return confirm('Xác nhận xóa kho này?');">Xóa</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
