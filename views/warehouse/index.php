
 <?php
 $summary = $summary ?? [];
 $warehouses = $warehouses ?? [];
 $documentGroups = $documentGroups ?? [];
$warehouseGroups = $warehouseGroups ?? [];
$warehouseEntryForms = $warehouseEntryForms ?? [];
$employees = $employees ?? [];
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
 
    .warehouse-section  .warehouse-section {
        margin-top: 3rem;
    }

    .warehouse-section .section-header {
        border-bottom: 1px solid rgba(15, 23, 42, 0.08);
    }

    .warehouse-section .section-header h4 {
        margin-bottom: 0.25rem;
    }

    .warehouse-section .section-header p {
        margin-bottom: 0;
    }

    .warehouse-stats-card .stat-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .warehouse-stats-card .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
    }
</style>
 
 <div class="d-flex justify-content-between align-items-center mb-4">
     <div>
         <h3 class="fw-bold mb-1">Quản lý kho</h3>
        <p class="text-muted mb-0">Theo dõi kho nguyên liệu, thành phẩm và kho xử lý lỗi cùng phiếu nhập tương ứng.</p>
     </div>
    <a href="?controller=warehouse&action=create" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Thêm kho mới</a>
 </div>
 
 <?php if (!empty($summary)): ?>
     <div class="row g-3 mb-4">
         <div class="col-xl-3 col-sm-6">
             <button type="button" class="metric-trigger" data-document-group-trigger="all" data-bs-toggle="modal" data-bs-target="#warehouseDocumentModal">
                 <div class="card metric-card">
                     <div class="icon-wrap bg-primary bg-opacity-10 text-primary"><i class="bi bi-archive"></i></div>
                     <div>
                         <div class="text-muted text-uppercase small">Tổng số kho</div>
                        <div class="fs-3 fw-bold"><?= number_format($summary['total_warehouses'] ?? 0) ?></div>
                        <div class="small text-success">Đang sử dụng: <?= number_format($summary['active_warehouses'] ?? 0) ?></div>
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
                        <div class="fs-3 fw-bold"><?= number_format($summary['total_capacity'] ?? 0) ?></div>
                        <div class="small text-muted">Kho tạm ngưng: <?= number_format(($summary['inactive_warehouses'] ?? 0)) ?></div>
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
                        <div class="fs-3 fw-bold"><?= number_format($summary['total_inventory_value'] ?? 0, 0, ',', '.') ?> đ</div>
                        <div class="small text-muted">Tổng lô: <?= number_format($summary['total_lots'] ?? 0) ?></div>
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
                        <div class="fs-3 fw-bold"><?= number_format($summary['total_quantity'] ?? 0) ?></div>
                         <div class="small text-success fw-semibold">Phiếu xuất: <?= number_format($documentGroups['outbound']['count'] ?? 0) ?></div>
                     </div>
                 </div>
             </button>
         </div>
     </div>
 <?php endif; ?>
 
