<?php
$detailItems = $orderDetails ?? [];
if (empty($detailItems)) {
    $detailItems = [[]];
}

$products = $products ?? [];
$configurations = $configurations ?? [];
$boms = $boms ?? [];

$formatBomStep = static function (array $step): string {
    $name = trim((string) ($step['name'] ?? $step['TenCongDoan'] ?? ''));
    if ($name === '') {
        return '';
    }

    $materials = [];
    foreach ($step['materials'] ?? [] as $material) {
        $label = $material['label'] ?? $material['Nhan'] ?? '';
        $unit = $material['unit'] ?? $material['DonVi'] ?? '';
        if ($label === '') {
            continue;
        }
        $materials[] = trim($label . ($unit ? " ($unit)" : ''));
    }

    if (!empty($materials)) {
        $name .= ' [' . implode(', ', $materials) . ']';
    }

    return $name;
};

$buildBomSummary = static function (array $bom) use ($formatBomStep): string {
    $steps = $bom['steps'] ?? [];
    if (empty($steps)) {
        return '';
    }

    $summaries = [];
    foreach ($steps as $step) {
        $summaries[] = $formatBomStep($step);
    }

    $summaries = array_filter($summaries);
    return implode('; ', $summaries);
};

$processedBoms = [];
$bomsByProduct = [];
foreach ($boms as $bomId => $bom) {
    $id = is_string($bomId) ? $bomId : ($bom['id'] ?? null);
    if (!$id) {
        $id = $bom['IdBOM'] ?? null;
    }
    if (!$id) {
        continue;
    }

    $productId = $bom['product_id'] ?? ($bom['IdSanPham'] ?? null);
    $summary = $buildBomSummary($bom);
    $preparedBom = [
        'id' => $id,
        'product_id' => $productId,
        'name' => $bom['name'] ?? ($bom['TenBOM'] ?? $id),
        'description' => $bom['description'] ?? ($bom['MoTa'] ?? ''),
        'steps' => $bom['steps'] ?? [],
        'summary' => $summary,
    ];

    $processedBoms[$id] = $preparedBom;
    if ($productId) {
        $bomsByProduct[$productId][] = $preparedBom;
    }
}

$boms = $processedBoms;
$configsByProduct = [];
foreach ($configurations as $configuration) {
    $productId = $configuration['IdSanPham'] ?? null;
    if (!$productId) {
        continue;
    }
    $bomId = $configuration['IdBOM'] ?? null;
    $bomData = $bomId && isset($boms[$bomId]) ? $boms[$bomId] : null;
    $configsByProduct[$productId][] = [
        'id' => $configuration['IdCauHinh'],
        'name' => $configuration['TenCauHinh'] ?? 'Cấu hình mới',
        'price' => (float) ($configuration['GiaBan'] ?? 0),
        'description' => $configuration['MoTa'] ?? '',
        'layout' => $configuration['Layout'] ?? '',
        'switch' => $configuration['SwitchType'] ?? '',
        'case' => $configuration['CaseType'] ?? '',
        'foam' => $configuration['Foam'] ?? '',
        'bom_id' => $bomId,
        'bom' => $bomData,
        'bom_summary' => $bomData['summary'] ?? '',
    ];
}

$productOptions = array_map(static function ($product) use ($configsByProduct, $bomsByProduct) {
    $productId = $product['IdSanPham'];
    return [
        'id' => $productId,
        'name' => $product['TenSanPham'],
        'unit' => $product['DonVi'] ?? '',
        'price' => (float) ($product['GiaBan'] ?? 0),
        'description' => $product['MoTa'] ?? '',
        'configurations' => $configsByProduct[$productId] ?? [],
        'bom_presets' => array_values($bomsByProduct[$productId] ?? []),
    ];
}, $products);

