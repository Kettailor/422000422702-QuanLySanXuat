<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Danh sách xưởng sản xuất</h3>
        <p class="text-muted mb-0">
            <?php if (!empty($isWorkshopManagerView)): ?>
                Bảng điều khiển dành riêng cho trưởng xưởng: theo dõi xưởng được giao, tiến độ và mức tải nhân sự.
            <?php else: ?>
                Góc nhìn tổng quan cho ban giám đốc/admin: ảnh chụp sức khỏe toàn bộ hệ thống xưởng.
            <?php endif; ?>
        </p>
    </div>
    <?php $canAssign = $canAssign ?? false; ?>
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

<?php if (empty($isWorkshopManagerView) && !empty($executiveOverview)): ?>
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
<?php endif; ?>

<?php if (!empty($isWorkshopManagerView) && !empty($focusWorkshop)): ?>
    <div class="card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <div class="badge bg-primary-subtle text-primary mb-2">Xưởng ưu tiên</div>
                <h4 class="fw-bold mb-1"><?= htmlspecialchars($focusWorkshop['name']) ?></h4>
                <div class="text-muted">Mã xưởng: <?= htmlspecialchars($focusWorkshop['id']) ?> • <?= htmlspecialchars($focusWorkshop['location']) ?></div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted small">Trưởng xưởng</span>
                <div class="fw-semibold"><?= htmlspecialchars($focusWorkshop['manager']) ?></div>
            </div>
        </div>
        <div class="row g-3 align-items-center mt-3">
            <div class="col-md-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Công suất</div>
                        <div class="fw-semibold"><?= htmlspecialchars($focusWorkshop['capacityLabel']) ?></div>
                    </div>
                    <div class="text-end">
                        <div class="small text-muted">Hiệu suất</div>
                        <div class="fs-5 fw-bold text-primary"><?= $focusWorkshop['capacityUsage'] ?>%</div>
                    </div>
                </div>
                <div class="progress mt-2" style="height: 10px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: <?= min($focusWorkshop['capacityUsage'], 100) ?>%"></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Nhân sự</div>
                        <div class="fw-semibold"><?= htmlspecialchars($focusWorkshop['workforceLabel']) ?></div>
                    </div>
                    <div class="text-end">
                        <div class="small text-muted">Lấp đầy</div>
                        <div class="fs-5 fw-bold text-success"><?= $focusWorkshop['workforceUsage'] ?>%</div>
                    </div>
                </div>
                <div class="progress mt-2" style="height: 10px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: <?= min($focusWorkshop['workforceUsage'], 100) ?>%"></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex flex-column gap-2">
                    <div>
                        <span class="text-muted small">Trạng thái</span>
                        <?php $status = $focusWorkshop['status']; ?>
                        <?php
                        $badgeClass = 'badge-soft-warning';
                        if ($status === 'Đang hoạt động') {
                            $badgeClass = 'badge-soft-success';
                        } elseif ($status === 'Tạm dừng') {
                            $badgeClass = 'badge-soft-danger';
                        }
                        ?>
                        <span class="badge <?= $badgeClass ?> ms-2"><?= htmlspecialchars($status) ?></span>
                    </div>
                    <?php if (!empty($focusWorkshop['description'])): ?>
                        <div class="text-muted small"><?= nl2br(htmlspecialchars($focusWorkshop['description'])) ?></div>
                    <?php else: ?>
                        <div class="text-muted small fst-italic">Chưa có ghi chú cho xưởng này.</div>
                    <?php endif; ?>
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
                    Hiện bạn chưa được phân công xưởng nào. Vui lòng liên hệ ban quản trị nếu cần được cấp quyền.
                </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>Mã xưởng</th>
                        <th>Tên xưởng</th>
                        <th>Quản lý</th>
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
                            <td><?= htmlspecialchars($workshop['TruongXuong'] ?? 'Chưa phân công') ?></td>
                            <td>
                                <?= number_format($workshop['CongSuatDangSuDung'] ?? $workshop['CongSuatHienTai'] ?? 0, 0, ',', '.') ?>
                                / <?= number_format($workshop['CongSuatToiDa'] ?? 0, 0, ',', '.') ?>
                            </td>
                            <td>
                                <?= number_format($workshop['SoLuongCongNhan'] ?? 0) ?>
                                / <?= number_format($workshop['SlNhanVien'] ?? 0) ?>
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

<?php if (!empty($isWorkshopManagerView) && !empty($workshopCards)): ?>
    <div class="card p-4 mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Thẻ tổng quan theo xưởng</h5>
            <span class="text-muted small">Dành riêng cho trưởng xưởng để nắm nhanh tải xưởng</span>
        </div>
        <div class="row g-3">
            <?php foreach ($workshopCards as $card): ?>
                <div class="col-xl-4 col-md-6">
                    <div class="card h-100 p-3 shadow-sm border-0">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <div class="fw-bold mb-1"><?= htmlspecialchars($card['name']) ?></div>
                                <div class="text-muted small"><?= htmlspecialchars($card['id']) ?> • <?= htmlspecialchars($card['location']) ?></div>
                            </div>
                            <?php
                            $status = $card['status'];
                            $badgeClass = 'badge-soft-warning';
                            if ($status === 'Đang hoạt động') {
                                $badgeClass = 'badge-soft-success';
                            } elseif ($status === 'Tạm dừng') {
                                $badgeClass = 'badge-soft-danger';
                            }
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
                        </div>
                        <div class="mb-2 text-muted small">Trưởng xưởng: <span class="text-dark fw-semibold"><?= htmlspecialchars($card['manager']) ?></span></div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small text-muted">
                                <span>Công suất</span>
                                <span class="fw-semibold text-dark"><?= htmlspecialchars($card['capacityLabel']) ?> (<?= $card['capacityUsage'] ?>%)</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary" style="width: <?= min($card['capacityUsage'], 100) ?>%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small text-muted">
                                <span>Nhân sự</span>
                                <span class="fw-semibold text-dark"><?= htmlspecialchars($card['workforceLabel']) ?> (<?= $card['workforceUsage'] ?>%)</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: <?= min($card['workforceUsage'], 100) ?>%"></div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <a class="btn btn-sm btn-outline-secondary" href="?controller=workshop&action=read&id=<?= urlencode($card['id']) ?>">Xem chi tiết</a>
                            <a class="btn btn-sm btn-primary" href="?controller=workshop&action=dashboard&workshop=<?= urlencode($card['id']) ?>">Dashboard xưởng</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
