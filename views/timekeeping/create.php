<?php
$shift = $shift ?? null;
$shiftId = $shiftId ?? null;
$workDate = $workDate ?? null;
$shifts = $shifts ?? [];
$employees = $employees ?? [];
$entries = $entries ?? [];
$defaultCheckIn = $defaultCheckIn ?? '';
$defaultCheckOut = $defaultCheckOut ?? '';
$employeeShiftMap = $employeeShiftMap ?? [];

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

$formattedWorkDate = $workDate ? date('d/m/Y', strtotime($workDate)) : 'Hôm nay';
$dayStart = $workDate ? ($workDate . 'T00:00') : '';
$dayEnd = $workDate ? ($workDate . 'T23:59') : '';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1">Phân công &amp; chấm công</h2>
        <p class="text-muted mb-0">
            Hệ thống tự động lấy dữ liệu ngày <span class="fw-semibold"><?= htmlspecialchars($formattedWorkDate) ?></span> và tổng hợp phân công, chấm công trong ngày.
        </p>
    </div>
    <div class="d-flex gap-2">
        <span class="badge rounded-pill text-bg-primary px-3 py-2">
            <i class="bi bi-calendar-event me-1"></i><?= htmlspecialchars($formattedWorkDate) ?>
        </span>
        <a href="?controller=timekeeping&action=index" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const employeeCheckboxes = document.querySelectorAll('input[name="employee_id[]"]');
    const shiftSelect = document.getElementById('shift-select');
    const checkInInput = document.getElementById('check-in-input');
    const checkOutInput = document.getElementById('check-out-input');
    const noteInput = document.getElementById('note-input');
    const summary = document.getElementById('employee-selected-summary');
    const shiftHint = document.getElementById('shift-hint');
    const employeeShiftMap = <?= json_encode($employeeShiftMap, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;

    const updateShiftOptions = () => {
        if (!shiftSelect) {
            return;
        }
        const selectedEmployees = Array.from(employeeCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
        if (selectedEmployees.length === 0) {
            Array.from(shiftSelect.options).forEach(option => {
                option.hidden = false;
                option.disabled = false;
            });
            shiftSelect.value = '';
            return;
        }

        const allowedSets = selectedEmployees.map(employeeId => new Set(employeeShiftMap[employeeId] || []));
        let allowed = allowedSets[0];
        for (let i = 1; i < allowedSets.length; i += 1) {
            allowed = new Set([...allowed].filter(value => allowedSets[i].has(value)));
        }

        let hasAllowed = false;
        Array.from(shiftSelect.options).forEach(option => {
            if (option.value === '') {
                option.hidden = false;
                option.disabled = false;
                return;
            }
            const isAllowed = allowed.has(option.value);
            option.hidden = !isAllowed;
            option.disabled = !isAllowed;
            if (isAllowed) {
                hasAllowed = true;
            }
        });

        if (!hasAllowed) {
            shiftSelect.value = '';
        }
    };

    const updateState = () => {
        const selected = Array.from(employeeCheckboxes).filter(cb => cb.checked);
        const hasSelection = selected.length > 0;
        if (summary) {
            summary.textContent = hasSelection
                ? `Đã chọn ${selected.length} nhân viên.`
                : 'Chưa chọn nhân viên.';
        }
        if (shiftSelect) {
            shiftSelect.disabled = !hasSelection;
        }
        updateShiftOptions();
        if (checkInInput) {
            checkInInput.disabled = !hasSelection;
        }
        if (checkOutInput) {
            checkOutInput.disabled = !hasSelection;
        }
        if (noteInput) {
            noteInput.disabled = !hasSelection;
        }
        if (shiftHint) {
            shiftHint.classList.toggle('d-none', hasSelection);
        }
    };

    employeeCheckboxes.forEach(cb => cb.addEventListener('change', updateState));
    updateState();
});
</script>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">Phân công / chấm công</h5>
                        <div class="text-muted small">Chọn ca, phân công nhân sự và chấm công theo ca.</div>
                    </div>
                    <div class="text-end">
                        <div class="text-muted small">Tổng ca hôm nay</div>
                        <div class="fw-semibold"><?= count($shifts) ?> ca</div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="post" action="?controller=timekeeping&action=store" class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nhân viên</label>
                        <div class="border rounded-3 p-2 bg-light" style="max-height: 320px; overflow: auto;">
                            <div class="row g-2">
                                <?php foreach ($employees as $employee): ?>
                                    <?php $id = $employee['IdNhanVien'] ?? ''; ?>
                                    <?php $name = $employee['HoTen'] ?? $id; ?>
                                    <div class="col-12 col-lg-6">
                                        <label class="card h-100 border-0 shadow-sm">
                                            <div class="card-body py-2">
                                                <div class="form-check mb-0">
                                                    <input class="form-check-input" type="checkbox" name="employee_id[]"
                                                           value="<?= htmlspecialchars($id) ?>" id="employee-<?= htmlspecialchars($id) ?>">
                                                    <span class="fw-semibold"><?= htmlspecialchars($name) ?></span>
                                                </div>
                                                <div class="text-muted small">Mã NV: <?= htmlspecialchars($id) ?></div>
                                            </div>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="text-muted small mt-2">Chọn nhiều nhân viên để phân công và ghi nhận chấm công cùng lúc.</div>
                        <div class="text-muted small mt-1" id="employee-selected-summary">Chưa chọn nhân viên.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Ca làm việc</label>
                        <select name="shift_id" class="form-select" required id="shift-select" disabled>
                            <option value="">Chọn ca làm việc</option>
                            <?php foreach ($shifts as $item): ?>
                                <?php $id = $item['IdCaLamViec'] ?? ''; ?>
                                <option value="<?= htmlspecialchars($id) ?>" <?= $id === $shiftId ? 'selected' : '' ?>>
                                    <?= htmlspecialchars(($item['TenCa'] ?? $id) . ' • ' . ($item['NgayLamViec'] ?? '')) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="text-muted small mt-2" id="shift-hint">Chọn nhân viên trước để hiển thị ca.</div>
                        <?php if ($shift): ?>
                            <div class="text-muted small mt-2">
                                <?= htmlspecialchars($shift['LoaiCa'] ?? '') ?> • <?= htmlspecialchars($formatDate($shift['ThoiGianBatDau'] ?? null, 'H:i')) ?>
                                <?= !empty($shift['ThoiGianKetThuc']) ? '→ ' . htmlspecialchars($formatDate($shift['ThoiGianKetThuc'] ?? null, 'H:i')) : '' ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Giờ vào</label>
                        <input type="datetime-local" name="check_in" class="form-control"
                               value="<?= htmlspecialchars($defaultCheckIn) ?>" min="<?= htmlspecialchars($dayStart) ?>"
                               max="<?= htmlspecialchars($dayEnd) ?>" required disabled id="check-in-input">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Giờ ra (tuỳ chọn)</label>
                        <input type="datetime-local" name="check_out" class="form-control"
                               value="<?= htmlspecialchars($defaultCheckOut) ?>" min="<?= htmlspecialchars($dayStart) ?>"
                               max="<?= htmlspecialchars($dayEnd) ?>" disabled id="check-out-input">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Ghi chú</label>
                        <textarea name="note" rows="3" class="form-control" placeholder="Ví dụ: Tăng ca, hỗ trợ kho, ..." id="note-input" disabled></textarea>
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check2-circle me-2"></i>Lưu chấm công
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Số lượt chấm công hôm nay</div>
                        <div class="fs-4 fw-semibold"><?= count($entries) ?></div>
                    </div>
                    <div class="text-end">
                        <div class="text-muted small">Nhân sự đang chờ ra ca</div>
                        <div class="fw-semibold"><?= count(array_filter($entries, static fn($entry) => empty($entry['ThoiGIanRa']))) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">Phân công / chấm công trong ngày</h6>
            </div>
            <div class="card-body p-0">
                <?php if (empty($entries)): ?>
                    <div class="p-4">
                        <div class="alert alert-light border mb-0">Chưa có dữ liệu chấm công hôm nay.</div>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nhân sự</th>
                                    <th>Ca</th>
                                    <th>Giờ vào</th>
                                    <th>Giờ ra</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($entries as $entry): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-semibold"><?= htmlspecialchars($entry['TenNhanVien'] ?? $entry['NHANVIEN IdNhanVien'] ?? '-') ?></div>
                                            <div class="text-muted small"><?= htmlspecialchars($entry['TenXuong'] ?? $entry['IdXuong'] ?? '-') ?></div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?= htmlspecialchars($entry['TenCa'] ?? $entry['IdCaLamViec'] ?? '-') ?></div>
                                            <div class="text-muted small"><?= htmlspecialchars($entry['NgayLamViec'] ?? '-') ?></div>
                                        </td>
                                        <td><?= $formatDate($entry['ThoiGianVao'] ?? null, 'H:i') ?></td>
                                        <td><?= $formatDate($entry['ThoiGIanRa'] ?? null, 'H:i') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
