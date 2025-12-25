<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Thêm xưởng sản xuất</h3>
        <p class="text-muted mb-0">Khai báo thông tin cơ bản, công suất và nhân sự hỗ trợ.</p>
    </div>
    <a href="?controller=workshop&action=index" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
</div>

<div class="card p-4">
    <?php
    $employeeGroups = $employeeGroups ?? ['warehouse' => [], 'production' => []];
    $selectedWarehouse = $selectedWarehouse ?? [];
    $selectedProduction = $selectedProduction ?? [];
    $warehouseSelectedCount = count($selectedWarehouse);
    $productionSelectedCount = count($selectedProduction);
    $workshopType = $workshopType ?? 'Xưởng sản xuất';
    $workshopTypes = $workshopTypes ?? [];
    $workshopTypeRules = $workshopTypeRules ?? [];
    $managerCandidatesByType = $managerCandidatesByType ?? [];
    ?>
    <form action="?controller=workshop&action=store" method="post" class="row g-4">
        <div class="col-md-4">
            <label class="form-label">Tên xưởng</label>
            <input type="text" name="TenXuong" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Xưởng trưởng <span class="text-danger">*</span></label>
            <select name="XUONGTRUONG_IdNhanVien" class="form-select" data-manager-select required>
                <option value="" disabled selected>Chọn xưởng trưởng</option>
                <?php foreach (($managerCandidates ?? []) as $manager): ?>
                    <option value="<?= htmlspecialchars($manager['IdNhanVien'] ?? '') ?>">
                        <?= htmlspecialchars($manager['HoTen'] ?? '') ?> (<?= htmlspecialchars($manager['IdNhanVien'] ?? '') ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Loại xưởng</label>
            <select name="LoaiXuong" class="form-select" data-workshop-type required>
                <?php foreach ($workshopTypes as $type): ?>
                    <option value="<?= htmlspecialchars($type) ?>" <?= $type === $workshopType ? 'selected' : '' ?>>
                        <?= htmlspecialchars($type) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Địa điểm</label>
            <input type="text" name="DiaDiem" class="form-control" placeholder="Khu công nghiệp, tỉnh/thành...">
        </div>
        <div class="col-md-4">
            <label class="form-label">Công suất tối đa (giờ máy / tháng)</label>
            <input type="number" name="CongSuatToiDa" class="form-control" min="0" step="0.01">
        </div>
        <div class="col-md-4">
            <label class="form-label">Nhân sự tối đa</label>
            <input type="number" name="SlNhanVien" class="form-control" min="0" placeholder="Ví dụ: 50">
        </div>
        <div class="col-md-4">
            <label class="form-label">Nhân sự hiện tại</label>
            <div class="form-control bg-light">0</div>
        </div>
        <div class="col-md-4">
            <label class="form-label">Trạng thái</label>
            <select name="TrangThai" class="form-select">
                <option value="Đang hoạt động">Đang hoạt động</option>
                <option value="Bảo trì">Bảo trì</option>
            </select>
        </div>
        <div class="col-12">
            <label class="form-label">Mô tả</label>
            <textarea name="MoTa" class="form-control" rows="4" placeholder="Ghi chú tình trạng thiết bị, hạng mục bảo trì..."></textarea>
        </div>
        <div class="col-12">
            <div class="card border-0 shadow-sm assignment-shell">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                        <div>
                            <div class="badge bg-secondary-subtle text-secondary mb-2">Phân công nhân sự</div>
                            <h6 class="fw-bold mb-1">Chia theo vai trò</h6>
                            <p class="text-muted small mb-0">Lọc nhanh theo tên/mã, danh sách được chia rõ theo kho và nhóm chính.</p>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="chip text-success bg-success-subtle" data-chip="warehouse-count">Kho: <?= $warehouseSelectedCount ?> chọn</span>
                            <span class="chip text-info bg-info-subtle" data-chip="production-count">Sản xuất: <?= $productionSelectedCount ?> chọn</span>
                        </div>
                    </div>
                    <div class="row g-3 align-items-start">
                        <div class="col-md-6">
                            <div class="assignment-panel h-100">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label mb-0">Nhân viên kho</label>
                                    <div class="input-group input-group-sm assignment-search-group">
                                        <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search"></i></span>
                                        <input type="text" class="form-control assignment-search border-start-0" data-target="#warehouse-list" placeholder="Tìm tên/mã nhân viên">
                                    </div>
                                </div>
                                <div class="assignment-list list-group" id="warehouse-list">
                                    <?php foreach ($employeeGroups['warehouse'] as $employee): ?>
                                        <?php $keyword = mb_strtolower(($employee['HoTen'] ?? '') . ' ' . ($employee['IdNhanVien'] ?? ''), 'UTF-8'); ?>
                                        <label class="list-group-item assignment-item d-flex align-items-start justify-content-between"
                                               data-keywords="<?= htmlspecialchars($keyword) ?>"
                                               data-employee-role="<?= htmlspecialchars($employee['IdVaiTro'] ?? '') ?>">
                                            <div class="form-check flex-grow-1">
                                                <input class="form-check-input" type="checkbox" name="warehouse_staff[]" value="<?= htmlspecialchars($employee['IdNhanVien']) ?>">
                                                <div class="ms-2">
                                                    <div class="fw-semibold"><?= htmlspecialchars($employee['HoTen']) ?></div>
                                                    <div class="text-muted small"><?= htmlspecialchars($employee['IdNhanVien']) ?> <?= !empty($employee['ChucVu']) ? '• ' . htmlspecialchars($employee['ChucVu']) : '' ?></div>
                                                </div>
                                            </div>
                                            <span class="badge bg-success-subtle text-success">Kho</span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" data-production-panel>
                            <div class="assignment-panel h-100">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label mb-0" data-production-label>Nhân viên sản xuất</label>
                                    <div class="input-group input-group-sm assignment-search-group">
                                        <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search"></i></span>
                                        <input type="text" class="form-control assignment-search border-start-0" data-target="#production-list" placeholder="Tìm tên/mã nhân viên">
                                    </div>
                                </div>
                                <div class="assignment-list list-group" id="production-list">
                                    <?php foreach ($employeeGroups['production'] as $employee): ?>
                                        <?php $keyword = mb_strtolower(($employee['HoTen'] ?? '') . ' ' . ($employee['IdNhanVien'] ?? ''), 'UTF-8'); ?>
                                        <label class="list-group-item assignment-item d-flex align-items-start justify-content-between"
                                               data-keywords="<?= htmlspecialchars($keyword) ?>"
                                               data-employee-role="<?= htmlspecialchars($employee['IdVaiTro'] ?? '') ?>">
                                            <div class="form-check flex-grow-1">
                                                <input class="form-check-input" type="checkbox" name="production_staff[]" value="<?= htmlspecialchars($employee['IdNhanVien']) ?>">
                                                <div class="ms-2">
                                                    <div class="fw-semibold"><?= htmlspecialchars($employee['HoTen']) ?></div>
                                                    <div class="text-muted small"><?= htmlspecialchars($employee['IdNhanVien']) ?> <?= !empty($employee['ChucVu']) ? '• ' . htmlspecialchars($employee['ChucVu']) : '' ?></div>
                                                </div>
                                            </div>
                                            <span class="badge bg-info-subtle text-info" data-production-badge>Sản xuất</span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
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
    const workshopTypeSelect = document.querySelector('[data-workshop-type]');
    const workshopTypeRules = <?= json_encode($workshopTypeRules, JSON_UNESCAPED_UNICODE) ?>;
    const managerOptionsByType = <?= json_encode($managerCandidatesByType, JSON_UNESCAPED_UNICODE) ?>;
    const managerSelect = document.querySelector('[data-manager-select]');
    const productionPanel = document.querySelector('[data-production-panel]');
    const productionLabel = document.querySelector('[data-production-label]');
    const productionBadge = document.querySelector('[data-production-badge]');

    const getConfig = () => {
        const selectedType = workshopTypeSelect ? workshopTypeSelect.value : '';
        return workshopTypeRules[selectedType] || {};
    };

    const filterProductionEmployees = () => {
        const config = getConfig();
        const productionRoles = Array.isArray(config.production_roles) ? config.production_roles : [];
        const allowProduction = config.allow_production !== false && productionRoles.length > 0;

        if (productionPanel) {
            productionPanel.classList.toggle('d-none', !allowProduction);
        }

        const label = config.production_label || 'Sản xuất';
        if (productionLabel) productionLabel.textContent = `Nhân viên ${label.toLowerCase()}`;
        if (productionBadge) productionBadge.textContent = label;

        document.querySelectorAll('#production-list .assignment-item').forEach((item) => {
            const employeeRole = item.getAttribute('data-employee-role');
            const shouldShow = allowProduction && productionRoles.includes(employeeRole);
            const checkbox = item.querySelector('input[type="checkbox"]');
            if (shouldShow) {
                item.classList.remove('d-none');
                if (checkbox) {
                    checkbox.disabled = false;
                }
            } else {
                item.classList.add('d-none');
                if (checkbox) {
                    checkbox.checked = false;
                    checkbox.disabled = true;
                }
            }
        });
    };

    const updateAssignmentSummary = () => {
        const warehouseCount = document.querySelectorAll('#warehouse-list input[type="checkbox"]:checked').length;
        const productionCount = Array.from(document.querySelectorAll('#production-list input[type="checkbox"]:checked'))
            .filter((checkbox) => !checkbox.closest('.assignment-item')?.classList.contains('d-none'))
            .length;
        const warehouseChip = document.querySelector('[data-chip="warehouse-count"]');
        const productionChip = document.querySelector('[data-chip="production-count"]');
        const label = (getConfig().production_label || 'Sản xuất').toLowerCase();
        if (warehouseChip) warehouseChip.textContent = `Kho: ${warehouseCount} chọn`;
        if (productionChip) productionChip.textContent = `${label}: ${productionCount} chọn`;
    };

    const updateManagerOptions = () => {
        if (!managerSelect) return;
        const selectedType = workshopTypeSelect ? workshopTypeSelect.value : '';
        const options = managerOptionsByType[selectedType] || [];
        const selectedValue = managerSelect.value;
        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.disabled = true;
        placeholder.textContent = 'Chọn xưởng trưởng';

        managerSelect.innerHTML = '';
        managerSelect.appendChild(placeholder);

        if (options.length === 0) {
            const emptyOption = document.createElement('option');
            emptyOption.value = '';
            emptyOption.disabled = true;
            emptyOption.textContent = 'Chưa có nhân sự phù hợp';
            managerSelect.appendChild(emptyOption);
        } else {
            options.forEach((manager) => {
                const option = document.createElement('option');
                option.value = manager.id || '';
                option.textContent = `${manager.name || ''} (${manager.id || ''})`;
                managerSelect.appendChild(option);
            });
        }

        const fallback = options.some((manager) => manager.id === selectedValue) ? selectedValue : '';
        managerSelect.value = fallback;
        if (!fallback) {
            placeholder.selected = true;
        }
    };

    document.querySelectorAll('.assignment-search').forEach((input) => {
        const targetSelector = input.getAttribute('data-target');
        const target = document.querySelector(targetSelector);
        if (!target) return;

        input.addEventListener('input', () => {
            const keyword = input.value.toLowerCase();
            target.querySelectorAll('.assignment-item').forEach((item) => {
                const name = (item.getAttribute('data-keywords') || '').toLowerCase();
                if (!keyword || name.includes(keyword)) {
                    item.classList.remove('d-none');
                } else {
                    item.classList.add('d-none');
                }
            });
        });
    });

    document.querySelectorAll('#warehouse-list input[type="checkbox"], #production-list input[type="checkbox"]').forEach((checkbox) => {
        checkbox.addEventListener('change', updateAssignmentSummary);
    });

    if (workshopTypeSelect) {
        workshopTypeSelect.addEventListener('change', () => {
            filterProductionEmployees();
            updateAssignmentSummary();
            updateManagerOptions();
        });
    }

    filterProductionEmployees();
    updateAssignmentSummary();
    updateManagerOptions();
});
</script>

<style>
.assignment-shell {
    background: #f7f9fc;
}
.assignment-panel {
    background: #fff;
    border: 1px solid #edf1f7;
    border-radius: 12px;
    padding: 12px;
    box-shadow: 0 4px 10px rgba(17, 38, 146, 0.06);
}
.assignment-list {
    max-height: 320px;
    overflow-y: auto;
}
.assignment-item {
    border: 1px solid #f0f2f5 !important;
    border-radius: 10px;
    margin-bottom: 8px;
    padding: 10px 12px;
}
.assignment-item:last-child {
    margin-bottom: 0;
}
.chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border-radius: 999px;
    font-weight: 600;
    font-size: 12px;
    border: 1px solid transparent;
}
</style>
