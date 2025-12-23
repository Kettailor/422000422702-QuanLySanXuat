<?php
$records = $records ?? [];
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
        <h3 class="fw-bold mb-1">Lịch sử chấm công</h3>
        <p class="text-muted mb-0">Theo dõi các lượt vào/ra ca của bạn.</p>
    </div>
    <a href="?controller=auth&action=profile" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Hồ sơ cá nhân
    </a>
</div>

<div class="card p-4">
    <?php if (empty($records)): ?>
        <div class="text-muted">Chưa có dữ liệu chấm công.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>Ngày làm việc</th>
                    <th>Ca</th>
                    <th>Giờ vào</th>
                    <th>Giờ ra</th>
                    <th>Ghi chú</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($records as $record): ?>
                    <tr>
                        <td><?= htmlspecialchars($formatDate($record['NgayLamViec'] ?? null, 'd/m/Y')) ?></td>
                        <td><?= htmlspecialchars($record['TenCa'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($formatDate($record['ThoiGianVao'] ?? null)) ?></td>
                        <td><?= htmlspecialchars($formatDate($record['ThoiGIanRa'] ?? null)) ?></td>
                        <td class="text-muted small"><?= htmlspecialchars($record['GhiChu'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
