<?php
$plan = $plan ?? null;
$workshopPlans = $workshopPlans ?? [];

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

$statusBadge = static function (string $status): string {
    $normalized = strtolower(trim($status));
    if ($normalized === '') {
        return 'badge bg-light text-muted';
    }
    if (str_contains($normalized, 'hủy')) {
        return 'badge bg-danger-subtle text-danger';
    }
    if (str_contains($normalized, 'hoàn thành')) {
        return 'badge bg-success-subtle text-success';
    }
    if (str_contains($normalized, 'đang')) {
        return 'badge bg-primary-subtle text-primary';
    }
    if (str_contains($normalized, 'chờ')) {
        return 'badge bg-warning-subtle text-warning';
    }
    return 'badge bg-secondary-subtle text-secondary';
};
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1">Chi tiết kế hoạch sản xuất</h2>
        <?php if ($plan): ?>
            <p class="text-muted mb-0">Theo dõi tiến độ đơn hàng <?= htmlspecialchars($plan['IdDonHang'] ?? '-') ?> và các xưởng phụ trách.</p>
        <?php else: ?>
            <p class="text-muted mb-0">Không tìm thấy dữ liệu kế hoạch.</p>
        <?php endif; ?>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="?controller=plan&action=index" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay về danh sách
        </a>
    </div>
</div>

<?php if (!$plan): ?>
    <div class="alert alert-warning">Không tìm thấy kế hoạch yêu cầu.</div>
<?php else: ?>
    <div class="row g-4 mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <div class="text-muted small text-uppercase">Mã kế hoạch</div>
                            <div class="fs-4 fw-semibold"><?= htmlspecialchars($plan['IdKeHoachSanXuat'] ?? '-') ?></div>
                        </div>
                        <span class="<?= $statusBadge((string) ($plan['TrangThai'] ?? '')) ?>">
                            <?= htmlspecialchars($plan['TrangThai'] ?? 'Chưa cập nhật') ?>
                        </span>
                    </div>
                    <div class="small text-muted">Được lập cho đơn hàng <?= htmlspecialchars($plan['IdDonHang'] ?? '-') ?></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase">Thời gian thực hiện</div>
                    <div class="fw-semibold mt-2">Bắt đầu: <?= $formatDate($plan['ThoiGianBD'] ?? null) ?></div>
                    <div class="fw-semibold">Hạn chót: <?= $formatDate($plan['ThoiGianKetThuc'] ?? null) ?></div>
                    <div class="text-muted small mt-2">Số lượng sản xuất: <?= htmlspecialchars((string) ($plan['SoLuong'] ?? 0)) ?> <?= htmlspecialchars($plan['DonVi'] ?? 'sp') ?></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase">Người phụ trách</div>
                    <div class="fw-semibold mt-2"><?= htmlspecialchars($plan['TenQuanLy'] ?? 'Chưa phân công') ?></div>
                    <?php if (!empty($plan['YeuCau'])): ?>
                        <div class="text-muted small mt-2">Yêu cầu: <?= htmlspecialchars($plan['YeuCau']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($plan['TenCauHinh'])): ?>
                        <div class="text-muted small mt-2">Cấu hình: <?= htmlspecialchars($plan['TenCauHinh']) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php
    $currentUser = $currentUser ?? [];
