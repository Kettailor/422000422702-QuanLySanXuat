<?php
$attendance = $attendance ?? [];
$period = $period ?? date('Y-m');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Tổng hợp chấm công</h3>
        <p class="text-muted mb-0">Kỳ lương: <strong><?= date('m/Y', strtotime($period . '-01')) ?></strong>. Kiểm tra tổng giờ làm và số ngày công của từng nhân viên.</p>
    </div>
    <a href="?controller=salary&action=create" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Chọn kỳ khác</a>
</div>

<div class="card p-4">
    <?php if (!$attendance): ?>
        <div class="alert alert-warning">Chưa có dữ liệu chấm công cho kỳ này.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã nhân viên</th>
                    <th>Tên nhân viên</th>
                    <th class="text-center">Tổng giờ</th>
                    <th class="text-center">Số ngày công</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($attendance as $index => $row): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($row['employee_id']) ?></td>
                        <td><?= htmlspecialchars($row['employee_name']) ?></td>
                        <td class="text-center fw-semibold"><?= number_format($row['total_hours'], 2) ?></td>
                        <td class="text-center fw-semibold text-primary"><?= number_format($row['working_days'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="alert alert-info mt-3">
            <div class="fw-semibold mb-1">Công thức tính</div>
            <div class="small mb-0">Giờ làm = Thời gian ra - Thời gian vào. Số ngày công = Tổng giờ làm / 8.</div>
        </div>
        <form method="post" action="?controller=salary&action=wizardAttendance" class="text-end mt-3">
            <button type="submit" class="btn btn-primary px-4">Lấy dữ liệu chấm công</button>
        </form>
    <?php endif; ?>
</div>
