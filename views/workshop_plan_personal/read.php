<?php
$plan = $plan ?? [];
$materials = $materials ?? [];
$assignments = $assignments ?? [];
$formatDate = static function (?string $value, string $format = 'd/m/Y H:i'): string {
    if (!$value) {
        return '-';
    }
    $timestamp = strtotime($value);
    return $timestamp ? date($format, $timestamp) : '-';
};
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Chi tiết kế hoạch xưởng</h3>
        <p class="text-muted mb-0">Theo dõi tiến độ và vật tư của kế hoạch được phân công.</p>
    </div>
    <a href="?controller=workshop_plan_personal&action=index" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card p-4 h-100">
            <h5 class="mb-3">Thông tin kế hoạch</h5>
            <ul class="list-unstyled mb-0">
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span>Mã kế hoạch</span>
                    <span class="fw-semibold"><?= htmlspecialchars($plan['IdKeHoachSanXuatXuong'] ?? '-') ?></span>
                </li>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span>Hạng mục</span>
                    <span class="fw-semibold"><?= htmlspecialchars($plan['TenThanhThanhPhanSP'] ?? '-') ?></span>
                </li>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span>Xưởng</span>
                    <span class="fw-semibold"><?= htmlspecialchars($plan['TenXuong'] ?? '-') ?></span>
                </li>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span>Thời gian bắt đầu</span>
                    <span class="fw-semibold"><?= htmlspecialchars($formatDate($plan['ThoiGianBatDau'] ?? null)) ?></span>
                </li>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span>Thời gian kết thúc</span>
                    <span class="fw-semibold"><?= htmlspecialchars($formatDate($plan['ThoiGianKetThuc'] ?? null)) ?></span>
                </li>
                <li class="d-flex justify-content-between py-2">
                    <span>Trạng thái</span>
                    <span class="badge <?= ($plan['TrangThai'] ?? '') === 'Hoàn thành' ? 'badge-soft-success' : 'badge-soft-warning' ?>">
                        <?= htmlspecialchars($plan['TrangThai'] ?? 'Đang thực hiện') ?>
                    </span>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card p-4 h-100">
            <h5 class="mb-3">Ca làm được phân công</h5>
            <?php if (empty($assignments)): ?>
                <div class="text-muted">Chưa có ca làm nào được ghi nhận cho bạn.</div>
            <?php else: ?>
                <ul class="list-unstyled mb-0">
                    <?php foreach ($assignments as $assignment): ?>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <div>
                                <div class="fw-semibold"><?= htmlspecialchars($assignment['TenCa'] ?? 'Ca làm việc') ?></div>
                                <div class="small text-muted">
                                    Ngày: <?= htmlspecialchars($formatDate($assignment['NgayLamViec'] ?? null, 'd/m/Y')) ?>
                                    • <?= htmlspecialchars($formatDate($assignment['ThoiGianBatDau'] ?? null, 'H:i')) ?>
                                    → <?= htmlspecialchars($formatDate($assignment['ThoiGianKetThuc'] ?? null, 'H:i')) ?>
                                </div>
                                <div class="small text-muted">Vai trò: <?= htmlspecialchars($assignment['VaiTro'] ?? 'Nhân viên') ?></div>
                            </div>
                            <span class="text-muted small">ID: <?= htmlspecialchars($assignment['IdCaLamViec'] ?? '-') ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="card p-4 mt-4">
    <h5 class="mb-3">Danh sách nguyên liệu</h5>
    <?php if (empty($materials)): ?>
        <div class="text-muted">Chưa có thông tin nguyên liệu cho kế hoạch này.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>Nguyên liệu</th>
                    <th>Đơn vị</th>
                    <th>Số lượng kế hoạch</th>
                    <th>Tồn kho</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($materials as $material): ?>
                    <tr>
                        <td class="fw-semibold"><?= htmlspecialchars($material['TenNL'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($material['DonVi'] ?? '-') ?></td>
                        <td><?= number_format($material['SoLuongKeHoach'] ?? 0) ?></td>
                        <td><?= number_format($material['SoLuongTonKho'] ?? 0) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
