<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Kế hoạch xưởng</h3>
        <p class="text-muted mb-0">Phân bổ kế hoạch sản xuất cho từng xưởng.</p>
    </div>
    <a href="?controller=factory_plan&action=create" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Thêm kế hoạch xưởng</a>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr>
                <th>Mã kế hoạch xưởng</th>
                <th>Xưởng</th>
                <th>Thành phần</th>
                <th>Số lượng</th>
                <th>Thời gian</th>
                <th>Trạng thái</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($plans as $plan): ?>
                <tr>
                    <td class="fw-semibold"><?= htmlspecialchars($plan['IdKeHoachSanXuatXuong']) ?></td>
                    <td><?= htmlspecialchars($plan['TenXuong'] ?? $plan['IdXuong']) ?></td>
                    <td><?= htmlspecialchars($plan['TenThanhThanhPhanSP']) ?></td>
                    <td><?= htmlspecialchars($plan['SoLuong']) ?></td>
                    <td>
                        <div class="text-muted small">Bắt đầu: <?= $plan['ThoiGianBatDau'] ? date('d/m H:i', strtotime($plan['ThoiGianBatDau'])) : '-' ?></div>
                        <div class="text-muted small">Kết thúc: <?= $plan['ThoiGianKetThuc'] ? date('d/m H:i', strtotime($plan['ThoiGianKetThuc'])) : '-' ?></div>
                    </td>
                    <td><span class="badge bg-light text-dark"><?= htmlspecialchars($plan['TrangThai']) ?></span></td>
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
