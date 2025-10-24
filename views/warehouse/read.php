<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Chi tiết kho</h3>
        <p class="text-muted mb-0">Thông tin kho và các lô hàng đang quản lý.</p>
    </div>
    <a href="?controller=warehouse&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php if (!$warehouse): ?>
    <div class="alert alert-warning">Không tìm thấy kho.</div>
<?php else: ?>
    <div class="card p-4 mb-4">
        <div class="row g-4">
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Mã kho</dt>
                    <dd class="col-sm-7 fw-semibold"><?= htmlspecialchars($warehouse['IdKho']) ?></dd>
                    <dt class="col-sm-5">Tên kho</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($warehouse['TenKho']) ?></dd>
                    <dt class="col-sm-5">Loại kho</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($warehouse['TenLoaiKho']) ?></dd>
                    <dt class="col-sm-5">Địa chỉ</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($warehouse['DiaChi']) ?></dd>
                </dl>
            </div>
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Quản kho</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($warehouse['`NHAN_VIEN_KHO_IdNhanVien`'] ?? '') ?></dd>
                    <dt class="col-sm-5">Tổng số lô</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($warehouse['TongSLLo']) ?></dd>
                    <dt class="col-sm-5">Tổng số lượng</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($warehouse['TongSL']) ?></dd>
                    <dt class="col-sm-5">Trạng thái</dt>
                    <dd class="col-sm-7"><span class="badge bg-info bg-opacity-25 text-primary"><?= htmlspecialchars($warehouse['TrangThai']) ?></span></dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Danh sách lô hàng</h5>
            <span class="text-muted small">Tổng cộng: <?= count($lots) ?> lô</span>
        </div>
        <?php if (empty($lots)): ?>
            <p class="text-muted">Chưa có lô hàng nào được ghi nhận.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>Mã lô</th>
                        <th>Tên lô</th>
                        <th>Số lượng</th>
                        <th>Loại lô</th>
                        <th>Ngày tạo</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($lots as $lot): ?>
                        <tr>
                            <td><?= htmlspecialchars($lot['IdLo']) ?></td>
                            <td><?= htmlspecialchars($lot['TenLo']) ?></td>
                            <td><?= htmlspecialchars($lot['SoLuong']) ?></td>
                            <td><?= htmlspecialchars($lot['LoaiLo']) ?></td>
                            <td><?= $lot['NgayTao'] ? date('d/m/Y', strtotime($lot['NgayTao'])) : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
