<?php
$compensation = $compensation ?? [];
$insurance = $insurance ?? [];
$period = $period ?? date('Y-m');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Tính bảo hiểm & thuế</h3>
        <p class="text-muted mb-0">Kỳ lương: <strong><?= date('m/Y', strtotime($period . '-01')) ?></strong>. Hệ thống tính tự động các khoản trích theo lương.</p>
    </div>
    <a href="?controller=salary&action=wizardCompensation" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<form method="post" action="?controller=salary&action=wizardInsurance">
    <div class="card p-4">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã nhân viên</th>
                    <th>Tên nhân viên</th>
                    <th class="text-end">BHYT (1.5%)</th>
                    <th class="text-end">BHXH (8%)</th>
                    <th class="text-end">BHTN (1%)</th>
                    <th class="text-end">Thuế TNCN (10%)</th>
                    <th class="text-end">Tổng bảo hiểm</th>
                </tr>
                </thead>
                <tbody>
                <?php $index = 1; foreach ($compensation as $id => $row):
                    $ins = $insurance[$id] ?? ['bhyt' => 0, 'bhxh' => 0, 'bhtn' => 0, 'tax' => 0, 'total' => 0];
                    ?>
                    <tr>
                        <td><?= $index++ ?></td>
                        <td><?= htmlspecialchars($id) ?></td>
                        <td><?= htmlspecialchars($row['employee_name']) ?></td>
                        <td class="text-end"><?= number_format($ins['bhyt'], 0, ',', '.') ?></td>
                        <td class="text-end"><?= number_format($ins['bhxh'], 0, ',', '.') ?></td>
                        <td class="text-end"><?= number_format($ins['bhtn'], 0, ',', '.') ?></td>
                        <td class="text-end text-warning fw-semibold"><?= number_format($ins['tax'], 0, ',', '.') ?></td>
                        <td class="text-end fw-semibold"><?= number_format($ins['total'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="alert alert-info mt-3">
            <div class="fw-semibold mb-1">Công thức tính</div>
            <ul class="small mb-0 ps-3">
                <li>BHYT = Lương cơ bản × 1.5%</li>
                <li>BHXH = Lương cơ bản × 8%</li>
                <li>BHTN = Lương cơ bản × 1%</li>
                <li>Tổng bảo hiểm = BHYT + BHXH + BHTN</li>
                <li>Thuế TNCN mặc định = Tổng thu nhập × 10%</li>
            </ul>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-3">
            <a href="?controller=salary&action=wizardCompensation" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
            <button type="submit" class="btn btn-primary px-4">Đồng ý</button>
        </div>
    </div>
</form>
