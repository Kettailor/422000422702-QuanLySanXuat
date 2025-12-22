<?php
$shift = $shift ?? null;
$shiftId = $shiftId ?? null;
$workDate = $workDate ?? null;
$shifts = $shifts ?? [];
$employees = $employees ?? [];
$entries = $entries ?? [];
$defaultCheckIn = $defaultCheckIn ?? '';
$defaultCheckOut = $defaultCheckOut ?? '';

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
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1">Ghi nhận chấm công</h2>
        <p class="text-muted mb-0">
            Hệ thống tự động lấy dữ liệu ngày <span class="fw-semibold"><?= htmlspecialchars($formattedWorkDate) ?></span> và tổng hợp chấm công trong ngày.
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

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">Thông tin chấm công</h5>
                        <div class="text-muted small">Cập nhật nhanh trạng thái vào/ra ca cho nhân sự.</div>
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
                                <?= htmlspecialchars($shift['LoaiCa'] ?? '') ?> • <?= htmlspecialchars($formatDate($shift['ThoiGianBatDau'] ?? null, 'H:i')) ?>
                                <?= !empty($shift['ThoiGianKetThuc']) ? '→ ' . htmlspecialchars($formatDate($shift['ThoiGianKetThuc'] ?? null, 'H:i')) : '' ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nhân viên</label>
                        <select name="employee_id[]" class="form-select" multiple size="8" required>
                            <?php foreach ($employees as $employee): ?>
                                <?php $id = $employee['IdNhanVien'] ?? ''; ?>
                                <option value="<?= htmlspecialchars($id) ?>">
                                    <?= htmlspecialchars($employee['HoTen'] ?? $id) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="text-muted small mt-2">Giữ Ctrl (Windows) hoặc Cmd (Mac) để chọn nhiều nhân viên.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Giờ vào</label>
                        <input type="datetime-local" name="check_in" class="form-control" value="<?= htmlspecialchars($defaultCheckIn) ?>" required>
                    </div>

                    <div class="col-md-6">
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
                <h6 class="mb-0">Chấm công trong ngày</h6>
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
