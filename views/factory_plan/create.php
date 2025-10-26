<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Thêm kế hoạch xưởng</h3>
        <p class="text-muted mb-0">Phân rã kế hoạch sản xuất cho từng xưởng.</p>
    </div>
    <a href="?controller=factory_plan&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<div class="card p-4">
    <form action="?controller=factory_plan&action=store" method="post" class="row g-4">
        <div class="col-md-4">
            <label class="form-label">Mã kế hoạch xưởng</label>
            <input type="text" name="IdKeHoachSanXuatXuong" class="form-control" placeholder="Tự sinh nếu để trống">
        </div>
        <div class="col-md-4">
            <label class="form-label">Kế hoạch sản xuất tổng</label>
            <select name="IdKeHoachSanXuat" id="production-plan-select" class="form-select" required>
                <option value="">-- Chọn kế hoạch tổng --</option>
                <?php foreach ($productionPlans as $plan): ?>
                    <?php $isSelected = isset($selectedPlanId) && $selectedPlanId === $plan['IdKeHoachSanXuat']; ?>
                    <option
                        value="<?= htmlspecialchars($plan['IdKeHoachSanXuat']) ?>"
                        data-order="<?= htmlspecialchars($plan['IdDonHang']) ?>"
                        data-product="<?= htmlspecialchars($plan['TenSanPham']) ?>"
                        data-config="<?= htmlspecialchars($plan['TenCauHinh'] ?? '') ?>"
                        data-quantity="<?= htmlspecialchars($plan['SoLuongChiTiet']) ?>"
                        data-unit="<?= htmlspecialchars($plan['DonVi'] ?? 'sản phẩm') ?>"
                        data-status="<?= htmlspecialchars($plan['TrangThai']) ?>"
                        <?= $isSelected ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars('KH ' . $plan['IdKeHoachSanXuat'] . ' • ĐH ' . $plan['IdDonHang'] . ' • ' . $plan['TenSanPham']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Xưởng thực hiện</label>
            <select name="IdXuong" id="workshop-select" class="form-select" required>
                <option value="">-- Chọn xưởng --</option>
                <?php foreach ($workshops as $workshop): ?>
                    <option value="<?= htmlspecialchars($workshop['IdXuong']) ?>">
                        <?= htmlspecialchars($workshop['TenXuong'] ?? $workshop['IdXuong']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-12">
            <div id="plan-summary" class="alert alert-secondary mb-0">
                <strong>Chưa chọn kế hoạch tổng.</strong>
                <div class="small text-muted">Chọn kế hoạch để xem thông tin sản phẩm và số lượng yêu cầu.</div>
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label">Tên thành phần / công đoạn</label>
            <input type="text" name="TenThanhThanhPhanSP" class="form-control" placeholder="Ví dụ: Lắp ráp switch" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Số lượng</label>
            <input type="number" name="SoLuong" id="workshop-quantity" class="form-control" min="0" placeholder="Theo kế hoạch tổng">
        </div>
        <div class="col-md-6">
            <label class="form-label">Thời gian bắt đầu</label>
            <input type="datetime-local" name="ThoiGianBatDau" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Thời gian kết thúc</label>
            <input type="datetime-local" name="ThoiGianKetThuc" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Trạng thái</label>
            <select name="TrangThai" class="form-select">
                <option value="Đang chuẩn bị">Đang chuẩn bị</option>
                <option value="Đang sản xuất">Đang sản xuất</option>
                <option value="Hoàn thành">Hoàn thành</option>
            </select>
        </div>
        <div class="col-12 text-end">
            <button class="btn btn-primary px-4" type="submit">Lưu kế hoạch</button>
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

            if (quantityInput && (!quantityInput.value || quantityInput.dataset.userEdited !== '1')) {
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
