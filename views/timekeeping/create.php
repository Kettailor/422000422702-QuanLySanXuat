<?php
$plan = $plan ?? null;
$planId = $planId ?? null;
$employees = $employees ?? [];
$defaultCheckIn = $defaultCheckIn ?? '';
$defaultCheckOut = $defaultCheckOut ?? '';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Ghi nhận chấm công</h2>
        <?php if ($plan): ?>
            <p class="text-muted mb-0">Liên kết kế hoạch xưởng <?= htmlspecialchars($plan['IdKeHoachSanXuatXuong'] ?? '') ?> - <?= htmlspecialchars($plan['TenThanhThanhPhanSP'] ?? '') ?>.</p>
        <?php else: ?>
            <p class="text-muted mb-0">Thêm bản ghi chấm công mới cho nhân sự sản xuất.</p>
        <?php endif; ?>
    </div>
    <a href="<?= $plan ? '?controller=factory_plan&action=read&id=' . urlencode($planId) : '?controller=factory_plan&action=index' ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Quay lại
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="post" action="?controller=timekeeping&action=store" class="row g-4">
            <input type="hidden" name="plan_id" value="<?= htmlspecialchars((string) $planId) ?>">

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
