<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Hóa đơn</h3>
        <p class="text-muted mb-0">Quản lý hóa đơn bán hàng và liên kết với đơn hàng.</p>
    </div>
    <a href="?controller=bill&action=create" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Lập hóa đơn</a>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr>
                <th>Mã hóa đơn</th>
                <th>Loại</th>
                <th>Ngày lập</th>
                <th>Trạng thái</th>
                <th>Đơn hàng</th>
                <th>Thuế</th>
                <th>Mã bưu điện</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($bills as $bill): ?>
                <tr>
                    <td class="fw-semibold">#<?= htmlspecialchars($bill['IdHoaDon']) ?></td>
                    <td><?= htmlspecialchars($bill['LoaiHD']) ?></td>
                    <td><?= $bill['NgayLap'] ? date('d/m/Y', strtotime($bill['NgayLap'])) : '-' ?></td>
                    <td><span class="badge bg-light text-dark"><?= htmlspecialchars($bill['TrangThai']) ?></span></td>
                    <td><?= htmlspecialchars($bill['DonHangYeuCau'] ?? $bill['IdDonHang']) ?></td>
                    <td><?= htmlspecialchars($bill['Thue'] ?? 0) ?>%</td>
                    <td><?= htmlspecialchars($bill['MaBuuDien'] ?? '-') ?></td>
                    <td class="text-end">
                        <div class="table-actions">
                            <a class="btn btn-sm btn-outline-secondary" href="?controller=bill&action=read&id=<?= urlencode($bill['IdHoaDon']) ?>">Chi tiết</a>
                            <a class="btn btn-sm btn-outline-primary" href="?controller=bill&action=edit&id=<?= urlencode($bill['IdHoaDon']) ?>">Sửa</a>
                            <?php if (($bill['TrangThai'] ?? '') === 'Chưa thanh toán'): ?>
                                <a class="btn btn-sm btn-outline-danger" href="?controller=bill&action=delete&id=<?= urlencode($bill['IdHoaDon']) ?>" onclick="return confirm('Xác nhận hủy hóa đơn này?');">Hủy</a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
