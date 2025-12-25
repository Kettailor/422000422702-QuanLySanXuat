<?php
$rows = $rows ?? [];
$summary = $summary ?? ['employees' => 0, 'total_net' => 0];
$period = $period ?? date('Y-m');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Tổng hợp tính lương</h3>
        <p class="text-muted mb-0">Kỳ lương: <strong><?= date('m/Y', strtotime($period . '-01')) ?></strong>. Kiểm tra kết quả cuối cùng trước khi lưu bảng lương.</p>
    </div>
    <a href="?controller=salary&action=wizardInsurance" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<form method="post" action="?controller=salary&action=wizardFinalize">
    <div class="card p-4">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>Mã NV</th>
                    <th>Tên nhân viên</th>
                    <th class="text-center">Số ngày công</th>
                    <th class="text-end">Lương cơ bản</th>
                    <th class="text-end">Tổng lương theo ngày công</th>
                    <th class="text-end">Phụ cấp</th>
                    <th class="text-end">Thưởng</th>
                    <th class="text-end">Bảo hiểm</th>
                    <th class="text-end">Thuế TNCN</th>
                    <th class="text-end">Tổng lương thực nhận</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['employee_id']) ?></td>
                        <td><?= htmlspecialchars($row['employee_name']) ?></td>
                        <td class="text-center fw-semibold"><?= number_format($row['working_days'], 2) ?></td>
                        <td class="text-end"><?= number_format($row['base_salary'], 0, ',', '.') ?></td>
                        <td class="text-end"><?= number_format($row['day_income'], 0, ',', '.') ?></td>
                        <td class="text-end"><?= number_format($row['allowance'], 0, ',', '.') ?></td>
                        <td class="text-end"><?= number_format($row['bonus'], 0, ',', '.') ?></td>
                        <td class="text-end text-danger">-<?= number_format($row['insurance'], 0, ',', '.') ?></td>
                        <td class="text-end text-danger">-<?= number_format($row['tax'], 0, ',', '.') ?></td>
                        <td class="text-end fw-semibold text-primary"><?= number_format($row['net_income'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="row g-3 mt-3">
            <div class="col-md-4">
                <div class="border rounded p-3 h-100 bg-light">
                    <div class="text-muted text-uppercase small">Tổng số nhân viên</div>
                    <div class="fs-4 fw-bold"><?= number_format($summary['employees']) ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-3 h-100 bg-light">
                    <div class="text-muted text-uppercase small">Tổng lương thực nhận</div>
                    <div class="fs-4 fw-bold text-success"><?= number_format($summary['total_net'], 0, ',', '.') ?> đ</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-3 h-100 bg-light">
                    <div class="text-muted text-uppercase small">Trạng thái bảng lương</div>
                    <div class="fs-5 fw-semibold text-primary">Chờ duyệt</div>
                    <div class="small text-muted">Bảng lương sẽ tự động tạo ở trạng thái chờ duyệt sau khi hoàn tất.</div>
                </div>
            </div>
        </div>
        <div class="text-end mt-4">
            <button type="submit" class="btn btn-success px-4"><i class="bi bi-check2-circle me-2"></i>Hoàn tất tạo bảng lương</button>
        </div>
    </div>
</form>
