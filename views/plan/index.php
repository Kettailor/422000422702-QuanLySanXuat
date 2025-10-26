<?php
$orders = $orders ?? [];
$plans = $plans ?? [];
$hasPlans = !empty($plans);
$initialPlan = null;
$initialOrderId = null;

if ($hasPlans) {
    if (!empty($initialPlanId) && isset($plans[$initialPlanId])) {
        $initialPlan = $plans[$initialPlanId];
    } else {
        $initialPlan = reset($plans) ?: null;
    }

    $initialOrderId = $initialPlan['IdDonHang'] ?? ($orders[0]['IdDonHang'] ?? null);
}

$initialOrderPlans = [];
if ($initialOrderId) {
    foreach ($orders as $order) {
        if (($order['IdDonHang'] ?? null) === $initialOrderId) {
            $initialOrderPlans = $order['plans'] ?? [];
            break;
        }
    }
}

$planningData = [
    'orders' => $orders,
    'plans' => $plans,
    'initialPlanId' => $initialPlan['IdKeHoachSanXuat'] ?? null,
];
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h3 class="fw-bold mb-1">Kế hoạch sản xuất SV5TOT</h3>
        <p class="text-muted mb-0">Theo dõi toàn bộ kế hoạch tổng và phân bổ cho từng xưởng.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="?controller=plan&action=create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Tạo kế hoạch mới
        </a>
        <a href="?controller=factory_plan&action=index" class="btn btn-outline-primary">
            <i class="bi bi-diagram-3 me-2"></i>Kế hoạch xưởng
        </a>
    </div>
</div>

<?php if (!$hasPlans): ?>
    <div class="alert alert-light border">
        Hiện chưa có kế hoạch sản xuất nào. Hãy tạo mới để bắt đầu theo dõi tiến độ.
    </div>
