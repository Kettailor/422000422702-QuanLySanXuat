<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Cập nhật kế hoạch</h3>
        <p class="text-muted mb-0">Điều chỉnh thông tin kế hoạch sản xuất.</p>
    </div>
    <a href="?controller=plan&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php if (!$plan): ?>
    <div class="alert alert-warning">Không tìm thấy kế hoạch.</div>
<?php else: ?>
    <div class="card p-4">
        <form action="?controller=plan&action=update" method="post" class="row g-4" id="plan-edit-form">
            <input type="hidden" name="IdKeHoachSanXuat" value="<?= htmlspecialchars($plan['IdKeHoachSanXuat']) ?>">
            <div class="col-md-8">
                <label class="form-label">Chi tiết đơn hàng</label>
                <select name="IdTTCTDonHang" id="order-detail-select" class="form-select" required>
                    <?php foreach ($orderDetails as $detail): ?>
                        <?php
                        $labelParts = [
                            'ĐH ' . $detail['IdDonHang'],
                            $detail['TenSanPham'],
                        ];
                        if (!empty($detail['TenCauHinh'])) {
                            $labelParts[] = $detail['TenCauHinh'];
                        }
                        $labelParts[] = 'SL: ' . $detail['SoLuong'];
                        $label = implode(' • ', array_filter($labelParts));
                        $isSelected = $detail['IdTTCTDonHang'] === $plan['IdTTCTDonHang'];
                        ?>
                        <option
                            value="<?= htmlspecialchars($detail['IdTTCTDonHang']) ?>"
                            data-order="<?= htmlspecialchars($detail['IdDonHang']) ?>"
                            data-product="<?= htmlspecialchars($detail['TenSanPham']) ?>"
                            data-config="<?= htmlspecialchars($detail['TenCauHinh'] ?? '') ?>"
                            data-quantity="<?= htmlspecialchars($detail['SoLuong']) ?>"
                            data-unit="<?= htmlspecialchars($detail['DonVi'] ?? 'sản phẩm') ?>"
                            data-request="<?= htmlspecialchars($detail['YeuCau'] ?? '') ?>"
                            <?= $isSelected ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Ban giám đốc phụ trách</label>
                <select name="BanGiamDoc" class="form-select">
                    <option value="">-- Chưa phân công --</option>
                    <?php foreach ($managers as $manager): ?>
                        <option value="<?= htmlspecialchars($manager['IdNhanVien']) ?>" <?= ($manager['IdNhanVien'] === ($plan['BanGiamDocId'] ?? null)) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($manager['HoTen'] . ' - ' . ($manager['ChucVu'] ?? '')) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12">
                <div id="order-detail-summary" class="alert alert-secondary mb-0">
                    <div><strong><?= htmlspecialchars(($plan['TenSanPham'] ?? '') . (!empty($plan['TenCauHinh']) ? ' - ' . $plan['TenCauHinh'] : '')) ?></strong></div>
                    <div class="small text-muted">Đơn hàng: <?= htmlspecialchars($plan['IdDonHang'] ?? '') ?> • SL yêu cầu: <?= htmlspecialchars($plan['SoLuongChiTiet'] ?? $plan['SoLuong']) ?> <?= htmlspecialchars($plan['DonVi'] ?? 'sản phẩm') ?></div>
                    <?php if (!empty($plan['YeuCau'])): ?>
                        <div class="small text-muted">Yêu cầu: <?= htmlspecialchars($plan['YeuCau']) ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Số lượng kế hoạch</label>
                <input type="number" name="SoLuong" id="plan-quantity" class="form-control" min="0" value="<?= htmlspecialchars($plan['SoLuong']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Thời gian bắt đầu</label>
                <input type="datetime-local" name="ThoiGianBD" class="form-control" value="<?= $plan['ThoiGianBD'] ? date('Y-m-d\TH:i', strtotime($plan['ThoiGianBD'])) : '' ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Thời gian kết thúc</label>
                <input type="datetime-local" name="ThoiGianKetThuc" class="form-control" value="<?= $plan['ThoiGianKetThuc'] ? date('Y-m-d\TH:i', strtotime($plan['ThoiGianKetThuc'])) : '' ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Trạng thái</label>
                <select name="TrangThai" class="form-select">
                    <?php foreach ([
                        'Mới tạo',
                        'Đang thực hiện',
                        'Hoàn thành',
                    ] as $status): ?>
                        <option value="<?= $status ?>" <?= $status === $plan['TrangThai'] ? 'selected' : '' ?>><?= $status ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 text-end">
                <button class="btn btn-primary px-4" type="submit">Cập nhật kế hoạch</button>
            </div>
        </form>
    </div>

    <script>
        (function () {
            const select = document.getElementById('order-detail-select');
            const summary = document.getElementById('order-detail-summary');
            const quantityInput = document.getElementById('plan-quantity');

            if (!select || !summary) {
                return;
            }

            if (quantityInput && quantityInput.value) {
                quantityInput.dataset.userEdited = '1';
            }

            const updateSummary = () => {
                const option = select.options[select.selectedIndex];
                if (!option || !option.value) {
                    summary.innerHTML = '<strong>Chưa chọn chi tiết đơn hàng.</strong>' +
                        '<div class="small text-muted">Vui lòng chọn chi tiết đơn để xem thông tin sản phẩm và số lượng yêu cầu.</div>';
                    return;
                }

                const {product, config, quantity, unit, order, request} = option.dataset;
                const requestNote = request ? `<div class="small text-muted">Yêu cầu: ${request}</div>` : '';
                summary.innerHTML = `
                    <div><strong>${product}${config ? ' - ' + config : ''}</strong></div>
                    <div class="small text-muted">Đơn hàng: ${order} • SL yêu cầu: ${quantity} ${unit}</div>
                    ${requestNote}
                `;

                if (quantityInput) {
                    const hasUserInput = quantityInput.dataset.userEdited === '1';
                    if (!hasUserInput) {
                        quantityInput.value = quantity;
                    }
                }
            };

            if (quantityInput) {
                quantityInput.addEventListener('input', () => {
                    quantityInput.dataset.userEdited = quantityInput.value ? '1' : '0';
                });
            }

            select.addEventListener('change', () => {
                if (quantityInput) {
                    quantityInput.dataset.userEdited = '0';
                }
                updateSummary();
            });

            updateSummary();
        })();
    </script>
<?php endif; ?>
