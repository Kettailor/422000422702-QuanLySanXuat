<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Danh sách đơn hàng</h3>
        <p class="text-muted mb-0">Theo dõi tiến độ và trạng thái các đơn hàng của khách hàng.</p>
    </div>
    <a href="?controller=order&action=create" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Thêm đơn hàng</a>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr>
                <th>Mã đơn</th>
                <th>Khách hàng</th>
                <th>Ngày lập</th>
                <th>Trạng thái</th>
                <th class="text-end">Tổng tiền</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td class="fw-semibold">#<?= htmlspecialchars($order['IdDonHang']) ?></td>
                    <td>
                        <div class="fw-medium"><?= htmlspecialchars($order['TenKhachHang']) ?></div>
                        <div class="text-muted small"><?= htmlspecialchars($order['SoDienThoai'] ?? '') ?></div>
                    </td>
                    <td><?= date('d/m/Y', strtotime($order['NgayLap'])) ?></td>
                    <td><span class="badge bg-light text-dark"><?= htmlspecialchars($order['TrangThai']) ?></span></td>
                    <td class="text-end fw-semibold text-primary"><?= number_format($order['TongTien'], 0, ',', '.') ?> đ</td>
                    <td class="text-end">
                        <div class="table-actions">
                            <a class="btn btn-sm btn-outline-secondary" href="?controller=order&action=read&id=<?= urlencode($order['IdDonHang']) ?>">Chi tiết</a>
                            <a class="btn btn-sm btn-outline-primary" href="?controller=order&action=edit&id=<?= urlencode($order['IdDonHang']) ?>">Sửa</a>
                            <a class="btn btn-sm btn-outline-danger" href="?controller=order&action=delete&id=<?= urlencode($order['IdDonHang']) ?>" onclick="return confirm('Xác nhận xóa đơn hàng này?');">Xóa</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
