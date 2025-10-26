<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Cập nhật kế hoạch xưởng</h3>
        <p class="text-muted mb-0">Điều chỉnh phân công cho từng xưởng.</p>
    </div>
    <a href="?controller=factory_plan&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php if (!$plan): ?>
    <div class="alert alert-warning">Không tìm thấy kế hoạch.</div>
<?php else: ?>
    <div class="card p-4">
        <form action="?controller=factory_plan&action=update" method="post" class="row g-4">
            <input type="hidden" name="IdKeHoachSanXuatXuong" value="<?= htmlspecialchars($plan['IdKeHoachSanXuatXuong']) ?>">
            <div class="col-md-4">
                <label class="form-label">Kế hoạch sản xuất tổng</label>
                <select name="IdKeHoachSanXuat" id="production-plan-select" class="form-select" required>
                    <?php foreach ($productionPlans as $productionPlan): ?>
                        <?php $isSelected = $productionPlan['IdKeHoachSanXuat'] === $plan['IdKeHoachSanXuat']; ?>
                        <option
                            value="<?= htmlspecialchars($productionPlan['IdKeHoachSanXuat']) ?>"
                            data-order="<?= htmlspecialchars($productionPlan['IdDonHang']) ?>"
                            data-product="<?= htmlspecialchars($productionPlan['TenSanPham']) ?>"
                            data-config="<?= htmlspecialchars($productionPlan['TenCauHinh'] ?? '') ?>"
                            data-quantity="<?= htmlspecialchars($productionPlan['SoLuongChiTiet']) ?>"
                            data-unit="<?= htmlspecialchars($productionPlan['DonVi'] ?? 'sản phẩm') ?>"
                            data-status="<?= htmlspecialchars($productionPlan['TrangThai']) ?>"
                            <?= $isSelected ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars('KH ' . $productionPlan['IdKeHoachSanXuat'] . ' • ĐH ' . $productionPlan['IdDonHang'] . ' • ' . $productionPlan['TenSanPham']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Xưởng thực hiện</label>
                <select name="IdXuong" id="workshop-select" class="form-select" required>
                    <?php foreach ($workshops as $workshop): ?>
                        <option value="<?= htmlspecialchars($workshop['IdXuong']) ?>" <?= ($workshop['IdXuong'] === $plan['IdXuong']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($workshop['TenXuong'] ?? $workshop['IdXuong']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Trạng thái</label>
                <select name="TrangThai" class="form-select">
                    <?php foreach ([
                        'Đang chuẩn bị',
                        'Đang sản xuất',
                        'Hoàn thành',
                    ] as $status): ?>
                        <option value="<?= $status ?>" <?= $status === $plan['TrangThai'] ? 'selected' : '' ?>><?= $status ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12">
                <div id="plan-summary" class="alert alert-secondary mb-0">
                    <div><strong><?= htmlspecialchars(($plan['TenSanPham'] ?? '') . (!empty($plan['TenCauHinh']) ? ' - ' . $plan['TenCauHinh'] : '')) ?></strong></div>
                    <div class="small text-muted">Đơn hàng: <?= htmlspecialchars($plan['IdDonHang'] ?? '') ?> • SL tổng: <?= htmlspecialchars($plan['SoLuongChiTiet'] ?? '') ?> <?= htmlspecialchars($plan['DonVi'] ?? 'sản phẩm') ?></div>
                    <div class="small text-muted">Trạng thái kế hoạch: <?= htmlspecialchars($plan['TrangThaiTong'] ?? '') ?></div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tên thành phần / công đoạn</label>
                <input type="text" name="TenThanhThanhPhanSP" class="form-control" value="<?= htmlspecialchars($plan['TenThanhThanhPhanSP']) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Số lượng</label>
                <input type="number" name="SoLuong" id="workshop-quantity" class="form-control" min="0" value="<?= htmlspecialchars($plan['SoLuong']) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Thời gian bắt đầu</label>
                <input type="datetime-local" name="ThoiGianBatDau" class="form-control" value="<?= $plan['ThoiGianBatDau'] ? date('Y-m-d\TH:i', strtotime($plan['ThoiGianBatDau'])) : '' ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Thời gian kết thúc</label>
                <input type="datetime-local" name="ThoiGianKetThuc" class="form-control" value="<?= $plan['ThoiGianKetThuc'] ? date('Y-m-d\TH:i', strtotime($plan['ThoiGianKetThuc'])) : '' ?>">
            </div>
            <div class="col-12 text-end">
                <button class="btn btn-primary px-4" type="submit">Cập nhật kế hoạch</button>
            </div>
        </form>
    </div>

    <script>
        (function () {
            const planSelect = document.getElementById('production-plan-select');
            const summary = document.getElementById('plan-summary');
            const quantityInput = document.getElementById('workshop-quantity');

            if (!planSelect || !summary) {
                return;
            }

            if (quantityInput && quantityInput.value) {
                quantityInput.dataset.userEdited = '1';
            }

            const updateSummary = () => {
                const option = planSelect.options[planSelect.selectedIndex];
                if (!option || !option.value) {
                    summary.innerHTML = '<strong>Chưa chọn kế hoạch tổng.</strong>' +
                        '<div class="small text-muted">Chọn kế hoạch để xem thông tin sản phẩm và số lượng yêu cầu.</div>';
                    return;
                }

                const {product, config, quantity, unit, order, status} = option.dataset;
                summary.innerHTML = `
                    <div><strong>${product}${config ? ' - ' + config : ''}</strong></div>
                    <div class="small text-muted">Đơn hàng: ${order} • SL tổng: ${quantity} ${unit}</div>
                    <div class="small text-muted">Trạng thái kế hoạch: ${status}</div>
                `;

                if (quantityInput && quantityInput.dataset.userEdited !== '1') {
                    quantityInput.value = quantity;
                }
            };

            if (quantityInput) {
                quantityInput.addEventListener('input', () => {
                    quantityInput.dataset.userEdited = quantityInput.value ? '1' : '0';
                });
            }

            planSelect.addEventListener('change', () => {
                if (quantityInput) {
                    quantityInput.dataset.userEdited = '0';
                }
                updateSummary();
            });

            updateSummary();
        })();
    </script>
<?php endif; ?>