<?php else: ?>
    <div class="card p-4 mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-lg-6">
                <label for="orderSelect" class="form-label">Chọn hóa đơn</label>
                <select id="orderSelect" class="form-select">
                    <?php foreach ($orders as $order): ?>
                        <option value="<?= htmlspecialchars($order['IdDonHang']) ?>" <?= ($order['IdDonHang'] ?? null) === $initialOrderId ? 'selected' : '' ?>>
                            ĐH <?= htmlspecialchars($order['IdDonHang']) ?><?= !empty($order['YeuCau']) ? ' - ' . htmlspecialchars($order['YeuCau']) : '' ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text">Chỉ hiển thị các kế hoạch thuộc hóa đơn đã chọn.</div>
            </div>
            <div class="col-lg-4">
                <label for="planSelect" class="form-label">Kế hoạch đã có</label>
                <select id="planSelect" class="form-select">
                    <?php foreach ($initialOrderPlans as $orderPlan): ?>
                        <option value="<?= htmlspecialchars($orderPlan['IdKeHoachSanXuat']) ?>" <?= ($orderPlan['IdKeHoachSanXuat'] ?? null) === ($initialPlan['IdKeHoachSanXuat'] ?? null) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($orderPlan['IdKeHoachSanXuat']) ?> - <?= htmlspecialchars($orderPlan['TenSanPham'] ?? '') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-2 d-flex gap-2">
                <a id="planDetailLink" href="#" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-eye me-1"></i>Xem chi tiết
                </a>
            </div>
        </div>
    </div>

    <div class="card p-4 mb-4" id="plan-info-card">
        <div class="d-flex justify-content-between align-items-start mb-4 gap-3">
            <div>
                <h5 class="fw-semibold mb-1">Thông tin kế hoạch</h5>
                <p class="text-muted small mb-0">Thông tin tổng quan về kế hoạch sản xuất đã chọn.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a id="planEditLink" href="#" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-pencil-square me-1"></i>Cập nhật kế hoạch
                </a>
                <span id="plan-status" class="badge bg-light text-dark"></span>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-xl-6">
                <dl class="row mb-0">
                    <dt class="col-sm-5 text-muted">Mã kế hoạch</dt>
                    <dd class="col-sm-7 fw-semibold" id="plan-code">-</dd>
                    <dt class="col-sm-5 text-muted">Người phụ trách</dt>
                    <dd class="col-sm-7" id="plan-manager">-</dd>
                    <dt class="col-sm-5 text-muted">Ngày bắt đầu</dt>
                    <dd class="col-sm-7" id="plan-start">-</dd>
                    <dt class="col-sm-5 text-muted">Ngày kết thúc</dt>
                    <dd class="col-sm-7" id="plan-end">-</dd>
                </dl>
            </div>
            <div class="col-xl-6">
                <dl class="row mb-0">
                    <dt class="col-sm-5 text-muted">Đơn hàng</dt>
                    <dd class="col-sm-7" id="plan-order">-</dd>
                    <dt class="col-sm-5 text-muted">Ngày lập đơn</dt>
                    <dd class="col-sm-7" id="plan-order-date">-</dd>
                    <dt class="col-sm-5 text-muted">Yêu cầu</dt>
                    <dd class="col-sm-7" id="plan-request">-</dd>
                    <dt class="col-sm-5 text-muted">Số lượng kế hoạch</dt>
                    <dd class="col-sm-7" id="plan-quantity">-</dd>
                </dl>
            </div>
        </div>
        <hr>
        <div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted">Tiến độ hoàn thành</span>
                <span class="fw-semibold" id="plan-progress-percent">0%</span>
            </div>
            <div class="progress" style="height: 10px;">
                <div id="plan-progress-bar" class="progress-bar bg-primary" role="progressbar" style="width: 0%;"></div>
            </div>
            <div class="text-muted small mt-2" id="plan-progress-note"></div>
        </div>
    </div>

    <div class="card p-4 mb-4" id="plan-product-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-semibold mb-0">Sản phẩm từ hóa đơn</h5>
            <span class="text-muted small" id="plan-product-subtitle"></span>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Cấu hình</th>
                        <th class="text-end">Số lượng đơn hàng</th>
                        <th class="text-end">Số lượng kế hoạch</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td id="product-name">-</td>
                        <td id="product-config">-</td>
                        <td class="text-end" id="product-order-qty">-</td>
                        <td class="text-end" id="product-plan-qty">-</td>
                        <td id="product-note">-</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card p-4" id="plan-workshops-card">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="fw-semibold mb-0">Tiến độ cho từng xưởng</h5>
            <div class="d-flex gap-2">
                <a id="factoryPlanCreateLink" href="?controller=factory_plan&action=create" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Thêm xưởng
                </a>
                <a href="?controller=factory_plan&action=index" class="btn btn-sm btn-outline-secondary">
                    Danh sách kế hoạch xưởng
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Xưởng</th>
                        <th>Thành phần</th>
                        <th class="text-end">Số lượng</th>
                        <th>Thời gian</th>
                        <th>Ghi chú</th>
                        <th>Trạng thái</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="workshop-table-body">
                    <tr>
                        <td colspan="7" class="text-center text-muted">Chưa có dữ liệu.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        window.productionPlanningData = <?= json_encode($planningData, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    </script>
    <script>
        (function () {
            const data = window.productionPlanningData || {orders: [], plans: {}, initialPlanId: null};
            const orderSelect = document.getElementById('orderSelect');
            const planSelect = document.getElementById('planSelect');
            const planDetailLink = document.getElementById('planDetailLink');
            const planEditLink = document.getElementById('planEditLink');
            const planStatusEl = document.getElementById('plan-status');
            const planCodeEl = document.getElementById('plan-code');
            const planManagerEl = document.getElementById('plan-manager');
            const planStartEl = document.getElementById('plan-start');
            const planEndEl = document.getElementById('plan-end');
            const planOrderEl = document.getElementById('plan-order');
            const planOrderDateEl = document.getElementById('plan-order-date');
            const planRequestEl = document.getElementById('plan-request');
            const planQuantityEl = document.getElementById('plan-quantity');
            const planProgressBarEl = document.getElementById('plan-progress-bar');
            const planProgressPercentEl = document.getElementById('plan-progress-percent');
            const planProgressNoteEl = document.getElementById('plan-progress-note');
            const productNameEl = document.getElementById('product-name');
            const productConfigEl = document.getElementById('product-config');
            const productOrderQtyEl = document.getElementById('product-order-qty');
            const productPlanQtyEl = document.getElementById('product-plan-qty');
            const productNoteEl = document.getElementById('product-note');
            const productSubtitleEl = document.getElementById('plan-product-subtitle');
            const workshopTableBody = document.getElementById('workshop-table-body');
            const factoryPlanCreateLink = document.getElementById('factoryPlanCreateLink');

            const formatDateTime = (value) => {
                if (!value) {
                    return '-';
                }
                const date = new Date(value.replace(' ', 'T'));
                if (Number.isNaN(date.getTime())) {
                    return '-';
                }
                return date.toLocaleString('vi-VN', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            };

            const formatDate = (value) => {
                if (!value) {
                    return '-';
                }
                const date = new Date(value.replace(' ', 'T'));
                if (Number.isNaN(date.getTime())) {
                    return '-';
                }
                return date.toLocaleDateString('vi-VN', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            };

            const formatQuantity = (qty, unit) => {
                if (qty === null || qty === undefined || qty === '') {
                    return '-';
                }
                const number = Number(qty);
                const formatted = Number.isNaN(number) ? qty : number.toLocaleString('vi-VN');
                return unit ? `${formatted} ${unit}` : formatted;
            };

            const updatePlanSelectOptions = (orderId) => {
                const order = data.orders.find(item => item.IdDonHang === orderId);
                const plansOfOrder = order ? (order.plans || []) : [];
                planSelect.innerHTML = '';

                if (plansOfOrder.length === 0) {
                    const option = document.createElement('option');
                    option.textContent = 'Chưa có kế hoạch';
                    option.value = '';
                    planSelect.appendChild(option);
                    planSelect.disabled = true;
                    return;
                }

                planSelect.disabled = false;

                plansOfOrder.forEach((item, index) => {
                    const option = document.createElement('option');
                    option.value = item.IdKeHoachSanXuat;
                    option.textContent = `${item.IdKeHoachSanXuat} - ${item.TenSanPham || ''}`.trim();
                    if (!planSelect.value && index === 0) {
                        option.selected = true;
                    }
                    planSelect.appendChild(option);
                });
            };

            const setBadgeForStatus = (el, status) => {
                const classes = {
                    'Hoàn thành': 'badge bg-success bg-opacity-25 text-success',
                    'Đang thực hiện': 'badge bg-primary bg-opacity-25 text-primary',
                    'Mới tạo': 'badge bg-warning bg-opacity-25 text-warning',
                    'Đã hủy': 'badge bg-danger bg-opacity-25 text-danger'
                };

                el.textContent = status || 'Chưa cập nhật';
                el.className = classes[status] || 'badge bg-light text-dark';
            };

            const renderWorkshopRows = (plan) => {
                workshopTableBody.innerHTML = '';
                const workshops = plan && plan.workshops ? plan.workshops : [];
                if (!workshops.length) {
                    const row = document.createElement('tr');
                    const cell = document.createElement('td');
                    cell.colSpan = 7;
                    cell.className = 'text-center text-muted';
                    cell.textContent = 'Chưa phân công kế hoạch xuống các xưởng.';
                    row.appendChild(cell);
                    workshopTableBody.appendChild(row);
                    return;
                }

                workshops.forEach(workshop => {
                    const row = document.createElement('tr');

                    const workshopCell = document.createElement('td');
                    workshopCell.innerHTML = `<div class="fw-semibold">${workshop.TenXuong || workshop.IdXuong || '-'}</div>`;
                    row.appendChild(workshopCell);

                    const componentCell = document.createElement('td');
                    componentCell.textContent = workshop.TenThanhThanhPhanSP || '-';
                    row.appendChild(componentCell);

                    const quantityCell = document.createElement('td');
                    quantityCell.className = 'text-end';
                    quantityCell.textContent = formatQuantity(workshop.SoLuong);
                    row.appendChild(quantityCell);

                    const timeCell = document.createElement('td');
                    const start = formatDateTime(workshop.ThoiGianBatDau);
                    const end = formatDateTime(workshop.ThoiGianKetThuc);
                    timeCell.innerHTML = `<div class="text-muted small">BĐ: ${start}</div><div class="text-muted small">KT: ${end}</div>`;
                    row.appendChild(timeCell);

                    const noteCell = document.createElement('td');
                    noteCell.textContent = workshop.TinhTrangVatTu || '-';
                    row.appendChild(noteCell);

                    const statusCell = document.createElement('td');
                    const badge = document.createElement('span');
                    setBadgeForStatus(badge, workshop.TrangThai);
                    statusCell.appendChild(badge);
                    row.appendChild(statusCell);

                    const actionCell = document.createElement('td');
                    actionCell.className = 'text-end';
                    const detailLink = document.createElement('a');
                    detailLink.className = 'btn btn-sm btn-outline-secondary';
                    detailLink.href = `?controller=factory_plan&action=read&id=${encodeURIComponent(workshop.IdKeHoachSanXuatXuong)}`;
                    detailLink.textContent = 'Chi tiết';
                    actionCell.appendChild(detailLink);
                    row.appendChild(actionCell);

                    workshopTableBody.appendChild(row);
                });
            };

            const renderPlan = (planId) => {
                const plan = data.plans[planId];
                if (!plan) {
                    return;
                }

                planDetailLink.href = `?controller=plan&action=read&id=${encodeURIComponent(planId)}`;
                planEditLink.href = `?controller=plan&action=edit&id=${encodeURIComponent(planId)}`;
                if (factoryPlanCreateLink) {
                    factoryPlanCreateLink.href = `?controller=factory_plan&action=create&IdKeHoachSanXuat=${encodeURIComponent(planId)}`;
                }

                planCodeEl.textContent = `#${plan.IdKeHoachSanXuat}`;
                planManagerEl.textContent = plan.TenQuanLy || 'Chưa phân công';
                planStartEl.textContent = formatDateTime(plan.ThoiGianBD);
                planEndEl.textContent = formatDateTime(plan.ThoiGianKetThuc);
                planOrderEl.textContent = `ĐH ${plan.IdDonHang || '-'}`;
                planOrderDateEl.textContent = formatDate(plan.NgayLapDonHang || plan.NgayLap);
                planRequestEl.textContent = plan.YeuCau || 'Không có yêu cầu bổ sung';
                planQuantityEl.textContent = `${formatQuantity(plan.SoLuong, plan.DonVi)} / ${formatQuantity(plan.SoLuongDonHang, plan.DonVi)}`;

                const progress = Number.isFinite(plan.progressPercent) ? Math.max(0, Math.min(100, plan.progressPercent)) : 0;
                planProgressBarEl.style.width = `${progress}%`;
                planProgressPercentEl.textContent = `${progress}%`;
                planProgressNoteEl.textContent = plan.totalSteps
                    ? `${plan.completedSteps} / ${plan.totalSteps} công đoạn đã hoàn thành.`
                    : 'Chưa phân bổ công đoạn cho xưởng.';

                setBadgeForStatus(planStatusEl, plan.TrangThai);

                productNameEl.textContent = plan.TenSanPham || '-';
                productConfigEl.textContent = plan.TenCauHinh || 'Cấu hình chuẩn';
                productOrderQtyEl.textContent = formatQuantity(plan.SoLuongDonHang, plan.DonVi);
                productPlanQtyEl.textContent = formatQuantity(plan.SoLuong, plan.DonVi);
                productNoteEl.textContent = plan.YeuCau || 'Không có ghi chú';
                if (productSubtitleEl) {
                    productSubtitleEl.textContent = plan.IdDonHang ? `Đơn hàng ${plan.IdDonHang}` : '';
                }

                renderWorkshopRows(plan);
            };

            const handleOrderChange = () => {
                const orderId = orderSelect.value || null;
                updatePlanSelectOptions(orderId);
                const selectedPlanId = planSelect.value || null;
                if (selectedPlanId) {
                    renderPlan(selectedPlanId);
                }
            };

            orderSelect.addEventListener('change', () => {
                planSelect.value = '';
                handleOrderChange();
            });

            planSelect.addEventListener('change', () => {
                const planId = planSelect.value;
                if (planId) {
                    renderPlan(planId);
                }
            });

            if (data.initialPlanId) {
                renderPlan(data.initialPlanId);
            } else if (planSelect.value) {
                renderPlan(planSelect.value);
            }
        })();
    </script>
<?php endif; ?>
