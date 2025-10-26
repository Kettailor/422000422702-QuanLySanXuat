<?php
$attendance = $attendance ?? [];
$compensation = $compensation ?? [];
$period = $period ?? date('Y-m');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Nhập phụ cấp & thưởng</h3>
        <p class="text-muted mb-0">Kỳ lương: <strong><?= date('m/Y', strtotime($period . '-01')) ?></strong>. Vui lòng điền thông tin lương của từng nhân viên.</p>
    </div>
    <a href="?controller=salary&action=wizardAttendance" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<form method="post" action="?controller=salary&action=wizardCompensation">
    <div class="card p-4">
        <?php if (!$attendance): ?>
            <div class="alert alert-warning">Chưa có dữ liệu nhân viên để nhập lương.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>STT</th>
                        <th>Mã nhân viên</th>
                        <th>Tên nhân viên</th>
                        <th class="text-center">Lương cơ bản</th>
                        <th class="text-center">Đơn giá ngày công</th>
                        <th class="text-center">Số ngày công</th>
                        <th class="text-center">Phụ cấp</th>
                        <th class="text-center">Thưởng</th>
                        <th class="text-end">Số tiền lương</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($attendance as $index => $row):
                        $employeeId = $row['employee_id'];
                        $defaults = $compensation[$employeeId] ?? [];
                        $workingDays = $row['working_days'];
                        $baseSalary = $defaults['base_salary'] ?? 0;
                        $allowance = $defaults['allowance'] ?? 0;
                        $dailyRate = $defaults['daily_rate'] ?? 0;
                        $bonus = $defaults['bonus'] ?? 0;
                        $dayIncome = $dailyRate * $workingDays;
                        $total = $baseSalary + $allowance + $dayIncome + $bonus;
                        ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($employeeId) ?></td>
                            <td><?= htmlspecialchars($row['employee_name']) ?></td>
                            <td class="text-center">
                                <input type="number" min="0" step="0.01" class="form-control form-control-sm calc-input"
                                       name="LuongCoBan[<?= htmlspecialchars($employeeId) ?>]"
                                       value="<?= htmlspecialchars($baseSalary) ?>">
                            </td>
                            <td class="text-center">
                                <input type="number" min="0" step="0.01" class="form-control form-control-sm calc-input"
                                       name="DonGiaNgayCong[<?= htmlspecialchars($employeeId) ?>]"
                                       value="<?= htmlspecialchars($dailyRate) ?>">
                            </td>
                            <td class="text-center">
                                <input type="number" class="form-control form-control-sm" readonly
                                       value="<?= htmlspecialchars($workingDays) ?>">
                                <input type="hidden" name="SoNgayCong[<?= htmlspecialchars($employeeId) ?>]"
                                       value="<?= htmlspecialchars($workingDays) ?>">
                            </td>
                            <td class="text-center">
                                <input type="number" min="0" step="0.01" class="form-control form-control-sm calc-input"
                                       name="PhuCap[<?= htmlspecialchars($employeeId) ?>]"
                                       value="<?= htmlspecialchars($allowance) ?>">
                            </td>
                            <td class="text-center">
                                <input type="number" min="0" step="0.01" class="form-control form-control-sm calc-input"
                                       name="Thuong[<?= htmlspecialchars($employeeId) ?>]"
                                       value="<?= htmlspecialchars($bonus) ?>">
                            </td>
                            <td class="text-end fw-semibold">
                                <span class="salary-total" data-employee="<?= htmlspecialchars($employeeId) ?>">
                                    <?= number_format($total, 0, ',', '.') ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="alert alert-info mt-3">
                <div class="fw-semibold mb-1">Công thức tính</div>
                <div class="small mb-0">Số tiền lương = Lương cơ bản + (Đơn giá ngày công × Số ngày công) + Phụ cấp + Thưởng.</div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <a href="?controller=salary&action=wizardAttendance" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
                <button type="submit" class="btn btn-primary px-4">Đồng ý</button>
            </div>
        <?php endif; ?>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    if (!form) return;

    const formatCurrency = value => Number(value).toLocaleString('vi-VN');

    const recalc = () => {
        document.querySelectorAll('.salary-total').forEach(span => {
            const employee = span.dataset.employee;
            const base = parseFloat(form.querySelector(`[name="LuongCoBan[${employee}]"]`)?.value || 0);
            const allowance = parseFloat(form.querySelector(`[name="PhuCap[${employee}]"]`)?.value || 0);
            const dailyRate = parseFloat(form.querySelector(`[name="DonGiaNgayCong[${employee}]"]`)?.value || 0);
            const workingDays = parseFloat(form.querySelector(`[name="SoNgayCong[${employee}]"]`)?.value || 0);
            const bonus = parseFloat(form.querySelector(`[name="Thuong[${employee}]"]`)?.value || 0);
            const dayIncome = dailyRate * workingDays;
            const total = base + allowance + dayIncome + bonus;
            span.textContent = formatCurrency(total);
        });
    };

    form.querySelectorAll('.calc-input').forEach(input => input.addEventListener('input', recalc));
    recalc();
});
</script>