$detailPayload = array_map(static function ($detail) {
    if (empty($detail)) {
        return new stdClass();
    }

    $delivery = $detail['NgayGiao'] ?? null;
    if ($delivery) {
        try {
            $deliveryDate = new DateTime($delivery);
            $delivery = $deliveryDate->format('Y-m-d\\TH:i');
        } catch (Exception $e) {
            $delivery = null;
        }
    }

    return [
        'product_id' => $detail['IdSanPham'] ?? null,
        'configuration_mode' => 'existing',
        'configuration_id' => $detail['IdCauHinh'] ?? null,
        'new_configuration_name' => '',
        'new_configuration_price' => '',
        'new_configuration_description' => '',
        'new_configuration_layout' => '',
        'new_configuration_switch' => '',
        'new_configuration_case' => '',
        'new_configuration_foam' => '',
        'new_configuration_bom_template' => '',
        'quantity' => (int) ($detail['SoLuong'] ?? 1),
        'delivery_date' => $delivery,
        'requirement' => $detail['YeuCau'] ?? null,
        'unit_price' => (float) ($detail['DonGia'] ?? 0),
        'vat' => isset($detail['VAT']) ? ((float) $detail['VAT']) * 100 : 8,
        'note' => $detail['GhiChu'] ?? null,
    ];
}, $detailItems);
?>
<div class="col-12">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <label class="form-label fw-semibold">Danh sách sản phẩm & cấu hình</label>
            <p class="text-muted small mb-0">Chọn sản phẩm SV5TOT, cấu hình tương ứng và cập nhật yêu cầu chi tiết.</p>
        </div>
        <button class="btn btn-outline-primary" type="button" id="add-detail-row"><i class="bi bi-plus-lg me-2"></i>Thêm sản phẩm</button>
    </div>
    <div id="order-detail-container" class="mt-3"></div>
    <div class="d-flex justify-content-end mt-3">
        <div class="text-end">
            <div class="text-muted small">Tổng tiền tạm tính</div>
            <div class="fs-5 fw-semibold text-primary" id="order-total-display">0 đ</div>
        </div>
    </div>
