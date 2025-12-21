<?php
$shift = $shift ?? null;
$shiftId = $shiftId ?? null;
$workDate = $workDate ?? null;
$workshopPlanId = $workshopPlanId ?? null;
$shifts = $shifts ?? [];
$employees = $employees ?? [];
$defaultCheckIn = $defaultCheckIn ?? '';
$defaultCheckOut = $defaultCheckOut ?? '';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Ghi nhận chấm công</h2>
        <p class="text-muted mb-0">Chọn ca làm việc đã được phân công để ghi nhận chấm công.</p>
    </div>
    <a href="?controller=timekeeping&action=index" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Quay lại
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="post" action="?controller=timekeeping&action=store" class="row g-4">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Ca làm việc</label>
                <select name="shift_id" class="form-select" required>
                    <option value="">Chọn ca làm việc</option>
                    <?php foreach ($shifts as $item): ?>
                        <?php $id = $item['IdCaLamViec'] ?? ''; ?>
                        <option value="<?= htmlspecialchars($id) ?>" <?= $id === $shiftId ? 'selected' : '' ?>>
                            <?= htmlspecialchars(($item['TenCa'] ?? $id) . ' • ' . ($item['NgayLamViec'] ?? '')) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if ($shift): ?>
                    <div class="text-muted small mt-2">
                        <?= htmlspecialchars($shift['LoaiCa'] ?? '') ?> • <?= htmlspecialchars($shift['ThoiGianBatDau'] ?? '-') ?>
                        <?= !empty($shift['ThoiGianKetThuc']) ? '→ ' . htmlspecialchars($shift['ThoiGianKetThuc']) : '' ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Nhân viên</label>
                <select name="employee_id" class="form-select" required>
                    <option value="">Chọn nhân viên</option>
                    <?php foreach ($employees as $employee): ?>
                        <?php $id = $employee['IdNhanVien'] ?? ''; ?>
                        <option value="<?= htmlspecialchars($id) ?>">
                            <?= htmlspecialchars($employee['HoTen'] ?? $id) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Giờ vào</label>
                <input type="datetime-local" name="check_in" class="form-control" value="<?= htmlspecialchars($defaultCheckIn) ?>" required>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Giờ ra (tuỳ chọn)</label>
                <input type="datetime-local" name="check_out" class="form-control" value="<?= htmlspecialchars($defaultCheckOut) ?>">
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Ghi chú</label>
                <textarea name="note" rows="3" class="form-control" placeholder="Ví dụ: Tăng ca, hỗ trợ kho, ..."></textarea>
            </div>

            <div class="col-12 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check2-circle me-2"></i>Lưu chấm công
                </button>
            </div>
        </form>
    </div>
</div>
