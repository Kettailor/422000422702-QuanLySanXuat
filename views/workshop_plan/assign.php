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
$normalizeDate = static function (?string $value, ?int $fallbackYear = null): ?string {
    if (!$value) {
        return null;
    }

    $value = trim($value);
    if (preg_match('/^\d{1,2}\/\d{1,2}$/', $value)) {
        $value .= '/' . ($fallbackYear ?? (int) date('Y'));
    }

    $formats = ['Y-m-d H:i:s', 'Y-m-d', 'd/m/Y H:i:s', 'd/m/Y H:i', 'd/m/Y', 'd/m/y'];
    foreach ($formats as $format) {
        $date = DateTime::createFromFormat($format, $value);
        if ($date instanceof DateTime) {
            return $date->format('Y-m-d');
        }
    }

    $timestamp = strtotime($value);
    if ($timestamp === false) {
        return null;
    }
    return date('Y-m-d', $timestamp);
};
$planDates = [];
$planStart = $plan['ThoiGianBatDau'] ?? null;
$planEnd = $plan['ThoiGianKetThuc'] ?? null;
if ($planStart) {
    $startDate = $normalizeDate($planStart);
    $startYear = $startDate ? (int) date('Y', strtotime($startDate)) : null;
    $endDate = $planEnd ? $normalizeDate($planEnd, $startYear) : $startDate;
    if (!$startDate) {
        $startDate = $normalizeDate($today);
        $startYear = (int) date('Y', strtotime($startDate));
    }
    if (!$endDate) {
        $endDate = $startDate;
    }
    if ($planEnd && $startDate && $endDate && strtotime($endDate) < strtotime($startDate)) {
        $endDate = date('Y-m-d', strtotime($startDate . ' +1 year'));
    }
    $current = new DateTimeImmutable($startDate);
    $end = new DateTimeImmutable($endDate);
    while ($current <= $end) {
        $planDates[] = $current->format('Y-m-d');
        $current = $current->modify('+1 day');
    }
}
if (empty($planDates)) {
    $planDates = array_keys($shiftsByDate);
}
foreach ($planDates as $dateKey) {
    if (!isset($shiftsByDate[$dateKey])) {
        $shiftsByDate[$dateKey] = [];
    }
}
if (!empty($planDates)) {
    $allowedDates = array_flip($planDates);
    $shiftsByDate = array_intersect_key($shiftsByDate, $allowedDates);
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

<div id="assignment-feedback" class="border rounded-3 bg-light-subtle text-muted p-3 d-none" role="status"></div>

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
                            <?php foreach ($planDates as $dateKey): ?>
                                <label class="btn btn-sm btn-outline-primary">
                                    <input type="checkbox" class="form-check-input me-2 assignment-date" value="<?= htmlspecialchars($dateKey) ?>">
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
                                    <input type="checkbox" class="form-check-input me-2 assignment-shift" value="<?= htmlspecialchars($type) ?>">
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

        <div id="assignment-cards" class="d-none">
            <?php foreach ($shiftsByDate as $dateKey => $shifts): ?>
                <?php
                    $isToday = $dateKey === $today;
                    $dateCompare = strcmp($dateKey, $today);
                    $groupStatus = $dateCompare < 0 ? 'past' : ($dateCompare > 0 ? 'future' : 'today');
                    $groupStatusLabel = $groupStatus === 'past' ? 'Đã khóa' : ($groupStatus === 'future' ? 'Sắp tới' : 'Hôm nay');
                    $groupStatusClass = $groupStatus === 'past' ? 'bg-secondary-subtle text-secondary' : ($groupStatus === 'future' ? 'bg-info-subtle text-info' : 'bg-primary-subtle text-primary');
                    $groupHint = $groupStatus === 'past'
                        ? 'Ngày đã qua: không chỉnh sửa phân công.'
                        : ($groupStatus === 'future' ? 'Ngày sắp tới: có thể chỉnh sửa phân công.' : 'Hôm nay: chỉ chỉnh sửa trước giờ vào ca.');
                ?>
                <div class="card border-0 shadow-sm mb-4 shift-group" data-date="<?= htmlspecialchars($dateKey) ?>">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">Ngày <?= htmlspecialchars($formatDate($dateKey)) ?></div>
                            <div class="text-muted small"><?= $groupHint ?></div>
                        </div>
                        <span class="badge <?= $groupStatusClass ?>">
                            <?= $groupStatusLabel ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <?php if (empty($shifts)): ?>
                            <div class="alert alert-light border mb-0">Chưa có ca làm việc cho ngày này.</div>
                        <?php else: ?>
                        <div class="row g-3">
                            <?php foreach ($shifts as $shift): ?>
                                <?php $shiftId = $shift['IdCaLamViec'] ?? ''; ?>
                                <?php
                                    $typeLabel = $shiftTypeMap((string) ($shift['TenCa'] ?? ''));
                                    $startTime = $shift['ThoiGianBatDau'] ?? null;
                                    $endTime = $shift['ThoiGianKetThuc'] ?? null;
                                    $startTs = $startTime ? strtotime($startTime) : null;
                                    $endTs = $endTime ? strtotime($endTime) : null;
                                    $shiftDate = $shift['NgayLamViec'] ?? '';
                                    $isTodayShift = $shiftDate === $today;
                                    $shiftDateCompare = $shiftDate ? strcmp($shiftDate, $today) : 0;
                                    $isPastDate = $shiftDateCompare < 0;
                                    $isFutureDate = $shiftDateCompare > 0;
                                    $isInProgress = $isTodayShift && $startTs && $endTs && $nowTimestamp >= $startTs && $nowTimestamp <= $endTs;
                                    $isAfterEnd = $isTodayShift && $endTs && $nowTimestamp > $endTs;
                                    $isAddOnly = $isTodayShift && !$isAfterEnd;
                                    $isEditable = $isFutureDate;
                                    if ($isPastDate || $isAfterEnd) {
                                        $isEditable = false;
                                        $isAddOnly = false;
                                    }

                                    if ($isPastDate || $isAfterEnd) {
                                        $statusLabel = 'Đã khóa';
                                        $statusClass = 'bg-secondary-subtle text-secondary';
                                    } elseif ($isAddOnly) {
                                        $statusLabel = 'Chỉ thêm nhân sự';
                                        $statusClass = 'bg-info-subtle text-info';
                                    } elseif ($isInProgress) {
                                        $statusLabel = 'Đang diễn ra';
                                        $statusClass = 'bg-warning-subtle text-warning';
                                    } else {
                                        $statusLabel = 'Chưa bắt đầu';
                                        $statusClass = 'bg-success-subtle text-success';
                                    }
                                ?>
                                <div class="col-lg-4">
                                    <div class="border rounded-3 p-3 h-100 shift-card" data-date="<?= htmlspecialchars($dateKey) ?>" data-shift-type="<?= htmlspecialchars($typeLabel) ?>" data-shift-id="<?= htmlspecialchars($shiftId) ?>" data-editable="<?= ($isEditable || $isAddOnly) ? '1' : '0' ?>">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="fw-semibold"><?= htmlspecialchars($shift['TenCa'] ?? $shiftId) ?></div>
                                            <button type="button" class="btn btn-sm btn-outline-danger shift-remove" data-shift-remove="<?= htmlspecialchars($shiftId) ?>" <?= $isAddOnly ? 'disabled' : '' ?>>
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                        <div class="text-muted small mb-2">
                                            <?= htmlspecialchars($shift['ThoiGianBatDau'] ?? '-') ?>
                                            <?= !empty($shift['ThoiGianKetThuc']) ? '→ ' . htmlspecialchars($shift['ThoiGianKetThuc']) : '' ?>
                                            <span class="badge <?= $statusClass ?> ms-2">
                                                <?= $statusLabel ?>
                                            </span>
                                        </div>
                                        <label class="form-label fw-semibold">Nhân sự</label>
                                        <select name="assignments[<?= htmlspecialchars($shiftId) ?>][]" class="form-select assignment-select" multiple size="5" <?= ($isEditable || $isAddOnly) ? '' : 'disabled' ?>>
                                            <?php foreach ($availableEmployees as $employee): ?>
                                                <?php $employeeId = $employee['IdNhanVien'] ?? ''; ?>
                                                <?php
                                                    $isAssigned = in_array($employeeId, $assignedMap[$shiftId] ?? [], true);
                                                    $optionDisabled = $isAddOnly && $isAssigned ? 'disabled' : '';
                                                ?>
                                                <option value="<?= htmlspecialchars($employeeId) ?>" <?= $isAssigned ? 'selected' : '' ?> <?= $optionDisabled ?> data-existing="<?= $isAssigned ? '1' : '0' ?>">
                                                    <?= htmlspecialchars($employee['HoTen'] ?? $employeeId) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (!($isEditable || $isAddOnly)): ?>
                                            <div class="text-muted small mt-2">
                                                <?= $isInProgress ? 'Không thể chỉnh sửa trong khung giờ đang diễn ra.' : 'Ca đã khóa sau giờ chấm công.' ?>
                                            </div>
                                        <?php elseif ($isAddOnly): ?>
                                            <div class="text-muted small mt-2">Chỉ được thêm nhân sự cho ca hôm nay, không thể gỡ bỏ.</div>
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
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-people me-2"></i>Lưu phân công
                </button>
            </div>
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
    const assignmentCards = document.getElementById('assignment-cards');
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

    dateFilters.forEach(cb => {
        cb.checked = false;
        cb.addEventListener('change', applyFilters);
    });
    shiftFilters.forEach(cb => {
        cb.checked = false;
        cb.addEventListener('change', applyFilters);
    });
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

    const feedback = document.getElementById('assignment-feedback');
    const showFeedback = (message) => {
        if (!feedback) {
            return;
        }
        feedback.textContent = message;
        feedback.classList.remove('d-none');
        feedback.scrollIntoView({ behavior: 'smooth', block: 'start' });
    };

    if (bulkAssignBtn) {
        bulkAssignBtn.addEventListener('click', function() {
            const selectedEmployees = Array.from(document.querySelectorAll('.employee-checkbox:checked')).map(cb => cb.value);
            if (selectedEmployees.length === 0) {
                showFeedback('Vui lòng chọn ít nhất một nhân viên trước khi thêm vào ca.');
                return;
            }

            const activeCards = Array.from(shiftCards).filter(card => card.style.display !== 'none' && card.dataset.editable === '1');
            if (activeCards.length === 0) {
                showFeedback('Không có ca nào đủ điều kiện để phân công. Hãy kiểm tra lại bộ lọc hoặc chọn ngày hợp lệ.');
                return;
            }

            if (assignmentCards) {
                assignmentCards.classList.remove('d-none');
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

    document.querySelectorAll('.shift-remove').forEach((button) => {
        button.addEventListener('click', () => {
            const shiftId = button.getAttribute('data-shift-remove');
            const card = shiftId ? document.querySelector(`.shift-card[data-shift-id="${shiftId}"]`) : null;
            if (!card) {
                return;
            }
            const select = card.querySelector('select.assignment-select');
            if (select) {
                Array.from(select.options).forEach((option) => {
                    if (option.dataset.existing !== '1') {
                        option.selected = false;
                    }
                });
            }
            card.style.display = 'none';
        });
    });
});
</script>
