<?php
$detailItems = $orderDetails ?? [];
$products = $products ?? [];
$configurations = $configurations ?? [];
$isEditMode = $isEditMode ?? false;

if (empty($detailItems)) {
    $detailItems = [[]];
}

$preparedProducts = array_map(static function ($product) {
    return [
        'id' => $product['IdSanPham'] ?? null,
        'name' => $product['TenSanPham'] ?? 'Sản phẩm mới',
        'unit' => $product['DonVi'] ?? '',
        'price' => isset($product['GiaBan']) ? (float) $product['GiaBan'] : 0.0,
        'description' => $product['MoTa'] ?? '',
    ];
}, $products);

$configByProduct = [];
foreach ($configurations as $configuration) {
    $productId = $configuration['IdSanPham'] ?? null;
    if (!$productId) {
        continue;
    }
    $configByProduct[$productId][] = [
        'id' => $configuration['IdCauHinh'] ?? null,
        'product_id' => $productId,
        'name' => $configuration['TenCauHinh'] ?? 'Cấu hình',
        'price' => isset($configuration['GiaBan']) ? (float) $configuration['GiaBan'] : 0.0,
        'description' => $configuration['MoTa'] ?? '',
        'keycap' => $configuration['Keycap'] ?? '',
        'mainboard' => $configuration['Mainboard'] ?? '',
        'layout' => $configuration['Layout'] ?? '',
        'switch' => $configuration['SwitchType'] ?? '',
        'case' => $configuration['CaseType'] ?? '',
        'others' => $configuration['Foam'] ?? '',
    ];
}

$initialDetails = array_map(static function ($detail) {
    if (empty($detail)) {
        return [
            'product_mode' => 'existing',
            'product_id' => '',
            'new_product_name' => '',
            'new_product_unit' => '',
            'new_product_description' => '',
            'configuration_mode' => 'existing',
            'configuration_id' => '',
            'new_configuration_name' => '',
            'new_configuration_price' => '',
            'new_configuration_description' => '',
            'config_description' => '',
            'config_keycap' => '',
            'config_mainboard' => '',
            'config_layout' => '',
            'config_switch_type' => '',
            'config_case_type' => '',
            'config_foam' => '',
            'quantity' => 1,
            'min_quantity' => 1,
            'unit_price' => 0,
            'vat' => 8,
            'delivery_date' => '',
            'min_delivery_date' => '',
            'requirement' => '',
            'note' => '',
            'detail_id' => '',
        ];
    }

    return [
        'product_mode' => $detail['product_mode'] ?? 'existing',
        'product_id' => $detail['product_id'] ?? '',
        'new_product_name' => $detail['new_product_name'] ?? '',
        'new_product_unit' => $detail['new_product_unit'] ?? '',
        'new_product_description' => $detail['new_product_description'] ?? '',
        'configuration_mode' => $detail['configuration_mode'] ?? 'existing',
        'configuration_id' => $detail['configuration_id'] ?? '',
        'new_configuration_name' => $detail['new_configuration_name'] ?? '',
        'new_configuration_price' => $detail['new_configuration_price'] ?? '',
        'new_configuration_description' => $detail['new_configuration_description'] ?? '',
        'config_description' => $detail['config_description'] ?? '',
        'config_keycap' => $detail['config_keycap'] ?? '',
        'config_mainboard' => $detail['config_mainboard'] ?? '',
        'config_layout' => $detail['config_layout'] ?? '',
        'config_switch_type' => $detail['config_switch_type'] ?? '',
        'config_case_type' => $detail['config_case_type'] ?? '',
        'config_foam' => $detail['config_foam'] ?? '',
        'quantity' => (int) ($detail['quantity'] ?? 1),
        'min_quantity' => (int) ($detail['min_quantity'] ?? ($detail['quantity'] ?? 1)),
        'unit_price' => (float) ($detail['unit_price'] ?? 0),
        'vat' => isset($detail['vat']) ? (float) $detail['vat'] : 8,
        'delivery_date' => $detail['delivery_date'] ?? '',
        'min_delivery_date' => $detail['min_delivery_date'] ?? '',
        'requirement' => $detail['requirement'] ?? '',
        'note' => $detail['note'] ?? '',
        'detail_id' => $detail['detail_id'] ?? '',
    ];
}, $detailItems);

