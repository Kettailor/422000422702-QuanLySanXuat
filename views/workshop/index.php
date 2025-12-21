<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Danh sách xưởng sản xuất</h3>
        <p class="text-muted mb-0">Tổng quan danh sách xưởng và tình trạng vận hành.</p>
    </div>
    <?php
    $canAssign = $canAssign ?? false;
    $showExecutiveOverview = $showExecutiveOverview ?? true;
    $managerOverview = $managerOverview ?? null;
    ?>
    <div class="d-flex gap-2">
        <a href="?controller=workshop&action=dashboard" class="btn btn-outline-primary">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard vật tư & tiến độ
        </a>
        <?php if ($canAssign): ?>
            <a href="?controller=workshop&action=create" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>Thêm xưởng
            </a>
        <?php endif; ?>
    </div>
</div>

<style>
.manager-overview {
    background: linear-gradient(135deg, #f8fbff 0%, #ffffff 100%);
    border: 1px solid #e8eef6;
}
</style>

<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-primary bg-opacity-10 text-primary"><i class="bi bi-buildings"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Tổng số xưởng</div>
                <div class="fs-3 fw-bold"><?= $summary['total_workshops'] ?></div>
                <div class="small text-muted">Đang hoạt động: <?= $statusDistribution['Đang hoạt động'] ?? 0 ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-success bg-opacity-10 text-success"><i class="bi bi-lightning-charge"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Công suất tối đa</div>
                <div class="fs-3 fw-bold"><?= number_format($summary['max_capacity'], 0, ',', '.') ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-info bg-opacity-10 text-info"><i class="bi bi-speedometer"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Công suất đang dùng</div>
                <div class="fs-3 fw-bold"><?= number_format($summary['current_capacity'], 0, ',', '.') ?></div>
                <div class="small text-muted">Hiệu suất: <?= number_format($summary['utilization'], 1) ?>%</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-warning bg-opacity-10 text-warning"><i class="bi bi-people"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Nhân sự</div>
                <div class="fs-5 fw-bold">Hiện tại: <?= number_format($summary['workforce']) ?></div>
                <div class="small text-muted">Tỷ lệ lấp đầy: <?= number_format($summary['workforce_utilization'], 1) ?>%</div>
            </div>
        </div>
    </div>
</div>

<?php if ($showExecutiveOverview && !empty($executiveOverview)): ?>
    <div class="card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <div class="badge bg-secondary-subtle text-secondary mb-2">Ảnh chụp tổng quan</div>
                <h4 class="fw-bold mb-1">Hiệu suất hệ thống xưởng</h4>
                <div class="text-muted">Ưu tiên tối ưu vận hành và phân bổ nhân sự</div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-center">
                    <div class="text-muted small">Hiệu suất công suất</div>
                    <div class="fs-4 fw-bold text-primary"><?= number_format($executiveOverview['utilization'], 1) ?>%</div>
                </div>
                <div class="text-center">
                    <div class="text-muted small">Lấp đầy nhân sự</div>
                    <div class="fs-4 fw-bold text-success"><?= number_format($executiveOverview['workforce_utilization'], 1) ?>%</div>
                </div>
            </div>
        </div>
        <div class="row g-3 mt-3">
            <div class="col-md-4">
                <div class="border rounded-3 p-3 h-100">
                    <div class="text-muted small mb-1">Trạng thái xưởng</div>
                    <div class="d-flex flex-wrap gap-3">
                        <span class="badge bg-success-subtle text-success px-3 py-2">Đang hoạt động: <?= $executiveOverview['active'] ?></span>
                        <span class="badge bg-warning-subtle text-warning px-3 py-2">Bảo trì: <?= $executiveOverview['maintenance'] ?></span>
                        <span class="badge bg-danger-subtle text-danger px-3 py-2">Tạm dừng: <?= $executiveOverview['paused'] ?></span>
                        <?php if ($executiveOverview['others'] > 0): ?>
                            <span class="badge bg-secondary-subtle text-secondary px-3 py-2">Khác: <?= $executiveOverview['others'] ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded-3 p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Công suất bình quân/xưởng</div>
                            <div class="fw-semibold"><?= number_format($executiveOverview['avg_capacity'], 1, ',', '.') ?></div>
                        </div>
                        <div class="text-end">
                            <div class="text-muted small">Hiệu suất</div>
                            <div class="fs-5 fw-bold text-primary"><?= number_format($executiveOverview['utilization'], 1) ?>%</div>
                        </div>
                    </div>
                    <p class="text-muted small mb-0 mt-2">Ưu tiên các xưởng công suất thấp để tăng phân bổ đơn hàng.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded-3 p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Nhân sự bình quân/xưởng</div>
                            <div class="fw-semibold"><?= number_format($executiveOverview['avg_headcount'], 1, ',', '.') ?></div>
                        </div>
                        <div class="text-end">
                            <div class="text-muted small">Lấp đầy</div>
                            <div class="fs-5 fw-bold text-success"><?= number_format($executiveOverview['workforce_utilization'], 1) ?>%</div>
                        </div>
                    </div>
                    <p class="text-muted small mb-0 mt-2">Cân nhắc luân chuyển nhân sự giữa các xưởng để giữ cân bằng tải.</p>
                </div>
            </div>
        </div>
    </div>
<?php elseif ($managerOverview): ?>
    <div class="card p-4 mb-4 manager-overview">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="badge bg-secondary-subtle text-secondary mb-2">Tổng quan nhanh</div>
                <h4 class="fw-bold mb-1"><?= htmlspecialchars($managerOverview['name']) ?></h4>
                <div class="text-muted small">Trạng thái: <?= htmlspecialchars($managerOverview['status']) ?></div>
            </div>
            <div class="d-flex flex-wrap gap-3">
                <div class="text-center">
                    <div class="text-muted small">Nhân sự</div>
                    <div class="fs-5 fw-bold text-primary">
                        <?= number_format($managerOverview['staff_current']) ?> / <?= number_format($managerOverview['staff_max']) ?>
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-muted small">Hiệu suất</div>
                    <div class="fs-5 fw-bold text-success">
                        <?= $managerOverview['capacity_rate'] !== null ? number_format($managerOverview['capacity_rate'], 1) . '%' : '—' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Thông tin chi tiết</h5>
                <span class="text-muted small">Theo dõi tình trạng từng xưởng</span>
            </div>
            <?php if (empty($workshops)): ?>
                <div class="alert alert-info mb-0">
                    Hiện chưa có xưởng nào để hiển thị.
                </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>Mã xưởng</th>
                        <th>Tên xưởng</th>
                        <th>Công suất</th>
                        <th>Nhân sự (hiện tại / tối đa)</th>
                        <th>Trạng thái</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($workshops as $workshop): ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($workshop['IdXuong']) ?></td>
                            <td><?= htmlspecialchars($workshop['TenXuong']) ?></td>
                            <td>
                                <?= number_format($workshop['CongSuatDangSuDung'] ?? $workshop['CongSuatHienTai'] ?? 0, 0, ',', '.') ?>
                                / <?= number_format($workshop['CongSuatToiDa'] ?? 0, 0, ',', '.') ?>
                            </td>
                            <td>
                                <?php $staffCurrent = $workshop['staff_current'] ?? $workshop['SoLuongCongNhan'] ?? 0; ?>
                                <?php $staffMax = $workshop['staff_max'] ?? $workshop['SlNhanVien'] ?? 0; ?>
                                <?= number_format($staffCurrent) ?>
                                / <?= number_format($staffMax) ?>
                            </td>
                            <td>
                                <?php $status = $workshop['TrangThai'] ?? 'Không xác định'; ?>
                                <?php
                                $badgeClass = 'badge-soft-warning';
                                if ($status === 'Đang hoạt động') {
                                    $badgeClass = 'badge-soft-success';
                                } elseif ($status === 'Bảo trì') {
                                    $badgeClass = 'badge-soft-warning';
                                } elseif ($status === 'Tạm dừng') {
                                    $badgeClass = 'badge-soft-danger';
                                }
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
                            </td>
                            <td class="text-end">
                                <div class="table-actions">
                                    <a class="btn btn-sm btn-outline-secondary" href="?controller=workshop&action=read&id=<?= urlencode($workshop['IdXuong']) ?>">Chi tiết</a>
                                    <a class="btn btn-sm btn-outline-primary" href="?controller=workshop&action=edit&id=<?= urlencode($workshop['IdXuong']) ?>">Sửa</a>
                                    <?php if ($canAssign): ?>
                                        <a class="btn btn-sm btn-outline-danger" href="?controller=workshop&action=delete&id=<?= urlencode($workshop['IdXuong']) ?>" onclick="return confirm('Xác nhận xóa xưởng này?');">Xóa</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card p-4 h-100">
            <h5 class="mb-3">Ghi chú vận hành</h5>
            <p class="text-muted small mb-2">Tập trung vào các xưởng có hiệu suất thấp để cân bằng tải, tăng hiệu quả sử dụng máy và nhân sự.</p>
            <ul class="list-unstyled mb-0">
                <li class="d-flex gap-2 align-items-start mb-2">
                    <i class="bi bi-check2-circle text-success"></i>
                    <span>Ưu tiên phân đơn cho xưởng đang hoạt động và có công suất trống.</span>
                </li>
                <li class="d-flex gap-2 align-items-start mb-2">
                    <i class="bi bi-people text-primary"></i>
                    <span>Luân chuyển nhân sự tạm thời từ xưởng tạm dừng sang xưởng quá tải.</span>
                </li>
                <li class="d-flex gap-2 align-items-start mb-2">
                    <i class="bi bi-wrench-adjustable text-warning"></i>
                    <span>Giám sát xưởng bảo trì để sắp xếp lịch khởi động lại hợp lý.</span>
                </li>
            </ul>
        </div>
    </div>
</div>
