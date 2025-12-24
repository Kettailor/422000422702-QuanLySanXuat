<?php
$pendingOrders = $pendingOrders ?? [];
$selectedOrderDetailId = $selectedOrderDetailId ?? null;
$selectedOrderDetail = $selectedOrderDetail ?? null;
$componentAssignments = $componentAssignments ?? [];
$configurationDetails = $configurationDetails ?? [];
$workshops = $workshops ?? [];
$currentUser = $currentUser ?? [];
$materialOverview = $materialOverview ?? [];

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

$formatDateInput = static function (?string $value): string {
    if (!$value) {
        return '';
    }

    $timestamp = strtotime($value);
    if ($timestamp === false) {
        return '';
    }

    return date('Y-m-d\TH:i', $timestamp);
};

$detailOptions = [];
foreach ($pendingOrders as $order) {
    foreach ($order['details'] ?? [] as $detail) {
        $detailOptions[] = [
            'id' => $detail['IdTTCTDonHang'] ?? null,
            'label' => sprintf(
                'Đơn %s - %s (%s) • %s %s',
                $detail['IdDonHang'] ?? '-',
                $detail['TenSanPham'] ?? 'Sản phẩm',
                $detail['TenCauHinh'] ?? 'Cấu hình tiêu chuẩn',
                $detail['SoLuong'] ?? 0,
                $detail['DonVi'] ?? 'sp'
            ),
        ];
    }
}

$plannerName = $currentUser['HoTen'] ?? $currentUser['TenDangNhap'] ?? null;
$defaultQuantity = (int) ($selectedOrderDetail['SoLuong'] ?? 0);
$defaultQuantity = max(1, $defaultQuantity);
$nowInput = date('Y-m-d\TH:i');
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1">Lập kế hoạch sản xuất</h2>
        <p class="text-muted mb-0">Chọn đơn hàng, kiểm tra nguyên liệu và phân công xưởng phù hợp.</p>
    </div>
    <div>
        <a href="?controller=plan&action=index" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay về danh sách
        </a>
    </div>
</div>

<?php if (empty($detailOptions)): ?>
    <div class="alert alert-info">Không còn đơn hàng nào cần lập kế hoạch.</div>