if (empty($initialDetails)) {
    $initialDetails[] = [
        'product_mode' => 'existing',
        'product_id' => '',
        'new_product_name' => '',
        'new_product_unit' => '',
        'new_product_description' => '',
        'configuration_mode' => 'existing',
        'configuration_id' => '',
        'new_configuration_name' => '',
        'new_configuration_price' => '',
        'new_configuration_description' => '',
        'config_description' => '',
        'config_keycap' => '',
        'config_mainboard' => '',
        'config_layout' => '',
        'config_switch_type' => '',
        'config_case_type' => '',
        'config_foam' => '',
        'quantity' => 1,
        'min_quantity' => 1,
        'unit_price' => 0,
        'vat' => 8,
        'delivery_date' => '',
        'min_delivery_date' => '',
        'requirement' => '',
        'note' => '',
        'detail_id' => '',
    ];
}
?>
<div class="col-12">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <label class="form-label fw-semibold">Danh sách sản phẩm & cấu hình</label>
            <p class="text-muted small mb-0">Chọn sản phẩm có sẵn hoặc nhập mới, sau đó điền cấu hình gồm mô tả, keycap, mainboard, layout, switch, case, foam.</p>
        </div>
        <?php if (!$isEditMode): ?>
            <button class="btn btn-outline-primary" type="button" id="add-detail-row"><i class="bi bi-plus-lg me-2"></i>Thêm dòng</button>
        <?php endif; ?>
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
    const products = <?= json_encode(array_values(array_filter($preparedProducts, static fn($product) => !empty($product['id']))), JSON_UNESCAPED_UNICODE) ?>;
    const configurations = <?= json_encode($configByProduct, JSON_UNESCAPED_UNICODE) ?>;
    const initialDetails = <?= json_encode($initialDetails, JSON_UNESCAPED_UNICODE) ?>;
    const detailContainer = document.getElementById('order-detail-container');
    const addRowButton = document.getElementById('add-detail-row');
    const isEditMode = <?= $isEditMode ? 'true' : 'false' ?>;
    let detailIndex = 0;

    function buildProductOptions(selectedId) {
        const options = ['<option value="">-- Chọn sản phẩm --</option>'];
        products.forEach(product => {
            const selected = selectedId && selectedId === product.id ? 'selected' : '';
            options.push(`<option value="${product.id}" data-price="${product.price}" data-description="${product.description ? product.description.replace(/"/g, '&quot;') : ''}" ${selected}>${product.name}</option>`);
        });
        return options.join('');
    }

    function buildConfigurationOptions(productId, selectedId) {
        const configs = configurations[productId] || [];
        const options = ['<option value="">-- Chọn cấu hình --</option>'];
        configs.forEach(config => {
            const selected = selectedId && selectedId === config.id ? 'selected' : '';
            options.push(`<option value="${config.id}" data-price="${config.price}" data-layout="${config.layout ? config.layout.replace(/"/g, '&quot;') : ''}" data-switch="${config.switch ? config.switch.replace(/"/g, '&quot;') : ''}" data-case="${config.case ? config.case.replace(/"/g, '&quot;') : ''}" data-others="${config.others ? config.others.replace(/"/g, '&quot;') : ''}" data-description="${config.description ? config.description.replace(/"/g, '&quot;') : ''}" ${selected}>${config.name}</option>`);
        });
        return options.join('');
    }

    function getConfiguration(productId, configurationId) {
        const configs = configurations[productId] || [];
        return configs.find(config => config.id === configurationId) || null;
    }

    function updateRowIndexes() {
        detailContainer.querySelectorAll('.order-detail-row').forEach((row, index) => {
            const label = row.querySelector('[data-field="row_index"]');
            if (label) {
                label.textContent = index + 1;
            }
        });
    }

    function updateProductHint(row, productId) {
        const hint = row.querySelector('[data-field="product_hint"]');
        if (!hint) {
            return;
        }
        const product = products.find(item => item.id === productId);
        hint.textContent = product && product.description ? product.description : '';
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
            const display = row.querySelector('[data-field="line_total"]');
            if (display) {
                display.textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(lineTotal || 0);
            }
        });
        const totalDisplay = document.getElementById('order-total-display');
        if (totalDisplay) {
            totalDisplay.textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(total || 0);
        }
    }

    function getMinDeliveryDate(baseValue) {
        const now = new Date();
        const local = new Date(now.getTime() - now.getTimezoneOffset() * 60000);
        const nowValue = local.toISOString().slice(0, 16);
        if (baseValue && baseValue > nowValue) {
            return baseValue;
        }
        return nowValue;
    }

    function applyDeliveryMin(row) {
        const input = row.querySelector('[data-field="delivery_date"]');
        if (!input) {
            return;
        }
        const baseValue = input.getAttribute('data-min-base') || '';
        const minValue = getMinDeliveryDate(baseValue);
        input.setAttribute('min', minValue);
        if (input.value && input.value < minValue) {
            input.value = minValue;
        }
        input.addEventListener('change', () => {
            const currentBase = input.getAttribute('data-min-base') || '';
            const currentMin = getMinDeliveryDate(currentBase);
            input.setAttribute('min', currentMin);
            if (input.value && input.value < currentMin) {
                input.value = currentMin;
            }
        });
    }

    function applyProductMode(row, mode) {
        const hidden = row.querySelector('[data-field="product_mode"]');
        const existingSection = row.querySelector('[data-product-section="existing"]');
        const newSection = row.querySelector('[data-product-section="new"]');
        const existingSelect = existingSection ? existingSection.querySelector('select') : null;
        const newInputs = newSection ? newSection.querySelectorAll('input, textarea') : [];

        if (hidden) {
            hidden.value = mode;
        }

        row.querySelectorAll('[data-action="toggle-product-mode"]').forEach(button => {
            button.classList.toggle('active', button.getAttribute('data-mode') === mode);
        });

        if (mode === 'new') {
            if (existingSection && existingSelect) {
                existingSection.classList.add('d-none');
                existingSelect.value = '';
                existingSelect.removeAttribute('required');
            }
            if (newSection) {
                newSection.classList.remove('d-none');
            }
            newInputs.forEach(input => {
                input.removeAttribute('disabled');
                if (input.hasAttribute('data-required')) {
                    input.setAttribute('required', 'required');
                }
            });
        } else {
            if (existingSection) {
                existingSection.classList.remove('d-none');
                if (existingSelect) {
                    existingSelect.setAttribute('required', 'required');
                }
            }
            if (newSection) {
                newSection.classList.add('d-none');
            }
            newInputs.forEach(input => {
                input.value = input.value;
                input.setAttribute('disabled', 'disabled');
                input.removeAttribute('required');
            });
        }
    }

    function applyConfigurationMode(row, mode) {
        const hidden = row.querySelector('[data-field="configuration_mode"]');
        const existingSection = row.querySelector('[data-configuration-section="existing"]');
        const newSection = row.querySelector('[data-configuration-section="new"]');
        const existingSelect = existingSection ? existingSection.querySelector('select') : null;
        const newInputs = newSection ? newSection.querySelectorAll('input, textarea') : [];

        if (hidden) {
            hidden.value = mode;
        }

        row.querySelectorAll('[data-action="toggle-configuration-mode"]').forEach(button => {
            button.classList.toggle('active', button.getAttribute('data-mode') === mode);
        });

        if (mode === 'new') {
            if (existingSection && existingSelect) {
                existingSection.classList.add('d-none');
                existingSelect.value = '';
                existingSelect.removeAttribute('required');
            }
            if (newSection) {
                newSection.classList.remove('d-none');
            }
            newInputs.forEach(input => {
                input.removeAttribute('disabled');
                if (input.hasAttribute('data-required')) {
                    input.setAttribute('required', 'required');
                }
            });
        } else {
            if (existingSection) {
                existingSection.classList.remove('d-none');
                if (existingSelect) {
                    existingSelect.setAttribute('required', 'required');
                }
            }
            if (newSection) {
                newSection.classList.add('d-none');
            }
            newInputs.forEach(input => {
                input.setAttribute('disabled', 'disabled');
                input.removeAttribute('required');
            });
        }
    }

    function fillConfigurationFields(row, productId, configurationId) {
        const configuration = productId && configurationId ? getConfiguration(productId, configurationId) : null;
        const keycapInput = row.querySelector('[data-field="config_keycap"]');
        const mainboardInput = row.querySelector('[data-field="config_mainboard"]');
        const layoutInput = row.querySelector('[data-field="config_layout"]');
        const switchInput = row.querySelector('[data-field="config_switch_type"]');
        const caseInput = row.querySelector('[data-field="config_case_type"]');
        const foamInput = row.querySelector('[data-field="config_foam"]');
        const descriptionInput = row.querySelector('[data-field="config_description"]');
        const description = configuration ? configuration.description : '';
        if (layoutInput && !layoutInput.value) {
            layoutInput.value = configuration ? (configuration.layout || '') : layoutInput.value;
        }
        if (switchInput && !switchInput.value) {
            switchInput.value = configuration ? (configuration.switch || '') : switchInput.value;
        }
        if (caseInput && !caseInput.value) {
            caseInput.value = configuration ? (configuration.case || '') : caseInput.value;
        }
        if (foamInput && !foamInput.value) {
            foamInput.value = configuration ? (configuration.others || '') : foamInput.value;
        }
        if (descriptionInput && !descriptionInput.value && description) {
            descriptionInput.value = description;
        }
        if (keycapInput && !keycapInput.value) {
            keycapInput.value = configuration ? (configuration.keycap || '') : keycapInput.value;
        }
        if (mainboardInput && !mainboardInput.value) {
            mainboardInput.value = configuration ? (configuration.mainboard || '') : mainboardInput.value;
        }

        const hint = row.querySelector('[data-field="configuration_hint"]');
        if (hint) {
            const lines = [];
            if (configuration) {
                if (configuration.description) {
                    lines.push(configuration.description);
                }
                const specs = [];
                if (configuration.keycap) {
                    specs.push(`Keycap: ${configuration.keycap}`);
                }
                if (configuration.mainboard) {
                    specs.push(`Mainboard: ${configuration.mainboard}`);
                }
                if (configuration.layout) {
                    specs.push(`Layout: ${configuration.layout}`);
                }
                if (configuration.switch) {
                    specs.push(`Switch: ${configuration.switch}`);
                }
                if (configuration.case) {
                    specs.push(`Case: ${configuration.case}`);
                }
                if (configuration.others) {
                    specs.push(`Foam: ${configuration.others}`);
                }
                if (specs.length) {
                    lines.push(specs.join(' • '));
                }
            }
            hint.innerHTML = lines.map(text => `<div>${text}</div>`).join('');
        }
    }

    function attachEvents(row) {
        const productSelect = row.querySelector('[data-field="product_id"]');
        const configurationSelect = row.querySelector('[data-field="configuration_id"]');
        const unitPriceInput = row.querySelector('[data-field="unit_price"]');
        const quantityInput = row.querySelector('[data-field="quantity"]');
        const vatInput = row.querySelector('[data-field="vat"]');
        const productModeButtons = row.querySelectorAll('[data-action="toggle-product-mode"]');
        const configurationModeButtons = row.querySelectorAll('[data-action="toggle-configuration-mode"]');
        const removeButton = row.querySelector('[data-action="remove"]');

        if (productSelect) {
            productSelect.addEventListener('change', () => {
                const productId = productSelect.value;
                if (configurationSelect) {
                    configurationSelect.innerHTML = buildConfigurationOptions(productId, '');
                }
                if (productId) {
                    const selected = products.find(product => product.id === productId);
                    if (selected && unitPriceInput && !unitPriceInput.dataset.manual) {
                        unitPriceInput.value = selected.price || 0;
                    }
                }
                updateProductHint(row, productId);
                fillConfigurationFields(row, productId, configurationSelect ? configurationSelect.value : '');
                recalcTotals();
            });
        }

        if (configurationSelect) {
            configurationSelect.addEventListener('change', () => {
                const productId = productSelect ? productSelect.value : '';
                const configurationId = configurationSelect.value;
                const configuration = productId ? getConfiguration(productId, configurationId) : null;
                if (configuration && unitPriceInput && !unitPriceInput.dataset.manual) {
                    unitPriceInput.value = configuration.price || 0;
                }
                fillConfigurationFields(row, productId, configurationId);
                recalcTotals();
            });
        }

        if (unitPriceInput) {
            unitPriceInput.addEventListener('input', () => {
                unitPriceInput.dataset.manual = unitPriceInput.value ? 'true' : '';
                recalcTotals();
            });
        }

        [quantityInput, vatInput].forEach(input => {
            if (!input) {
                return;
            }
            input.addEventListener('input', recalcTotals);
        });

        productModeButtons.forEach(button => {
            button.addEventListener('click', () => {
                applyProductMode(row, button.getAttribute('data-mode'));
            });
        });

        configurationModeButtons.forEach(button => {
            button.addEventListener('click', () => {
                applyConfigurationMode(row, button.getAttribute('data-mode'));
            });
        });

        if (removeButton) {
            removeButton.addEventListener('click', () => {
                row.remove();
                updateRowIndexes();
                recalcTotals();
            });
        }
    }

    function addDetailRow(data = {}) {
        const index = detailIndex++;
        const productId = data.product_id || '';
        const configurationId = data.configuration_id || '';
        const productMode = data.product_mode || 'existing';
        const configurationMode = data.configuration_mode || 'existing';
        const vatValue = typeof data.vat !== 'undefined' ? data.vat : 8;

        const row = document.createElement('div');
        row.className = 'order-detail-row border rounded-3 p-3 mb-3';
        row.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-semibold mb-0">Sản phẩm #<span data-field="row_index"></span></h6>
                ${isEditMode ? '' : '<button class="btn btn-link text-danger p-0" type="button" data-action="remove"><i class="bi bi-x-lg"></i></button>'}
            </div>
            <input type="hidden" name="details[${index}][detail_id]" value="${data.detail_id || ''}">
            <div class="row g-3">
                <div class="col-lg-6">
                    <label class="form-label">Sản phẩm</label>
                    <div class="btn-group w-100 mb-2" role="group">
                        <input type="hidden" name="details[${index}][product_mode]" data-field="product_mode" value="${productMode}">
                        <button type="button" class="btn btn-outline-primary ${productMode === 'existing' ? 'active' : ''}" data-action="toggle-product-mode" data-mode="existing">Chọn sản phẩm có sẵn</button>
                        <button type="button" class="btn btn-outline-primary ${productMode === 'new' ? 'active' : ''}" data-action="toggle-product-mode" data-mode="new">Nhập sản phẩm mới</button>
                    </div>
                    <div data-product-section="existing" class="${productMode === 'existing' ? '' : 'd-none'}">
                        <select class="form-select" name="details[${index}][product_id]" data-field="product_id" ${productMode === 'existing' ? 'required' : ''} ${isEditMode ? 'disabled' : ''}>
                            ${buildProductOptions(productId)}
                        </select>
                        <div class="form-text text-muted" data-field="product_hint"></div>
                    </div>
                    <div data-product-section="new" class="${productMode === 'new' ? '' : 'd-none'} border rounded p-3 mt-2">
                        <div class="row g-2">
                            <div class="col-12">
                                <input class="form-control" name="details[${index}][new_product_name]" data-field="new_product_name" placeholder="Tên sản phẩm" value="${data.new_product_name || ''}" ${productMode === 'new' ? 'required' : 'disabled'} data-required="true" ${isEditMode ? 'disabled' : ''}>
                            </div>
                            <div class="col-sm-6">
                                <input class="form-control" name="details[${index}][new_product_unit]" data-field="new_product_unit" placeholder="Đơn vị" value="${data.new_product_unit || ''}" ${productMode === 'new' ? '' : 'disabled'} ${isEditMode ? 'disabled' : ''}>
                            </div>
                            <div class="col-sm-6">
                                <textarea class="form-control" rows="1" name="details[${index}][new_product_description]" data-field="new_product_description" placeholder="Mô tả" ${productMode === 'new' ? '' : 'disabled'} ${isEditMode ? 'disabled' : ''}>${data.new_product_description || ''}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <label class="form-label">Cấu hình sản phẩm</label>
                    <div class="btn-group w-100 mb-2" role="group">
                        <input type="hidden" name="details[${index}][configuration_mode]" data-field="configuration_mode" value="${configurationMode}">
                        <button type="button" class="btn btn-outline-primary ${configurationMode === 'existing' ? 'active' : ''}" data-action="toggle-configuration-mode" data-mode="existing">Chọn cấu hình có sẵn</button>
                        <button type="button" class="btn btn-outline-primary ${configurationMode === 'new' ? 'active' : ''}" data-action="toggle-configuration-mode" data-mode="new">Nhập cấu hình mới</button>
                    </div>
                    <div data-configuration-section="existing" class="${configurationMode === 'existing' ? '' : 'd-none'}">
                        <select class="form-select" name="details[${index}][configuration_id]" data-field="configuration_id" ${configurationMode === 'existing' ? 'required' : ''} ${isEditMode ? 'disabled' : ''}>
                            ${buildConfigurationOptions(productId, configurationId)}
                        </select>
                        <div class="form-text text-muted" data-field="configuration_hint"></div>
                    </div>
                    <div data-configuration-section="new" class="${configurationMode === 'new' ? '' : 'd-none'} border rounded p-3 mt-2">
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <input class="form-control" name="details[${index}][new_configuration_name]" data-field="new_configuration_name" placeholder="Tên cấu hình" value="${data.new_configuration_name || ''}" ${configurationMode === 'new' ? 'required' : 'disabled'} data-required="true" ${isEditMode ? 'disabled' : ''}>
                            </div>
                            <div class="col-sm-3">
                                <input class="form-control" type="number" min="0" step="1000" name="details[${index}][new_configuration_price]" data-field="new_configuration_price" placeholder="Giá (đ)" value="${data.new_configuration_price || ''}" ${configurationMode === 'new' ? 'required' : 'disabled'} ${isEditMode ? 'disabled' : ''}>
                            </div>
                            <div class="col-sm-3">
                                <textarea class="form-control" rows="1" name="details[${index}][new_configuration_description]" data-field="new_configuration_description" placeholder="Mô tả" ${configurationMode === 'new' ? 'required' : 'disabled'} ${isEditMode ? 'disabled' : ''}>${data.new_configuration_description || ''}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label">Mô tả cấu hình</label>
                    <input class="form-control" name="details[${index}][config_description]" data-field="config_description" value="${data.config_description || ''}" ${isEditMode ? 'readonly' : 'required'}>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label">Keycap</label>
                    <input class="form-control" name="details[${index}][config_keycap]" data-field="config_keycap" value="${data.config_keycap || ''}" ${isEditMode ? 'readonly' : 'required'}>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label">Mainboard</label>
                    <input class="form-control" name="details[${index}][config_mainboard]" data-field="config_mainboard" value="${data.config_mainboard || ''}" ${isEditMode ? 'readonly' : 'required'}>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label">Layout</label>
                    <input class="form-control" name="details[${index}][config_layout]" data-field="config_layout" value="${data.config_layout || ''}" ${isEditMode ? 'readonly' : 'required'}>
                </div>
                <div class="col-12">
                    <label class="form-label">Switch / Case / Foam</label>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input class="form-control" name="details[${index}][config_switch_type]" data-field="config_switch_type" placeholder="Switch" value="${data.config_switch_type || ''}" ${isEditMode ? 'readonly' : 'required'}>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" name="details[${index}][config_case_type]" data-field="config_case_type" placeholder="Case" value="${data.config_case_type || ''}" ${isEditMode ? 'readonly' : 'required'}>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" name="details[${index}][config_foam]" data-field="config_foam" placeholder="Foam" value="${data.config_foam || ''}" ${isEditMode ? 'readonly' : 'required'}>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label">Số lượng</label>
                    <input class="form-control" type="number" min="${data.min_quantity || 1}" name="details[${index}][quantity]" data-field="quantity" value="${data.quantity || 1}" required>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label">Đơn giá (đ)</label>
                    <input class="form-control" type="number" min="0" step="1000" name="details[${index}][unit_price]" data-field="unit_price" value="${data.unit_price || 0}" data-manual="${data.unit_price ? 'true' : ''}" ${isEditMode ? 'readonly' : 'required'}>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label">VAT (%)</label>
                    <input class="form-control" type="number" min="0" step="0.1" name="details[${index}][vat]" data-field="vat" value="${vatValue}" ${isEditMode ? 'readonly' : 'required'}>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label">Ngày giao dự kiến</label>
                    <input class="form-control" type="datetime-local" name="details[${index}][delivery_date]" data-field="delivery_date" value="${data.delivery_date || ''}" data-min-base="${data.min_delivery_date || ''}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Yêu cầu chi tiết</label>
                    <textarea class="form-control" rows="2" name="details[${index}][requirement]" ${isEditMode ? 'readonly' : ''}>${data.requirement || ''}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ghi chú thêm</label>
                    <textarea class="form-control" rows="2" name="details[${index}][note]" ${isEditMode ? 'readonly' : ''}>${data.note || ''}</textarea>
                </div>
                <div class="col-12 text-end">
                    <span class="text-muted small">Thành tiền: <span class="fw-semibold text-primary" data-field="line_total">0 đ</span></span>
                </div>
            </div>
        `;

        detailContainer.appendChild(row);
        applyProductMode(row, productMode);
        applyConfigurationMode(row, configurationMode);
        if (isEditMode) {
            row.querySelectorAll('[data-action="toggle-product-mode"], [data-action="toggle-configuration-mode"]').forEach(button => {
                button.setAttribute('disabled', 'disabled');
            });
        }
        attachEvents(row);
        fillConfigurationFields(row, productId, configurationId);
        updateProductHint(row, productId);
        applyDeliveryMin(row);
        updateRowIndexes();
        recalcTotals();
    }

    if (addRowButton) {
        addRowButton.addEventListener('click', () => {
            addDetailRow({ quantity: 1, vat: 8 });
        });
    }

    if (initialDetails.length > 0) {
        initialDetails.forEach(detail => addDetailRow(detail));
    } else {
        addDetailRow({ quantity: 1, vat: 8 });
    }
})();
</script>
