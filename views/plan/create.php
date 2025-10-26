<?php
$orderDetails = $orderDetails ?? [];
$managers = $managers ?? [];
$workshops = $workshops ?? [];
$selectedOrderDetailId = $selectedOrderDetailId ?? null;

$orderPayload = [];
foreach ($orderDetails as $detail) {
    $orderPayload[] = [
        'id' => $detail['IdTTCTDonHang'],
        'order_id' => $detail['IdDonHang'] ?? null,
        'product' => $detail['TenSanPham'] ?? null,
        'configuration' => $detail['TenCauHinh'] ?? null,
        'quantity' => (int) ($detail['SoLuong'] ?? 0),
        'unit' => $detail['DonVi'] ?? 'sản phẩm',
        'request' => $detail['YeuCauChiTiet'] ?? $detail['YeuCauDonHang'] ?? null,
        'delivery_date' => $detail['NgayGiao'] ?? null,
        'note' => $detail['GhiChuChiTiet'] ?? null,
        'layout' => $detail['Layout'] ?? null,
        'switch' => $detail['SwitchType'] ?? null,
        'case' => $detail['CaseType'] ?? null,
        'foam' => $detail['Foam'] ?? null,
        'components' => $detail['components'] ?? [],
    ];
}

$workshopPayload = [];
foreach ($workshops as $workshop) {
    if (empty($workshop['IdXuong'])) {
        continue;
    }
    $workshopPayload[] = [
        'id' => $workshop['IdXuong'],
        'name' => $workshop['TenXuong'] ?? $workshop['IdXuong'],
    ];
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Tạo kế hoạch sản xuất</h3>
        <p class="text-muted mb-0">Lập kế hoạch theo đúng quy trình: chọn đơn hàng, định lượng và phân xưởng phụ trách.</p>
    </div>
    <a href="?controller=plan&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<div class="card p-4">
    <form action="?controller=plan&action=store" method="post" class="row g-4">
        <div class="col-12">
            <div class="d-flex align-items-start gap-3">
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">Bước 1</span>
                <div>
                    <h5 class="mb-1">Chọn chi tiết đơn hàng cần lập kế hoạch</h5>
                    <p class="text-muted small mb-0">Nhân viên kinh doanh đã nhập đơn hàng, vui lòng chọn chi tiết sản phẩm để kế hoạch lấy dữ liệu tự động.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label">Mã kế hoạch</label>
            <input type="text" name="IdKeHoachSanXuat" class="form-control" placeholder="Tự sinh nếu để trống">
        </div>
        <div class="col-md-8">
            <label class="form-label">Chi tiết đơn hàng</label>
            <select name="IdTTCTDonHang" id="order-detail-select" class="form-select" required>
                <option value="">-- Chọn sản phẩm trong đơn hàng --</option>
                <?php foreach ($orderDetails as $detail): ?>
                    <?php
                    $labelParts = [
                        'ĐH ' . ($detail['IdDonHang'] ?? ''),
                        $detail['TenSanPham'] ?? '',
                        $detail['TenCauHinh'] ?? '',
                        'SL: ' . ($detail['SoLuong'] ?? 0),
                    ];
                    $label = implode(' • ', array_filter($labelParts));
                    $isSelected = $selectedOrderDetailId && $selectedOrderDetailId === $detail['IdTTCTDonHang'];
                    ?>
                    <option
                        value="<?= htmlspecialchars($detail['IdTTCTDonHang']) ?>"
                        data-order="<?= htmlspecialchars($detail['IdDonHang'] ?? '') ?>"
                        data-product="<?= htmlspecialchars($detail['TenSanPham'] ?? '') ?>"
                        data-config="<?= htmlspecialchars($detail['TenCauHinh'] ?? '') ?>"
                        data-quantity="<?= htmlspecialchars((string) ($detail['SoLuong'] ?? 0)) ?>"
                        data-unit="<?= htmlspecialchars($detail['DonVi'] ?? 'sản phẩm') ?>"
                        data-request="<?= htmlspecialchars($detail['YeuCauChiTiet'] ?? $detail['YeuCauDonHang'] ?? '') ?>"
                        data-layout="<?= htmlspecialchars($detail['Layout'] ?? '') ?>"
                        data-switch="<?= htmlspecialchars($detail['SwitchType'] ?? '') ?>"
                        data-case="<?= htmlspecialchars($detail['CaseType'] ?? '') ?>"
                        data-foam="<?= htmlspecialchars($detail['Foam'] ?? '') ?>"
                        data-delivery="<?= htmlspecialchars($detail['NgayGiao'] ?? '') ?>"
                        <?= $isSelected ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($label) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-12">
            <div id="order-detail-summary" class="alert alert-secondary mb-0">
                <strong>Chưa chọn chi tiết đơn hàng.</strong>
                <div class="small text-muted">Vui lòng chọn chi tiết sản phẩm để xem thông tin cấu hình, yêu cầu và lịch giao dự kiến.</div>
            </div>
        </div>

        <div class="col-12 pt-2">
            <div class="d-flex align-items-start gap-3">
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">Bước 2</span>
                <div>
                    <h5 class="mb-1">Điền thông tin kế hoạch tổng</h5>
                    <p class="text-muted small mb-0">Số lượng, thời gian và trạng thái sẽ được đồng bộ với kế hoạch xưởng.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label">Ban giám đốc phụ trách</label>
            <select name="BanGiamDoc" class="form-select">
                <option value="">-- Chưa phân công --</option>
                <?php foreach ($managers as $manager): ?>
                    <option value="<?= htmlspecialchars($manager['IdNhanVien']) ?>">
                        <?= htmlspecialchars($manager['HoTen'] . ' - ' . ($manager['ChucVu'] ?? '')) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Số lượng kế hoạch</label>
            <input type="number" name="SoLuong" id="plan-quantity" class="form-control" min="0" placeholder="Theo số lượng đơn hàng">
        </div>
        <div class="col-md-4">
            <label class="form-label">Trạng thái</label>
            <select name="TrangThai" class="form-select">
                <option value="Mới tạo">Mới tạo</option>
                <option value="Đang thực hiện">Đang thực hiện</option>
                <option value="Hoàn thành">Hoàn thành</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Thời gian bắt đầu</label>
            <input type="datetime-local" name="ThoiGianBD" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Thời gian kết thúc dự kiến</label>
            <input type="datetime-local" name="ThoiGianKetThuc" class="form-control">
        </div>

        <div class="col-12 pt-2">
            <div class="d-flex align-items-start gap-3">
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">Bước 3</span>
                <div>
                    <h5 class="mb-1">Phân bổ xưởng phụ trách theo cấu hình</h5>
                    <p class="text-muted small mb-2">Sử dụng dữ liệu cấu hình sản phẩm (keycap, switch, case, PCB, v.v.) để gán xưởng và giao hạn chót.</p>
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn btn-sm btn-outline-primary" type="button" id="prefill-assignments">
                            <i class="bi bi-lightning-charge me-1"></i>Tự động tải hạng mục theo cấu hình
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" type="button" id="add-assignment">
                            <i class="bi bi-plus-lg me-1"></i>Thêm hạng mục thủ công
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div id="assignment-container" class="vstack gap-3"></div>
            <div class="alert alert-light border mt-2 mb-0" id="assignment-empty">
                Chưa có hạng mục nào. Hãy nhấn "Tự động tải hạng mục" để lấy gợi ý từ cấu hình sản phẩm hoặc thêm thủ công.
            </div>
        </div>

        <div class="col-12 text-end">
            <button class="btn btn-primary px-4" type="submit">Lưu kế hoạch</button>
        </div>
    </form>
</div>

<script>
    (function () {
        const orderDetailSelect = document.getElementById('order-detail-select');
        const summaryBox = document.getElementById('order-detail-summary');
        const quantityInput = document.getElementById('plan-quantity');
        const assignmentContainer = document.getElementById('assignment-container');
        const assignmentEmpty = document.getElementById('assignment-empty');
        const addAssignmentButton = document.getElementById('add-assignment');
        const prefillButton = document.getElementById('prefill-assignments');

        const orderDetails = new Map();
        const orderPayload = <?= json_encode($orderPayload, JSON_UNESCAPED_UNICODE) ?>;
        const workshops = <?= json_encode($workshopPayload, JSON_UNESCAPED_UNICODE) ?>;
        const defaultSelected = <?= json_encode($selectedOrderDetailId) ?>;
        const statuses = ['Đang chuẩn bị', 'Đang sản xuất', 'Tạm dừng', 'Hoàn thành'];

        orderPayload.forEach(detail => {
            if (detail && detail.id) {
                orderDetails.set(detail.id, detail);
            }
        });

        let assignmentIndex = 0;

        function escapeHtml(value) {
            if (value === null || value === undefined) {
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
                    case "'":
                        return '&#039;';
                    default:
                        return char;
                }
            });
        }

        function buildWorkshopOptions(selectedId) {
            const options = ['<option value="">-- Chọn xưởng phụ trách --</option>'];
            workshops.forEach(workshop => {
                const selected = selectedId && workshop.id === selectedId ? 'selected' : '';
                options.push(`<option value="${escapeHtml(workshop.id)}" ${selected}>${escapeHtml(workshop.name)}</option>`);
            });
            return options.join('');
        }

        function buildComponentOptions(detail, selectedId) {
            const components = detail ? detail.components || [] : [];
            const options = ['<option value="">-- Chọn công đoạn / hạng mục --</option>'];
            components.forEach(component => {
                const selected = component.id && component.id === selectedId ? 'selected' : '';
                const label = component.name || 'Hạng mục sản xuất';
                options.push(`<option value="${escapeHtml(component.id || '')}" ${selected} data-unit="${escapeHtml(component.unit || detail.unit || 'sản phẩm')}" data-ratio="${escapeHtml(component.quantity_ratio || 1)}" data-default-workshop="${escapeHtml(component.default_workshop || '')}" data-include-request="${component.include_request ? '1' : '0'}">${escapeHtml(label)}</option>`);
            });
            return options.join('');
        }

        function toggleEmptyState() {
            if (!assignmentEmpty) {
                return;
            }
            const hasAssignments = assignmentContainer && assignmentContainer.children.length > 0;
            assignmentEmpty.classList.toggle('d-none', hasAssignments);
        }

        function createAssignmentRow(detail, defaults = {}) {
            const row = document.createElement('div');
            row.className = 'assignment-row border rounded-3 p-3';
            const index = assignmentIndex++;
            const components = buildComponentOptions(detail, defaults.component_id || '');
            const workshopsOptions = buildWorkshopOptions(defaults.workshop_id || '');
            const selectedStatus = defaults.status && statuses.includes(defaults.status) ? defaults.status : statuses[0];

            row.innerHTML = `
                <div class="row g-3">
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Hạng mục / công đoạn</label>
                        <select class="form-select" name="workshop_assignments[${index}][component_id]" data-field="component">
                            ${components}
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Tên hiển thị cho xưởng</label>
                        <input class="form-control" name="workshop_assignments[${index}][label]" data-field="label" value="${escapeHtml(defaults.label || '')}" placeholder="Ví dụ: Lắp switch, in keycap..." data-user-edited="${defaults.label ? '1' : '0'}">
                        <div class="form-text">Tùy chỉnh tên hạng mục gửi tới xưởng.</div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Xưởng phụ trách</label>
                        <select class="form-select" name="workshop_assignments[${index}][workshop_id]" data-field="workshop">
                            ${workshopsOptions}
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Số lượng</label>
                        <input class="form-control" type="number" min="1" name="workshop_assignments[${index}][quantity]" data-field="quantity" value="${escapeHtml(defaults.quantity || '')}">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Trạng thái ban đầu</label>
                        <select class="form-select" name="workshop_assignments[${index}][status]" data-field="status">
                            ${statuses.map(status => `<option value="${escapeHtml(status)}" ${status === selectedStatus ? 'selected' : ''}>${escapeHtml(status)}</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Thời gian bắt đầu</label>
                        <input class="form-control" type="datetime-local" name="workshop_assignments[${index}][start]" data-field="start" value="${escapeHtml(defaults.start || '')}">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Hạn chót</label>
                        <input class="form-control" type="datetime-local" name="workshop_assignments[${index}][end]" data-field="end" value="${escapeHtml(defaults.end || '')}">
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-sm btn-outline-danger" type="button" data-action="remove-assignment">
                            <i class="bi bi-x-lg"></i> Bỏ hạng mục
                        </button>
                    </div>
                </div>
            `;

            assignmentContainer.appendChild(row);

            const componentSelect = row.querySelector('[data-field="component"]');
            const labelInput = row.querySelector('[data-field="label"]');
            const workshopSelect = row.querySelector('[data-field="workshop"]');
            const quantityField = row.querySelector('[data-field="quantity"]');
            const removeButton = row.querySelector('[data-action="remove-assignment"]');

            function applyComponentDefaults(option) {
                if (!option) {
                    return;
                }
                const ratio = parseFloat(option.getAttribute('data-ratio')) || 1;
                const defaultWorkshop = option.getAttribute('data-default-workshop');
                const includeRequest = option.getAttribute('data-include-request') === '1';
                const unitLabel = option.getAttribute('data-unit') || (detail ? detail.unit : 'sản phẩm');

                if (quantityField && (!quantityField.value || quantityField.value === '')) {
                    const baseQuantity = detail ? detail.quantity || 1 : 1;
                    const computed = Math.max(1, Math.round(baseQuantity * ratio));
                    quantityField.value = computed;
                }

                if (workshopSelect && defaultWorkshop && !workshopSelect.value) {
                    workshopSelect.value = defaultWorkshop;
                }

                if (labelInput && labelInput.dataset.userEdited !== '1') {
                    let label = option.textContent.trim();
                    if (includeRequest && detail && detail.request) {
                        label += ' - ' + detail.request;
                    }
                    labelInput.value = label;
                }

                const helper = row.querySelector('.form-text');
                if (helper) {
                    helper.textContent = `Đơn vị: ${unitLabel}`;
                }
            }

            if (componentSelect) {
                componentSelect.addEventListener('change', () => {
                    const selectedOption = componentSelect.options[componentSelect.selectedIndex];
                    if (labelInput && labelInput.dataset.userEdited !== '1') {
                        labelInput.value = selectedOption ? selectedOption.textContent.trim() : '';
                    }
                    applyComponentDefaults(selectedOption);
                });

                const selectedOption = componentSelect.options[componentSelect.selectedIndex];
                if (selectedOption) {
                    applyComponentDefaults(selectedOption);
                }
            }

            if (labelInput) {
                labelInput.addEventListener('input', () => {
                    labelInput.dataset.userEdited = labelInput.value ? '1' : '0';
                });
            }

            if (removeButton) {
                removeButton.addEventListener('click', () => {
                    row.remove();
                    toggleEmptyState();
                });
            }

            toggleEmptyState();
        }

        function addAssignment(detail) {
            createAssignmentRow(detail || null, {});
        }

        function loadAssignmentsFromConfiguration(detail) {
            assignmentContainer.innerHTML = '';
            assignmentIndex = 0;

            if (!detail) {
                toggleEmptyState();
                return;
            }

            const components = detail.components || [];
            if (!components.length) {
                toggleEmptyState();
                return;
            }

            components.forEach(component => {
                const defaults = {
                    component_id: component.id || '',
                    label: component.name || '',
                    workshop_id: component.default_workshop || '',
                    quantity: Math.max(1, Math.round((detail.quantity || 1) * (component.quantity_ratio || 1))),
                };
                createAssignmentRow(detail, defaults);
            });
        }

        function updateSummary() {
            if (!summaryBox) {
                return;
            }

            const selectedId = orderDetailSelect.value;
            const detail = orderDetails.get(selectedId);

            if (!detail) {
                summaryBox.innerHTML = '<strong>Chưa chọn chi tiết đơn hàng.</strong><div class="small text-muted">Vui lòng chọn chi tiết sản phẩm để xem thông tin cấu hình, yêu cầu và lịch giao dự kiến.</div>';
                toggleEmptyState();
                return;
            }

            const lines = [];
            const headline = [detail.product, detail.configuration].filter(Boolean).join(' - ');
            if (headline) {
                lines.push(`<div><strong>${escapeHtml(headline)}</strong></div>`);
            }

            const meta = [`Đơn hàng: ${escapeHtml(detail.order_id || '-')}`, `Số lượng: ${escapeHtml(detail.quantity || 0)} ${escapeHtml(detail.unit || 'sản phẩm')}`];
            if (detail.delivery_date) {
                meta.push(`Giao dự kiến: ${escapeHtml(detail.delivery_date)}`);
            }
            lines.push(`<div class="small text-muted">${meta.join(' • ')}</div>`);

            const specs = [];
            if (detail.layout) specs.push(`Layout: ${escapeHtml(detail.layout)}`);
            if (detail.switch) specs.push(`Switch: ${escapeHtml(detail.switch)}`);
            if (detail.case) specs.push(`Case: ${escapeHtml(detail.case)}`);
            if (detail.foam) specs.push(`Foam: ${escapeHtml(detail.foam)}`);
            if (specs.length) {
                lines.push(`<div class="small text-muted">${specs.join(' • ')}</div>`);
            }

            if (detail.request) {
                lines.push(`<div class="small text-muted">Yêu cầu khách hàng: ${escapeHtml(detail.request)}</div>`);
            }

            summaryBox.innerHTML = lines.join('');

            if (quantityInput && (!quantityInput.value || quantityInput.dataset.userEdited !== '1')) {
                quantityInput.value = detail.quantity || '';
            }

            if (!assignmentContainer.children.length && detail.components && detail.components.length) {
                loadAssignmentsFromConfiguration(detail);
            }
        }

        if (quantityInput) {
            quantityInput.addEventListener('input', () => {
                quantityInput.dataset.userEdited = quantityInput.value ? '1' : '0';
            });
        }

        if (addAssignmentButton) {
            addAssignmentButton.addEventListener('click', () => {
                const detail = orderDetails.get(orderDetailSelect.value);
                addAssignment(detail);
            });
        }

        if (prefillButton) {
            prefillButton.addEventListener('click', () => {
                const detail = orderDetails.get(orderDetailSelect.value);
                loadAssignmentsFromConfiguration(detail);
            });
        }

        if (orderDetailSelect) {
            orderDetailSelect.addEventListener('change', () => {
                if (quantityInput) {
                    quantityInput.dataset.userEdited = '0';
                }
                updateSummary();
            });

            if (defaultSelected && orderDetails.has(defaultSelected)) {
                orderDetailSelect.value = defaultSelected;
            }
        }

        updateSummary();
        toggleEmptyState();
    })();
</script>
