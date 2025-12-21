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
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h3 class="fw-bold mb-1">Phân công theo ca</h3>
        <p class="text-muted mb-0">Chọn nhân sự sản xuất cho từng ca của kế hoạch xưởng.</p>
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
        <div class="row g-4">
            <?php foreach ($workShifts as $shift): ?>
                <?php $shiftId = $shift['IdCaLamViec'] ?? ''; ?>
                <div class="col-lg-6">
                    <div class="card p-4 h-100">
                        <div class="fw-semibold mb-2"><?= htmlspecialchars($shift['TenCa'] ?? $shiftId) ?></div>
                        <div class="text-muted small mb-3">
                            <?= htmlspecialchars($shift['NgayLamViec'] ?? '-') ?>
                            <?= !empty($shift['ThoiGianBatDau']) ? ' • ' . htmlspecialchars($shift['ThoiGianBatDau']) : '' ?>
                            <?= !empty($shift['ThoiGianKetThuc']) ? ' → ' . htmlspecialchars($shift['ThoiGianKetThuc']) : '' ?>
                        </div>
                        <label class="form-label fw-semibold">Nhân sự sản xuất</label>
                        <select name="assignments[<?= htmlspecialchars($shiftId) ?>][]" class="form-select" multiple size="6">
                            <?php foreach ($availableEmployees as $employee): ?>
                                <?php $employeeId = $employee['IdNhanVien'] ?? ''; ?>
                                <option value="<?= htmlspecialchars($employeeId) ?>" <?= in_array($employeeId, $assignedMap[$shiftId] ?? [], true) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($employee['HoTen'] ?? $employeeId) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="text-muted small mt-2">Giữ Ctrl/Cmd để chọn nhiều nhân sự.</div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-people me-2"></i>Lưu phân công
            </button>
        </div>
    </form>
<?php endif; ?>
