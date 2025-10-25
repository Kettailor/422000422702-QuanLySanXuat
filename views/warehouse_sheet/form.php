<?php
$document = $document ?? [];
$warehouses = $warehouses ?? [];
$employees = $employees ?? [];
$types = $types ?? [];
$actionUrl = $actionUrl ?? '?controller=warehouse_sheet&action=store';
$isEdit = $isEdit ?? false;
?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="post" action="<?= $actionUrl ?>" class="row g-4">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Mã phiếu <span class="text-danger">*</span></label>
                <input type="text" name="IdPhieu" class="form-control" value="<?= htmlspecialchars($document['IdPhieu'] ?? '') ?>" <?= $isEdit ? 'readonly' : 'required' ?> placeholder="Ví dụ: PN20231101">
                <div class="form-text">Định danh duy nhất của phiếu kho.</div>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Loại phiếu <span class="text-danger">*</span></label>
                <input type="text" name="LoaiPhieu" class="form-control" list="sheet-types" value="<?= htmlspecialchars($document['LoaiPhieu'] ?? '') ?>" required placeholder="Phiếu nhập nguyên liệu">
                <datalist id="sheet-types">
                    <?php foreach ($types as $type): ?>
                        <option value="<?= htmlspecialchars($type) ?>"></option>
                    <?php endforeach; ?>
                </datalist>
                <div class="form-text">Ví dụ: Phiếu nhập nguyên liệu, Phiếu xuất thành phẩm...</div>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Kho áp dụng <span class="text-danger">*</span></label>
                <select class="form-select" name="IdKho" required>
                    <option value="">-- Chọn kho --</option>
                    <?php foreach ($warehouses as $warehouse): ?>
                        <option value="<?= htmlspecialchars($warehouse['IdKho']) ?>" <?= ($document['IdKho'] ?? '') === $warehouse['IdKho'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($warehouse['TenKho']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Ngày lập phiếu</label>
                <input type="date" name="NgayLP" class="form-control" value="<?= htmlspecialchars($document['NgayLP'] ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Ngày xác nhận</label>
                <input type="date" name="NgayXN" class="form-control" value="<?= htmlspecialchars($document['NgayXN'] ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Tổng giá trị</label>
                <div class="input-group">
                    <input type="number" min="0" step="1000" name="TongTien" class="form-control" value="<?= htmlspecialchars($document['TongTien'] ?? 0) ?>">
                    <span class="input-group-text">đ</span>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Người lập phiếu <span class="text-danger">*</span></label>
                <select class="form-select" name="NguoiLap" required>
                    <option value="">-- Chọn nhân viên --</option>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?= htmlspecialchars($employee['IdNhanVien']) ?>" <?= ($document['NHAN_VIENIdNhanVien'] ?? '') === $employee['IdNhanVien'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($employee['HoTen']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Người xác nhận <span class="text-danger">*</span></label>
                <select class="form-select" name="NguoiXacNhan" required>
                    <option value="">-- Chọn nhân viên --</option>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?= htmlspecialchars($employee['IdNhanVien']) ?>" <?= ($document['NHAN_VIENIdNhanVien2'] ?? '') === $employee['IdNhanVien'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($employee['HoTen']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 d-flex justify-content-between">
                <a href="?controller=warehouse_sheet&action=index" class="btn btn-light">
                    <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
                </a>
                <div>
                    <?php if ($isEdit): ?>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Cập nhật phiếu
                        </button>
                    <?php else: ?>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> Lưu phiếu mới
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if (!$isEdit): ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const typeInput = document.querySelector('input[name="LoaiPhieu"]');
            const idInput = document.querySelector('input[name="IdPhieu"]');

            if (!typeInput || !idInput) {
                return;
            }

            const buildId = (prefix) => {
                const now = new Date();
                const pad = (value) => value.toString().padStart(2, '0');
                return [
                    prefix,
                    now.getFullYear(),
                    pad(now.getMonth() + 1),
                    pad(now.getDate()),
                    pad(now.getHours()),
                    pad(now.getMinutes()),
                    pad(now.getSeconds())
                ].join('');
            };

            typeInput.addEventListener('change', () => {
                if (idInput.dataset.userEdited === '1') {
                    return;
                }

                const value = typeInput.value.toLowerCase();
                let prefix = 'PH';
                if (value.includes('nhập')) {
                    prefix = 'PN';
                } else if (value.includes('xuất')) {
                    prefix = 'PX';
                }

                idInput.value = buildId(prefix);
            });

            idInput.addEventListener('input', () => {
                idInput.dataset.userEdited = '1';
            });
        });
    </script>
<?php endif; ?>