<?php if (!empty($summary['by_type'])): ?>
    <div class="row g-3 mb-5">
        <?php foreach ($summary['by_type'] as $typeKey => $typeSummary): ?>
            <?php $group = $warehouseGroups[$typeKey] ?? ['label' => $typeSummary['label'] ?? '', 'description' => '', 'warehouses' => [], 'statistics' => $typeSummary]; ?>
            <?php $form = $warehouseEntryForms[$typeKey] ?? null; ?>
            <?php $hasWarehouses = !empty($group['warehouses']); ?>
            <?php $canCreateDocument = $form && $hasWarehouses && !empty($employees); ?>
            <div class="col-xl-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm warehouse-stats-card">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3 gap-3">
                            <div>
                                <h5 class="fw-semibold mb-1"><?= htmlspecialchars($group['label'] ?? ($typeSummary['label'] ?? '')) ?></h5>
                                <?php if (!empty($group['description'])): ?>
                                    <p class="text-muted small mb-0"><?= htmlspecialchars($group['description']) ?></p>
                                <?php endif; ?>
                            </div>
                            <?php if ($form): ?>
                                <button type="button" class="btn btn-sm btn-outline-primary<?= $canCreateDocument ? '' : ' disabled' ?>" <?php if ($canCreateDocument): ?>data-bs-toggle="modal" data-bs-target="#warehouse-entry-modal-<?= htmlspecialchars($typeKey) ?>"<?php endif; ?>>
                                    <i class="bi bi-plus-lg me-1"></i><?= htmlspecialchars($form['submit_label']) ?>
                                </button>
                            <?php endif; ?>
                        </div>
                        <div class="row g-2 small flex-grow-1">
                            <div class="col-6">
                                <div class="stat-label text-muted fw-semibold">Số kho</div>
                                <div class="stat-value"><?= number_format($typeSummary['count'] ?? 0) ?></div>
                                <div class="text-muted">Đang hoạt động: <?= number_format($typeSummary['active_warehouses'] ?? 0) ?></div>
                            </div>
                            <div class="col-6">
                                <div class="stat-label text-muted fw-semibold">Giá trị tồn</div>
                                <div class="fs-5 fw-semibold text-primary"><?= number_format($typeSummary['total_inventory_value'] ?? 0, 0, ',', '.') ?> đ</div>
                                <div class="text-muted">Sức chứa: <?= number_format($typeSummary['total_capacity'] ?? 0) ?></div>
                            </div>
                            <div class="col-6">
                                <div class="stat-label text-muted fw-semibold">Tổng lô</div>
                                <div class="fs-5 fw-semibold"><?= number_format($typeSummary['total_lots'] ?? 0) ?></div>
                            </div>
                            <div class="col-6">
                                <div class="stat-label text-muted fw-semibold">Tổng lượng</div>
                                <div class="fs-5 fw-semibold"><?= number_format($typeSummary['total_quantity'] ?? 0) ?></div>
                            </div>
                         </div>

                        <?php if ($form && !$canCreateDocument): ?>
                            <div class="alert alert-warning mt-3 py-2 px-3 small mb-0">
                                <?php if (!$hasWarehouses): ?>
                                    Chưa có kho thuộc nhóm này để lập phiếu.
                                <?php elseif (empty($employees)): ?>
                                    Cần có nhân viên kho hoạt động để lập phiếu.
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php foreach ($warehouseGroups as $typeKey => $group): ?>
    <?php $form = $warehouseEntryForms[$typeKey] ?? null; ?>
    <section class="warehouse-section" id="warehouse-group-<?= htmlspecialchars($typeKey) ?>">
        <div class="section-header d-flex justify-content-between align-items-start mb-3">
            <div>
                <h4 class="fw-semibold mb-1"><?= htmlspecialchars($group['label']) ?></h4>
                <?php if (!empty($group['description'])): ?>
                    <p class="text-muted small mb-0"><?= htmlspecialchars($group['description']) ?></p>
                <?php endif; ?>
            </div>
            <?php if ($form): ?>
                <?php $hasWarehouses = !empty($group['warehouses']); ?>
                <?php $canCreateDocument = $hasWarehouses && !empty($employees); ?>
                <button type="button" class="btn btn-success<?= $canCreateDocument ? '' : ' disabled' ?>" <?php if ($canCreateDocument): ?>data-bs-toggle="modal" data-bs-target="#warehouse-entry-modal-<?= htmlspecialchars($typeKey) ?>"<?php endif; ?>>
                    <i class="bi bi-plus-lg me-2"></i><?= htmlspecialchars($form['submit_label']) ?>
                </button>
            <?php endif; ?>
        </div>

        <div class="card border-0 shadow-sm">
            <?php if (empty($group['warehouses'])): ?>
                <div class="card-body">
                    <div class="alert alert-light border mb-0">
                        Chưa có kho nào thuộc nhóm "<?= htmlspecialchars($group['label']) ?>". Vui lòng thêm kho mới để bắt đầu quản lý.
                    </div>
                </div>
             <?php else: ?>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
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
             <?php endif; ?>

        </div>
    </section>

    <?php if ($form): ?>
        <?php $formDisabled = empty($group['warehouses']) || empty($employees); ?>
        <?php $defaultDate = date('Y-m-d'); ?>
        <?php $firstWarehouseId = $group['warehouses'][0]['IdKho'] ?? ''; ?>
        <div class="modal fade" id="warehouse-entry-modal-<?= htmlspecialchars($typeKey) ?>" tabindex="-1" aria-hidden="true" data-warehouse-entry-modal data-entry-prefix="<?= htmlspecialchars($form['prefix'] ?? 'PN') ?>">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?= htmlspecialchars($form['modal_title'] ?? ('Lập phiếu cho ' . $group['label'])) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <?php if (!empty($form['description'])): ?>
                            <p class="text-muted small mb-4"><?= htmlspecialchars($form['description']) ?></p>
                        <?php endif; ?>

                        <?php if ($formDisabled): ?>
                            <div class="alert alert-warning mb-0">
                                <?php if (empty($group['warehouses'])): ?>
                                    Hiện chưa có kho nào trong nhóm này. Vui lòng thêm kho trước khi lập phiếu.
                                <?php elseif (empty($employees)): ?>
                                    Chưa có nhân viên kho hoạt động để lập phiếu nhập.
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <form method="post" action="?controller=warehouse_sheet&action=store" class="row g-3" data-warehouse-entry-form>
                                <input type="hidden" name="LoaiPhieu" value="<?= htmlspecialchars($form['document_type']) ?>">
                                <div class="col-md-6">
                                    <label class="form-label">Loại phiếu</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($form['document_type']) ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Mã phiếu <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="IdPhieu" class="form-control" required placeholder="VD: PN20231101010101" data-field="IdPhieu" data-prefix="<?= htmlspecialchars($form['prefix'] ?? 'PN') ?>">
                                        <button type="button" class="btn btn-outline-secondary" data-action="regenerate-id"><i class="bi bi-arrow-repeat me-1"></i>Tạo mã mới</button>
                                    </div>
                                    <div class="form-text">Hệ thống sẽ tự sinh mã theo thời gian khi mở biểu mẫu.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Kho áp dụng <span class="text-danger">*</span></label>
                                    <select name="IdKho" class="form-select" required>
                                        <?php foreach ($group['warehouses'] as $warehouseOption): ?>
                                            <option value="<?= htmlspecialchars($warehouseOption['IdKho']) ?>" <?= $warehouseOption['IdKho'] === $firstWarehouseId ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($warehouseOption['TenKho']) ?> (<?= htmlspecialchars($warehouseOption['IdKho']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Ngày lập phiếu <span class="text-danger">*</span></label>
                                    <input type="date" name="NgayLP" class="form-control" required value="<?= htmlspecialchars($defaultDate) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Ngày xác nhận</label>
                                    <input type="date" name="NgayXN" class="form-control" value="<?= htmlspecialchars($defaultDate) ?>" data-synced="1">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tổng giá trị (đ)</label>
                                    <input type="number" name="TongTien" class="form-control" min="0" step="1000" value="0">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Người lập phiếu <span class="text-danger">*</span></label>
                                    <select name="NguoiLap" class="form-select" required>
                                        <option value="" disabled selected>-- Chọn nhân viên --</option>
                                        <?php foreach ($employees as $employee): ?>
                                            <option value="<?= htmlspecialchars($employee['IdNhanVien']) ?>">
                                                <?= htmlspecialchars($employee['HoTen']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Người xác nhận</label>
                                    <select name="NguoiXacNhan" class="form-select">
                                        <option value="">-- Chọn nhân viên --</option>
                                        <?php foreach ($employees as $employee): ?>
                                            <option value="<?= htmlspecialchars($employee['IdNhanVien']) ?>">
                                                <?= htmlspecialchars($employee['HoTen']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
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
        var entryModals = document.querySelectorAll('[data-warehouse-entry-modal]');
        entryModals.forEach(function (modalEl) {
            var form = modalEl.querySelector('form[data-warehouse-entry-form]');
            if (!form) {
                return;
            }

            var idInput = form.querySelector('[data-field="IdPhieu"]');
            var prefix = idInput ? (idInput.dataset.prefix || modalEl.getAttribute('data-entry-prefix') || 'PN') : (modalEl.getAttribute('data-entry-prefix') || 'PN');
            var regenerateButton = form.querySelector('[data-action="regenerate-id"]');

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

            modalEl.addEventListener('show.bs.modal', function () {
                updateId();
            });

            if (regenerateButton) {
                regenerateButton.addEventListener('click', function () {
                    updateId();
                });
            }

            var dateInput = form.querySelector('input[name="NgayLP"]');
            var confirmInput = form.querySelector('input[name="NgayXN"]');

            if (dateInput && confirmInput) {
                var syncDates = function () {
                    if (!confirmInput.value || confirmInput.dataset.synced === '1') {
                        confirmInput.value = dateInput.value;
                        confirmInput.dataset.synced = '1';
                    }
                };

                syncDates();

                dateInput.addEventListener('change', syncDates);
                confirmInput.addEventListener('input', function () {
                    confirmInput.dataset.synced = '0';
                });
            }
        });
    });
</script>

 <script>
     document.addEventListener('DOMContentLoaded', function ()) {
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
        }