<?php
$plan = $plan ?? null;
$availableEmployees = $availableEmployees ?? [];
$planAssignments = $planAssignments ?? [];
$workShifts = $workShifts ?? [];

$assignedMap = [];
foreach ($planAssignments as $assignment) {
    $shiftId = $assignment['IdCaLamViec'] ?? null;
    if (!$shiftId) {
        continue;
    }
    if (!isset($assignedMap[$shiftId])) {
        $assignedMap[$shiftId] = [];
    }
    $assignedMap[$shiftId][] = $assignment['IdNhanVien'];
}

$today = date('Y-m-d');
$shiftsByDate = [];
foreach ($workShifts as $shift) {
    $dateKey = $shift['NgayLamViec'] ?? 'unknown';
    $shiftsByDate[$dateKey][] = $shift;
}
ksort($shiftsByDate);

$formatDate = static function (?string $value, string $format = 'd/m/Y'): string {
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
        <h3 class="fw-bold mb-1">Phân công theo ca</h3>
        <p class="text-muted mb-0">Giao nhân sự theo từng ca để cập nhật tiến độ và chấm công.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="?controller=workshop_plan&action=read&id=<?= urlencode($plan['IdKeHoachSanXuatXuong'] ?? '') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Chi tiết kế hoạch
        </a>
    </div>
</div>

<?php if (!$plan): ?>
    <div class="alert alert-warning">Không tìm thấy kế hoạch xưởng.</div>
<?php elseif (empty($workShifts)): ?>
    <div class="alert alert-light border">Chưa có ca làm việc cho kế hoạch xưởng.</div>
<?php elseif (empty($availableEmployees)): ?>
    <div class="alert alert-light border">Chưa có nhân sự sản xuất được gán cho xưởng.</div>
<?php else: ?>
    <form method="post" action="?controller=workshop_plan&action=saveAssignments">
        <input type="hidden" name="IdKeHoachSanXuatXuong" value="<?= htmlspecialchars($plan['IdKeHoachSanXuatXuong']) ?>">
        <div class="card p-4 mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="text-muted small">Kế hoạch xưởng</div>
                    <div class="fw-semibold"><?= htmlspecialchars($plan['IdKeHoachSanXuatXuong'] ?? '-') ?></div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Hạng mục</div>
                    <div class="fw-semibold"><?= htmlspecialchars($plan['TenThanhThanhPhanSP'] ?? '-') ?></div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Thời gian kế hoạch</div>
                    <div class="fw-semibold">
                        <?= $formatDate($plan['ThoiGianBatDau'] ?? null) ?> → <?= $formatDate($plan['ThoiGianKetThuc'] ?? null) ?>
                    </div>
                </div>
            </div>
        </div>

        <?php foreach ($shiftsByDate as $dateKey => $shifts): ?>
            <?php $isToday = $dateKey === $today; ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold">Ngày <?= htmlspecialchars($formatDate($dateKey)) ?></div>
                        <div class="text-muted small">
                            <?= $isToday ? 'Hôm nay: không chỉnh sửa phân công' : 'Có thể điều chỉnh phân công' ?>
                        </div>
                    </div>
                    <span class="badge <?= $isToday ? 'bg-secondary-subtle text-secondary' : 'bg-success-subtle text-success' ?>">
                        <?= $isToday ? 'Đã khóa' : 'Đang mở' ?>
                    </span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <?php foreach ($shifts as $shift): ?>
                            <?php $shiftId = $shift['IdCaLamViec'] ?? ''; ?>
                            <div class="col-lg-4">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="fw-semibold"><?= htmlspecialchars($shift['TenCa'] ?? $shiftId) ?></div>
                                    <div class="text-muted small mb-2">
                                        <?= htmlspecialchars($shift['ThoiGianBatDau'] ?? '-') ?>
                                        <?= !empty($shift['ThoiGianKetThuc']) ? '→ ' . htmlspecialchars($shift['ThoiGianKetThuc']) : '' ?>
                                    </div>
                                    <label class="form-label fw-semibold">Nhân sự</label>
                                    <select name="assignments[<?= htmlspecialchars($shiftId) ?>][]" class="form-select" multiple size="5" <?= $isToday ? 'disabled' : '' ?>>
                                        <?php foreach ($availableEmployees as $employee): ?>
                                            <?php $employeeId = $employee['IdNhanVien'] ?? ''; ?>
                                            <option value="<?= htmlspecialchars($employeeId) ?>" <?= in_array($employeeId, $assignedMap[$shiftId] ?? [], true) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($employee['HoTen'] ?? $employeeId) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if ($isToday): ?>
                                        <div class="text-muted small mt-2">Phân công hôm nay không chỉnh sửa.</div>
                                    <?php else: ?>
                                        <div class="text-muted small mt-2">Giữ Ctrl/Cmd để chọn nhiều nhân sự.</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-people me-2"></i>Lưu phân công
            </button>
        </div>
    </form>
<?php endif; ?>
