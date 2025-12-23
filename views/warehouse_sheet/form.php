<?php
$document = $document ?? [];
$warehouses = $warehouses ?? [];
$employees = $employees ?? [];
$types = $types ?? [];
$actionUrl = $actionUrl ?? '?controller=warehouse_sheet&action=store';
$isEdit = $isEdit ?? false;
$products = $products ?? [];
$lots = $lots ?? [];
$approvers = $approvers ?? [];
$approversJson = htmlspecialchars(json_encode($approvers, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}', ENT_QUOTES, 'UTF-8');
$details = $details ?? [];
$productsJson = htmlspecialchars(json_encode($products, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '[]', ENT_QUOTES, 'UTF-8');
$lotsJson = htmlspecialchars(json_encode($lots, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '[]', ENT_QUOTES, 'UTF-8');
$documentCode = htmlspecialchars($document['IdPhieu'] ?? '', ENT_QUOTES, 'UTF-8');
$currentWarehouseId = $document['IdKho'] ?? '';
$currentApprover = $approvers[$currentWarehouseId] ?? null;
?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="post" action="<?= $actionUrl ?>" class="row g-4">
            <input type="hidden" name="IdPhieu" value="<?= $documentCode ?>">
            <!-- Mã phiếu được giữ dưới dạng hidden để không hiển thị trên giao diện -->

            <div class="col-lg-7">
                <div class="border rounded-3 p-3 h-100">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-info-subtle text-info border">Thông tin phiếu</span>
                        <span class="ms-2 text-muted small">Chọn loại phiếu và đối tác tương ứng.</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Loại phiếu <span class="text-danger">*</span></label>
                            <input type="text" name="LoaiPhieu" class="form-control" list="sheet-types" value="<?= htmlspecialchars($document['LoaiPhieu'] ?? '') ?>" required placeholder="Phiếu nhập nguyên liệu">
                            <datalist id="sheet-types">
                                <?php foreach ($types as $type): ?>
                                    <option value="<?= htmlspecialchars($type) ?>"></option>
                                <?php endforeach; ?>
                            </datalist>
                            <div class="form-text">Ví dụ: Phiếu nhập nguyên liệu, Phiếu xuất thành phẩm, Phiếu nhập xử lý lỗi...</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Loại đối tác <span class="text-danger">*</span></label>
                            <?php $partnerType = $document['LoaiDoiTac'] ?? 'Nội bộ'; ?>
                            <select name="LoaiDoiTac" class="form-select" required>
                                <?php foreach (['Nội bộ', 'Nhà cung cấp', 'Khách hàng', 'Xưởng khác'] as $option): ?>
                                    <option value="<?= htmlspecialchars($option) ?>" <?= $partnerType === $option ? 'selected' : '' ?>><?= htmlspecialchars($option) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Đối tác/đơn vị <span class="text-danger">*</span></label>
                            <input type="text" name="DoiTac" class="form-control" required value="<?= htmlspecialchars($document['DoiTac'] ?? 'Nội bộ') ?>" placeholder="Ví dụ: Nhà cung cấp switch Lotus">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Số chứng từ tham chiếu</label>
                            <input type="text" name="SoThamChieu" class="form-control" value="<?= htmlspecialchars($document['SoThamChieu'] ?? '') ?>" placeholder="PO/PR/Đơn hàng liên quan">
                            <div class="form-text">Nhập số PO/ĐH hoặc chứng từ liên quan để truy vết.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="border rounded-3 p-3 h-100">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-success-subtle text-success border">Kho & lịch trình</span>
                        <span class="ms-2 text-muted small">Chọn kho, ngày lập và xác nhận.</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Kho áp dụng <span class="text-danger">*</span></label>
                            <select class="form-select" name="IdKho" required>
                                <option value="">-- Chọn kho --</option>
                                <?php foreach ($warehouses as $warehouse): ?>
                                    <option value="<?= htmlspecialchars($warehouse['IdKho']) ?>" data-type="<?= htmlspecialchars($warehouse['TenLoaiKho'] ?? '') ?>" <?= ($document['IdKho'] ?? '') === $warehouse['IdKho'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($warehouse['TenKho']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Ngày lập phiếu</label>
                            <input type="date" name="NgayLP" class="form-control" value="<?= htmlspecialchars($document['NgayLP'] ?? '') ?>">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Ngày xác nhận</label>
                            <input type="date" name="NgayXN" class="form-control" value="<?= htmlspecialchars($document['NgayXN'] ?? '') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Tổng giá trị</label>
                            <div class="input-group">
                                <input type="number" min="0" step="1000" name="TongTien" class="form-control" value="<?= htmlspecialchars($document['TongTien'] ?? 0) ?>">
                                <span class="input-group-text">đ</span>
                            </div>
                            <div class="form-text">Giá trị dự kiến hoặc thực tế của phiếu.</div>
                        </div>
                    </div>
                </div>
            </div>

<div class="col-md-6">
    <label class="form-label fw-semibold">Người lập phiếu <span class="text-danger">*</span></label>
    <select class="form-select" name="NguoiLap" required>
        <option value="">-- Chọn nhân viên --</option>
        <?php foreach ($employees as $employee): ?>
                        <option value="<?= htmlspecialchars($employee['IdNhanVien']) ?>" <?= ($document['NHAN_VIENIdNhanVien'] ?? '') === $employee['IdNhanVien'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($employee['HoTen']) ?>
                        </option>
        <?php endforeach; ?>
    </select>
</div>
<div class="col-md-6">
    <label class="form-label fw-semibold">Xưởng trưởng xác nhận <span class="text-danger">*</span></label>
    <div class="form-control-plaintext" data-role="approver-display">
        <?php if ($currentApprover): ?>
            <?= htmlspecialchars($currentApprover['HoTen'] ?? '') ?><?= !empty($currentApprover['ChucVu']) ? ' · ' . htmlspecialchars($currentApprover['ChucVu']) : '' ?>
        <?php else: ?>
            <span class="text-muted">Chọn kho để hiển thị xưởng trưởng.</span>
        <?php endif; ?>
    </div>
    <input type="hidden" name="NguoiXacNhan" value="<?= htmlspecialchars($document['NHAN_VIENIdNhanVien2'] ?? ($currentApprover['IdNhanVien'] ?? '')) ?>" data-role="approver-input">
    <div class="form-text text-muted">Hệ thống tự động gán xưởng trưởng của kho làm người xác nhận.</div>
</div>

            <div class="col-12">
                <label class="form-label fw-semibold">Lý do/Nội dung nghiệp vụ <span class="text-danger">*</span></label>
                <textarea name="LyDo" class="form-control" rows="3" required placeholder="Ví dụ: Nhập lô switch theo hợp đồng, xuất cấp cho chuyền lắp ráp"><?= htmlspecialchars($document['LyDo'] ?? '') ?></textarea>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Ghi chú bổ sung</label>
                <textarea name="GhiChu" class="form-control" rows="3" placeholder="Ghi chú điều kiện bảo quản, người giao/nhận, biển số xe..."><?= htmlspecialchars($document['GhiChu'] ?? '') ?></textarea>
            </div>

            <div class="col-12 d-flex justify-content-between">
                <a href="?controller=warehouse_sheet&action=index" class="btn btn-light">
                    <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
                </a>
                <div>
                    <?php if ($isEdit): ?>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Cập nhật phiếu
                        </button>
                    <?php else: ?>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> Lưu phiếu mới
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if ($isEdit): ?>
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-semibold mb-0">Chi tiết phiếu (chỉ xem)</h6>
                <a class="btn btn-sm btn-outline-secondary" href="?controller=warehouse_sheet&action=read&id=<?= urlencode($document['IdPhieu'] ?? '') ?>">Xem đầy đủ</a>
            </div>
            <?php if (empty($details)): ?>
                <p class="text-muted mb-0">Phiếu chưa có chi tiết nào.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                        <tr>
                            <th>Mã lô</th>
                            <th>Tên lô</th>
                            <th>Mặt hàng</th>
                            <th>Số lượng</th>
                            <th>Đơn vị</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($details as $detail): ?>
                            <tr>
                                <td><?= htmlspecialchars($detail['IdLo']) ?></td>
                                <td><?= htmlspecialchars($detail['TenLo'] ?? '') ?></td>
                                <td>
                                    <div class="fw-semibold mb-0"><?= htmlspecialchars($detail['TenSanPham'] ?? '-') ?></div>
                                    <small class="text-muted">Mã SP: <?= htmlspecialchars($detail['IdSanPham'] ?? '-') ?></small>
                                </td>
                                <td><?= number_format($detail['SoLuong'] ?? 0) ?></td>
                                <td><?= htmlspecialchars($detail['DonViTinh'] ?? $detail['DonVi'] ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            <p class="text-muted small mb-0 mt-2">Cập nhật chi tiết lô bằng cách xóa và tạo phiếu mới để đảm bảo số liệu chính xác.</p>
        </div>
    </div>
<?php else: ?>
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-semibold mb-0">Chi tiết phiếu & cập nhật tồn kho</h6>
                    <p class="text-muted small mb-0">Thêm nhanh các lô nguyên liệu, thành phẩm hoặc lô lỗi để hệ thống tự động tính tồn.</p>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" data-action="add-detail-row">
                    <i class="bi bi-plus-lg me-1"></i> Thêm dòng chi tiết
                </button>
            </div>
            <div class="table-responsive">
                <table class="table align-middle table-hover" id="detail-table">
                    <thead class="table-light">
                        <tr>
                            <th class="text-nowrap">Hình thức</th>
                            <th class="text-nowrap">Mã lô</th>
                            <th>Tên lô</th>
                            <th>Mặt hàng</th>
                            <th class="text-nowrap">Số lượng</th>
                            <th class="text-nowrap">Đơn vị</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody data-detail-rows></tbody>
                </table>
            </div>
            <div class="bg-light border rounded-3 p-3 small mb-0">
                <div class="fw-semibold mb-1">Lưu ý nghiệp vụ</div>
                <ul class="mb-0">
                    <li>Phiếu nhập có thể tạo lô mới hoặc cập nhật lô sẵn có.</li>
                    <li>Phiếu xuất chỉ được chọn lô đã tồn tại trong kho.</li>
                    <li>Số lượng thực nhận để trống sẽ mặc định bằng số lượng kế hoạch.</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const products = JSON.parse('<?= $productsJson ?>');
            const lots = JSON.parse('<?= $lotsJson ?>');
            const approvers = JSON.parse('<?= $approversJson ?>');
            const lotMap = {};
            lots.forEach(lot => {
                lotMap[lot.IdLo] = lot;
            });

            const productMap = {};
            products.forEach(product => {
                if (product.IdSanPham) {
                    productMap[product.IdSanPham] = product;
                }
            });

            const tableBody = document.querySelector('[data-detail-rows]');
            const addRowBtn = document.querySelector('[data-action="add-detail-row"]');
            const warehouseSelect = document.querySelector('select[name="IdKho"]');
            const typeInput = document.querySelector('input[name="LoaiPhieu"]');
            const approverDisplay = document.querySelector('[data-role="approver-display"]');
            const approverInput = document.querySelector('[data-role="approver-input"]');

            const updateApprover = () => {
                if (!warehouseSelect || !approverDisplay || !approverInput) {
                    return;
                }
                const warehouseId = warehouseSelect.value || '';
                const approver = approvers[warehouseId] || null;

                if (approver) {
                    approverDisplay.textContent = approver.HoTen || approver.IdNhanVien || '';
                    if (approver.ChucVu) {
                        approverDisplay.textContent += ' · ' + approver.ChucVu;
                    }
                    approverInput.value = approver.IdNhanVien || '';
                } else {
                    approverDisplay.innerHTML = '<span class=\"text-muted\">Chọn kho để hiển thị xưởng trưởng.</span>';
                    approverInput.value = '';
                }
            };

            const resolveWarehouseType = () => {
                const selectedOption = warehouseSelect ? warehouseSelect.options[warehouseSelect.selectedIndex] : null;
                const typeValue = selectedOption ? (selectedOption.dataset.type || '') : '';
                const normalized = typeValue.toLowerCase();
                if (normalized.includes('thành phẩm') || normalized.includes('thanh pham')) return 'finished';
                if (normalized.includes('lỗi') || normalized.includes('loi') || normalized.includes('xử lý') || normalized.includes('xu ly')) return 'quality';
                return 'material';
            };

            const resolveLotPrefix = () => {
                const type = resolveWarehouseType();
                if (type === 'finished') return 'LOTP';
                if (type === 'quality') return 'LOXL';
                return 'LONL';
            };

            const buildLotOptions = () => {
                const warehouseId = warehouseSelect ? warehouseSelect.value : '';
                return lots.filter(lot => !warehouseId || lot.IdKho === warehouseId);
            };

            const isOutbound = () => {
                const value = typeInput ? typeInput.value.toLowerCase() : '';
                return value.includes('xuất');
            };

            const updateRowMode = (row) => {
                const modeSelect = row.querySelector('[data-field="mode"]');
                let isNew = modeSelect && modeSelect.value === 'new';
                const lotInput = row.querySelector('[data-field="lot-id"]');
                const lotNameInput = row.querySelector('[data-field="lot-name"]');
                const productSelect = row.querySelector('[data-field="product"]');
                const lotSelect = row.querySelector('[data-field="existing-lot"]');
                const quantityInput = row.querySelector('[data-field="quantity"]');
                const unitInput = row.querySelector('[data-field="unit"]');
                const modeHidden = row.querySelector('[data-field="mode-hidden"]');

                if (!modeSelect) return;

                if (isOutbound()) {
                    modeSelect.value = 'existing';
                    modeSelect.disabled = true;
                    if (modeHidden) {
                        modeHidden.value = 'existing';
                    }
                } else {
                    modeSelect.disabled = false;
                }

                if (modeHidden) {
                    modeHidden.value = modeSelect.value;
                }

                isNew = modeSelect && modeSelect.value === 'new';

                if (isNew) {
                    lotInput.classList.remove('d-none');
                    lotNameInput.classList.remove('d-none');
                    productSelect.classList.remove('d-none');
                    lotSelect.classList.add('d-none');
                    lotInput.disabled = false;
                    lotNameInput.disabled = false;
                    productSelect.disabled = false;
                    lotSelect.disabled = true;
                    if (lotInput.value.trim() === '') {
                        lotInput.value = generateId(resolveLotPrefix());
                    }
                } else {
                    lotInput.classList.add('d-none');
                    lotNameInput.classList.add('d-none');
                    productSelect.classList.add('d-none');
                    lotSelect.classList.remove('d-none');
                    lotInput.disabled = true;
                    lotNameInput.disabled = true;
                    productSelect.disabled = true;
                    lotSelect.disabled = false;
                    lotInput.value = '';
                    lotNameInput.value = '';
                    if (productSelect) {
                        productSelect.value = '';
                    }
                }

                if (!isNew && lotSelect) {
                    const selectedLot = lotMap[lotSelect.value] || null;
                    if (selectedLot) {
                        unitInput.value = selectedLot.DonVi || '';
                        if (quantityInput && quantityInput.value === '') {
                            quantityInput.value = selectedLot.SoLuong || 0;
                        }
                    }
                }
            };

            const buildProductOptions = () => {
                const fragment = document.createDocumentFragment();
                products.forEach(product => {
                    const option = document.createElement('option');
                    option.value = product.IdSanPham;
                    option.textContent = `${product.TenSanPham || product.IdSanPham} (${product.IdSanPham})`;
                    option.dataset.unit = product.DonVi || '';
                    fragment.appendChild(option);
                });
                return fragment;
            };

            const buildLotSelectOptions = (selectEl) => {
                selectEl.innerHTML = '<option value="">-- Chọn lô --</option>';
                buildLotOptions().forEach(lot => {
                    const option = document.createElement('option');
                    option.value = lot.IdLo;
                    const available = lot.SoLuong ? ` · SL: ${lot.SoLuong}` : '';
                    option.textContent = `${lot.IdLo} - ${lot.TenLo || ''} (${lot.TenKho || ''})${available}`;
                    option.dataset.unit = lot.DonVi || '';
                    selectEl.appendChild(option);
                });
            };

            const updateProductUnit = (row) => {
                const productSelect = row.querySelector('[data-field="product"]');
                const unitInput = row.querySelector('[data-field="unit"]');
                if (!productSelect || !unitInput) return;
                const option = productSelect.options[productSelect.selectedIndex];
                unitInput.value = option ? (option.dataset.unit || '') : '';
            };

            const generateId = (prefix) => {
                const now = new Date();
                const pad = (v) => v.toString().padStart(2, '0');
                return [
                    prefix,
                    now.getFullYear(),
                    pad(now.getMonth() + 1),
                    pad(now.getDate()),
                    pad(now.getHours()),
                    pad(now.getMinutes()),
                    pad(now.getSeconds())
                ].join('');
            };

            const addRow = () => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <input type="hidden" name="Detail_Mode[]" value="existing" data-field="mode-hidden">
                        <select class="form-select form-select-sm" data-field="mode">
                            <option value="existing">Dùng lô sẵn</option>
                            <option value="new">Tạo lô mới</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" name="Detail_IdLo[]" class="form-control form-control-sm mb-2" placeholder="Mã lô" data-field="lot-id">
                        <select class="form-select form-select-sm d-none" name="Detail_IdLo[]" data-field="existing-lot"></select>
                    </td>
                    <td><input type="text" name="Detail_TenLo[]" class="form-control form-control-sm" placeholder="Tên lô" data-field="lot-name"></td>
                    <td>
                        <select class="form-select form-select-sm" name="Detail_IdSanPham[]" data-field="product">
                            <option value="">-- Chọn sản phẩm --</option>
                        </select>
                    </td>
                    <td><input type="number" min="1" class="form-control form-control-sm" name="Detail_SoLuong[]" data-field="quantity" required></td>
                    <td><input type="text" class="form-control form-control-sm" name="Detail_DonVi[]" data-field="unit" placeholder="Đơn vị"></td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger" data-action="remove-row"><i class="bi bi-x"></i></button>
                    </td>
                `;

                const modeSelect = row.querySelector('[data-field="mode"]');
                const modeHidden = row.querySelector('[data-field="mode-hidden"]');
                const productSelect = row.querySelector('[data-field="product"]');
                const lotSelect = row.querySelector('[data-field="existing-lot"]');
                const lotIdInput = row.querySelector('[data-field="lot-id"]');
                const lotNameInput = row.querySelector('[data-field="lot-name"]');
                const unitInput = row.querySelector('[data-field="unit"]');

                if (productSelect) {
                    productSelect.appendChild(buildProductOptions());
                    productSelect.addEventListener('change', () => updateProductUnit(row));
                }

                if (lotSelect) {
                    buildLotSelectOptions(lotSelect);
                    lotSelect.addEventListener('change', () => {
                        const lotInfo = lotMap[lotSelect.value] || null;
                        if (lotInfo && unitInput) {
                            unitInput.value = lotInfo.DonVi || '';
                        }
                    });
                }

                if (modeSelect) {
                    modeSelect.addEventListener('change', () => {
                        modeHidden.value = modeSelect.value;
                        updateRowMode(row);
                    });
                }

                const removeBtn = row.querySelector('[data-action="remove-row"]');
                if (removeBtn) {
                    removeBtn.addEventListener('click', () => row.remove());
                }

                tableBody.appendChild(row);
                updateRowMode(row);
            };

            if (addRowBtn) {
                addRowBtn.addEventListener('click', addRow);
            }

            if (warehouseSelect) {
                warehouseSelect.addEventListener('change', () => {
                    tableBody.querySelectorAll('tr').forEach(row => {
                        const lotSelect = row.querySelector('[data-field="existing-lot"]');
                        if (lotSelect) {
                            buildLotSelectOptions(lotSelect);
                        }
                        updateRowMode(row);
                    });
                    updateApprover();
                });
                updateApprover();
            }

            if (typeInput) {
                typeInput.addEventListener('input', () => {
                    tableBody.querySelectorAll('tr').forEach(row => updateRowMode(row));
                });
            }

            addRow();
        });
    </script>
<?php endif; ?>

<?php if (!$isEdit): ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const typeInput = document.querySelector('input[name="LoaiPhieu"]');
            const idInput = document.querySelector('input[name="IdPhieu"]');

            if (!typeInput || !idInput) {
                return;
            }

            const buildId = (prefix) => {
                const now = new Date();
                const pad = (value) => value.toString().padStart(2, '0');
                return [
                    prefix,
                    now.getFullYear(),
                    pad(now.getMonth() + 1),
                    pad(now.getDate()),
                    pad(now.getHours()),
                    pad(now.getMinutes()),
                    pad(now.getSeconds())
                ].join('');
            };

            typeInput.addEventListener('change', () => {
                if (idInput.dataset.userEdited === '1') {
                    return;
                }

                const value = typeInput.value.toLowerCase();
                let prefix = 'PH';
                if (value.includes('nhập')) {
                    prefix = 'PN';
                } else if (value.includes('xuất')) {
                    prefix = 'PX';
                }

                idInput.value = buildId(prefix);
            });

            idInput.addEventListener('input', () => {
                idInput.dataset.userEdited = '1';
            });
        });
    </script>
<?php endif; ?>
