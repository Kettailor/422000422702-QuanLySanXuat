<?php
$summary = $summary ?? [];
$documents = $documents ?? [];
$activeFilter = $activeFilter ?? 'all';
$filterLabel = $filterLabel ?? 'Tất cả phiếu kho';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Phiếu xuất nhập kho</h3>
        <p class="text-muted mb-0">Tổng hợp luồng nhập - xuất và hiệu suất xử lý chứng từ kho.</p>
    </div>
    <a href="?controller=warehouse_sheet&action=create" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tạo phiếu mới
    </a>
</div>

<?php if (!empty($summary)): ?>
    <?php
    $metrics = [
        'all' => [
            'label' => 'Tổng số phiếu',
            'value' => $summary['total_documents'] ?? 0,
            'icon' => 'bi bi-receipt',
            'class' => 'primary',
            'target' => '?controller=warehouse_sheet&action=index&type=all',
        ],
        'inbound' => [
            'label' => 'Phiếu nhập',
            'value' => $summary['inbound_documents'] ?? 0,
            'icon' => 'bi bi-box-arrow-in-down',
            'class' => 'success',
            'target' => '?controller=warehouse_sheet&action=index&type=inbound',
        ],
        'outbound' => [
            'label' => 'Phiếu xuất',
            'value' => $summary['outbound_documents'] ?? 0,
            'icon' => 'bi bi-box-arrow-up',
            'class' => 'danger',
            'target' => '?controller=warehouse_sheet&action=index&type=outbound',
        ],
    ];
    ?>
    <div class="row g-3 mb-4">
        <?php foreach ($metrics as $key => $metric): ?>
            <div class="col-xl-3 col-sm-6">
                <a href="<?= $metric['target'] ?>" class="text-decoration-none text-reset">
                    <div class="card metric-card border-0 <?= $activeFilter === $key ? 'shadow-sm border border-' . $metric['class'] : '' ?>">
                        <div class="icon-wrap bg-<?= $metric['class'] ?> bg-opacity-10 text-<?= $metric['class'] ?>">
                            <i class="<?= $metric['icon'] ?>"></i>
                        </div>
                        <div>
                            <div class="text-muted text-uppercase small"><?= $metric['label'] ?></div>
                            <div class="fs-3 fw-bold"><?= number_format($metric['value']) ?></div>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
        <div class="col-xl-3 col-sm-6">
            <div class="card metric-card">
                <div class="icon-wrap bg-warning bg-opacity-10 text-warning"><i class="bi bi-cash-stack"></i></div>
                <div>
                    <div class="text-muted text-uppercase small">Tổng giá trị</div>
                    <div class="fs-3 fw-bold"><?= number_format($summary['total_value'] ?? 0, 0, ',', '.') ?> đ</div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($summary['monthly_trend'])): ?>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-semibold mb-0">Xu hướng 6 tháng gần nhất</h6>
                    <span class="text-muted small">Theo tháng lập phiếu</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                        <tr>
                            <th>Tháng</th>
                            <th>Số phiếu</th>
                            <th>Tổng giá trị</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($summary['monthly_trend'] as $trend): ?>
                            <tr>
                                <td><?= htmlspecialchars($trend['thang']) ?></td>
                                <td><?= number_format($trend['so_phieu']) ?></td>
                                <td><?= number_format($trend['tong_tien'], 0, ',', '.') ?> đ</td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 fw-semibold"><?= htmlspecialchars($filterLabel) ?></h5>
        <?php if ($activeFilter !== 'all'): ?>
            <a class="btn btn-sm btn-outline-secondary" href="?controller=warehouse_sheet&action=index">
                <i class="bi bi-x-lg me-1"></i> Bỏ lọc
            </a>
        <?php endif; ?>
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr>
                <th>Mã phiếu</th>
                <th>Kho</th>
                <th>Loại phiếu</th>
                <th>Đối tác</th>
                <th>Lý do</th>
                <th>Ngày lập</th>
                <th>Ngày xác nhận</th>
                <th>Tổng tiền</th>
                <th>Người lập</th>
                <th>Người xác nhận</th>
                <th>Mặt hàng</th>
                <th>Số lượng</th>
                <th class="text-end">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($documents)): ?>
                <tr>
                    <td colspan="13" class="text-center text-muted py-4">Chưa có phiếu nào phù hợp với bộ lọc hiện tại.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($documents as $document): ?>
                    <?php
                    $type = $document['LoaiPhieu'] ?? '';
                    $classification = $document['classification'] ?? [];
                    $typeClass = $classification['badge_class'] ?? 'bg-secondary bg-opacity-10 text-secondary';
                    $directionLabel = $classification['direction_label'] ?? $type;
                    $categoryLabel = $classification['category'] ?? '';
                    ?>
                    <tr>
                        <td class="fw-semibold"><?= htmlspecialchars($document['IdPhieu']) ?></td>
                        <td><?= htmlspecialchars($document['TenKho']) ?></td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="badge <?= $typeClass ?> mb-1"><?= htmlspecialchars($directionLabel) ?></span>
                                <?php if ($categoryLabel !== ''): ?>
                                    <span class="badge bg-info-subtle text-info border mb-1"><?= htmlspecialchars($categoryLabel) ?></span>
                                <?php endif; ?>
                                <small class="text-muted"><?= htmlspecialchars($document['LoaiPhieu']) ?></small>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold mb-0"><?= htmlspecialchars($document['DoiTac'] ?? '-') ?></div>
                            <small class="text-muted"><?= htmlspecialchars($document['LoaiDoiTac'] ?? '') ?></small>
                        </td>
                        <td>
                            <div class="text-truncate" style="max-width: 220px;"><?= htmlspecialchars($document['LyDo'] ?? '-') ?></div>
                            <?php if (!empty($document['SoThamChieu'])): ?>
                                <small class="text-muted">Tham chiếu: <?= htmlspecialchars($document['SoThamChieu']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?= $document['NgayLP'] ? date('d/m/Y', strtotime($document['NgayLP'])) : '-' ?></td>
                        <td><?= $document['NgayXN'] ? date('d/m/Y', strtotime($document['NgayXN'])) : '-' ?></td>
                        <td class="fw-semibold text-primary"><?= number_format($document['TongTien'], 0, ',', '.') ?> đ</td>
                        <td><?= htmlspecialchars($document['NguoiLap'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($document['NguoiXacNhan'] ?? '-') ?></td>
                        <td><?= number_format($document['TongMatHang']) ?></td>
                        <td><?= number_format($document['TongSoLuong']) ?></td>
                        <td class="text-end">
                            <div class="btn-group" role="group">
                                <a href="?controller=warehouse_sheet&action=read&id=<?= urlencode($document['IdPhieu']) ?>" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="?controller=warehouse_sheet&action=edit&id=<?= urlencode($document['IdPhieu']) ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="?controller=warehouse_sheet&action=export_pdf&id=<?= urlencode($document['IdPhieu']) ?>" class="btn btn-sm btn-outline-success" title="Xuất PDF">
                                    <i class="bi bi-filetype-pdf"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
