<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Cập nhật kho SV5TOT</h3>
        <p class="text-muted mb-0">Điều chỉnh thông tin kho SV5TOT và người phụ trách.</p>
    </div>
    <a href="?controller=warehouse&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<style>
    .warehouse-edit-wrapper {
        background: #f3f6fb;
        border-radius: 1rem;
        padding: 1.75rem;
        border: 1px solid rgba(15, 23, 42, 0.05);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.6);
    }

    .warehouse-form-section {
        border: 1px solid rgba(15, 23, 42, 0.06);
        border-radius: 1rem;
        padding: 1.5rem;
        background: linear-gradient(145deg, #ffffff 0%, #f7fbff 100%);
        height: 100%;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        position: relative;
        overflow: hidden;
    }

    .warehouse-form-section:before {
        content: "";
        position: absolute;
        inset: 0;
        pointer-events: none;
        background: radial-gradient(circle at 15% 20%, rgba(25, 118, 210, 0.08), transparent 35%),
                    radial-gradient(circle at 85% 10%, rgba(76, 175, 80, 0.08), transparent 30%);
        opacity: 0.8;
    }

    .warehouse-form-section > * {
        position: relative;
        z-index: 1;
    }

    .warehouse-edit-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
        gap: 1.75rem;
    }

    .warehouse-form-section + .warehouse-form-section {
        margin-top: 1rem;
    }

    .section-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.75rem;
        border-radius: 999px;
        background: rgba(25, 118, 210, 0.08);
        color: #0d6efd;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .warehouse-subpanel {
        border: 1px dashed rgba(15, 23, 42, 0.08);
        border-radius: 0.9rem;
        padding: 1rem 1.25rem;
        background: #f8fbff;
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
            <div class="warehouse-edit-wrapper">
                <form action="?controller=warehouse&action=update" method="post" class="warehouse-edit-grid">
                <input type="hidden" name="IdKho" value="<?= htmlspecialchars($warehouse['IdKho']) ?>">

                <div>
                    <div class="warehouse-form-section">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="text-uppercase small text-muted">Thông tin kho</div>
                                <h5 class="fw-semibold mb-1"><?= htmlspecialchars($warehouse['TenKho']) ?></h5>
                                <div class="text-muted small">Mã: <?= htmlspecialchars($warehouse['IdKho']) ?></div>
                            </div>
                            <span class="section-chip"><i class="bi bi-pencil-square"></i> Đang cập nhật</span>
                        </div>
                        <div class="row g-3 row-cols-1 row-cols-md-2 align-items-start">
                            <div class="col">
                                <label class="form-label">Tên kho <span class="text-danger">*</span></label>
                                <input type="text" name="TenKho" class="form-control" value="<?= htmlspecialchars($warehouse['TenKho']) ?>" required>
                            </div>
                            <div class="col">
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
                            <div class="col">
                                <label class="form-label">Địa chỉ</label>
                                <input type="text" name="DiaChi" class="form-control" value="<?= htmlspecialchars($warehouse['DiaChi']) ?>">
                            </div>
                            <div class="col">
                                <label class="form-label">Trạng thái</label>
                                <select name="TrangThai" class="form-select">
                                    <?php foreach ($statuses as $status): ?>
                                        <option value="<?= htmlspecialchars($status) ?>" <?= $status === ($warehouse['TrangThai'] ?? '') ? 'selected' : '' ?>><?= htmlspecialchars($status) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col">
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

                        <div class="warehouse-subpanel mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <div class="text-uppercase small text-muted">Sức chứa & số liệu</div>
                                    <h6 class="fw-semibold mb-0">Quy mô kho</h6>
                                </div>
                                <span class="section-chip text-success" style="background: rgba(25, 135, 84, 0.12); color: #198754;">
                                    <i class="bi bi-archive"></i> Tồn kho
                                </span>
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
                </div>

                <div class="col-lg-4">
                    <div class="warehouse-form-section h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <div class="text-uppercase small text-muted mb-1">Người phụ trách</div>
                                <h6 class="fw-semibold mb-0">Thông tin quản lý kho</h6>
                            </div>
                            <span class="section-chip" style="background: rgba(121, 80, 242, 0.12); color: #6f42c1;">
                                <i class="bi bi-person-check"></i> Quản kho
                            </span>
                        </div>
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
    </div>
<?php endif; ?>
