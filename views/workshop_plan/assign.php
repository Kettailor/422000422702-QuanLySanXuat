<?php
$plan = $plan ?? null;
$availableEmployees = $availableEmployees ?? [];
$planAssignments = $planAssignments ?? [];
$workShifts = $workShifts ?? [];

$assignedMap = [];
$assignedNameMap = [];
foreach ($planAssignments as $assignment) {
    $shiftId = $assignment['IdCaLamViec'] ?? null;
    if (!$shiftId) {
        continue;
    }
    if (!isset($assignedMap[$shiftId])) {
        $assignedMap[$shiftId] = [];
        $assignedNameMap[$shiftId] = [];
    }
    $assignedMap[$shiftId][] = $assignment['IdNhanVien'];
    $assignedNameMap[$shiftId][] = $assignment['HoTen'] ?? $assignment['IdNhanVien'];
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

$nowTimestamp = time();
$shiftTypes = ['Ca sáng', 'Ca trưa', 'Ca tối'];
$shiftTypeMap = static function (string $label) use ($shiftTypes): string {
    foreach ($shiftTypes as $type) {
        if (stripos($label, $type) !== false) {
            return $type;
        }
    }
    return 'Ca khác';
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

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">Bộ lọc phân công</h5>
            </div>
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-4">
                        <div class="fw-semibold mb-2">Chọn ngày phân công</div>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach (array_keys($shiftsByDate) as $dateKey): ?>
                                <label class="btn btn-sm btn-outline-primary">
                                    <input type="checkbox" class="form-check-input me-2 assignment-date" value="<?= htmlspecialchars($dateKey) ?>" checked>
                                    <?= htmlspecialchars($formatDate($dateKey)) ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="fw-semibold mb-2">Chọn ca</div>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($shiftTypes as $type): ?>
                                <label class="btn btn-sm btn-outline-secondary">
                                    <input type="checkbox" class="form-check-input me-2 assignment-shift" value="<?= htmlspecialchars($type) ?>" checked>
                                    <?= htmlspecialchars($type) ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label fw-semibold">Tìm nhân viên</label>
                        <input type="text" class="form-control" id="employee-search" placeholder="Nhập tên nhân viên...">
                    </div>
                </div>

                <div class="mt-4">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="fw-semibold">Danh sách nhân viên</div>
                                <span class="text-muted small">Chọn nhiều nhân viên để phân công nhanh.</span>
                            </div>
                            <div class="row g-2" id="employee-list">
                                <?php foreach ($availableEmployees as $employee): ?>
                                    <?php $employeeId = $employee['IdNhanVien'] ?? ''; ?>
                                    <div class="col-md-6 col-lg-4 employee-item" data-name="<?= htmlspecialchars(mb_strtolower($employee['HoTen'] ?? $employeeId, 'UTF-8')) ?>">
                                        <label class="form-check d-flex align-items-center gap-2 border rounded-3 p-2 bg-white">
                                            <input type="checkbox" class="form-check-input employee-checkbox" value="<?= htmlspecialchars($employeeId) ?>">
                                            <span><?= htmlspecialchars($employee['HoTen'] ?? $employeeId) ?></span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="mt-3 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary" id="bulk-assign-btn">
                                    <i class="bi bi-plus-circle me-2"></i>Thêm vào ca đã chọn
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php foreach ($shiftsByDate as $dateKey => $shifts): ?>
            <?php $isToday = $dateKey === $today; ?>
            <div class="card border-0 shadow-sm mb-4 shift-group" data-date="<?= htmlspecialchars($dateKey) ?>">
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
                            <?php
                                $typeLabel = $shiftTypeMap((string) ($shift['TenCa'] ?? ''));
                                $startTime = $shift['ThoiGianBatDau'] ?? null;
                                $endTime = $shift['ThoiGianKetThuc'] ?? null;
                                $startTs = $startTime ? strtotime($startTime) : null;
                                $endTs = $endTime ? strtotime($endTime) : null;
                                $isTodayShift = ($shift['NgayLamViec'] ?? '') === $today;
                                $isInProgress = $isTodayShift && $startTs && $endTs && $nowTimestamp >= $startTs && $nowTimestamp <= $endTs;
                                $isEditable = !$isTodayShift || ($isTodayShift && (!$startTs || !$endTs || $nowTimestamp < $startTs || $nowTimestamp > $endTs));
                            ?>
                            <div class="col-lg-4">
                                <div class="border rounded-3 p-3 h-100 shift-card" data-date="<?= htmlspecialchars($dateKey) ?>" data-shift-type="<?= htmlspecialchars($typeLabel) ?>" data-shift-id="<?= htmlspecialchars($shiftId) ?>" data-editable="<?= $isEditable ? '1' : '0' ?>">
                                    <div class="fw-semibold"><?= htmlspecialchars($shift['TenCa'] ?? $shiftId) ?></div>
                                    <div class="text-muted small mb-2">
                                        <?= htmlspecialchars($shift['ThoiGianBatDau'] ?? '-') ?>
                                        <?= !empty($shift['ThoiGianKetThuc']) ? '→ ' . htmlspecialchars($shift['ThoiGianKetThuc']) : '' ?>
                                        <span class="badge <?= $isEditable ? 'bg-success-subtle text-success' : ($isInProgress ? 'bg-warning-subtle text-warning' : 'bg-secondary-subtle text-secondary') ?> ms-2">
                                            <?= $isEditable ? 'Có thể sửa' : ($isInProgress ? 'Đang diễn ra' : 'Đã khóa') ?>
                                        </span>
                                    </div>
                                    <label class="form-label fw-semibold">Nhân sự</label>
                                    <select name="assignments[<?= htmlspecialchars($shiftId) ?>][]" class="form-select assignment-select" multiple size="5" <?= $isEditable ? '' : 'disabled' ?>>
                                        <?php foreach ($availableEmployees as $employee): ?>
                                            <?php $employeeId = $employee['IdNhanVien'] ?? ''; ?>
                                            <option value="<?= htmlspecialchars($employeeId) ?>" <?= in_array($employeeId, $assignedMap[$shiftId] ?? [], true) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($employee['HoTen'] ?? $employeeId) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (!$isEditable): ?>
                                        <div class="text-muted small mt-2">Không thể chỉnh sửa trong khung giờ đang diễn ra.</div>
                                    <?php else: ?>
                                        <div class="text-muted small mt-2">Giữ Ctrl/Cmd để chọn nhiều nhân sự.</div>
                                    <?php endif; ?>
                                    <div class="mt-3">
                                        <div class="text-muted small mb-1">Nhân sự đã phân công</div>
                                        <div class="assigned-list small text-muted" data-shift-id="<?= htmlspecialchars($shiftId) ?>">
                                            <?= !empty($assignedNameMap[$shiftId]) ? implode(', ', array_map('htmlspecialchars', $assignedNameMap[$shiftId])) : 'Chưa có' ?>
                                        </div>
                                    </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const employeeSearch = document.getElementById('employee-search');
    const employeeItems = document.querySelectorAll('.employee-item');
    const bulkAssignBtn = document.getElementById('bulk-assign-btn');
    const dateFilters = document.querySelectorAll('.assignment-date');
    const shiftFilters = document.querySelectorAll('.assignment-shift');
    const shiftCards = document.querySelectorAll('.shift-card');
    const shiftGroups = document.querySelectorAll('.shift-group');

    const applyFilters = () => {
        const activeDates = Array.from(dateFilters).filter(cb => cb.checked).map(cb => cb.value);
        const activeShifts = Array.from(shiftFilters).filter(cb => cb.checked).map(cb => cb.value);

        shiftGroups.forEach(group => {
            const date = group.dataset.date;
            group.style.display = activeDates.includes(date) ? '' : 'none';
        });

        shiftCards.forEach(card => {
            const date = card.dataset.date;
            const type = card.dataset.shiftType;
            const visible = activeDates.includes(date) && activeShifts.includes(type);
            card.style.display = visible ? '' : 'none';
        });
    };

    dateFilters.forEach(cb => cb.addEventListener('change', applyFilters));
    shiftFilters.forEach(cb => cb.addEventListener('change', applyFilters));
    applyFilters();

    if (employeeSearch) {
        employeeSearch.addEventListener('input', function() {
            const keyword = this.value.trim().toLowerCase();
            employeeItems.forEach(item => {
                const name = item.dataset.name || '';
                item.style.display = name.includes(keyword) ? '' : 'none';
            });
        });
    }

    if (bulkAssignBtn) {
        bulkAssignBtn.addEventListener('click', function() {
            const selectedEmployees = Array.from(document.querySelectorAll('.employee-checkbox:checked')).map(cb => cb.value);
            if (selectedEmployees.length === 0) {
                alert('Vui lòng chọn nhân viên để thêm.');
                return;
            }

            const activeCards = Array.from(shiftCards).filter(card => card.style.display !== 'none' && card.dataset.editable === '1');
            if (activeCards.length === 0) {
                alert('Không có ca nào đang được chọn để phân công.');
                return;
            }

            activeCards.forEach(card => {
                const select = card.querySelector('select.assignment-select');
                if (!select) {
                    return;
                }
                selectedEmployees.forEach(employeeId => {
                    const option = Array.from(select.options).find(opt => opt.value === employeeId);
                    if (option) {
                        option.selected = true;
                    }
                });
            });

            document.querySelectorAll('.employee-checkbox').forEach(cb => {
                cb.checked = false;
            });
        });
    }
});
</script>
