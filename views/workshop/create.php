<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Thêm xưởng sản xuất</h3>
        <p class="text-muted mb-0">Khai báo thông tin cơ bản, công suất và người phụ trách xưởng.</p>
    </div>
    <a href="?controller=workshop&action=index" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
</div>

<div class="card p-4">
    <?php
    $employeeGroups = $employeeGroups ?? ['warehouse' => [], 'production' => []];
    $employees = $employees ?? [];
    ?>
    <form action="?controller=workshop&action=store" method="post" class="row g-4">
        <div class="col-md-4">
            <label class="form-label">Mã xưởng</label>
            <input type="text" name="IdXuong" class="form-control" placeholder="Tự sinh nếu để trống">
        </div>
        <div class="col-md-4">
            <label class="form-label">Tên xưởng</label>
            <input type="text" name="TenXuong" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Ngày thành lập</label>
            <input type="date" name="NgayThanhLap" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Địa điểm</label>
            <input type="text" name="DiaDiem" class="form-control" placeholder="Khu công nghiệp, tỉnh/thành...">
        </div>
        <div class="col-md-6">
            <label class="form-label">Trưởng xưởng</label>
            <select name="XUONGTRUONG_IdNhanVien" class="form-select" required>
                <option value="">Chọn trưởng xưởng</option>
                <?php foreach ($employees as $employee): ?>
                    <option value="<?= htmlspecialchars($employee['IdNhanVien']) ?>">
                        <?= htmlspecialchars($employee['HoTen']) ?> (<?= htmlspecialchars($employee['IdNhanVien']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Công suất tối đa (giờ máy / tháng)</label>
            <input type="number" name="CongSuatToiDa" class="form-control" min="0" step="0.01">
        </div>
        <div class="col-md-4">
            <label class="form-label">Công suất đang sử dụng</label>
            <input type="number" name="CongSuatDangSuDung" class="form-control" min="0" step="0.01">
        </div>
        <div class="col-md-4">
            <label class="form-label">Nhân sự tối đa</label>
            <input type="number" name="SlNhanVien" class="form-control" min="0" placeholder="Ví dụ: 50">
        </div>
        <div class="col-md-4">
            <label class="form-label">Nhân sự hiện tại</label>
            <input type="number" name="SoLuongCongNhan" class="form-control" min="0" placeholder="Ví dụ: 42">
        </div>
        <div class="col-md-4">
            <label class="form-label">Trạng thái</label>
            <select name="TrangThai" class="form-select">
                <option value="Đang hoạt động">Đang hoạt động</option>
                <option value="Bảo trì">Bảo trì</option>
                <option value="Tạm dừng">Tạm dừng</option>
            </select>
        </div>
        <div class="col-12">
            <label class="form-label">Mô tả</label>
            <textarea name="MoTa" class="form-control" rows="4" placeholder="Ghi chú tình trạng thiết bị, hạng mục bảo trì..."></textarea>
        </div>
        <div class="col-12">
            <div class="card bg-light border-0">
                <div class="card-body p-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-6">
                            <label class="form-label mb-1">Nhân viên kho</label>
                            <div class="d-flex gap-2 align-items-center mb-2">
                                <input type="text" class="form-control form-control-sm assignment-search" data-target="#warehouse-list" placeholder="Tìm theo tên hoặc mã">
                            </div>
                            <div class="assignment-list border rounded p-3" id="warehouse-list">
                                <?php foreach ($employeeGroups['warehouse'] as $employee): ?>
                                    <?php $nameKey = mb_strtolower($employee['HoTen'] ?? '', 'UTF-8'); ?>
                                    <div class="form-check assignment-item" data-name="<?= htmlspecialchars($nameKey) ?>">
                                        <input class="form-check-input" type="checkbox" name="warehouse_staff[]" value="<?= htmlspecialchars($employee['IdNhanVien']) ?>">
                                        <label class="form-check-label">
                                            <?= htmlspecialchars($employee['HoTen']) ?> (<?= htmlspecialchars($employee['IdNhanVien']) ?>)
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1">Nhân viên sản xuất</label>
                            <div class="d-flex gap-2 align-items-center mb-2">
                                <input type="text" class="form-control form-control-sm assignment-search" data-target="#production-list" placeholder="Tìm theo tên hoặc mã">
                            </div>
                            <div class="assignment-list border rounded p-3" id="production-list">
                                <?php foreach ($employeeGroups['production'] as $employee): ?>
                                    <?php $nameKey = mb_strtolower($employee['HoTen'] ?? '', 'UTF-8'); ?>
                                    <div class="form-check assignment-item" data-name="<?= htmlspecialchars($nameKey) ?>">
                                        <input class="form-check-input" type="checkbox" name="production_staff[]" value="<?= htmlspecialchars($employee['IdNhanVien']) ?>">
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
            <button type="submit" class="btn btn-primary px-4">Lưu thông tin xưởng</button>
        </div>
    </form>
</div>

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
