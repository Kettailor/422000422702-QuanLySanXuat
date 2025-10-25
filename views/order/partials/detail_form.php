<?php
$detailItems = $orderDetails ?? [];
if (empty($detailItems)) {
    $detailItems = [[]];
}
$productOptions = array_map(static fn($product) => [
    'id' => $product['IdSanPham'],
    'name' => $product['TenSanPham'],
    'unit' => $product['DonVi'] ?? '',
    'price' => (float) ($product['GiaBan'] ?? 0),
], $products ?? []);
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
        'product_mode' => 'existing',
        'product_id' => $detail['IdSanPham'] ?? null,
        'quantity' => (int) ($detail['SoLuong'] ?? 0),
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
            <label class="form-label fw-semibold">Chi tiết cấu hình bàn phím Aurora</label>
            <p class="text-muted small mb-0">Thêm các cấu hình Aurora cần lắp ráp, số lượng và yêu cầu bàn giao.</p>
        </div>
        <button class="btn btn-outline-primary" type="button" id="add-detail-row"><i class="bi bi-plus-lg me-2"></i>Thêm cấu hình Aurora</button>
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

    function recalcTotals() {
        let total = 0;
        detailContainer.querySelectorAll('.order-detail-row').forEach(row => {
            const quantity = parseFloat(row.querySelector('[data-field="quantity"]').value) || 0;
            const unitPrice = parseFloat(row.querySelector('[data-field="unit_price"]').value) || 0;
            const vat = parseFloat(row.querySelector('[data-field="vat"]').value) || 0;
            const vatRate = vat > 1 ? vat / 100 : vat;
            const lineTotal = quantity * unitPrice * (1 + vatRate);
            total += lineTotal;
            row.querySelector('[data-field="line_total"]').textContent = formatCurrency(lineTotal);
        });
        const totalDisplay = document.getElementById('order-total-display');
        if (totalDisplay) {
            totalDisplay.textContent = formatCurrency(total);
        }
    }

    function buildProductOptions(selectedId) {
        const options = ['<option value="">-- Chọn cấu hình Aurora --</option>'];
        productOptions.forEach(product => {
            const selected = selectedId === product.id ? 'selected' : '';
            options.push(`<option value="${product.id}" data-price="${product.price}" data-unit="${product.unit || ''}" ${selected}>${product.name}</option>`);
        });
        return options.join('');
    }

    function setProductDefaults(row, productId) {
        const product = productOptions.find(item => item.id === productId);
        if (product) {
            row.querySelector('[data-field="unit_price"]').value = product.price || 0;
        }
    }

    function toggleNewProductFields(row, mode) {
        const newFields = row.querySelector('.new-product-fields');
        const existingFields = row.querySelector('.existing-product-fields');
        if (mode === 'new') {
            newFields.classList.remove('d-none');
            existingFields.classList.add('d-none');
        } else {
            newFields.classList.add('d-none');
            existingFields.classList.remove('d-none');
        }
    }

    function attachEvents(row) {
        const productMode = row.querySelector('[data-field="product_mode"]');
        const productSelect = row.querySelector('[data-field="product_id"]');
        const inputsToWatch = row.querySelectorAll('[data-field="quantity"], [data-field="unit_price"], [data-field="vat"]');
        productMode.addEventListener('change', () => {
            toggleNewProductFields(row, productMode.value);
            if (productMode.value === 'existing') {
                setProductDefaults(row, productSelect.value);
            }
            recalcTotals();
        });
        productSelect.addEventListener('change', () => {
            setProductDefaults(row, productSelect.value);
            recalcTotals();
        });
        inputsToWatch.forEach(input => input.addEventListener('input', recalcTotals));
        const removeButton = row.querySelector('[data-action="remove"]');
        removeButton.addEventListener('click', () => {
            row.remove();
            recalcTotals();
        });
    }

    function addDetailRow(data = {}) {
        const index = detailIndex++;
        const row = document.createElement('div');
        row.className = 'order-detail-row border rounded p-3 mb-3';
        const mode = data.product_mode || 'existing';
        const vatValue = typeof data.vat !== 'undefined' ? data.vat : 8;
        row.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-semibold mb-0">Cấu hình Aurora #${index + 1}</h6>
                <button class="btn btn-sm btn-outline-danger" type="button" data-action="remove"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Loại cấu hình</label>
                    <select class="form-select" name="details[${index}][product_mode]" data-field="product_mode">
                        <option value="existing" ${mode === 'existing' ? 'selected' : ''}>Chọn từ danh mục Aurora</option>
                        <option value="new" ${mode === 'new' ? 'selected' : ''}>Nhập cấu hình Aurora mới</option>
                    </select>
                </div>
                <div class="col-md-4 existing-product-fields">
                    <label class="form-label">Bàn phím/Linh kiện</label>
                    <select class="form-select" name="details[${index}][product_id]" data-field="product_id">
                        ${buildProductOptions(data.product_id || '')}
                    </select>
                </div>
                <div class="col-md-8 new-product-fields d-none">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Mã cấu hình</label>
                            <input class="form-control" name="details[${index}][new_product_id]" value="${data.new_product_id || ''}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tên cấu hình</label>
                            <input class="form-control" name="details[${index}][new_product_name]" value="${data.new_product_name || ''}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Đơn vị</label>
                            <input class="form-control" name="details[${index}][new_product_unit]" value="${data.new_product_unit || ''}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Giá bán</label>
                            <input class="form-control" type="number" min="0" step="1000" name="details[${index}][new_product_price]" value="${data.new_product_price || ''}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Mô tả (layout, switch, phụ kiện)</label>
                            <input class="form-control" name="details[${index}][new_product_description]" value="${data.new_product_description || ''}">
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Số lượng</label>
                    <input class="form-control" type="number" min="1" name="details[${index}][quantity]" data-field="quantity" value="${data.quantity || 1}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Đơn giá Aurora (đ)</label>
                    <input class="form-control" type="number" min="0" step="1000" name="details[${index}][unit_price]" data-field="unit_price" value="${data.unit_price || 0}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">VAT (%)</label>
                    <input class="form-control" type="number" min="0" step="0.1" name="details[${index}][vat]" data-field="vat" value="${vatValue}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Thời gian bàn giao dự kiến</label>
                    <input class="form-control" type="datetime-local" name="details[${index}][delivery_date]" value="${data.delivery_date || ''}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Yêu cầu lắp ráp chi tiết</label>
                    <textarea class="form-control" rows="2" name="details[${index}][requirement]">${data.requirement || ''}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ghi chú đóng gói</label>
                    <textarea class="form-control" rows="2" name="details[${index}][note]">${data.note || ''}</textarea>
                </div>
                <div class="col-12 text-end">
                    <span class="text-muted small">Thành tiền Aurora: <span class="fw-semibold text-primary" data-field="line_total">0 đ</span></span>
                </div>
            </div>
        `;
        detailContainer.appendChild(row);
        toggleNewProductFields(row, mode);
        setProductDefaults(row, row.querySelector('[data-field="product_id"]').value);
        attachEvents(row);
        recalcTotals();
    }

    addRowButton.addEventListener('click', () => addDetailRow({ quantity: 1, vat: 8 }));
    initialDetails.forEach(detail => addDetailRow(detail));
})();
</script>