$roleId = $currentUser['ActualIdVaiTro'] ?? ($currentUser['IdVaiTro'] ?? null);
$canManage = in_array($roleId, ['VT_BAN_GIAM_DOC', 'VT_ADMIN'], true);
$status = $plan['TrangThai'] ?? '';
$canEditDeadline = $canManage && !in_array($status, ['Hoàn thành', 'Hủy'], true);
$canCancel = $canManage && in_array($status, ['Đang chuẩn bị', 'Đang sản xuất'], true);
?>
    <?php if ($canManage): ?>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <h6 class="fw-semibold mb-1">Điều chỉnh kế hoạch</h6>
                        <div class="text-muted small">Chỉnh sửa hạn chót hoặc hủy kế hoạch khi đang chuẩn bị hoặc đang sản xuất.</div>
                    </div>
                </div>
                <?php if ($canEditDeadline): ?>
                    <form method="post" action="?controller=plan&action=updateDeadline" class="row g-2 align-items-end mt-3">
                        <input type="hidden" name="IdKeHoachSanXuat" value="<?= htmlspecialchars($plan['IdKeHoachSanXuat']) ?>">
                        <div class="col-md-4">
                            <label class="form-label">Hạn chót mới</label>
                            <input type="datetime-local" name="ThoiGianKetThuc" class="form-control" required
                                   value="<?= !empty($plan['ThoiGianKetThuc']) ? htmlspecialchars(date('Y-m-d\TH:i', strtotime($plan['ThoiGianKetThuc']))) : '' ?>">
                        </div>
                        <div class="col-md-auto">
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="bi bi-calendar-check me-2"></i>Cập nhật hạn chót
                            </button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-light border mt-3 mb-0">Không thể chỉnh sửa hạn chót khi kế hoạch đã hoàn tất hoặc bị hủy.</div>
                <?php endif; ?>

                <?php if ($canCancel): ?>
                    <form method="post" action="?controller=plan&action=cancel" class="mt-3" onsubmit="return confirm('Bạn chắc chắn muốn hủy kế hoạch này?');">
                        <input type="hidden" name="IdKeHoachSanXuat" value="<?= htmlspecialchars($plan['IdKeHoachSanXuat']) ?>">
                        <div class="mb-3">
                            <label class="form-label">Ghi chú hủy kế hoạch</label>
                            <textarea name="cancel_note" rows="3" class="form-control" placeholder="Lý do hủy kế hoạch..." required></textarea>
                        </div>
                        <button class="btn btn-outline-danger" type="submit">
                            <i class="bi bi-x-circle me-2"></i>Hủy kế hoạch sản xuất
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Thông tin sản phẩm</h5>
                <span class="text-muted small">Dòng sản phẩm và yêu cầu chi tiết từ khách hàng.</span>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <div class="fw-semibold mb-1">Sản phẩm</div>
                        <div><?= htmlspecialchars($plan['TenSanPham'] ?? 'Không xác định') ?></div>
                        <?php if (!empty($plan['TenCauHinh'])): ?>
                            <div class="text-muted small mt-2">Cấu hình: <?= htmlspecialchars($plan['TenCauHinh']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <div class="fw-semibold mb-1">Ghi chú khách hàng</div>
                        <div class="text-muted small mb-2">Ngày đặt: <?= $formatDate($plan['NgayLapDonHang'] ?? null, 'd/m/Y') ?></div>
                        <div><?= htmlspecialchars($plan['YeuCau'] ?? 'Không có yêu cầu đặc biệt') ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Danh sách hạng mục xưởng</h5>
                <span class="text-muted small">Các công đoạn được giao cho từng xưởng phụ trách.</span>
            </div>
        </div>
        <?php if (empty($workshopPlans)): ?>
            <div class="card-body">
                <div class="alert alert-light border mb-0">Chưa có hạng mục nào được phân công cho xưởng.</div>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>Hạng mục</th>
                        <th>Xưởng phụ trách</th>
                        <th>Số lượng</th>
                        <th>Bắt đầu</th>
                        <th>Hạn chót</th>
                        <th>Trạng thái</th>
                        <th>Tình trạng vật tư</th>
                        <th class="text-center">Nguyên liệu</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($workshopPlans as $item): ?>
                        <tr>
                            <td>
                                <div class="fw-semibold"><?= htmlspecialchars($item['TenThanhThanhPhanSP'] ?? 'Hạng mục') ?></div>
                                <?php if (!empty($item['IdCongDoan'])): ?>
                                    <div class="text-muted small">Mã công đoạn: <?= htmlspecialchars($item['IdCongDoan']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($item['TenXuong'] ?? 'Chưa xác định') ?></td>
                            <td><?= htmlspecialchars((string) ($item['SoLuong'] ?? 0)) ?></td>
                            <td><?= $formatDate($item['ThoiGianBatDau'] ?? null) ?></td>
                            <td><?= $formatDate($item['ThoiGianKetThuc'] ?? null) ?></td>
                            <td>
                                <span class="<?= $statusBadge((string) ($item['TrangThai'] ?? '')) ?>">
                                    <?= htmlspecialchars($item['TrangThai'] ?? 'Chưa cập nhật') ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-muted">
                                    <?= htmlspecialchars($item['TinhTrangVatTu'] ?? 'Chưa kiểm tra') ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="?controller=workshop_plan&action=read&id=<?= urlencode($item['IdKeHoachSanXuatXuong']) ?>" class="btn btn-sm btn-outline-primary">
                                    Kiểm tra
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
