<?php
$entries = $entries ?? [];
$workDate = $workDate ?? null;
$shifts = $shifts ?? [];

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
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1">Nhật ký chấm công</h2>
        <p class="text-muted mb-0">Danh sách chấm công mới nhất theo ca làm việc.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="?controller=timekeeping&action=create" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Ghi nhận chấm công
        </a>
        <a href="?controller=factory_plan&action=index" class="btn btn-outline-secondary">
            <i class="bi bi-list me-2"></i>Kế hoạch xưởng
        </a>
    </div>
</div>

<div class="alert alert-light border d-flex flex-wrap align-items-center gap-2 mb-4">
    <i class="bi bi-calendar-event text-primary"></i>
    <span class="fw-semibold">Ngày làm việc:</span>
    <span><?= htmlspecialchars((string) $workDate) ?></span>
    <span class="text-muted">• Hiển thị tự động chấm công trong ngày.</span>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0">
        <h5 class="mb-0">Danh sách chấm công</h5>
    </div>
    <div class="card-body">
        <?php if (empty($entries)): ?>
            <div class="alert alert-light border mb-0">Chưa có bản ghi chấm công.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nhân sự</th>
                            <th>Ca làm việc</th>
                            <th>Giờ vào</th>
                            <th>Giờ ra</th>
                            <th>Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($entries as $entry): ?>
                            <tr>
                                <td><?= htmlspecialchars($entry['TenNhanVien'] ?? $entry['NHANVIEN IdNhanVien'] ?? '-') ?></td>
                                <td>
                                    <div class="fw-semibold"><?= htmlspecialchars($entry['TenCa'] ?? $entry['IdCaLamViec'] ?? '-') ?></div>
                                    <div class="text-muted small">
                                        <?= htmlspecialchars($entry['NgayLamViec'] ?? '-') ?>
                                        <?php if (!empty($entry['LoaiCa'])): ?>
                                            • <?= htmlspecialchars($entry['LoaiCa']) ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td><?= $formatDate($entry['ThoiGianVao'] ?? null) ?></td>
                                <td><?= $formatDate($entry['ThoiGIanRa'] ?? null) ?></td>
                                <td class="text-muted small"><?= htmlspecialchars($entry['GhiChu'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
