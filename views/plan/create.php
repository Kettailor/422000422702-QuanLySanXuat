<?php
$pendingOrders = $pendingOrders ?? [];
$selectedOrderDetailId = $selectedOrderDetailId ?? null;
$selectedOrderDetail = $selectedOrderDetail ?? null;
$componentAssignments = $componentAssignments ?? [];
$configurationDetails = $configurationDetails ?? [];
$workshops = $workshops ?? [];
$currentUser = $currentUser ?? null;

$formatDate = static function (?string $value, string $format = 'd/m/Y H:i'): string {
    if (!$value) {
        return '-';
    }
    $timestamp = strtotime($value);
    if ($timestamp === false) {
        return '-';
    }
    return date($format, $timestamp);
};

$toDateTimeInput = static function (?string $value, string $fallback = ''): string {
    if ($value) {
        $timestamp = strtotime($value);
        if ($timestamp !== false) {
            return date('Y-m-d\TH:i', $timestamp);
        }
    }
    return $fallback;
};

$defaultStart = date('Y-m-d\TH:i');
$defaultEnd = $toDateTimeInput($selectedOrderDetail['NgayGiao'] ?? null, date('Y-m-d\TH:i', strtotime('+7 days')));
$selectedQuantity = (int) ($selectedOrderDetail['SoLuong'] ?? 0);
$configurationHeaders = [
    'Keycap' => 'Keycap',
    'Mainboard' => 'Mainboard',
    'Layout' => 'Layout',
    'SwitchType' => 'Switch',
    'CaseType' => 'Case',
    'Foam' => 'Foam',
];
$configurationLookup = [];
foreach ($configurationDetails as $detail) {
    $configurationLookup[$detail['key']] = $detail['value'];
}
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1">Lập kế hoạch sản xuất</h2>
        <p class="text-muted mb-0">Chọn đơn hàng cần xử lý, xác nhận thông tin và phân công từng xưởng phụ trách cấu hình sản phẩm.</p>
    </div>
    <a href="?controller=plan&action=index" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Quay về tổng quan
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">1. Chọn dòng sản phẩm</h5>
                <span class="text-muted small">Các dòng chưa có kế hoạch sẽ hiển thị tại đây.</span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($pendingOrders)): ?>
                    <div class="p-4 text-center text-muted">Không còn đơn hàng chờ lập kế hoạch.</div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($pendingOrders as $order): ?>
                            <div class="list-group-item py-3">
                                <div class="fw-semibold mb-1">Đơn <?= htmlspecialchars($order['IdDonHang'] ?? '-') ?></div>
                                <?php if (!empty($order['TenKhachHang'])): ?>
                                    <div class="text-muted small">Khách hàng: <?= htmlspecialchars($order['TenKhachHang']) ?></div>
                                <?php endif; ?>
                                <?php
                                $orderEmail = $order['EmailLienHe'] ?? null;
                                if (!$orderEmail && !empty($order['details'][0]['Email'])) {
                                    $orderEmail = $order['details'][0]['Email'];
                                }
                                ?>
                                <?php if (!empty($orderEmail)): ?>
                                    <div class="text-muted small">Email: <?= htmlspecialchars($orderEmail) ?></div>
                                <?php endif; ?>
                                <div class="mt-3 vstack gap-2">
                                    <?php foreach ($order['details'] as $detail): ?>
                                        <?php $isSelected = ($detail['IdTTCTDonHang'] ?? null) === $selectedOrderDetailId; ?>
                                        <div class="border rounded-3 p-3 position-relative <?= $isSelected ? 'border-primary shadow-sm' : '' ?>">
                                            <div class="d-flex justify-content-between align-items-start gap-2">
                                                <div>
                                                    <div class="fw-semibold"><?= htmlspecialchars($detail['TenSanPham'] ?? 'Sản phẩm') ?></div>
                                                    <div class="text-muted small"><?= htmlspecialchars($detail['TenCauHinh'] ?? 'Cấu hình tiêu chuẩn') ?></div>
                                                </div>
                                                <div class="text-end">
                                                    <div class="fw-semibold"><?= htmlspecialchars((string) ($detail['SoLuong'] ?? 0)) ?> <?= htmlspecialchars($detail['DonVi'] ?? 'sp') ?></div>
                                                    <?php if (!empty($detail['NgayGiao'])): ?>
                                                        <div class="text-muted small">Giao: <?= $formatDate($detail['NgayGiao']) ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="text-muted small mt-2">
                                                <?= htmlspecialchars($detail['YeuCauChiTiet'] ?? $detail['YeuCauDonHang'] ?? 'Không có yêu cầu thêm') ?>
                                            </div>
                                            <a class="stretched-link" href="?controller=plan&action=create&order_detail_id=<?= urlencode($detail['IdTTCTDonHang'] ?? '') ?>"></a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">2. Xác nhận thông tin & phân công xưởng</h5>
                    <span class="text-muted small">Thông tin đơn hàng được tự động điền từ hệ thống.</span>
                </div>
            </div>
            <?php if (!$selectedOrderDetail): ?>
                <div class="card-body">
                    <div class="alert alert-light border">
                        Vui lòng chọn một dòng sản phẩm ở cột bên trái để bắt đầu lập kế hoạch.
                    </div>
                </div>
            <?php else: ?>
                <div class="card-body">
                    <form method="post" action="?controller=plan&action=store" class="vstack gap-4">
                        <input type="hidden" name="IdTTCTDonHang" value="<?= htmlspecialchars($selectedOrderDetail['IdTTCTDonHang'] ?? '') ?>">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Khách hàng</label>
                                <div class="border rounded-3 p-3 bg-light-subtle">
                                    <div class="fw-semibold"><?= htmlspecialchars($selectedOrderDetail['TenKhachHang'] ?? 'Chưa có thông tin') ?></div>
                                    <?php if (!empty($selectedOrderDetail['TenCongTy'])): ?>
                                        <div class="text-muted small">Công ty: <?= htmlspecialchars($selectedOrderDetail['TenCongTy']) ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($selectedOrderDetail['SoDienThoai'])): ?>
                                        <div class="text-muted small">SĐT: <?= htmlspecialchars($selectedOrderDetail['SoDienThoai']) ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($selectedOrderDetail['EmailLienHe']) || !empty($selectedOrderDetail['Email'])): ?>
                                        <div class="text-muted small">Email: <?= htmlspecialchars($selectedOrderDetail['EmailLienHe'] ?? $selectedOrderDetail['Email'] ?? '') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Sản phẩm</label>
                                <div class="border rounded-3 p-3 bg-light-subtle">
                                    <div class="fw-semibold"><?= htmlspecialchars($selectedOrderDetail['TenSanPham'] ?? 'Sản phẩm') ?></div>
                                    <div class="text-muted small">Cấu hình: <?= htmlspecialchars($selectedOrderDetail['TenCauHinh'] ?? 'Tiêu chuẩn') ?></div>
                                    <?php if (!empty($configurationDetails)): ?>
                                        <div class="d-flex flex-wrap gap-2 mt-2">
                                            <?php foreach ($configurationDetails as $detail): ?>
                                                <span class="badge text-bg-light">
                                                    <?= htmlspecialchars($detail['label']) ?>:
                                                    <span class="fw-semibold ms-1"><?= htmlspecialchars($detail['value']) ?></span>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if (!empty(array_filter($configurationLookup))): ?>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Chi tiết cấu hình sản phẩm</label>
                                    <div class="table-responsive border rounded-3">
                                        <table class="table table-sm align-middle mb-0">
                                            <thead class="table-light">
                                            <tr>
                                                <?php foreach ($configurationHeaders as $headerLabel): ?>
                                                    <th><?= htmlspecialchars($headerLabel) ?></th>
                                                <?php endforeach; ?>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <?php foreach ($configurationHeaders as $field => $headerLabel): ?>
                                                    <td><?= htmlspecialchars($configurationLookup[$field] ?? '-') ?></td>
                                                <?php endforeach; ?>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Số lượng cần sản xuất</label>
                                <input type="number" min="1" name="SoLuong" value="<?= htmlspecialchars((string) max(1, $selectedQuantity)) ?>" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Ngày bắt đầu</label>
                                <input type="datetime-local" name="ThoiGianBD" class="form-control" value="<?= htmlspecialchars($toDateTimeInput($selectedOrderDetail['ThoiGianBD'] ?? null, $defaultStart)) ?>" data-plan-start required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Hạn chót giao hàng</label>
                                <input type="datetime-local" name="ThoiGianKetThuc" class="form-control" value="<?= htmlspecialchars($defaultEnd) ?>" data-plan-end required>
                            </div>
                        </div>

                        <div class="border-top"></div>

                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="fw-semibold mb-1">Phân công xưởng theo cấu hình sản phẩm</h6>
                                    <span class="text-muted small">Điều chỉnh số lượng, thời gian cho từng hạng mục bên dưới.</span>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-add-assignment>
                                    <i class="bi bi-plus-circle me-1"></i> Thêm cấu hình / hạng mục
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="table-light">
                                    <tr>
                                        <th>Cấu hình / Hạng mục</th>
                                        <th style="width: 180px;">Xưởng phụ trách</th>
                                        <th style="width: 140px;">Số lượng</th>
                                        <th style="width: 180px;">Bắt đầu</th>
                                        <th style="width: 180px;">Hạn chót</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($componentAssignments as $index => $component): ?>
                                        <?php $allowedTypes = $component['allowed_workshop_types'] ?? []; ?>
                                        <tr data-assignment-row data-allowed-types="<?= htmlspecialchars(json_encode(array_values($allowedTypes))) ?>">
                                            <td>
                                                <input type="hidden" name="component_assignments[<?= $index ?>][component_id]" value="<?= htmlspecialchars($component['id'] ?? '') ?>">
                                                <input type="hidden" name="component_assignments[<?= $index ?>][configuration_id]" value="<?= htmlspecialchars($component['configuration_id'] ?? '') ?>">
                                                <input type="hidden" name="component_assignments[<?= $index ?>][default_status]" value="<?= htmlspecialchars($component['default_status'] ?? '') ?>">
                                                <input type="text" name="component_assignments[<?= $index ?>][label]" class="form-control" value="<?= htmlspecialchars($component['label'] ?? 'Hạng mục sản xuất') ?>" required>
                                                <?php if (!empty($component['configuration_label'])): ?>
                                                    <div class="text-muted small mt-2">Cấu hình: <?= htmlspecialchars($component['configuration_label']) ?></div>
                                                <?php endif; ?>
                                                <?php if (!empty($component['detail_key']) && !empty($component['detail_value'])): ?>
                                                    <div class="text-muted small mt-1">Chi tiết: <?= htmlspecialchars($component['detail_value']) ?></div>
                                                <?php elseif (!empty($component['configuration_details'])): ?>
                                                    <div class="d-flex flex-wrap gap-1 mt-2">
                                                        <?php foreach ($component['configuration_details'] as $detail): ?>
                                                            <span class="badge rounded-pill text-bg-light"><?= htmlspecialchars($detail['label']) ?>: <?= htmlspecialchars($detail['value']) ?></span>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <select name="component_assignments[<?= $index ?>][workshop_id]" class="form-select" required>
                                                    <option value="">-- Chọn xưởng --</option>
                                                    <?php foreach ($workshops as $workshop): ?>
                                                        <?php $selected = ($workshop['IdXuong'] ?? null) === ($component['default_workshop'] ?? null) ? 'selected' : ''; ?>
                                                        <option value="<?= htmlspecialchars($workshop['IdXuong'] ?? '') ?>" data-workshop-type="<?= htmlspecialchars($workshop['LoaiXuong'] ?? '') ?>" <?= $selected ?>>
                                                            <?= htmlspecialchars($workshop['TenXuong'] ?? 'Xưởng sản xuất') ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" min="1" name="component_assignments[<?= $index ?>][quantity]" class="form-control" value="<?= htmlspecialchars((string) max(1, (int) ($component['default_quantity'] ?? $selectedQuantity))) ?>" required>
                                                <div class="form-text">
                                                    <?= htmlspecialchars($component['unit'] ?? 'sp') ?> (tỉ lệ <?= htmlspecialchars(number_format((float) ($component['quantity_ratio'] ?? 1), 2)) ?>)
                                                </div>
                                            </td>
                                            <td>
                                                <input type="datetime-local" name="component_assignments[<?= $index ?>][start]" class="form-control" value="<?= htmlspecialchars($defaultStart) ?>" data-sync-start>
                                            </td>
                                            <td>
                                                <input type="datetime-local" name="component_assignments[<?= $index ?>][deadline]" class="form-control" value="<?= htmlspecialchars($defaultEnd) ?>" data-sync-end>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="?controller=plan&action=index" class="btn btn-light">Hủy</a>
                            <button type="submit" class="btn btn-primary">Hoàn tất lập kế hoạch</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if ($selectedOrderDetail): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const planStart = document.querySelector('[data-plan-start]');
            const planEnd = document.querySelector('[data-plan-end]');
            const startFields = document.querySelectorAll('[data-sync-start]');
            const endFields = document.querySelectorAll('[data-sync-end]');
            const defaultQuantity = <?= htmlspecialchars((string) max(1, $selectedQuantity)) ?>;
            const now = new Date();
            const toDateTimeLocal = function (date) {
                const pad = (num) => String(num).padStart(2, '0');
                return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
            };
            const todayMin = toDateTimeLocal(now);

            function syncValues(source, targets) {
                if (!source) return;
                targets.forEach(function (input) {
                    if (!input.dataset.userEdited) {
                        input.value = source.value;
                    }
                });
            }

            function setMinDates() {
                if (planStart) {
                    planStart.min = todayMin;
                }
                if (planEnd) {
                    planEnd.min = planStart && planStart.value ? planStart.value : todayMin;
                }
                startFields.forEach(function (input) {
                    input.min = todayMin;
                });
                endFields.forEach(function (input) {
                    const startValue = input.closest('tr')?.querySelector('[data-sync-start]')?.value;
                    input.min = startValue || (planStart ? planStart.value : todayMin);
                });
            }

            function applyWorkshopFilter(row) {
                if (!row) return;
                const select = row.querySelector('select[name^="component_assignments"]');
                if (!select) return;
                const allowedTypes = JSON.parse(row.getAttribute('data-allowed-types') || '[]');
                const options = Array.from(select.querySelectorAll('option'));
                const allowedSet = new Set(allowedTypes.map((type) => type.toLowerCase()));
                let hasAllowed = false;

                options.forEach((option) => {
                    const optionType = (option.getAttribute('data-workshop-type') || '').toLowerCase();
                    if (!option.value) {
                        option.hidden = false;
                        option.disabled = false;
                        return;
                    }
                    if (allowedSet.size === 0 || allowedSet.has(optionType)) {
                        option.hidden = false;
                        option.disabled = false;
                        hasAllowed = true;
                    } else {
                        option.hidden = true;
                        option.disabled = true;
                    }
                });

                if (!hasAllowed && allowedSet.size > 0) {
                    options.forEach((option) => {
                        option.hidden = false;
                        option.disabled = false;
                    });
                }

                if (select.selectedOptions.length === 0 || (select.selectedOptions[0] && select.selectedOptions[0].disabled)) {
                    const firstValid = options.find((option) => !option.disabled && option.value);
                    if (firstValid) {
                        select.value = firstValid.value;
                    }
                }
            }

            function buildAssignmentRow(index) {
                const row = document.createElement('tr');
                row.dataset.assignmentRow = '';
                row.dataset.allowedTypes = JSON.stringify(['Sản xuất']);

                const optionsSource = document.querySelector('select[name^="component_assignments"]');
                const optionMarkup = optionsSource
                    ? Array.from(optionsSource.querySelectorAll('option'))
                        .filter((option) => option.value)
                        .map((option) => {
                            const type = option.getAttribute('data-workshop-type') || '';
                            return `<option value="${option.value}" data-workshop-type="${type}">${option.textContent}</option>`;
                        })
                        .join('')
                    : '';

                row.innerHTML = `
                    <td>
                        <input type="hidden" name="component_assignments[${index}][component_id]" value="">
                        <input type="hidden" name="component_assignments[${index}][configuration_id]" value="">
                        <input type="hidden" name="component_assignments[${index}][default_status]" value="">
                        <input type="text" name="component_assignments[${index}][label]" class="form-control" value="Hạng mục bổ sung" required>
                        <div class="text-muted small mt-2">Tùy chỉnh hạng mục nếu cần.</div>
                    </td>
                    <td>
                        <select name="component_assignments[${index}][workshop_id]" class="form-select" required>
                            <option value="">-- Chọn xưởng --</option>
                            ${optionMarkup}
                        </select>
                    </td>
                    <td>
                        <input type="number" min="1" name="component_assignments[${index}][quantity]" class="form-control" value="${defaultQuantity}" required>
                        <div class="form-text">sp (tỉ lệ 1.00)</div>
                    </td>
                    <td>
                        <input type="datetime-local" name="component_assignments[${index}][start]" class="form-control" value="${planStart ? planStart.value : ''}" data-sync-start>
                    </td>
                    <td>
                        <input type="datetime-local" name="component_assignments[${index}][deadline]" class="form-control" value="${planEnd ? planEnd.value : ''}" data-sync-end>
                    </td>
                `;

                return row;
            }

            function markEdited(event) {
                event.target.dataset.userEdited = 'true';
            }

            startFields.forEach(function (input) {
                input.addEventListener('change', markEdited);
                input.addEventListener('input', markEdited);
            });
            endFields.forEach(function (input) {
                input.addEventListener('change', markEdited);
                input.addEventListener('input', markEdited);
            });

            if (planStart) {
                planStart.addEventListener('change', function () {
                    syncValues(planStart, startFields);
                    setMinDates();
                });
            }
            if (planEnd) {
                planEnd.addEventListener('change', function () {
                    syncValues(planEnd, endFields);
                    setMinDates();
                });
            }

            startFields.forEach(function (input) {
                input.addEventListener('change', setMinDates);
                input.addEventListener('input', setMinDates);
            });
            endFields.forEach(function (input) {
                input.addEventListener('change', setMinDates);
                input.addEventListener('input', setMinDates);
            });

            const addAssignmentButton = document.querySelector('[data-add-assignment]');
            if (addAssignmentButton) {
                addAssignmentButton.addEventListener('click', () => {
                    const tbody = document.querySelector('tbody');
                    if (!tbody) return;
                    const index = document.querySelectorAll('[data-assignment-row]').length;
                    const row = buildAssignmentRow(index);
                    tbody.appendChild(row);
                    row.querySelectorAll('[data-sync-start], [data-sync-end]').forEach((input) => {
                        input.addEventListener('change', setMinDates);
                        input.addEventListener('input', setMinDates);
                    });
                    applyWorkshopFilter(row);
                    setMinDates();
                });
            }

            document.querySelectorAll('[data-assignment-row]').forEach(applyWorkshopFilter);
            setMinDates();
        });
    </script>
<?php endif; ?>
