<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Cập nhật kho SV5TOT</h3>
        <p class="text-muted mb-0">Điều chỉnh thông tin kho SV5TOT và người phụ trách.</p>
    </div>
    <a href="?controller=warehouse&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<style>
    .warehouse-form-section {
        border: 1px solid rgba(15, 23, 42, 0.06);
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #ffffff, #f8fbff);
        height: 100%;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
    }

    .warehouse-edit-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 1.5rem;
    }

    .warehouse-form-section + .warehouse-form-section {
        margin-top: 1rem;
    }
</style>

<?php
$workshops = $workshops ?? [];
$managers = $managers ?? [];
$statuses = $statuses ?? ['Đang sử dụng', 'Tạm dừng', 'Bảo trì'];
$types = $types ?? [];
?>

<?php if (!$warehouse): ?>
    <div class="alert alert-warning">Không tìm thấy kho.</div>
<?php else: ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="?controller=warehouse&action=update" method="post" class="warehouse-edit-grid">
                <input type="hidden" name="IdKho" value="<?= htmlspecialchars($warehouse['IdKho']) ?>">

                <div>
                    <div class="warehouse-form-section">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="text-uppercase small text-muted">Thông tin kho</div>
                                <h5 class="fw-semibold mb-0"><?= htmlspecialchars($warehouse['TenKho']) ?></h5>
                            </div>
                            <span class="badge bg-primary-subtle text-primary border">Đang cập nhật</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tên kho <span class="text-danger">*</span></label>
                                <input type="text" name="TenKho" class="form-control" value="<?= htmlspecialchars($warehouse['TenKho']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Loại kho <span class="text-danger">*</span></label>
                                <select name="TenLoaiKho" class="form-select" required>
                                    <?php foreach ($types as $typeLabel): ?>
                                        <option value="<?= htmlspecialchars($typeLabel) ?>" <?= ($warehouse['TenLoaiKho'] ?? '') === $typeLabel ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($typeLabel) ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <?php if (!empty($warehouse['TenLoaiKho']) && !in_array($warehouse['TenLoaiKho'], $types, true)): ?>
                                        <option value="<?= htmlspecialchars($warehouse['TenLoaiKho']) ?>" selected>
                                            <?= htmlspecialchars($warehouse['TenLoaiKho']) ?>
                                        </option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Địa chỉ</label>
                                <input type="text" name="DiaChi" class="form-control" value="<?= htmlspecialchars($warehouse['DiaChi']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Trạng thái</label>
                                <select name="TrangThai" class="form-select">
                                    <?php foreach ($statuses as $status): ?>
                                        <option value="<?= htmlspecialchars($status) ?>" <?= $status === ($warehouse['TrangThai'] ?? '') ? 'selected' : '' ?>><?= htmlspecialchars($status) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Xưởng phụ trách <span class="text-danger">*</span></label>
                                <select name="IdXuong" class="form-select" required>
                                    <?php foreach ($workshops as $workshop): ?>
                                        <option value="<?= htmlspecialchars($workshop['IdXuong']) ?>" <?= $workshop['IdXuong'] === ($warehouse['IdXuong'] ?? '') ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($workshop['TenXuong']) ?> (<?= htmlspecialchars($workshop['IdXuong']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="warehouse-form-section">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="text-uppercase small text-muted">Sức chứa & số liệu</div>
                                <h6 class="fw-semibold mb-0">Quy mô kho</h6>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Tổng số lô</label>
                                <input type="number" name="TongSLLo" class="form-control" value="<?= (int) $warehouse['TongSLLo'] ?>" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tổng sức chứa</label>
                                <input type="number" name="TongSL" class="form-control" value="<?= (int) $warehouse['TongSL'] ?>" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tổng giá trị hàng tồn (đ)</label>
                                <input type="number" name="ThanhTien" class="form-control" value="<?= (float) $warehouse['ThanhTien'] ?>" min="0">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="warehouse-form-section h-100">
                        <div class="text-uppercase small text-muted mb-2">Người phụ trách</div>
                        <h6 class="fw-semibold mb-3">Thông tin quản lý kho</h6>
                        <div class="mb-3">
                            <label class="form-label">Nhân viên quản kho <span class="text-danger">*</span></label>
                            <select name="NHAN_VIEN_KHO_IdNhanVien" class="form-select" required>
                                <?php foreach ($managers as $manager): ?>
                                    <option value="<?= htmlspecialchars($manager['IdNhanVien']) ?>" <?= $manager['IdNhanVien'] === ($warehouse['NHAN_VIEN_KHO_IdNhanVien'] ?? '') ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($manager['HoTen']) ?><?= !empty($manager['ChucVu']) ? ' · ' . htmlspecialchars($manager['ChucVu']) : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mã quản kho</label>
                            <input type="text" name="IdQuanKho" class="form-control" value="<?= htmlspecialchars($warehouse['NHAN_VIEN_KHO_IdNhanVien'] ?? '') ?>">
                        </div>
                        <div class="text-muted small">Giữ nguyên mã kho và mã lô hiện tại để không ảnh hưởng đến phiếu đã lập.</div>
                    </div>
                </div>

                <div class="col-12 text-end">
                    <button class="btn btn-primary px-4" type="submit">Cập nhật kho SV5TOT</button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>