<?php else: ?>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="get" class="row g-3 align-items-end">
                <input type="hidden" name="controller" value="plan">
                <input type="hidden" name="action" value="create">
                <div class="col-lg-8">
                    <label class="form-label">Chọn dòng sản phẩm cần lập kế hoạch</label>
                    <select name="order_detail_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Chọn đơn hàng --</option>
                        <?php foreach ($detailOptions as $option): ?>
                            <?php if (!$option['id']) {
                                continue;
                            } ?>
                            <option value="<?= htmlspecialchars($option['id']) ?>" <?= $selectedOrderDetailId === $option['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($option['label']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-2"></i>Tải thông tin
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<?php if (!$selectedOrderDetail): ?>
    <?php if (!empty($detailOptions)): ?>
        <div class="alert alert-warning">Vui lòng chọn một dòng sản phẩm để tiếp tục.</div>
    <?php endif; ?>
<?php else: ?>
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase">Thông tin đơn hàng</div>
                    <div class="fw-semibold mt-2">Đơn hàng <?= htmlspecialchars($selectedOrderDetail['IdDonHang'] ?? '-') ?></div>
                    <div class="text-muted small">Khách hàng: <?= htmlspecialchars($selectedOrderDetail['TenKhachHang'] ?? 'Chưa cập nhật') ?></div>
                    <div class="text-muted small">Sản phẩm: <?= htmlspecialchars($selectedOrderDetail['TenSanPham'] ?? 'Sản phẩm') ?></div>
                    <div class="text-muted small">Cấu hình: <?= htmlspecialchars($selectedOrderDetail['TenCauHinh'] ?? 'Tiêu chuẩn') ?></div>
                    <div class="text-muted small">Số lượng: <?= htmlspecialchars((string) ($selectedOrderDetail['SoLuong'] ?? 0)) ?> <?= htmlspecialchars($selectedOrderDetail['DonVi'] ?? 'sp') ?></div>
                    <?php if (!empty($selectedOrderDetail['NgayGiao'])): ?>
                        <div class="text-muted small">Ngày giao: <?= $formatDate($selectedOrderDetail['NgayGiao'], 'd/m/Y') ?></div>
                    <?php endif; ?>
                    <?php if (!empty($selectedOrderDetail['YeuCauChiTiet']) || !empty($selectedOrderDetail['YeuCauDonHang'])): ?>
                        <div class="mt-2 small">Yêu cầu: <?= htmlspecialchars($selectedOrderDetail['YeuCauChiTiet'] ?? $selectedOrderDetail['YeuCauDonHang'] ?? '') ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase">Cấu hình sản phẩm</div>
                    <?php if (empty($configurationDetails)): ?>
                        <div class="text-muted mt-3">Chưa có thông tin cấu hình chi tiết.</div>
                    <?php else: ?>
                        <ul class="list-unstyled mt-2 mb-0">
                            <?php foreach ($configurationDetails as $detail): ?>
                                <li class="d-flex justify-content-between">
                                    <span class="text-muted"><?= htmlspecialchars($detail['label'] ?? '') ?></span>
                                    <span class="fw-semibold"><?= htmlspecialchars($detail['value'] ?? '') ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-1">Đối chiếu nguyên liệu / thành phẩm</h5>
                    <div class="text-muted small">Tự động ánh xạ cấu hình vào kho để xem tồn kho và nhu cầu sản xuất.</div>
                </div>
            </div>
            <?php if (empty($materialOverview)): ?>
                <div class="alert alert-light border mb-0">Chưa có định mức nguyên liệu cho cấu hình này.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>Nguyên liệu</th>
                            <th class="text-end">Nhu cầu</th>
                            <th class="text-end">Tồn kho</th>
                            <th class="text-end">Cần bổ sung</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($materialOverview as $material): ?>
                            <?php $shortage = (int) ($material['shortage'] ?? 0); ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold"><?= htmlspecialchars($material['name'] ?? $material['label'] ?? '-') ?></div>
                                    <div class="text-muted small"><?= htmlspecialchars($material['id'] ?? '') ?></div>
                                </td>
                                <td class="text-end">
                                    <?= number_format((int) ($material['required'] ?? 0)) ?> <?= htmlspecialchars($material['unit'] ?? '') ?>
                                </td>
                                <td class="text-end">
                                    <?= number_format((int) ($material['available'] ?? 0)) ?> <?= htmlspecialchars($material['unit'] ?? '') ?>
                                </td>
                                <td class="text-end">
                                    <span class="badge <?= $shortage > 0 ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' ?>">
                                        <?= number_format($shortage) ?> <?= htmlspecialchars($material['unit'] ?? '') ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <form method="post" action="?controller=plan&action=store" id="plan-form">
        <input type="hidden" name="IdTTCTDonHang" value="<?= htmlspecialchars($selectedOrderDetail['IdTTCTDonHang'] ?? '') ?>">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="mb-3">Thông tin kế hoạch</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Mã kế hoạch (tùy chọn)</label>
                        <input type="text" name="IdKeHoachSanXuat" class="form-control" placeholder="Tự động nếu bỏ trống">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Số lượng kế hoạch</label>
                        <input type="number" min="1" name="SoLuong" class="form-control" value="<?= htmlspecialchars((string) $defaultQuantity) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Người lập</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($plannerName ?? 'Chưa xác định') ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Thời gian bắt đầu</label>
                        <input type="datetime-local" name="ThoiGianBD" class="form-control" value="<?= htmlspecialchars($formatDateInput($selectedOrderDetail['NgayLap'] ?? null) ?: $nowInput) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Hạn chót</label>
                        <input type="datetime-local" name="ThoiGianKetThuc" class="form-control" value="<?= htmlspecialchars($formatDateInput($selectedOrderDetail['NgayGiao'] ?? null)) ?>" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="mb-1">Phân công hạng mục sản xuất</h5>
                        <div class="text-muted small">Có thể xóa hạng mục không cần thiết trước khi lưu.</div>
                    </div>
                </div>
                <?php if (empty($componentAssignments)): ?>
                    <div class="alert alert-warning">Chưa có hạng mục nào được đề xuất cho kế hoạch.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle" id="assignment-table">
                            <thead class="table-light">
                            <tr>
                                <th>Hạng mục</th>
                                <th class="text-end">Số lượng</th>
                                <th>Xưởng</th>
                                <th>Trạng thái</th>
                                <th>Bắt đầu</th>
                                <th>Hạn chót</th>
                                <th class="text-end"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($componentAssignments as $index => $assignment): ?>
                                <?php
                                $allowedTypes = $assignment['allowed_workshop_types'] ?? [];
                                $workshopOptions = array_filter($workshops, function (array $workshop) use ($allowedTypes): bool {
                                    if (empty($allowedTypes)) {
                                        return true;
                                    }
                                    return in_array($workshop['LoaiXuong'] ?? '', $allowedTypes, true);
                                });
                                $defaultStart = $formatDateInput($assignment['start'] ?? null)
                                    ?: $formatDateInput($assignment['default_start'] ?? null)
                                    ?: $nowInput;
                                $defaultEnd = $formatDateInput($assignment['end'] ?? null)
                                    ?: $formatDateInput($assignment['default_end'] ?? null)
                                    ?: $formatDateInput($selectedOrderDetail['NgayGiao'] ?? null)
                                    ?: $nowInput;
                                ?>
                                <tr data-assignment-row>
                                    <td>
                                        <div class="fw-semibold"><?= htmlspecialchars($assignment['label'] ?? 'Hạng mục') ?></div>
                                        <?php if (!empty($assignment['configuration_label'])): ?>
                                            <div class="text-muted small"><?= htmlspecialchars($assignment['configuration_label']) ?></div>
                                        <?php endif; ?>
                                        <input type="hidden" name="component_assignments[<?= $index ?>][label]" value="<?= htmlspecialchars($assignment['label'] ?? '') ?>">
                                        <input type="hidden" name="component_assignments[<?= $index ?>][component_id]" value="<?= htmlspecialchars($assignment['id'] ?? '') ?>">
                                        <input type="hidden" name="component_assignments[<?= $index ?>][configuration_id]" value="<?= htmlspecialchars($assignment['configuration_id'] ?? '') ?>">
                                        <input type="hidden" name="component_assignments[<?= $index ?>][default_status]" value="<?= htmlspecialchars($assignment['default_status'] ?? '') ?>">
                                    </td>
                                    <td class="text-end">
                                        <input type="number" min="1" class="form-control" style="min-width: 120px;" name="component_assignments[<?= $index ?>][quantity]" value="<?= htmlspecialchars((string) ($assignment['default_quantity'] ?? $defaultQuantity)) ?>" required>
                                        <div class="small text-muted mt-1"><?= htmlspecialchars($assignment['unit'] ?? 'sp') ?></div>
                                    </td>
                                    <td style="min-width: 180px;">
                                        <select class="form-select" name="component_assignments[<?= $index ?>][workshop_id]" required>
                                            <option value="">-- Chọn xưởng --</option>
                                            <?php foreach ($workshopOptions as $workshop): ?>
                                                <option value="<?= htmlspecialchars($workshop['IdXuong']) ?>" <?= ($assignment['default_workshop'] ?? '') === ($workshop['IdXuong'] ?? '') ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($workshop['TenXuong'] ?? $workshop['IdXuong']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td style="min-width: 170px;">
                                        <select class="form-select" name="component_assignments[<?= $index ?>][status]">
                                            <?php
                                            $statuses = ['Đang chuẩn bị', 'Đang sản xuất', 'Chờ nghiệm thu', 'Hoàn thành', 'Đang chờ xác nhận'];
                                            $statusValue = $assignment['default_status'] ?? 'Đang chuẩn bị';
                                            ?>
                                            <?php foreach ($statuses as $status): ?>
                                                <option value="<?= htmlspecialchars($status) ?>" <?= $statusValue === $status ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($status) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="datetime-local" class="form-control" name="component_assignments[<?= $index ?>][start]" value="<?= htmlspecialchars($defaultStart) ?>" required>
                                    </td>
                                    <td>
                                        <input type="datetime-local" class="form-control" name="component_assignments[<?= $index ?>][end]" value="<?= htmlspecialchars($defaultEnd) ?>" required>
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-outline-danger btn-sm" data-remove-assignment>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
            <?php if (!empty($componentAssignments)): ?>
                <div class="card-footer bg-white border-0 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Lưu kế hoạch
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </form>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const table = document.getElementById('assignment-table');
        if (!table) {
            return;
        }

        table.addEventListener('click', (event) => {
            const target = event.target.closest('[data-remove-assignment]');
            if (!target) {
                return;
            }

            const row = target.closest('[data-assignment-row]');
            if (row) {
                row.remove();
            }

            const remaining = table.querySelectorAll('[data-assignment-row]').length;
            if (remaining === 0) {
                const message = document.createElement('div');
                message.className = 'alert alert-warning mt-3';
                message.textContent = 'Bạn đã xóa hết hạng mục. Vui lòng thêm lại hoặc quay về chọn đơn hàng khác.';
                table.parentElement?.appendChild(message);
            }
        });
    });
</script>
