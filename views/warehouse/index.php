<?php
$summary = $summary ?? [];
$warehouses = $warehouses ?? [];
$documentGroups = $documentGroups ?? [];
$warehouseGroups = $warehouseGroups ?? [];
$documentGroupsJson = htmlspecialchars(json_encode($documentGroups, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}', ENT_QUOTES, 'UTF-8');
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
                <?php $hasWarehouses = !empty($group['warehouses']); ?>
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
                        <?php if (!$hasWarehouses): ?>
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
    <section class="warehouse-section" id="warehouse-group-<?= htmlspecialchars($typeKey) ?>">
        <div class="group-card">
            <div class="group-header">
                <div>
                    <h4 class="fw-semibold mb-1"><?= htmlspecialchars($group['label']) ?></h4>
                    <?php if (!empty($group['description'])): ?>
                        <p class="text-muted small mb-0"><?= htmlspecialchars($group['description']) ?></p>
                    <?php endif; ?>
                </div>
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
