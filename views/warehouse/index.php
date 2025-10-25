<?php
$summary = $summary ?? [];
$warehouses = $warehouses ?? [];
$documentGroups = $documentGroups ?? [];
$documentGroupsJson = htmlspecialchars(json_encode($documentGroups, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}', ENT_QUOTES, 'UTF-8');
?>

<style>
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

    .metric-trigger .metric-card {
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .metric-trigger:hover .metric-card {
        transform: translateY(-3px);
        box-shadow: 0 0.75rem 1.5rem rgba(15, 23, 42, 0.12);
    }

    .metric-trigger:focus-visible .metric-card,
    .metric-trigger.active .metric-card {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        transform: translateY(-2px);
    }
</style>

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
            <button type="button" class="metric-trigger" data-document-group-trigger="all" data-bs-toggle="modal" data-bs-target="#warehouseDocumentModal">
                <div class="card metric-card">
                    <div class="icon-wrap bg-primary bg-opacity-10 text-primary"><i class="bi bi-archive"></i></div>
                    <div>
                        <div class="text-muted text-uppercase small">Tổng số kho</div>
                        <div class="fs-3 fw-bold"><?= number_format($summary['total_warehouses']) ?></div>
                        <div class="small text-success">Đang sử dụng: <?= number_format($summary['active_warehouses']) ?></div>
                        <div class="small text-primary fw-semibold">Phiếu kho: <?= number_format($documentGroups['all']['count'] ?? 0) ?></div>
                    </div>
                </div>
            </button>
        </div>
        <div class="col-xl-3 col-sm-6">
            <button type="button" class="metric-trigger" data-document-group-trigger="inbound" data-bs-toggle="modal" data-bs-target="#warehouseDocumentModal">
                <div class="card metric-card">
                    <div class="icon-wrap bg-info bg-opacity-10 text-info"><i class="bi bi-box-seam"></i></div>
                    <div>
                        <div class="text-muted text-uppercase small">Sức chứa hệ thống</div>
                        <div class="fs-3 fw-bold"><?= number_format($summary['total_capacity']) ?></div>
                        <div class="small text-muted">Kho tạm ngưng: <?= number_format($summary['inactive_warehouses']) ?></div>
                        <div class="small text-info fw-semibold">Phiếu nhập: <?= number_format($documentGroups['inbound']['count'] ?? 0) ?></div>
                    </div>
                </div>
            </button>
        </div>
        <div class="col-xl-3 col-sm-6">
            <button type="button" class="metric-trigger" data-document-group-trigger="valuable" data-bs-toggle="modal" data-bs-target="#warehouseDocumentModal">
                <div class="card metric-card">
                    <div class="icon-wrap bg-warning bg-opacity-10 text-warning"><i class="bi bi-graph-up"></i></div>
                    <div>
                        <div class="text-muted text-uppercase small">Giá trị hàng tồn</div>
                        <div class="fs-3 fw-bold"><?= number_format($summary['total_inventory_value'], 0, ',', '.') ?> đ</div>
                        <div class="small text-muted">Tổng lô: <?= number_format($summary['total_lots']) ?></div>
                        <div class="small text-warning fw-semibold">Phiếu giá trị cao: <?= number_format($documentGroups['valuable']['count'] ?? 0) ?></div>
                    </div>
                </div>
            </button>
        </div>
        <div class="col-xl-3 col-sm-6">
            <button type="button" class="metric-trigger" data-document-group-trigger="outbound" data-bs-toggle="modal" data-bs-target="#warehouseDocumentModal">
                <div class="card metric-card">
                    <div class="icon-wrap bg-success bg-opacity-10 text-success"><i class="bi bi-layers"></i></div>
                    <div>
                        <div class="text-muted text-uppercase small">Tổng số lượng tồn</div>
                        <div class="fs-3 fw-bold"><?= number_format($summary['total_quantity']) ?></div>
                        <div class="small text-success fw-semibold">Phiếu xuất: <?= number_format($documentGroups['outbound']['count'] ?? 0) ?></div>
                    </div>
                </div>
            </button>
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
                        <div class="table-actions d-flex align-items-center gap-2 flex-wrap">
                            <a class="btn btn-sm btn-outline-secondary" href="?controller=warehouse&action=read&id=<?= urlencode($warehouse['IdKho']) ?>">Chi tiết</a>
                            <a class="btn btn-sm btn-outline-primary" href="?controller=warehouse&action=edit&id=<?= urlencode($warehouse['IdKho']) ?>">Sửa</a>
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
                            <th>Kho</th>
                            <th>Ngày lập</th>
                            <th>Ngày xác nhận</th>
                            <th>Người lập</th>
                            <th>Người xác nhận</th>
                            <th>Tổng tiền</th>
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

                var warehouseCell = document.createElement('td');
                warehouseCell.textContent = doc.TenKho || doc.IdKho || '-';
                row.appendChild(warehouseCell);

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

                var valueCell = document.createElement('td');
                valueCell.className = 'text-primary fw-semibold';
                valueCell.textContent = formatNumber(doc.TongTien || 0) + ' đ';
                row.appendChild(valueCell);

                var quantityCell = document.createElement('td');
                quantityCell.textContent = formatNumber(doc.TongSoLuong || doc.TongMatHang || 0);
                row.appendChild(quantityCell);

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
