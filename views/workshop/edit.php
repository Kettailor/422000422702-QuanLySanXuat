<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Cập nhật thông tin xưởng</h3>
        <p class="text-muted mb-0">Điều chỉnh lại thông tin, công suất vận hành và trạng thái hoạt động.</p>
    </div>
    <a href="?controller=workshop&action=index" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
</div>

<?php if (!$workshop): ?>
    <div class="alert alert-warning">Không tìm thấy thông tin xưởng.</div>
<?php else: ?>
    <?php
    $employeeGroups = $employeeGroups ?? ['warehouse' => [], 'production' => []];
    $selectedWarehouse = $selectedWarehouse ?? [];
    $selectedProduction = $selectedProduction ?? [];
    $selectedManager = $selectedManager ?? ($workshop['XUONGTRUONG_IdNhanVien'] ?? '');
    $canAssign = $canAssign ?? false;
    $employees = $employees ?? [];
    ?>
    <div class="card p-4">
        <form action="?controller=workshop&action=update" method="post" class="row g-4">
            <input type="hidden" name="IdXuong" value="<?= htmlspecialchars($workshop['IdXuong']) ?>">
            <div class="col-md-4">
                <label class="form-label">Tên xưởng</label>
                <input type="text" name="TenXuong" class="form-control" value="<?= htmlspecialchars($workshop['TenXuong']) ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Ngày thành lập</label>
                <input type="date" name="NgayThanhLap" class="form-control" value="<?= htmlspecialchars($workshop['NgayThanhLap'] ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Trưởng xưởng</label>
                <select name="XUONGTRUONG_IdNhanVien" class="form-select" <?= $canAssign ? '' : 'disabled' ?>>
                    <option value="">Chọn trưởng xưởng</option>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?= htmlspecialchars($employee['IdNhanVien']) ?>" <?= $selectedManager === $employee['IdNhanVien'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($employee['HoTen']) ?> (<?= htmlspecialchars($employee['IdNhanVien']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Địa điểm</label>
                <input type="text" name="DiaDiem" class="form-control" value="<?= htmlspecialchars($workshop['DiaDiem'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Công suất tối đa</label>
                <input type="number" name="CongSuatToiDa" class="form-control" min="0" step="0.01" value="<?= htmlspecialchars($workshop['CongSuatToiDa'] ?? 0) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Công suất đang sử dụng</label>
                <input type="number" name="CongSuatDangSuDung" class="form-control" min="0" step="0.01" value="<?= htmlspecialchars($workshop['CongSuatDangSuDung'] ?? $workshop['CongSuatHienTai'] ?? 0) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Nhân sự tối đa</label>
                <input type="number" name="SlNhanVien" class="form-control" min="0" value="<?= htmlspecialchars($workshop['SlNhanVien'] ?? 0) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Nhân sự hiện tại</label>
                <input type="number" name="SoLuongCongNhan" class="form-control" min="0" value="<?= htmlspecialchars($workshop['SoLuongCongNhan'] ?? 0) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Trạng thái</label>
                <select name="TrangThai" class="form-select">
                    <?php foreach (['Đang hoạt động', 'Bảo trì', 'Tạm dừng'] as $status): ?>
                        <option value="<?= $status ?>" <?= ($workshop['TrangThai'] ?? '') === $status ? 'selected' : '' ?>><?= $status ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Mô tả</label>
                <textarea name="MoTa" class="form-control" rows="4"><?= htmlspecialchars($workshop['MoTa'] ?? '') ?></textarea>
            </div>
            <div class="col-12">
                <div class="card bg-light border-0">
                    <div class="card-body p-3">
                        <?php if (!$canAssign): ?>
                            <div class="alert alert-info py-2 mb-3">
                                Chỉ quản trị hệ thống hoặc ban giám đốc được phép thay đổi phân công nhân sự. Bạn đang xem ở chế độ chỉ đọc.
                            </div>
                        <?php endif; ?>
                        <div class="row g-3 align-items-start">
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label mb-0">Nhân viên kho</label>
                                    <input type="text" class="form-control form-control-sm assignment-search" data-target="#warehouse-list" placeholder="Tìm theo tên hoặc mã">
                                </div>
                                <div class="assignment-list border rounded p-3" id="warehouse-list">
                                    <?php foreach ($employeeGroups['warehouse'] as $employee): ?>
                                        <?php $nameKey = mb_strtolower($employee['HoTen'] ?? '', 'UTF-8'); ?>
                                        <div class="form-check assignment-item" data-name="<?= htmlspecialchars($nameKey) ?>">
                                            <input class="form-check-input" type="checkbox" name="warehouse_staff[]" value="<?= htmlspecialchars($employee['IdNhanVien']) ?>" <?= in_array($employee['IdNhanVien'], $selectedWarehouse, true) ? 'checked' : '' ?> <?= $canAssign ? '' : 'disabled' ?>>
                                            <label class="form-check-label">
                                                <?= htmlspecialchars($employee['HoTen']) ?> (<?= htmlspecialchars($employee['IdNhanVien']) ?>)
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label mb-0">Nhân viên sản xuất</label>
                                    <input type="text" class="form-control form-control-sm assignment-search" data-target="#production-list" placeholder="Tìm theo tên hoặc mã">
                                </div>
                                <div class="assignment-list border rounded p-3" id="production-list">
                                    <?php foreach ($employeeGroups['production'] as $employee): ?>
                                        <?php $nameKey = mb_strtolower($employee['HoTen'] ?? '', 'UTF-8'); ?>
                                        <div class="form-check assignment-item" data-name="<?= htmlspecialchars($nameKey) ?>">
                                            <input class="form-check-input" type="checkbox" name="production_staff[]" value="<?= htmlspecialchars($employee['IdNhanVien']) ?>" <?= in_array($employee['IdNhanVien'], $selectedProduction, true) ? 'checked' : '' ?> <?= $canAssign ? '' : 'disabled' ?>>
                                            <label class="form-check-label">
                                                <?= htmlspecialchars($employee['HoTen']) ?> (<?= htmlspecialchars($employee['IdNhanVien']) ?>)
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary px-4">Cập nhật</button>
            </div>
        </form>
    </div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.assignment-search').forEach((input) => {
        const targetSelector = input.getAttribute('data-target');
        const target = document.querySelector(targetSelector);
        if (!target) return;

        input.addEventListener('input', () => {
            const keyword = input.value.toLowerCase();
            target.querySelectorAll('.assignment-item').forEach((item) => {
                const name = item.getAttribute('data-name') || '';
                if (!keyword || name.includes(keyword)) {
                    item.classList.remove('d-none');
                } else {
                    item.classList.add('d-none');
                }
            });
        });
    });
});
</script>
