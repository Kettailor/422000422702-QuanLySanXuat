<?php
$summary = $summary ?? [];
$warehouses = $warehouses ?? [];
$documentGroups = $documentGroups ?? [];
$warehouseGroups = $warehouseGroups ?? [];
$warehouseEntryForms = $warehouseEntryForms ?? [];
$employees = $employees ?? [];
$productOptionsByType = $productOptionsByType ?? [];
$outboundDocumentTypes = $outboundDocumentTypes ?? [];
$lotOptionsByType = $lotOptionsByType ?? [];
$documentGroupsJson = htmlspecialchars(json_encode($documentGroups, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}', ENT_QUOTES, 'UTF-8');
$lotMeta = [
    'material' => [
        'title' => 'Thông tin lô nguyên liệu',
        'description' => 'Ghi nhận chi tiết lô nguyên liệu mới nhập kho, số lượng và đơn vị tính.',
    ],
    'finished' => [
        'title' => 'Thông tin lô thành phẩm',
        'description' => 'Nhập thông tin thành phẩm hoàn thiện được đưa vào kho xuất bán.',
    ],
    'quality' => [
        'title' => 'Thông tin lô xử lý lỗi',
        'description' => 'Ghi lại lô sản phẩm lỗi cần xử lý và theo dõi riêng.',
    ],
];
$lotMetaDefault = [
    'title' => 'Thông tin lô nhập kho',
    'description' => 'Điền thông tin lô hàng chuẩn bị nhập kho.',
];
$lotPrefixMap = [
    'material' => 'LONL',
    'finished' => 'LOTP',
    'quality' => 'LOXL',
];
?>

<style>
    .warehouse-dashboard {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .warehouse-hero {
        background: linear-gradient(135deg, #eef2ff 0%, #f8fafc 50%, #fff 100%);
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 1.25rem;
        padding: 2rem;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.08);
    }

    .warehouse-hero .hero-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.35rem 0.9rem;
        border-radius: 999px;
        background: rgba(59, 130, 246, 0.1);
        color: #1d4ed8;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1rem;
    }

    .metric-trigger {
        border: 0;
        background: transparent;
        padding: 0;
        width: 100%;
        text-align: left;
    }

    .metric-trigger:focus {
        outline: none;
        box-shadow: none;
    }

    .summary-card {
        border-radius: 1rem;
        border: 1px solid rgba(15, 23, 42, 0.08);
        padding: 1.25rem;
        height: 100%;
        background: #fff;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .metric-trigger:hover .summary-card {
        transform: translateY(-4px);
        box-shadow: 0 20px 35px rgba(15, 23, 42, 0.12);
    }

    .metric-trigger:focus-visible .summary-card,
    .metric-trigger.active .summary-card {
        box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.2);
        transform: translateY(-2px);
    }

    .summary-icon {
        width: 44px;
        height: 44px;
        border-radius: 0.85rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }

    .summary-meta {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #64748b;
        font-weight: 600;
    }

    .summary-value {
        font-size: 1.85rem;
        font-weight: 700;
        color: #0f172a;
    }

    .warehouse-section {
        margin-top: 0.5rem;
    }

    .group-card {
        background: #fff;
        border-radius: 1.25rem;
        border: 1px solid rgba(15, 23, 42, 0.08);
        padding: 1.5rem;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.05);
    }

    .group-card + .group-card {
        margin-top: 2rem;
    }

    .group-header {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .group-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .group-stat {
        background: #f8fafc;
        border-radius: 0.85rem;
        padding: 0.75rem 1rem;
    }

    .group-stat .label {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #64748b;
        font-weight: 600;
    }

    .group-stat .value {
        font-weight: 700;
        font-size: 1.1rem;
        color: #0f172a;
    }

    .table-modern {
        border-radius: 1rem;
        overflow: hidden;
        border: 1px solid rgba(148, 163, 184, 0.35);
    }

    .table-modern thead {
        background: #f1f5f9;
    }

    .utilization-bar {
        height: 6px;
        border-radius: 999px;
        background: #e2e8f0;
        overflow: hidden;
        margin-top: 0.35rem;
    }

    .utilization-bar span {
        display: block;
        height: 100%;
    }
</style>

<div class="warehouse-dashboard">
    <div class="warehouse-hero">
        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
            <div>
                <div class="hero-tag mb-3"><i class="bi bi-grid-1x2-fill"></i>Trung tâm điều phối kho</div>
                <h2 class="fw-bold mb-2">Tổng quan hệ thống kho</h2>
                <p class="text-muted mb-0">Theo dõi sức chứa, giá trị tồn, phiếu nhập xuất và trạng thái vận hành của từng nhóm kho.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="?controller=warehouse&action=create" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Thêm kho mới</a>
                <a href="?controller=warehouse_sheet&action=create" class="btn btn-outline-primary"><i class="bi bi-file-earmark-plus me-2"></i>Tạo phiếu kho</a>
            </div>
        </div>
    </div>

    <?php if (!empty($summary)): ?>
        <div class="summary-grid">
            <button type="button" class="metric-trigger" data-document-group-trigger="all" data-bs-toggle="modal" data-bs-target="#warehouseDocumentModal">
                <div class="summary-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="summary-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-archive"></i></span>
                        <div>
                            <div class="summary-meta">Tổng số kho</div>
                            <div class="summary-value"><?= number_format($summary['total_warehouses'] ?? 0) ?></div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span class="text-success">Đang sử dụng: <?= number_format($summary['active_warehouses'] ?? 0) ?></span>
                        <span class="text-primary fw-semibold">Phiếu kho: <?= number_format($documentGroups['all']['count'] ?? 0) ?></span>
                    </div>
                </div>
            </button>
            <button type="button" class="metric-trigger" data-document-group-trigger="inbound" data-bs-toggle="modal" data-bs-target="#warehouseDocumentModal">
                <div class="summary-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="summary-icon bg-info bg-opacity-10 text-info"><i class="bi bi-box-seam"></i></span>
                        <div>
                            <div class="summary-meta">Sức chứa hệ thống</div>
                            <div class="summary-value"><?= number_format($summary['total_capacity'] ?? 0) ?></div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span class="text-muted">Kho tạm ngưng: <?= number_format(($summary['inactive_warehouses'] ?? 0)) ?></span>
                        <span class="text-info fw-semibold">Phiếu nhập: <?= number_format($documentGroups['inbound']['count'] ?? 0) ?></span>
                    </div>
                </div>
            </button>
            <button type="button" class="metric-trigger" data-document-group-trigger="valuable" data-bs-toggle="modal" data-bs-target="#warehouseDocumentModal">
                <div class="summary-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="summary-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-graph-up"></i></span>
                        <div>
                            <div class="summary-meta">Giá trị hàng tồn</div>
                            <div class="summary-value"><?= number_format($summary['total_inventory_value'] ?? 0, 0, ',', '.') ?> đ</div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span class="text-muted">Tổng lô: <?= number_format($summary['total_lots'] ?? 0) ?></span>
                        <span class="text-warning fw-semibold">Phiếu giá trị cao: <?= number_format($documentGroups['valuable']['count'] ?? 0) ?></span>
                    </div>
                </div>
            </button>
            <button type="button" class="metric-trigger" data-document-group-trigger="outbound" data-bs-toggle="modal" data-bs-target="#warehouseDocumentModal">
                <div class="summary-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="summary-icon bg-success bg-opacity-10 text-success"><i class="bi bi-layers"></i></span>
                        <div>
                            <div class="summary-meta">Tổng số lượng tồn</div>
                            <div class="summary-value"><?= number_format($summary['total_quantity'] ?? 0) ?></div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span class="text-success fw-semibold">Phiếu xuất: <?= number_format($documentGroups['outbound']['count'] ?? 0) ?></span>
                        <span class="text-muted">Tổng lô: <?= number_format($summary['total_lots'] ?? 0) ?></span>
                    </div>
                </div>
            </button>
        </div>
    <?php endif; ?>

    <?php if (!empty($summary['by_type'])): ?>
        <div class="row g-4">
            <?php foreach ($summary['by_type'] as $typeKey => $typeSummary): ?>
                <?php $group = $warehouseGroups[$typeKey] ?? ['label' => $typeSummary['label'] ?? '', 'description' => '', 'warehouses' => [], 'statistics' => $typeSummary]; ?>
                <?php $form = $warehouseEntryForms[$typeKey] ?? null; ?>
                <?php $hasWarehouses = !empty($group['warehouses']); ?>
                <?php $canCreateDocument = $form && $hasWarehouses; ?>
                <div class="col-xl-4 col-md-6">
                    <div class="summary-card h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3 gap-3">
                            <div>
                                <div class="summary-meta"><?= htmlspecialchars($group['label'] ?? ($typeSummary['label'] ?? '')) ?></div>
                                <div class="fw-semibold fs-5"><?= htmlspecialchars($group['label'] ?? ($typeSummary['label'] ?? '')) ?></div>
                                <?php if (!empty($group['description'])): ?>
                                    <div class="text-muted small"><?= htmlspecialchars($group['description']) ?></div>
                                <?php endif; ?>
                            </div>
                            <?php if ($form): ?>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#warehouse-entry-modal-<?= htmlspecialchars($typeKey) ?>">
                                    <i class="bi bi-plus-lg me-1"></i><?= htmlspecialchars($form['submit_label']) ?>
                                </button>
                            <?php endif; ?>
                        </div>
                        <div class="group-stats">
                            <div class="group-stat">
                                <div class="label">Số kho</div>
                                <div class="value"><?= number_format($typeSummary['count'] ?? 0) ?></div>
                                <div class="text-muted small">Đang hoạt động: <?= number_format($typeSummary['active_warehouses'] ?? 0) ?></div>
                            </div>
                            <div class="group-stat">
                                <div class="label">Giá trị tồn</div>
                                <div class="value text-primary"><?= number_format($typeSummary['total_inventory_value'] ?? 0, 0, ',', '.') ?> đ</div>
                                <div class="text-muted small">Sức chứa: <?= number_format($typeSummary['total_capacity'] ?? 0) ?></div>
                            </div>
                            <div class="group-stat">
                                <div class="label">Tổng lô</div>
                                <div class="value"><?= number_format($typeSummary['total_lots'] ?? 0) ?></div>
                            </div>
                            <div class="group-stat">
                                <div class="label">Tổng lượng</div>
                                <div class="value"><?= number_format($typeSummary['total_quantity'] ?? 0) ?></div>
                            </div>
                        </div>
                        <?php if ($form && !$canCreateDocument): ?>
                            <div class="alert alert-warning mt-3 py-2 px-3 small mb-0">
                                Chưa có kho thuộc nhóm này để lập phiếu.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php foreach ($warehouseGroups as $typeKey => $group): ?>
    <?php $form = $warehouseEntryForms[$typeKey] ?? null; ?>
    <?php $formUi = $form['ui'] ?? []; ?>
    <?php $optionsForType = $productOptionsByType[$typeKey] ?? []; ?>
    <?php $productOptionsJson = htmlspecialchars(json_encode($optionsForType, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '[]', ENT_QUOTES, 'UTF-8'); ?>
    <?php $lotOptionsJson = htmlspecialchars(json_encode($lotOptionsByType[$typeKey] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '[]', ENT_QUOTES, 'UTF-8'); ?>
    <?php $lotInfo = $lotMeta[$typeKey] ?? $lotMetaDefault; ?>
    <?php $lotPrefix = $lotPrefixMap[$typeKey] ?? 'LONL'; ?>
    <section class="warehouse-section" id="warehouse-group-<?= htmlspecialchars($typeKey) ?>">
        <div class="group-card">
            <div class="group-header">
                <div>
                    <h4 class="fw-semibold mb-1"><?= htmlspecialchars($group['label']) ?></h4>
                    <?php if (!empty($group['description'])): ?>
                        <p class="text-muted small mb-0"><?= htmlspecialchars($group['description']) ?></p>
                    <?php endif; ?>
                </div>
                <?php if ($form): ?>
                    <div class="d-flex gap-2 flex-wrap">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#warehouse-entry-modal-<?= htmlspecialchars($typeKey) ?>" data-direction-target="inbound">
                            <i class="bi bi-plus-lg me-2"></i><?= htmlspecialchars($form['submit_label']) ?>
                        </button>
                        <?php if (!empty($outboundDocumentTypes[$typeKey])): ?>
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#warehouse-entry-modal-<?= htmlspecialchars($typeKey) ?>" data-direction-target="outbound">
                                <i class="bi bi-box-arrow-up me-2"></i>Xuất <?= htmlspecialchars($group['label']) ?>
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="group-stats">
                <div class="group-stat">
                    <div class="label">Số kho</div>
                    <div class="value"><?= number_format($group['statistics']['count'] ?? 0) ?></div>
                    <div class="text-muted small">Hoạt động: <?= number_format($group['statistics']['active_warehouses'] ?? 0) ?></div>
                </div>
                <div class="group-stat">
                    <div class="label">Giá trị tồn</div>
                    <div class="value text-primary"><?= number_format($group['statistics']['total_inventory_value'] ?? 0, 0, ',', '.') ?> đ</div>
                    <div class="text-muted small">Sức chứa: <?= number_format($group['statistics']['total_capacity'] ?? 0) ?></div>
                </div>
                <div class="group-stat">
                    <div class="label">Tổng lô</div>
                    <div class="value"><?= number_format($group['statistics']['total_lots'] ?? 0) ?></div>
                </div>
                <div class="group-stat">
                    <div class="label">Tổng lượng</div>
                    <div class="value"><?= number_format($group['statistics']['total_quantity'] ?? 0) ?></div>
                </div>
            </div>

            <?php if (empty($group['warehouses'])): ?>
                <div class="alert alert-light border mb-0 rounded-3 mt-3">
                    Chưa có kho nào thuộc nhóm "<?= htmlspecialchars($group['label']) ?>". Vui lòng thêm kho mới để bắt đầu quản lý.
                </div>
            <?php else: ?>
                <div class="table-responsive table-modern mt-4">
                    <table class="table align-middle mb-0 table-hover">
                        <thead>
                        <tr>
                            <th>Mã kho</th>
                            <th>Tên kho</th>
                            <th>Quản kho</th>
                            <th>Tổng lô</th>
                            <th>Số lượng</th>
                            <th>Giá trị tồn</th>
                            <th>Phiếu phát sinh</th>
                            <th>Lần nhập/xuất</th>
                            <th>Tỷ lệ sử dụng</th>
                            <th>Trạng thái</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($group['warehouses'] as $warehouse): ?>
                            <?php $utilization = (float) ($warehouse['TyLeSuDung'] ?? 0); ?>
                            <tr>
                                <td class="fw-semibold"><?= htmlspecialchars($warehouse['IdKho']) ?></td>
                                <td>
                                    <div class="fw-semibold mb-1"><?= htmlspecialchars($warehouse['TenKho']) ?></div>
                                    <span class="badge bg-light text-secondary border"><?= htmlspecialchars($warehouse['TenLoaiKho'] ?? '') ?></span>
                                </td>
                                <td>
                                    <?= htmlspecialchars($warehouse['TenQuanKho'] ?? '-') ?>
                                    <div class="text-muted small">Mã NV: <?= htmlspecialchars($warehouse['NHAN_VIEN_KHO_IdNhanVien'] ?? '-') ?></div>
                                </td>
                                <td>
                                    <div class="fw-semibold"><?= number_format((int) ($warehouse['SoLoDangQuanLy'] ?? 0)) ?></div>
                                    <div class="text-muted small">Thiết kế: <?= number_format((int) ($warehouse['TongSLLo'] ?? 0)) ?></div>
                                </td>
                                <td><?= number_format((int) ($warehouse['TongSoLuongLo'] ?? 0)) ?></td>
                                <td>
                                    <div class="fw-semibold text-primary"><?= number_format((float) ($warehouse['ThanhTien'] ?? 0), 0, ',', '.') ?> đ</div>
                                    <div class="text-muted small">Giá trị tháng: <?= number_format((float) ($warehouse['GiaTriPhieuThang'] ?? 0), 0, ',', '.') ?> đ</div>
                                </td>
                                <td>
                                    <div class="fw-semibold"><?= number_format((int) ($warehouse['TongSoPhieu'] ?? 0)) ?></div>
                                    <div class="text-muted small">Tổng giá trị: <?= number_format((float) ($warehouse['TongGiaTriPhieu'] ?? 0), 0, ',', '.') ?> đ</div>
                                </td>
                                <td>
                                    <?= !empty($warehouse['LanNhapXuatGanNhat']) ? date('d/m/Y', strtotime($warehouse['LanNhapXuatGanNhat'])) : '-' ?>
                                </td>
                                <td>
                                    <span class="badge <?= $utilization > 85 ? 'badge-soft-danger' : ($utilization > 60 ? 'badge-soft-warning' : 'badge-soft-success') ?>">
                                        <?= number_format($utilization, 1) ?>%
                                    </span>
                                    <div class="utilization-bar">
                                        <span style="width: <?= min(100, max(0, $utilization)) ?>%; background: <?= $utilization > 85 ? '#ef4444' : ($utilization > 60 ? '#f59e0b' : '#22c55e') ?>;"></span>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark"><?= htmlspecialchars($warehouse['TrangThai']) ?></span></td>
                                <td class="text-end">
                                    <div class="table-actions d-flex align-items-center gap-2 flex-wrap">
                                        <a class="btn btn-sm btn-outline-secondary" href="?controller=warehouse&action=read&id=<?= urlencode($warehouse['IdKho']) ?>">Chi tiết</a>
                                        <a class="btn btn-sm btn-outline-primary" href="?controller=warehouse&action=edit&id=<?= urlencode($warehouse['IdKho']) ?>">Sửa</a>
                                        <?php if (!empty($outboundDocumentTypes[$typeKey])): ?>
                                            <a class="btn btn-sm btn-outline-success" href="?controller=warehouse_sheet&action=create&warehouse=<?= urlencode($warehouse['IdKho']) ?>&direction=inbound&category=<?= urlencode($typeKey) ?>">
                                                Nhập
                                            </a>
                                            <a class="btn btn-sm btn-outline-danger" href="?controller=warehouse_sheet&action=create&warehouse=<?= urlencode($warehouse['IdKho']) ?>&direction=outbound&category=<?= urlencode($typeKey) ?>">
                                                Xuất
                                            </a>
                                        <?php endif; ?>
                                        <form method="post" action="?controller=warehouse&action=delete" class="d-inline" onsubmit="return confirm('Xác nhận xóa kho này?');">
                                            <input type="hidden" name="IdKho" value="<?= htmlspecialchars($warehouse['IdKho']) ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php if ($form): ?>
        <?php $formDisabled = empty($group['warehouses']); ?>
        <?php $defaultDate = ''; ?>
        <?php $firstWarehouseId = $group['warehouses'][0]['IdKho'] ?? ''; ?>
        <?php
            $inboundTitle = $form['modal_title'] ?? ('Lập phiếu cho ' . $group['label']);
        $outboundTitle = 'Xuất ' . ($group['label'] ?? 'kho');
        ?>
        <div class="modal fade" id="warehouse-entry-modal-<?= htmlspecialchars($typeKey) ?>" tabindex="-1" aria-hidden="true" data-warehouse-entry-modal data-entry-prefix="<?= htmlspecialchars($form['prefix'] ?? 'PN') ?>" data-inbound-title="<?= htmlspecialchars($inboundTitle) ?>" data-outbound-title="<?= htmlspecialchars($outboundTitle) ?>">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" data-modal-title><?= htmlspecialchars($inboundTitle) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <?php if (!empty($form['description'])): ?>
                            <p class="text-muted small mb-4"><?= htmlspecialchars($form['description']) ?></p>
                        <?php endif; ?>

                        <?php if ($formDisabled): ?>
                            <div class="alert alert-warning mb-0">
                                Hiện chưa có kho nào trong nhóm này. Vui lòng thêm kho trước khi lập phiếu.
                            </div>
                        <?php else: ?>
                            <form method="post" action="?controller=warehouse_sheet&action=store" class="row g-4" data-warehouse-entry-form data-products='<?= $productOptionsJson ?>' data-lot-prefix='<?= htmlspecialchars($lotPrefix) ?>' data-lots='<?= $lotOptionsJson ?>'>
                                <input type="hidden" name="quick_entry" value="1">
                                <div class="col-md-6">
                                    <label class="form-label">Loại phiếu <span class="text-danger">*</span></label>
                                    <select name="LoaiPhieu" class="form-select" required data-direction-select>
                                    <option value="<?= htmlspecialchars($form['document_type']) ?>" selected><?= htmlspecialchars($form['document_type']) ?></option>
                                        <?php if (!empty($outboundDocumentTypes[$typeKey])): ?>
                                            <option value="<?= htmlspecialchars($outboundDocumentTypes[$typeKey]) ?>"><?= htmlspecialchars($outboundDocumentTypes[$typeKey]) ?></option>
                                        <?php endif; ?>
                                    </select>
                                    <div class="form-text">Chỉ cập nhật tồn khi được đánh dấu xác nhận.</div>
                                </div>
                                <input type="hidden" name="redirect" value="?controller=warehouse&action=index">
                                <input type="hidden" name="WarehouseType" value="<?= htmlspecialchars($typeKey) ?>">
                                <div class="col-md-6">
                                    <label class="form-label">Loại đối tác <span class="text-danger">*</span></label>
                                    <select name="LoaiDoiTac" class="form-select" required>
                                        <option value="Nội bộ">Nội bộ</option>
                                        <option value="Nhà cung cấp">Nhà cung cấp</option>
                                        <option value="Khách hàng">Khách hàng</option>
                                        <option value="Xưởng khác">Xưởng khác</option>
                                    </select>
                                </div>
                                <input type="hidden" name="IdPhieu" data-field="IdPhieu" data-prefix="<?= htmlspecialchars($form['prefix'] ?? 'PN') ?>">
                                <div class="col-md-6">
                                    <label class="form-label">Kho áp dụng <span class="text-danger">*</span></label>
                                    <select name="IdKho" class="form-select" required data-field="Warehouse">
                                        <?php foreach ($group['warehouses'] as $warehouseOption): ?>
                                            <option value="<?= htmlspecialchars($warehouseOption['IdKho']) ?>" <?= $warehouseOption['IdKho'] === $firstWarehouseId ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($warehouseOption['TenKho']) ?> (<?= htmlspecialchars($warehouseOption['IdKho']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 d-none" data-outbound-only>
                                    <label class="form-label">Chọn lô để xuất <span class="text-danger">*</span></label>
                                    <select name="Quick_IdLo_Existing" class="form-select" data-field="ExistingLot">
                                        <option value="">-- Chọn lô trong kho --</option>
                                    </select>
                                    <div class="small text-muted mt-1" data-existing-lot-info></div>
                                    <div class="form-text">Chỉ hiển thị lô thuộc kho đã chọn.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Đối tác/đơn vị <span class="text-danger">*</span></label>
                                    <input type="text" name="DoiTac" class="form-control" required value="Nội bộ" placeholder="Ví dụ: Nhà cung cấp linh kiện/khách hàng">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Ngày lập phiếu <span class="text-danger">*</span></label>
                                    <input type="date" name="NgayLP" class="form-control" required value="<?= htmlspecialchars($defaultDate) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Ngày xác nhận</label>
                                    <input type="date" name="NgayXN" class="form-control" value="<?= htmlspecialchars($defaultDate) ?>" data-confirm-date>
                                    <div class="form-check mt-1">
                                        <input class="form-check-input" type="checkbox" value="1" id="confirm-now-<?= htmlspecialchars($typeKey) ?>" data-confirm-toggle>
                                        <label class="form-check-label" for="confirm-now-<?= htmlspecialchars($typeKey) ?>">Xác nhận và cập nhật tồn ngay</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tổng giá trị (đ)</label>
                                    <input type="number" name="TongTien" class="form-control" min="0" step="1000" value="0">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Số chứng từ tham chiếu</label>
                                    <input type="text" name="SoThamChieu" class="form-control" placeholder="PO/Đơn yêu cầu liên quan">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Người lập phiếu <span class="text-danger">*</span></label>
                                    <select name="NguoiLap" class="form-select" required>
                                        <?php if (!empty($employees)): ?>
                                            <option value="" disabled selected>-- Chọn nhân viên --</option>
                                            <?php foreach ($employees as $employee): ?>
                                                <option value="<?= htmlspecialchars($employee['IdNhanVien']) ?>">
                                                    <?= htmlspecialchars($employee['HoTen']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="" disabled selected>Chưa có nhân viên khả dụng</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Người xác nhận</label>
                                    <select name="NguoiXacNhan" class="form-select">
                                        <?php if (!empty($employees)): ?>
                                            <option value="">-- Chọn nhân viên --</option>
                                            <?php foreach ($employees as $employee): ?>
                                                <option value="<?= htmlspecialchars($employee['IdNhanVien']) ?>">
                                                    <?= htmlspecialchars($employee['HoTen']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="" selected>Chưa có nhân viên khả dụng</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Lý do/Nội dung nghiệp vụ <span class="text-danger">*</span></label>
                                    <textarea name="LyDo" class="form-control" rows="2" required placeholder="Mô tả lý do nhập/xuất kho"></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Ghi chú bổ sung</label>
                                    <textarea name="GhiChu" class="form-control" rows="2" placeholder="Điều kiện bảo quản, người giao nhận..."></textarea>
                                </div>
                                <div class="col-12"><hr class="text-muted my-2"></div>
                                <div class="col-12">
                                    <h6 class="fw-semibold mb-1"><?= htmlspecialchars($lotInfo['title']) ?></h6>
                                    <p class="text-muted small mb-3"><?= htmlspecialchars($lotInfo['description']) ?></p>
                                </div>
                                <input type="hidden" name="Quick_IdLo" data-field="IdLo" data-prefix="<?= htmlspecialchars($lotPrefix) ?>">
                                <div class="col-md-6" data-inbound-only>
                                    <label class="form-label"><?= htmlspecialchars($formUi['lot_name_label'] ?? 'Tên lô') ?> <span class="text-danger">*</span></label>
                                    <input type="text" name="Quick_TenLo" class="form-control" required placeholder="<?= htmlspecialchars($formUi['lot_name_label'] ?? 'Tên lô nhập kho') ?>" data-inbound-required>
                                </div>
                                <div class="col-md-6" data-inbound-only>
                                    <label class="form-label"><?= htmlspecialchars($formUi['product_label'] ?? 'Sản phẩm/nguyên liệu') ?> <span class="text-danger">*</span></label>
                                    <select name="Quick_IdSanPham" class="form-select" required data-field="Product" data-inbound-required>
                                        <option value="">-- <?= htmlspecialchars($formUi['product_placeholder'] ?? 'Chọn mặt hàng cần nhập') ?> --</option>
                                        <?php foreach ($optionsForType as $product): ?>
                                            <option value="<?= htmlspecialchars($product['id']) ?>" data-unit="<?= htmlspecialchars($product['unit']) ?>">
                                                <?= htmlspecialchars($product['name']) ?> (<?= htmlspecialchars($product['id']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (empty($optionsForType)): ?>
                                        <div class="form-text text-danger">Chưa có danh mục phù hợp, vui lòng thêm sản phẩm trước khi nhập kho.</div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Đơn vị tính</label>
                                    <input type="text" name="Quick_DonViTinh" class="form-control" placeholder="<?= htmlspecialchars($formUi['unit_hint'] ?? 'Tự động theo sản phẩm') ?>" data-field="ProductUnit" readonly>
                                    <div class="form-text" data-outbound-only>Đơn vị tự động lấy theo lô đang chọn.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><?= htmlspecialchars($formUi['quantity_label'] ?? 'Số lượng dự kiến') ?> <span class="text-danger">*</span></label>
                                    <input type="number" name="Quick_SoLuong" class="form-control" min="1" required data-field="Quantity" placeholder="Nhập số lượng theo đơn vị">
                                </div>
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-circle me-1"></i> <?= htmlspecialchars($form['submit_label']) ?>
                                    </button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>
</div>

<div id="warehouse-document-data" data-document-groups='<?= $documentGroupsJson ?>'></div>

<div class="modal fade" id="warehouseDocumentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Danh sách phiếu kho</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <div class="text-muted small" data-modal-description>Danh sách phiếu kho tương ứng.</div>
                        <div class="fw-semibold" data-modal-count></div>
                    </div>
                    <a href="?controller=warehouse_sheet&action=create" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Tạo phiếu mới
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle" id="warehouse-document-table">
                        <thead>
                        <tr>
                            <th>Mã phiếu</th>
                            <th>Loại phiếu</th>
                            <th>Đối tác</th>
                            <th>Kho</th>
                            <th>Sản phẩm</th>
                            <th>Ngày lập</th>
                            <th>Ngày xác nhận</th>
                            <th>Người lập</th>
                            <th>Người xác nhận</th>
                            <th>Tham chiếu</th>
                            <th>Lý do</th>
                            <th>Tổng tiền</th>
                            <th>Mặt hàng</th>
                            <th>Tổng số lượng</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <p class="text-muted text-center my-4 d-none" data-empty-state>Không có phiếu nào phù hợp.</p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var entryModals = document.querySelectorAll('[data-warehouse-entry-modal]');
        entryModals.forEach(function (modalEl) {
            var form = modalEl.querySelector('form[data-warehouse-entry-form]');
            if (!form) {
                return;
            }

            var idInput = form.querySelector('[data-field="IdPhieu"]');
            var prefix = idInput ? (idInput.dataset.prefix || modalEl.getAttribute('data-entry-prefix') || 'PN') : (modalEl.getAttribute('data-entry-prefix') || 'PN');

            var lotInput = form.querySelector('[data-field="IdLo"]');
            var lotPrefix = lotInput ? (lotInput.dataset.prefix || form.getAttribute('data-lot-prefix') || 'LO') : (form.getAttribute('data-lot-prefix') || 'LO');

            var productMap = {};
            try {
                var productData = JSON.parse(form.getAttribute('data-products') || '[]');
                productData.forEach(function (product) {
                    if (product && product.id) {
                        productMap[product.id] = product;
                    }
                });
            } catch (error) {
                productMap = {};
            }

            var lotOptions = [];
            try {
                lotOptions = JSON.parse(form.getAttribute('data-lots') || '[]');
            } catch (error) {
                lotOptions = [];
            }

            var lotByWarehouse = {};
            lotOptions.forEach(function (lot) {
                var warehouseId = lot.warehouse_id || '';
                if (!warehouseId) {
                    return;
                }
                if (!lotByWarehouse[warehouseId]) {
                    lotByWarehouse[warehouseId] = [];
                }
                lotByWarehouse[warehouseId].push({
                    id: lot.id,
                    name: lot.name || lot.id,
                    quantity: Number(lot.quantity || 0),
                    unit: lot.unit || '',
                    productId: lot.product_id || '',
                    productName: lot.product_name || '',
                });
            });

            var productSelect = form.querySelector('[data-field="Product"]');
            var productUnitInput = form.querySelector('[data-field="ProductUnit"]');
            var warehouseSelect = form.querySelector('[data-field="Warehouse"]');
            var existingLotSelect = form.querySelector('[data-field="ExistingLot"]');
            var existingLotInfo = form.querySelector('[data-existing-lot-info]');

            var updateProductUnit = function () {
                if (!productSelect || !productUnitInput) {
                    return;
                }

                var selectedOption = productSelect.options[productSelect.selectedIndex] || null;
                var unit = '';

                if (selectedOption && selectedOption.dataset.unit) {
                    unit = selectedOption.dataset.unit;
                }

                if (!unit && productMap[productSelect.value]) {
                    unit = productMap[productSelect.value].unit || '';
                }

                productUnitInput.value = unit;
            };

            if (productSelect) {
                productSelect.addEventListener('change', updateProductUnit);
            }

            var fillExistingLots = function () {
                if (!existingLotSelect) {
                    return;
                }
                var warehouseId = warehouseSelect ? warehouseSelect.value : '';
                var options = lotByWarehouse[warehouseId] || [];
                existingLotSelect.innerHTML = '<option value=\"\">-- Chọn lô trong kho --</option>';
                options.forEach(function (lot) {
                    var opt = document.createElement('option');
                    opt.value = lot.id;
                    opt.textContent = lot.name + ' (' + lot.id + ') - Tồn: ' + (lot.quantity || 0);
                    opt.dataset.unit = lot.unit || '';
                    opt.dataset.quantity = lot.quantity || 0;
                    opt.dataset.productId = lot.productId || '';
                    opt.dataset.productName = lot.productName || '';
                    existingLotSelect.appendChild(opt);
                });
            };

            var updateExistingLotInfo = function () {
                if (!existingLotSelect || !existingLotInfo) {
                    return;
                }
                var selected = existingLotSelect.options[existingLotSelect.selectedIndex] || null;
                if (!selected || !selected.value) {
                    existingLotInfo.textContent = 'Chọn lô để xem tồn hiện tại.';
                    return;
                }
                var qty = selected.dataset.quantity || 0;
                var unit = selected.dataset.unit || '';
                var productName = selected.dataset.productName || '';
                existingLotInfo.textContent = 'Tồn khả dụng: ' + qty + (unit ? (' ' + unit) : '') + (productName ? (' | Sản phẩm: ' + productName) : '');
            };

            if (warehouseSelect) {
                warehouseSelect.addEventListener('change', function () {
                    fillExistingLots();
                    updateExistingLotInfo();
                });
            }

            if (existingLotSelect) {
                existingLotSelect.addEventListener('change', function () {
                    var selected = existingLotSelect.options[existingLotSelect.selectedIndex] || null;
                    if (productUnitInput && selected && selected.dataset.unit) {
                        productUnitInput.value = selected.dataset.unit;
                    }
                    if (selected && selected.dataset.quantity && selected.dataset.quantity !== '') {
                        var available = Number(selected.dataset.quantity);
                        if (!Number.isNaN(available) && quantityInput) {
                            quantityInput.value = Math.min(available, Number(quantityInput.value || available) || available);
                        }
                    }
                    updateExistingLotInfo();
                });
            }

            var buildId = function (pre) {
                var now = new Date();
                var pad = function (value) {
                    return value.toString().padStart(2, '0');
                };

                return [
                    pre,
                    now.getFullYear(),
                    pad(now.getMonth() + 1),
                    pad(now.getDate()),
                    pad(now.getHours()),
                    pad(now.getMinutes()),
                    pad(now.getSeconds())
                ].join('');
            };

            var updateId = function () {
                if (!idInput) {
                    return;
                }
                idInput.value = buildId(prefix);
            };

            var buildLotId = function (pre) {
                var now = new Date();
                var pad = function (value) {
                    return value.toString().padStart(2, '0');
                };

                return [
                    pre,
                    now.getFullYear(),
                    pad(now.getMonth() + 1),
                    pad(now.getDate()),
                    pad(now.getHours()),
                    pad(now.getMinutes()),
                    pad(now.getSeconds())
                ].join('');
            };

            var updateLotId = function () {
                if (!lotInput) {
                    return;
                }
                lotInput.value = buildLotId(lotPrefix);
            };

            updateLotId();

            updateId();

            var dateInput = form.querySelector('input[name="NgayLP"]');
            var confirmInput = form.querySelector('input[name="NgayXN"]');
            var confirmToggle = form.querySelector('[data-confirm-toggle]');
            var directionSelect = form.querySelector('[data-direction-select]');
            var inboundFields = form.querySelectorAll('[data-inbound-only]');
            var outboundFields = form.querySelectorAll('[data-outbound-only]');
            var inboundRequired = form.querySelectorAll('[data-inbound-required]');
            var modalId = modalEl.getAttribute('id');
            var directionTriggers = document.querySelectorAll('[data-direction-target][data-bs-target="#' + modalId + '"]');
            var modalTitle = modalEl.querySelector('[data-modal-title]');
            var inboundTitle = modalEl.getAttribute('data-inbound-title') || '';
            var outboundTitle = modalEl.getAttribute('data-outbound-title') || inboundTitle;

            if (confirmToggle && confirmInput) {
                confirmToggle.addEventListener('change', function () {
                    if (confirmToggle.checked) {
                        var now = new Date();
                        var yyyy = now.getFullYear();
                        var mm = String(now.getMonth() + 1).padStart(2, '0');
                        var dd = String(now.getDate()).padStart(2, '0');
                        confirmInput.value = `${yyyy}-${mm}-${dd}`;
                    } else {
                        confirmInput.value = '';
                    }
                });
            }

            var toggleDirection = function () {
                var isOutbound = false;
                if (directionSelect && directionSelect.value) {
                    var normalized = directionSelect.value.toLowerCase();
                    isOutbound = normalized.indexOf('xuất') !== -1 || normalized.indexOf('xuat') !== -1;
                }

                inboundFields.forEach(function (el) {
                    el.classList.toggle('d-none', isOutbound);
                });
                outboundFields.forEach(function (el) {
                    el.classList.toggle('d-none', !isOutbound);
                });
                inboundRequired.forEach(function (el) {
                    if (isOutbound) {
                        el.removeAttribute('required');
                    } else {
                        el.setAttribute('required', 'required');
                    }
                });
                if (existingLotSelect) {
                    if (isOutbound) {
                        existingLotSelect.setAttribute('required', 'required');
                    } else {
                        existingLotSelect.removeAttribute('required');
                        existingLotSelect.value = '';
                    }
                    updateExistingLotInfo();
                }
                if (productUnitInput && isOutbound && existingLotSelect) {
                    var selected = existingLotSelect.options[existingLotSelect.selectedIndex] || null;
                    if (selected && selected.dataset.unit) {
                        productUnitInput.value = selected.dataset.unit;
                    }
                }

                if (modalTitle) {
                    modalTitle.textContent = isOutbound ? outboundTitle : inboundTitle;
                }
            };

            modalEl.addEventListener('show.bs.modal', function () {
                updateId();
                updateLotId();
                updateProductUnit();
                fillExistingLots();
                toggleDirection();
                updateExistingLotInfo();
            });

            if (directionSelect) {
                directionSelect.addEventListener('change', toggleDirection);
            }

            if (directionTriggers.length) {
                directionTriggers.forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        var target = btn.getAttribute('data-direction-target') || '';
                        if (!directionSelect) {
                            return;
                        }
                        if (target === 'outbound' && existingLotSelect) {
                            var outboundOption = directionSelect.querySelector('option[value*="xuất"], option[value*="xuat"]');
                            if (outboundOption) {
                                directionSelect.value = outboundOption.value;
                            }
                        } else {
                            directionSelect.selectedIndex = 0;
                        }
                        toggleDirection();
                    });
                });
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var dataElement = document.getElementById('warehouse-document-data');
        if (!dataElement) {
            return;
        }

        var groups;
        try {
            groups = JSON.parse(dataElement.dataset.documentGroups || '{}');
        } catch (error) {
            groups = {};
        }

        var modal = document.getElementById('warehouseDocumentModal');
        if (!modal) {
            return;
        }

        var modalTitle = modal.querySelector('.modal-title');
        var modalDescription = modal.querySelector('[data-modal-description]');
        var modalCount = modal.querySelector('[data-modal-count]');
        var tableBody = modal.querySelector('tbody');
        var emptyState = modal.querySelector('[data-empty-state]');
        var triggers = Array.prototype.slice.call(document.querySelectorAll('[data-document-group-trigger]'));

        function setActiveTrigger(groupKey) {
            triggers.forEach(function (trigger) {
                if (!groupKey) {
                    trigger.classList.remove('active');
                    return;
                }

                var triggerKey = trigger.getAttribute('data-document-group-trigger');
                if (triggerKey === groupKey) {
                    trigger.classList.add('active');
                } else {
                    trigger.classList.remove('active');
                }
            });
        }

        function formatDate(value) {
            if (!value) {
                return '-';
            }
            var date = new Date(value);
            if (Number.isNaN(date.getTime())) {
                return value;
            }
            return date.toLocaleDateString('vi-VN');
        }

        function formatNumber(value) {
            var number = Number(value);
            if (Number.isNaN(number)) {
                return '0';
            }
            return number.toLocaleString('vi-VN');
        }

        function buildActionCell(id) {
            var container = document.createElement('div');
            container.className = 'd-flex justify-content-end gap-2';

            var viewLink = document.createElement('a');
            viewLink.className = 'btn btn-sm btn-outline-secondary';
            viewLink.href = '?controller=warehouse_sheet&action=read&id=' + encodeURIComponent(id);
            viewLink.textContent = 'Xem';
            container.appendChild(viewLink);

            var editLink = document.createElement('a');
            editLink.className = 'btn btn-sm btn-outline-primary';
            editLink.href = '?controller=warehouse_sheet&action=edit&id=' + encodeURIComponent(id);
            editLink.textContent = 'Sửa';
            container.appendChild(editLink);

            var deleteForm = document.createElement('form');
            deleteForm.method = 'post';
            deleteForm.action = '?controller=warehouse_sheet&action=delete';
            deleteForm.className = 'd-inline';

            var idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'IdPhieu';
            idInput.value = id;
            deleteForm.appendChild(idInput);

            var deleteButton = document.createElement('button');
            deleteButton.type = 'submit';
            deleteButton.className = 'btn btn-sm btn-outline-danger';
            deleteButton.textContent = 'Xóa';
            deleteButton.addEventListener('click', function (event) {
                if (!confirm('Xác nhận xóa phiếu kho này?')) {
                    event.preventDefault();
                }
            });

            deleteForm.appendChild(deleteButton);
            container.appendChild(deleteForm);

            return container;
        }

        triggers.forEach(function (trigger) {
            trigger.addEventListener('click', function () {
                var groupKey = this.getAttribute('data-document-group-trigger');
                renderDocuments(groupKey);
            });
        });

        function renderDocuments(groupKey) {
            setActiveTrigger(groupKey);

            var group = groups[groupKey];
            if (!group) {
                tableBody.innerHTML = '';
                emptyState.classList.remove('d-none');
                modalDescription.textContent = 'Không tìm thấy dữ liệu phiếu.';
                modalCount.textContent = '';
                modalTitle.textContent = 'Danh sách phiếu kho';
                setActiveTrigger(null);
                return;
            }

            modalTitle.textContent = group.title || 'Danh sách phiếu kho';
            modalDescription.textContent = group.description || '';
            modalCount.textContent = 'Tổng cộng: ' + formatNumber(group.count || 0) + ' phiếu';

            var documents = Array.isArray(group.documents) ? group.documents : [];
            tableBody.innerHTML = '';

            if (documents.length === 0) {
                emptyState.classList.remove('d-none');
                return;
            }

            emptyState.classList.add('d-none');

            documents.forEach(function (doc) {
                var row = document.createElement('tr');

                var idCell = document.createElement('td');
                idCell.className = 'fw-semibold';
                idCell.textContent = doc.IdPhieu || '-';
                row.appendChild(idCell);

                var typeCell = document.createElement('td');
                typeCell.textContent = doc.LoaiPhieu || '-';
                row.appendChild(typeCell);

                var partnerCell = document.createElement('td');
                var partnerName = doc.DoiTac || '-';
                var partnerType = doc.LoaiDoiTac || '';
                partnerCell.innerHTML = '<div class="fw-semibold mb-0">' + partnerName + '</div><div class="text-muted small">' + partnerType + '</div>';
                row.appendChild(partnerCell);

                var warehouseCell = document.createElement('td');
                warehouseCell.textContent = doc.TenKho || doc.IdKho || '-';
                row.appendChild(warehouseCell);

                var productCell = document.createElement('td');
                productCell.innerHTML = doc.DanhSachSanPham ? ('<div class="text-truncate" style="max-width: 260px;">' + doc.DanhSachSanPham + '</div>') : '-';
                row.appendChild(productCell);

                var createdCell = document.createElement('td');
                createdCell.textContent = formatDate(doc.NgayLP);
                row.appendChild(createdCell);

                var confirmedCell = document.createElement('td');
                confirmedCell.textContent = formatDate(doc.NgayXN);
                row.appendChild(confirmedCell);

                var creatorCell = document.createElement('td');
                creatorCell.textContent = doc.NguoiLap || doc.NHAN_VIENIdNhanVien || '-';
                row.appendChild(creatorCell);

                var confirmerCell = document.createElement('td');
                confirmerCell.textContent = doc.NguoiXacNhan || doc.NHAN_VIENIdNhanVien2 || '-';
                row.appendChild(confirmerCell);

                var refCell = document.createElement('td');
                refCell.textContent = doc.SoThamChieu || '-';
                row.appendChild(refCell);

                var reasonCell = document.createElement('td');
                reasonCell.textContent = doc.LyDo || '-';
                row.appendChild(reasonCell);

                var valueCell = document.createElement('td');
                valueCell.className = 'text-primary fw-semibold';
                valueCell.textContent = formatNumber(doc.TongTien || 0) + ' đ';
                row.appendChild(valueCell);

                var quantityCell = document.createElement('td');
                quantityCell.textContent = formatNumber(doc.TongMatHang || 0);
                row.appendChild(quantityCell);

                var receivedCell = document.createElement('td');
                receivedCell.textContent = formatNumber(doc.TongSoLuong || 0);
                row.appendChild(receivedCell);

                var actionsCell = document.createElement('td');
                actionsCell.className = 'text-end';
                actionsCell.appendChild(buildActionCell(doc.IdPhieu || ''));
                row.appendChild(actionsCell);

                tableBody.appendChild(row);
            });
        }

        modal.addEventListener('shown.bs.modal', function (event) {
            var button = event.relatedTarget;
            if (!button) {
                return;
            }

            var key = button.getAttribute('data-document-group-trigger');
            renderDocuments(key);
        });

        modal.addEventListener('hidden.bs.modal', function () {
            setActiveTrigger(null);
        });
    });
</script>
