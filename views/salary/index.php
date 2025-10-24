<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Bảng lương</h3>
        <p class="text-muted mb-0">Theo dõi các bảng lương và trạng thái thanh toán.</p>
    </div>
    <a href="?controller=salary&action=create" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Thêm bảng lương</a>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr>
                <th>Mã bảng lương</th>
                <th>Nhân viên</th>
                <th>Tháng</th>
                <th>Tổng thu nhập</th>
                <th>Trạng thái</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($payrolls as $payroll): ?>
                <tr>
                    <td class="fw-semibold"><?= htmlspecialchars($payroll['IdBangLuong']) ?></td>
                    <td><?= htmlspecialchars($payroll['HoTen']) ?></td>
                    <td><?= htmlspecialchars($payroll['ThangNam']) ?></td>
                    <td class="fw-semibold text-primary"><?= number_format($payroll['TongThuNhap'], 0, ',', '.') ?> đ</td>
                    <td><span class="badge bg-light text-dark"><?= htmlspecialchars($payroll['TrangThai']) ?></span></td>
                    <td class="text-end">
                        <div class="table-actions">
                            <a class="btn btn-sm btn-outline-secondary" href="?controller=salary&action=read&id=<?= urlencode($payroll['IdBangLuong']) ?>">Chi tiết</a>
                            <a class="btn btn-sm btn-outline-primary" href="?controller=salary&action=edit&id=<?= urlencode($payroll['IdBangLuong']) ?>">Sửa</a>
                            <a class="btn btn-sm btn-outline-danger" href="?controller=salary&action=delete&id=<?= urlencode($payroll['IdBangLuong']) ?>" onclick="return confirm('Xác nhận xóa bảng lương này?');">Xóa</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
