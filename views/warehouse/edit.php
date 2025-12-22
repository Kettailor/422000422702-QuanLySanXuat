<?php
$workshops = $workshops ?? [];
$managers = $managers ?? [];
$statuses = $statuses ?? ['Đang sử dụng', 'Tạm dừng', 'Bảo trì'];
$types = $types ?? [];
?>

<style>
    .warehouse-edit-shell {
        background: #f4f6fb;
        border-radius: 18px;
        padding: 24px;
        border: 1px solid rgba(15, 23, 42, 0.06);
        box-shadow: 0 8px 28px rgba(15, 23, 42, 0.06);
    }

    .warehouse-edit-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }

    @media (max-width: 1200px) {
        .warehouse-edit-grid {
            grid-template-columns: 1fr;
        }
    }

    .panel-soft {
        background: linear-gradient(145deg, #ffffff 0%, #f9fbff 100%);
        border: 1px solid rgba(15, 23, 42, 0.05);
        border-radius: 14px;
        padding: 20px;
        position: relative;
        overflow: hidden;
    }

    .panel-soft:before {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 15% 20%, rgba(13, 110, 253, 0.08), transparent 40%),
                    radial-gradient(circle at 90% 10%, rgba(25, 135, 84, 0.06), transparent 35%);
        pointer-events: none;
    }

    .panel-soft > * {
        position: relative;
        z-index: 1;
    }

    .section-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 14px;
    }

    .section-head .eyebrow {
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-size: 0.72rem;
        color: #6c757d;
        margin-bottom: 4px;
    }

    .pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .summary-card {
        background: #0d1b2a;
        color: #fff;
        border-radius: 14px;
        padding: 18px;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08);
    }

    .summary-card .label {
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.06em;
        color: rgba(255, 255, 255, 0.7);
    }

    .summary-card .value {
        font-size: 1.25rem;
        font-weight: 700;
    }

    .divider {
        height: 1px;
        background: rgba(15, 23, 42, 0.08);
        margin: 14px 0;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Chỉnh sửa thông tin kho</h3>
        <p class="text-muted mb-0">Cập nhật dữ liệu kho SV5TOT với bố cục rõ ràng, tối ưu cho màn hình desktop.</p>
    </div>
    <a href="?controller=warehouse&action=index" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại danh sách
    </a>
</div>

<?php if (!$warehouse): ?>
    <div class="alert alert-warning">Không tìm thấy kho.</div>
<?php else: ?>
    <div class="warehouse-edit-shell">
        <form action="?controller=warehouse&action=update" method="post">
            <input type="hidden" name="IdKho" value="<?= htmlspecialchars($warehouse['IdKho']) ?>">

            <div class="warehouse-edit-grid">
                <div class="panel-soft">
                    <div class="section-head">
                        <div>
                            <div class="eyebrow">Thông tin chính</div>
                            <h5 class="fw-semibold mb-0"><?= htmlspecialchars($warehouse['TenKho']) ?></h5>
                            <div class="text-muted small">Mã kho: <?= htmlspecialchars($warehouse['IdKho']) ?></div>
                        </div>
                        <span class="pill"><i class="bi bi-pencil-square"></i> Đang chỉnh sửa</span>
                    </div>

                    <div class="row g-3 row-cols-1 row-cols-md-2">
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
                        <div class="col">
                            <label class="form-label">Mã quản kho</label>
                            <input type="text" name="IdQuanKho" class="form-control" value="<?= htmlspecialchars($warehouse['NHAN_VIEN_KHO_IdNhanVien'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div class="warehouse-subpanel">
                        <div class="section-head">
                            <div>
                                <div class="eyebrow">Sức chứa & số liệu</div>
                                <h6 class="fw-semibold mb-0">Quy mô kho</h6>
                            </div>
                            <span class="pill" style="background: rgba(25, 135, 84, 0.12); color: #198754;"><i class="bi bi-archive"></i> Tồn kho</span>
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

                <div class="panel-soft">
                    <div class="section-head">
                        <div>
                            <div class="eyebrow">Người phụ trách</div>
                            <h6 class="fw-semibold mb-0">Quản lý kho</h6>
                        </div>
                        <span class="pill" style="background: rgba(111, 66, 193, 0.12); color: #6f42c1;"><i class="bi bi-person-check"></i> Nhân sự</span>
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

                    <div class="summary-card mt-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="label">Mã kho</div>
                                <div class="value"><?= htmlspecialchars($warehouse['IdKho']) ?></div>
                            </div>
                            <div>
                                <div class="label">Trạng thái</div>
                                <div class="value"><?= htmlspecialchars($warehouse['TrangThai'] ?? 'Đang cập nhật') ?></div>
                            </div>
                        </div>
                        <div class="divider" style="background: rgba(255,255,255,0.15);"></div>
                        <div class="label mb-1">Ghi chú</div>
                        <div class="small">Giữ nguyên mã kho/lô để không ảnh hưởng các phiếu đã lập. Cập nhật nhân sự quản kho để đảm bảo trách nhiệm.</div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button class="btn btn-primary px-4" type="submit">
                    <i class="bi bi-save"></i> Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
<?php endif; ?>
