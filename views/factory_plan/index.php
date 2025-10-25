<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Kế hoạch xưởng SV5TOT</h3>
        <p class="text-muted mb-0">Phân bổ hạng mục bàn phím SV5TOT cho từng xưởng chuyên trách.</p>
    </div>
    <a href="?controller=factory_plan&action=create" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Thêm kế hoạch xưởng SV5TOT</a>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr>
                <th>Mã kế hoạch xưởng</th>
                <th>Đơn hàng</th>
                <th>Xưởng</th>
                <th>Hạng mục</th>
                <th>Số lượng</th>
                <th>Tiến độ</th>
                <th>Trạng thái</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($plans as $plan): ?>
                <tr>
                    <td class="fw-semibold"><?= htmlspecialchars($plan['IdKeHoachSanXuatXuong']) ?></td>
                    <td>
                        <div class="fw-semibold">ĐH <?= htmlspecialchars($plan['IdDonHang']) ?></div>
                        <div class="text-muted small"><?= htmlspecialchars($plan['TenSanPham']) ?></div>
                        <?php if (!empty($plan['TenCauHinh'])): ?>
                            <div class="text-muted small">Cấu hình: <?= htmlspecialchars($plan['TenCauHinh']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($plan['TenXuong'] ?? $plan['IdXuong']) ?></td>
                    <td><?= htmlspecialchars($plan['TenThanhThanhPhanSP']) ?></td>
                    <td><?= htmlspecialchars($plan['SoLuong']) ?></td>
                    <td>
                        <div class="text-muted small">BĐ: <?= $plan['ThoiGianBatDau'] ? date('d/m H:i', strtotime($plan['ThoiGianBatDau'])) : '-' ?></div>
                        <div class="text-muted small">KT: <?= $plan['ThoiGianKetThuc'] ? date('d/m H:i', strtotime($plan['ThoiGianKetThuc'])) : '-' ?></div>
                    </td>
                    <td>
                        <div><span class="badge bg-light text-dark"><?= htmlspecialchars($plan['TrangThai']) ?></span></div>
                        <div class="text-muted small">Kế hoạch tổng: <?= htmlspecialchars($plan['TrangThaiTong']) ?></div>
                    </td>
                    <td class="text-end">
                        <div class="table-actions">
                            <a class="btn btn-sm btn-outline-secondary" href="?controller=factory_plan&action=read&id=<?= urlencode($plan['IdKeHoachSanXuatXuong']) ?>">Chi tiết</a>
                            <a class="btn btn-sm btn-outline-primary" href="?controller=factory_plan&action=edit&id=<?= urlencode($plan['IdKeHoachSanXuatXuong']) ?>">Sửa</a>
                            <a class="btn btn-sm btn-outline-danger" href="?controller=factory_plan&action=delete&id=<?= urlencode($plan['IdKeHoachSanXuatXuong']) ?>" onclick="return confirm('Xác nhận xóa kế hoạch xưởng?');">Xóa</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
