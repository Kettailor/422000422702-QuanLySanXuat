<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Dashboard cấp phát vật tư xưởng</h3>
        <p class="text-muted mb-0">Theo dõi nhu cầu nguyên liệu và xác nhận kế hoạch trước khi triển khai.</p>
    </div>
    <form class="d-flex align-items-center gap-2" method="get">
        <input type="hidden" name="controller" value="workshop">
        <input type="hidden" name="action" value="dashboard">
        <label for="workshop-filter" class="text-muted small mb-0"><?= $isScoped ? 'Xưởng của bạn' : 'Lọc theo xưởng' ?></label>
        <select class="form-select" id="workshop-filter" name="workshop" onchange="this.form.submit()" <?= $isScoped && count($workshops) <= 1 ? 'disabled' : '' ?>>
            <?php if (!$isScoped): ?>
                <option value="">Tất cả</option>
            <?php endif; ?>
            <?php foreach ($workshops as $workshop): ?>
                <option value="<?= htmlspecialchars($workshop['IdXuong']) ?>" <?= $selectedWorkshop === ($workshop['IdXuong'] ?? null) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($workshop['TenXuong'] ?? $workshop['IdXuong']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<?php if ($isScoped): ?>
    <div class="alert alert-info d-flex align-items-center gap-2">
        <i class="bi bi-person-workspace text-primary fs-5"></i>
        <div>
            <div class="fw-semibold">Chỉ hiển thị kế hoạch của các xưởng bạn quản lý.</div>
            <div class="small mb-0">Liên hệ Ban giám đốc để xem thêm xưởng khác.</div>
        </div>
    </div>
<?php endif; ?>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card metric-card">
            <div class="icon-wrap bg-primary bg-opacity-10 text-primary"><i class="bi bi-kanban"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Kế hoạch đang chờ</div>
                <div class="fs-3 fw-bold"><?= $metrics['total_plans'] ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card metric-card">
            <div class="icon-wrap bg-danger bg-opacity-10 text-danger"><i class="bi bi-exclamation-triangle"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Thiếu vật tư</div>
                <div class="fs-3 fw-bold"><?= $metrics['shortage_plans'] ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card metric-card">
            <div class="icon-wrap bg-info bg-opacity-10 text-info"><i class="bi bi-box-seam"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Dòng vật tư theo dõi</div>
                <div class="fs-3 fw-bold"><?= $metrics['total_materials'] ?></div>
            </div>
        </div>
    </div>
</div>

<?php if (empty($groupedPlans)): ?>
    <div class="alert alert-info">Chưa có kế hoạch xưởng nào trong trạng thái cần chuẩn bị.</div>
<?php endif; ?>

<?php foreach ($groupedPlans as $workshopId => $group): ?>
    <div class="card mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1"><?= htmlspecialchars($group['info']['TenXuong']) ?></h5>
                <small class="text-muted">Mã xưởng: <?= htmlspecialchars($group['info']['IdXuong']) ?></small>
            </div>
            <span class="badge bg-secondary"><?= count($group['plans']) ?> kế hoạch</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>Kế hoạch</th>
                        <th>Thông tin đơn hàng</th>
                        <th>Vật tư yêu cầu</th>
                        <th class="text-center">Tình trạng</th>
                        <th class="text-end">Xác nhận</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($group['plans'] as $item): ?>
                        <?php $plan = $item['data']; ?>
                        <tr class="<?= $item['has_shortage'] ? 'table-warning' : '' ?>">
                            <td style="min-width: 240px;">
                                <div class="fw-semibold mb-1"><?= htmlspecialchars($plan['TenThanhThanhPhanSP']) ?></div>
                                <div class="text-muted small">Số lượng: <?= number_format($plan['SoLuong']) ?> <?= htmlspecialchars($plan['DonVi'] ?? '') ?></div>
                                <div class="text-muted small">Bắt đầu: <?= htmlspecialchars($plan['ThoiGianBatDau'] ?? 'Chưa xác định') ?></div>
                                <div class="text-muted small">Kết thúc: <?= htmlspecialchars($plan['ThoiGianKetThuc'] ?? 'Chưa xác định') ?></div>
                            </td>
                            <td style="min-width: 220px;">
                                <div class="small text-muted">Sản phẩm</div>
                                <div class="fw-semibold"><?= htmlspecialchars($plan['TenSanPham'] ?? 'Không rõ') ?></div>
                                <?php if (!empty($plan['TenCauHinh'])): ?>
                                    <div class="text-muted small">Cấu hình: <?= htmlspecialchars($plan['TenCauHinh']) ?></div>
                                <?php endif; ?>
                                <?php if (!empty($plan['YeuCau'])): ?>
                                    <div class="text-muted small">Yêu cầu KH: <?= htmlspecialchars($plan['YeuCau']) ?></div>
                                <?php endif; ?>
                                <div class="text-muted small">Đơn hàng: <?= htmlspecialchars($plan['IdDonHang'] ?? 'N/A') ?></div>
                            </td>
                            <td style="min-width: 260px;">
                                <?php if (empty($item['materials'])): ?>
                                    <span class="badge bg-secondary-subtle text-secondary">Không yêu cầu vật tư</span>
                                <?php else: ?>
                                    <ul class="list-unstyled mb-0">
                                        <?php foreach ($item['materials'] as $material): ?>
                                            <li class="d-flex justify-content-between py-1 border-bottom">
                                                <div>
                                                    <div class="fw-semibold small"><?= htmlspecialchars($material['label']) ?></div>
                                                    <div class="text-muted small">Mã: <?= htmlspecialchars($material['id']) ?><?= $material['unit'] ? ' • ' . htmlspecialchars($material['unit']) : '' ?></div>
                                                </div>
                                                <div class="text-end small">
                                                    <div>Cần: <?= number_format($material['required']) ?></div>
                                                    <div>Tồn: <?= number_format($material['stock']) ?></div>
                                                    <?php if ($material['deficit'] > 0): ?>
                                                        <div class="text-danger">Thiếu <?= number_format($material['deficit']) ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php
                                $statusLabel = $item['material_status'] ?? 'Chưa xác định';
                                $badgeClass = 'bg-secondary';
                                if ($statusLabel === 'Đủ vật tư') {
                                    $badgeClass = 'bg-success';
                                } elseif ($statusLabel === 'Thiếu vật tư') {
                                    $badgeClass = 'bg-danger';
                                } elseif ($statusLabel === 'Chờ cấp phát') {
                                    $badgeClass = 'bg-warning text-dark';
                                }
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($statusLabel) ?></span>
                                <?php if (!empty($plan['TrangThai'])): ?>
                                    <div class="text-muted small mt-2">Tiến độ: <?= htmlspecialchars($plan['TrangThai']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="text-end" style="min-width: 220px;">
                                <form action="?controller=workshop&action=confirmPlan" method="post" class="d-flex flex-column gap-2">
                                    <input type="hidden" name="IdKeHoachSanXuatXuong" value="<?= htmlspecialchars($plan['IdKeHoachSanXuatXuong']) ?>">
                                    <input type="hidden" name="redirect_workshop" value="<?= htmlspecialchars($selectedWorkshop ?? '') ?>">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">SL</span>
                                        <input type="number" min="1" class="form-control" name="SoLuong" value="<?= htmlspecialchars($plan['SoLuong']) ?>">
                                    </div>
                                    <select name="TinhTrangVatTu" class="form-select form-select-sm">
                                        <?php
                                        $materialStatuses = ['Đủ vật tư', 'Thiếu vật tư', 'Chờ cấp phát', 'Không yêu cầu vật tư'];
                                        $currentStatus = $item['material_status'] ?? '';
                                        ?>
                                        <option value="">Giữ nguyên</option>
                                        <?php foreach ($materialStatuses as $status): ?>
                                            <option value="<?= htmlspecialchars($status) ?>" <?= $currentStatus === $status ? 'selected' : '' ?>><?= htmlspecialchars($status) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <select name="TrangThai" class="form-select form-select-sm">
                                        <?php
                                        $progressStatuses = ['Đang chờ xưởng xác nhận', 'Đã xác nhận', 'Đang sản xuất', 'Tạm hoãn'];
                                        $currentProgress = $plan['TrangThai'] ?? '';
                                        ?>
                                        <option value="">Giữ nguyên tiến độ</option>
                                        <?php foreach ($progressStatuses as $status): ?>
                                            <option value="<?= htmlspecialchars($status) ?>" <?= $currentProgress === $status ? 'selected' : '' ?>><?= htmlspecialchars($status) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="bi bi-check2-circle me-1"></i>Xác nhận
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endforeach; ?>
