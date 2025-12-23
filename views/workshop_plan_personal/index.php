<?php
$plans = $plans ?? [];
$formatDate = static function (?string $value, string $format = 'd/m/Y'): string {
    if (!$value) {
        return '-';
    }
    $timestamp = strtotime($value);
    return $timestamp ? date($format, $timestamp) : '-';
};
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Kế hoạch xưởng được phân công</h3>
        <p class="text-muted mb-0">Theo dõi các kế hoạch liên quan trực tiếp đến bạn.</p>
    </div>
    <a href="?controller=dashboard&action=index" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
</div>

<div class="card p-4">
    <?php if (empty($plans)): ?>
        <div class="text-muted">Chưa có kế hoạch xưởng nào được phân công.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>Hạng mục</th>
                    <th>Xưởng</th>
                    <th>Số lượng</th>
                    <th>Thời gian</th>
                    <th>Trạng thái</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($plans as $plan): ?>
                    <?php
                    $status = $plan['TrangThai'] ?? 'Đang thực hiện';
                    $statusClass = $status === 'Hoàn thành' ? 'badge-soft-success' : 'badge-soft-warning';
                    ?>
                    <tr>
                        <td class="fw-semibold"><?= htmlspecialchars($plan['TenThanhThanhPhanSP'] ?? 'Kế hoạch xưởng') ?></td>
                        <td><?= htmlspecialchars($plan['TenXuong'] ?? '-') ?></td>
                        <td><?= number_format($plan['SoLuong'] ?? 0) ?></td>
                        <td><?= htmlspecialchars($formatDate($plan['ThoiGianBatDau'] ?? null)) ?></td>
                        <td><span class="badge <?= $statusClass ?>"><?= htmlspecialchars($status) ?></span></td>
                        <td>
                            <a href="?controller=workshop_plan_personal&action=read&id=<?= urlencode($plan['IdKeHoachSanXuatXuong'] ?? '') ?>" class="btn btn-sm btn-outline-primary">
                                Chi tiết
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
