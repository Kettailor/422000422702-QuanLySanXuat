<?php
$entries = $entries ?? [];
$workDate = $workDate ?? null;
$shifts = $shifts ?? [];
$workshopId = $workshopId ?? null;
$planId = $planId ?? null;
$workshops = $workshops ?? [];
$plans = $plans ?? [];
$employeeFilter = $employeeFilter ?? null;

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
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1">Nhật ký chấm công</h2>
        <p class="text-muted mb-0">Tổng hợp đầy đủ chấm công, có thể lọc theo ngày, xưởng và kế hoạch.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="?controller=timekeeping&action=create" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Ghi nhận chấm công
        </a>
        <a href="?controller=factory_plan&action=index" class="btn btn-outline-secondary">
            <i class="bi bi-list me-2"></i>Kế hoạch xưởng
        </a>
    </div>
</div>

<?php if ($employeeFilter): ?>
    <div class="alert alert-info d-flex align-items-center" role="alert">
        <i class="bi bi-person-check me-2"></i>
        <div>
            Đang hiển thị chấm công của <strong><?= htmlspecialchars($employeeFilter['HoTen'] ?? '') ?></strong>
            (<?= htmlspecialchars($employeeFilter['IdNhanVien'] ?? '') ?>).
            <a href="?controller=timekeeping&action=index" class="alert-link ms-2">Xóa bộ lọc</a>
        </div>
    </div>
<?php endif; ?>

<form method="get" class="card border-0 shadow-sm mb-4">
    <input type="hidden" name="controller" value="timekeeping">
    <input type="hidden" name="action" value="index">
    <?php if ($employeeFilter): ?>
        <input type="hidden" name="employee_id" value="<?= htmlspecialchars($employeeFilter['IdNhanVien'] ?? '') ?>">
    <?php endif; ?>
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Ngày làm việc</label>
                <input type="date" name="work_date" class="form-control" value="<?= htmlspecialchars((string) $workDate) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Xưởng</label>
                <select name="workshop_id" class="form-select">
                    <option value="">Tất cả xưởng</option>
                    <?php foreach ($workshops as $workshop): ?>
                        <?php $id = $workshop['IdXuong'] ?? ''; ?>
                        <option value="<?= htmlspecialchars($id) ?>" <?= $id === $workshopId ? 'selected' : '' ?>>
                            <?= htmlspecialchars($workshop['TenXuong'] ?? $id) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Kế hoạch xưởng</label>
                <select name="plan_id" class="form-select">
                    <option value="">Tất cả kế hoạch</option>
                    <?php foreach ($plans as $plan): ?>
                        <?php $id = $plan['IdKeHoachSanXuatXuong'] ?? ''; ?>
                        <option value="<?= htmlspecialchars($id) ?>" <?= $id === $planId ? 'selected' : '' ?>>
                            <?= htmlspecialchars(($plan['TenThanhThanhPhanSP'] ?? $id) . ' • ' . ($plan['TenXuong'] ?? '')) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="?controller=timekeeping&action=index" class="btn btn-outline-secondary">Xóa lọc</a>
            <button type="submit" class="btn btn-primary">Áp dụng</button>
        </div>
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0">
        <h5 class="mb-0">Danh sách chấm công</h5>
    </div>
    <div class="card-body">
        <?php if (empty($entries)): ?>
            <div class="alert alert-light border mb-0">Chưa có bản ghi chấm công.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nhân sự</th>
                            <th>Xưởng</th>
                            <th>Kế hoạch</th>
                            <th>Ca làm việc</th>
                            <th>Giờ vào</th>
                            <th>Giờ ra</th>
                            <th>Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($entries as $entry): ?>
                            <tr>
                                <td><?= htmlspecialchars($entry['TenNhanVien'] ?? $entry['NHANVIEN IdNhanVien'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($entry['TenXuong'] ?? $entry['IdXuong'] ?? '-') ?></td>
                                <td>
                                    <div class="fw-semibold"><?= htmlspecialchars($entry['TenThanhThanhPhanSP'] ?? $entry['IdKeHoachSanXuatXuong'] ?? '-') ?></div>
                                    <?php if (!empty($entry['IdKeHoachSanXuat'])): ?>
                                        <div class="text-muted small">KH tổng: <?= htmlspecialchars($entry['IdKeHoachSanXuat']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-semibold"><?= htmlspecialchars($entry['TenCa'] ?? $entry['IdCaLamViec'] ?? '-') ?></div>
                                    <div class="text-muted small">
                                        <?= htmlspecialchars($entry['NgayLamViec'] ?? '-') ?>
                                        <?php if (!empty($entry['LoaiCa'])): ?>
                                            • <?= htmlspecialchars($entry['LoaiCa']) ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td><?= $formatDate($entry['ThoiGianVao'] ?? null) ?></td>
                                <td><?= $formatDate($entry['ThoiGIanRa'] ?? null) ?></td>
                                <td class="text-muted small"><?= htmlspecialchars($entry['GhiChu'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