</div>
<script>
(function () {
    const productOptions = <?= json_encode($productOptions, JSON_UNESCAPED_UNICODE) ?>;
    const initialDetails = <?= json_encode($detailPayload, JSON_UNESCAPED_UNICODE) ?>;
    const detailContainer = document.getElementById('order-detail-container');
    const addRowButton = document.getElementById('add-detail-row');
    let detailIndex = 0;

    function formatCurrency(value) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value || 0);
    }

    function escapeHtml(value) {
        if (value === null || typeof value === 'undefined') {
            return '';
        }
        return value.toString().replace(/[&<>"']/g, function (char) {
            switch (char) {
                case '&':
                    return '&amp;';
                case '<':
                    return '&lt;';
                case '>':
                    return '&gt;';
                case '"':
                    return '&quot;';
                case '\'':
                    return '&#039;';
                default:
                    return char;
            }
        });
    }

    function getProduct(productId) {
        return productOptions.find(product => product.id === productId) || null;
    }

    function getConfigurationDetail(productId, configurationId) {
        const product = getProduct(productId);
        if (!product) {
            return null;
        }
        return product.configurations.find(item => item.id === configurationId) || null;
    }

    function getBomPresets(productId) {
        const product = getProduct(productId);
        return product ? product.bom_presets : [];
    }

    function buildProductOptions(selectedId) {
        const options = ['<option value="">-- Chọn sản phẩm --</option>'];
        productOptions.forEach(product => {
            const selected = selectedId === product.id ? 'selected' : '';
            options.push(`<option value="${product.id}" ${selected}>${product.name}</option>`);
        });
        return options.join('');
    }

    function buildBomPresetOptions(productId, selectedId) {
        const presets = getBomPresets(productId);
        const options = ['<option value="">-- Chọn preset BOM --</option>'];
        presets.forEach(preset => {
            const selected = preset.id === selectedId ? 'selected' : '';
            options.push(`<option value="${preset.id}" ${selected}>${preset.name}</option>`);
        });
        return options.join('');
    }

    function buildConfigurationOptions(productId, selectedId) {
        const product = getProduct(productId);
        const configs = product ? product.configurations : [];
        const options = ['<option value="">-- Chọn cấu hình --</option>'];
        configs.forEach(configuration => {
            const selected = configuration.id === selectedId ? 'selected' : '';
            options.push(`<option value="${configuration.id}" data-price="${configuration.price}" ${selected}>${configuration.name}</option>`);
        });
        return options.join('');
    }

    function updateConfigurationHint(row, productId, configurationId) {
        const hint = row.querySelector('[data-field="configuration_hint"]');
        const productHint = row.querySelector('[data-field="product_hint"]');
        const product = getProduct(productId);
        if (productHint) {
            productHint.textContent = product && product.description ? product.description : '';
        }
        if (!hint) {
            return;
        }
        const configuration = getConfigurationDetail(productId, configurationId);
        if (!configuration) {
            hint.textContent = '';
            return;
        }
        const lines = [];
        if (configuration.description) {
            lines.push(`<div>${escapeHtml(configuration.description)}</div>`);
        }
        const specs = [];
        if (configuration.layout) {
            specs.push(`Layout: ${escapeHtml(configuration.layout)}`);
        }
        if (configuration.switch) {
            specs.push(`Switch: ${escapeHtml(configuration.switch)}`);
        }
        if (configuration.case) {
            specs.push(`Case: ${escapeHtml(configuration.case)}`);
        }
        if (configuration.foam) {
            specs.push(`Foam: ${escapeHtml(configuration.foam)}`);
        }
        if (specs.length) {
            lines.push(`<div>${specs.join(' • ')}</div>`);
        }
        if (configuration.bom_summary) {
            lines.push(`<div>BOM: ${escapeHtml(configuration.bom_summary)}</div>`);
        }
        hint.innerHTML = lines.join('');
    }

    function updateBomPresetHint(row, productId, bomId) {
        const hint = row.querySelector('[data-field="bom_preset_hint"]');
        if (!hint) {
            return;
        }
        const presets = productId ? getBomPresets(productId) : [];
        const preset = presets.find(item => item.id === bomId);
        if (!preset) {
            hint.innerHTML = '';
            return;
        }
        const lines = [];
        if (preset.description) {
            lines.push(`<div>${escapeHtml(preset.description)}</div>`);
        }
        if (preset.summary) {
            lines.push(`<div>${escapeHtml(preset.summary)}</div>`);
        }
        hint.innerHTML = lines.join('');
    }

    function setConfigurationDefaults(row, productId, configurationId, force = false) {
        const product = getProduct(productId);
        const configuration = product && product.configurations.find(item => item.id === configurationId);
        const unitPriceInput = row.querySelector('[data-field="unit_price"]');
        if (!unitPriceInput) {
            return;
        }

        const isManual = unitPriceInput.dataset.manual === 'true';
        if (isManual && !force) {
            return;
        }

        if (configuration) {
            unitPriceInput.value = configuration.price || 0;
            unitPriceInput.dataset.manual = 'false';
        } else if (product && !configurationId) {
            unitPriceInput.value = product.price || 0;
            unitPriceInput.dataset.manual = 'false';
        }
    }

    function recalcTotals() {
        let total = 0;
        detailContainer.querySelectorAll('.order-detail-row').forEach(row => {
            const quantity = parseFloat(row.querySelector('[data-field="quantity"]').value) || 0;
            const unitPrice = parseFloat(row.querySelector('[data-field="unit_price"]').value) || 0;
            const vat = parseFloat(row.querySelector('[data-field="vat"]').value) || 0;
            const vatRate = vat / 100;
            const lineTotal = quantity * unitPrice * (1 + vatRate);
            total += lineTotal;
            const lineDisplay = row.querySelector('[data-field="line_total"]');
            if (lineDisplay) {
                lineDisplay.textContent = formatCurrency(lineTotal);
            }
        });
        const totalDisplay = document.getElementById('order-total-display');
        if (totalDisplay) {
            totalDisplay.textContent = formatCurrency(total);
        }
    }

    function setConfigurationMode(row, mode) {
        const modeInput = row.querySelector('[data-field="configuration_mode"]');
        const modeButtons = row.querySelectorAll('[data-action="toggle-mode"]');
        const configurationSelect = row.querySelector('[data-field="configuration_id"]');
        const newFields = row.querySelector('.new-configuration-fields');
        const newInputs = newFields ? newFields.querySelectorAll('input, textarea, select') : [];
        const unitPriceInput = row.querySelector('[data-field="unit_price"]');
        const productSelect = row.querySelector('[data-field="product_id"]');
        const bomSelect = row.querySelector('[data-field="new_configuration_bom_template"]');

        if (modeInput) {
            modeInput.value = mode;
        }

        modeButtons.forEach(button => {
            button.classList.toggle('active', button.getAttribute('data-mode') === mode);
        });

        if (mode === 'new') {
            if (configurationSelect) {
                configurationSelect.setAttribute('disabled', 'disabled');
                configurationSelect.removeAttribute('required');
                configurationSelect.value = '';
            }
            if (newFields) {
                newFields.classList.remove('d-none');
            }
            newInputs.forEach(input => {
                input.removeAttribute('disabled');
                if (input.getAttribute('data-required') === 'true' || input.name.includes('[new_configuration_name]')) {
                    input.setAttribute('required', 'required');
                }
            });
            if (bomSelect) {
                const productId = productSelect ? productSelect.value : null;
                const previousValue = bomSelect.value || '';
                bomSelect.innerHTML = buildBomPresetOptions(productId, previousValue);
                bomSelect.removeAttribute('disabled');
                if (!bomSelect.value && productId) {
                    const presets = getBomPresets(productId);
                    bomSelect.value = presets.length ? presets[0].id : '';
                }
                updateBomPresetHint(row, productId, bomSelect.value);
            }
            if (unitPriceInput) {
                unitPriceInput.dataset.manual = 'true';
            }
        } else {
            if (configurationSelect) {
                configurationSelect.removeAttribute('disabled');
                configurationSelect.setAttribute('required', 'required');
            }
            if (newFields) {
                newFields.classList.add('d-none');
            }
            newInputs.forEach(input => {
                input.setAttribute('disabled', 'disabled');
                input.removeAttribute('required');
            });
            if (bomSelect) {
                bomSelect.value = '';
                bomSelect.setAttribute('disabled', 'disabled');
                updateBomPresetHint(row, null, null);
            }
            const productId = productSelect ? productSelect.value : null;
            const configurationId = configurationSelect ? configurationSelect.value : null;
            setConfigurationDefaults(row, productId, configurationId, true);
        }

        recalcTotals();
    }

    function attachEvents(row) {
        const productSelect = row.querySelector('[data-field="product_id"]');
        const configurationSelect = row.querySelector('[data-field="configuration_id"]');
        const quantityInput = row.querySelector('[data-field="quantity"]');
        const unitPriceInput = row.querySelector('[data-field="unit_price"]');
        const vatInput = row.querySelector('[data-field="vat"]');
        const removeButton = row.querySelector('[data-action="remove"]');
        const modeButtons = row.querySelectorAll('[data-action="toggle-mode"]');
        const newConfigurationPrice = row.querySelector('[data-field="new_configuration_price"]');
        const bomSelect = row.querySelector('[data-field="new_configuration_bom_template"]');

        const modeInput = row.querySelector('[data-field="configuration_mode"]');

        if (productSelect) {
            productSelect.addEventListener('change', () => {
                const productId = productSelect.value;
                if (configurationSelect) {
                    configurationSelect.innerHTML = buildConfigurationOptions(productId, '');
                }
                updateConfigurationHint(row, productId, configurationSelect ? configurationSelect.value : null);
                if ((modeInput ? modeInput.value : 'existing') === 'existing') {
                    setConfigurationDefaults(row, productId, configurationSelect ? configurationSelect.value : null, true);
                    if (bomSelect) {
                        bomSelect.value = '';
                        bomSelect.setAttribute('disabled', 'disabled');
                        updateBomPresetHint(row, null, null);
                    }
                } else if (bomSelect) {
                    bomSelect.innerHTML = buildBomPresetOptions(productId, '');
                    const presets = getBomPresets(productId);
                    const defaultPreset = presets.length ? presets[0].id : '';
                    bomSelect.value = defaultPreset;
                    bomSelect.removeAttribute('disabled');
                    updateBomPresetHint(row, productId, bomSelect.value);
                }
                recalcTotals();
            });
        }

        if (configurationSelect) {
            configurationSelect.addEventListener('change', () => {
                const productId = productSelect ? productSelect.value : null;
                setConfigurationDefaults(row, productId, configurationSelect.value, true);
                updateConfigurationHint(row, productId, configurationSelect.value);
                recalcTotals();
            });
        }

        [quantityInput, unitPriceInput, vatInput].forEach(input => {
            if (!input) {
                return;
            }
            input.addEventListener('input', recalcTotals);
        });

        if (unitPriceInput) {
            unitPriceInput.addEventListener('input', () => {
                unitPriceInput.dataset.manual = 'true';
            });
        }

        if (newConfigurationPrice) {
            newConfigurationPrice.addEventListener('input', () => {
                if (unitPriceInput) {
                    unitPriceInput.value = newConfigurationPrice.value;
                    unitPriceInput.dataset.manual = newConfigurationPrice.value ? 'false' : unitPriceInput.dataset.manual;
                }
                recalcTotals();
            });
        }

        if (bomSelect) {
            bomSelect.addEventListener('change', () => {
                const productId = productSelect ? productSelect.value : null;
                updateBomPresetHint(row, productId, bomSelect.value);
            });
        }

        modeButtons.forEach(button => {
            button.addEventListener('click', () => {
                setConfigurationMode(row, button.getAttribute('data-mode'));
            });
        });

        if (removeButton) {
            removeButton.addEventListener('click', () => {
                row.remove();
                recalcTotals();
            });
        }
    }

    function addDetailRow(data = {}) {
        const index = detailIndex++;
        const productId = data.product_id || '';
        const configurationId = data.configuration_id || '';
        const mode = data.configuration_mode || 'existing';
        const vatValue = typeof data.vat !== 'undefined' ? data.vat : 8;

        const row = document.createElement('div');
        row.className = 'order-detail-row border rounded p-3 mb-3';
        row.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-semibold mb-0">Hạng mục #${index + 1}</h6>
                <button class="btn btn-sm btn-outline-danger" type="button" data-action="remove"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="row g-3 align-items-start">
                <div class="col-lg-4 col-md-6">
                    <label class="form-label">Sản phẩm</label>
                    <select class="form-select" name="details[${index}][product_id]" data-field="product_id" required>
                        ${buildProductOptions(productId)}
                    </select>
                    <div class="form-text" data-field="product_hint"></div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <label class="form-label">Cấu hình</label>
                    <select class="form-select" name="details[${index}][configuration_id]" data-field="configuration_id" required>
                        ${buildConfigurationOptions(productId, configurationId)}
                    </select>
                    <div class="form-text text-muted" data-field="configuration_hint"></div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <label class="form-label">Tùy chọn cấu hình</label>
                    <div class="btn-group w-100" role="group">
                        <input type="hidden" name="details[${index}][configuration_mode]" data-field="configuration_mode" value="${mode}">
                        <button class="btn btn-outline-primary ${mode === 'existing' ? 'active' : ''}" type="button" data-action="toggle-mode" data-mode="existing">Chọn cấu hình có sẵn</button>
                        <button class="btn btn-outline-primary ${mode === 'new' ? 'active' : ''}" type="button" data-action="toggle-mode" data-mode="new">Thêm cấu hình mới</button>
                    </div>
                </div>
                <div class="col-12 new-configuration-fields d-none">
                    <div class="row g-3">
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label">Tên cấu hình mới</label>
                            <input class="form-control" name="details[${index}][new_configuration_name]" data-field="new_configuration_name" value="${data.new_configuration_name || ''}" data-required="true" disabled>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label">Giá tham khảo (đ)</label>
                            <input class="form-control" type="number" min="0" step="1000" name="details[${index}][new_configuration_price]" data-field="new_configuration_price" value="${data.new_configuration_price || ''}" disabled>
                        </div>
                        <div class="col-lg-5 col-md-12">
                            <label class="form-label">Mô tả cấu hình</label>
                            <textarea class="form-control" rows="2" name="details[${index}][new_configuration_description]" data-field="new_configuration_description" disabled>${data.new_configuration_description || ''}</textarea>
                        </div>
                    </div>
                    <div class="row g-3 mt-1">
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label">Layout</label>
                            <input class="form-control" name="details[${index}][new_configuration_layout]" data-field="new_configuration_layout" value="${data.new_configuration_layout || ''}" disabled>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label">Switch</label>
                            <input class="form-control" name="details[${index}][new_configuration_switch]" data-field="new_configuration_switch" value="${data.new_configuration_switch || ''}" disabled>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label">Case/Vỏ</label>
                            <input class="form-control" name="details[${index}][new_configuration_case]" data-field="new_configuration_case" value="${data.new_configuration_case || ''}" disabled>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label">Foam/đệm</label>
                            <input class="form-control" name="details[${index}][new_configuration_foam]" data-field="new_configuration_foam" value="${data.new_configuration_foam || ''}" disabled>
                        </div>
                    </div>
                    <div class="row g-3 mt-1">
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label">Preset BOM</label>
                            <select class="form-select" name="details[${index}][new_configuration_bom_template]" data-field="new_configuration_bom_template" disabled>
                                ${buildBomPresetOptions(productId, data.new_configuration_bom_template || '')}
                            </select>
                            <div class="form-text text-muted" data-field="bom_preset_hint"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label">Số lượng</label>
                    <input class="form-control" type="number" min="1" name="details[${index}][quantity]" data-field="quantity" value="${data.quantity || 1}">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label">Đơn giá (đ)</label>
                    <input class="form-control" type="number" min="0" step="1000" name="details[${index}][unit_price]" data-field="unit_price" value="${data.unit_price || 0}" data-manual="${data.unit_price ? 'true' : 'false'}">
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label">VAT (%)</label>
                    <input class="form-control" type="number" min="0" step="0.1" name="details[${index}][vat]" data-field="vat" value="${vatValue}">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label">Thời gian bàn giao dự kiến</label>
                    <input class="form-control" type="datetime-local" name="details[${index}][delivery_date]" value="${data.delivery_date || ''}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Yêu cầu chi tiết</label>
                    <textarea class="form-control" rows="2" name="details[${index}][requirement]">${data.requirement || ''}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ghi chú đóng gói</label>
                    <textarea class="form-control" rows="2" name="details[${index}][note]">${data.note || ''}</textarea>
                </div>
                <div class="col-12 text-end">
                    <span class="text-muted small">Thành tiền: <span class="fw-semibold text-primary" data-field="line_total">0 đ</span></span>
                </div>
            </div>
        `;

        detailContainer.appendChild(row);
        updateConfigurationHint(row, productId, configurationId);
        setConfigurationMode(row, mode);
        if (mode === 'existing') {
            setConfigurationDefaults(row, productId, configurationId);
        }
        attachEvents(row);
        recalcTotals();
    }

    addRowButton.addEventListener('click', () => addDetailRow({ quantity: 1, vat: 8 }));
    if (initialDetails.length > 0) {
        initialDetails.forEach(detail => addDetailRow(detail));
    } else {
        addDetailRow({ quantity: 1, vat: 8 });
    }
})();
</script>
