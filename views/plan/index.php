<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Kế hoạch SV5TOT</h3>
        <p class="text-muted mb-0">Theo dõi tiến độ các kế hoạch sản xuất bàn phím SV5TOT theo đơn hàng.</p>
    </div>
    <a href="?controller=plan&action=create" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Thêm kế hoạch SV5TOT</a>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr>
                <th>Mã kế hoạch</th>
                <th>Đơn hàng</th>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Tiến độ xưởng</th>
                <th>Trạng thái</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($plans as $plan): ?>
                <tr>
                    <td class="fw-semibold">#<?= htmlspecialchars($plan['IdKeHoachSanXuat']) ?></td>
                    <td>
                        <div class="fw-semibold">ĐH <?= htmlspecialchars($plan['IdDonHang']) ?></div>
                        <div class="text-muted small"><?= htmlspecialchars($plan['YeuCau'] ?? 'Không có ghi chú') ?></div>
                    </td>
                    <td>
                        <div class="fw-semibold"><?= htmlspecialchars($plan['TenSanPham']) ?></div>
                        <div class="text-muted small"><?= htmlspecialchars($plan['TenCauHinh'] ?? 'Cấu hình chuẩn') ?></div>
                    </td>
                    <td>
                        <div><?= htmlspecialchars($plan['SoLuong']) ?> / <?= htmlspecialchars($plan['SoLuongDonHang']) ?> <?= htmlspecialchars($plan['DonVi'] ?? 'sản phẩm') ?></div>
                        <div class="text-muted small">Bắt đầu: <?= $plan['ThoiGianBD'] ? date('d/m/Y', strtotime($plan['ThoiGianBD'])) : '-' ?></div>
                        <div class="text-muted small">Kết thúc: <?= $plan['ThoiGianKetThuc'] ? date('d/m/Y', strtotime($plan['ThoiGianKetThuc'])) : '-' ?></div>
                    </td>
                    <td>
                        <?php
                        $totalSteps = (int) ($plan['TongCongDoan'] ?? 0);
                        $doneSteps = (int) ($plan['CongDoanHoanThanh'] ?? 0);
                        ?>
                        <span class="badge bg-light text-dark">
                            <?= $totalSteps ? $doneSteps . ' / ' . $totalSteps . ' công đoạn' : 'Chưa phân xưởng' ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge <?= $plan['TrangThai'] === 'Hoàn thành' ? 'badge-soft-success' : 'badge-soft-warning' ?>">
                            <?= htmlspecialchars($plan['TrangThai']) ?>
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="table-actions">
                            <a class="btn btn-sm btn-outline-secondary" href="?controller=plan&action=read&id=<?= urlencode($plan['IdKeHoachSanXuat']) ?>">Chi tiết</a>
                            <a class="btn btn-sm btn-outline-primary" href="?controller=plan&action=edit&id=<?= urlencode($plan['IdKeHoachSanXuat']) ?>">Sửa</a>
                            <a class="btn btn-sm btn-outline-danger" href="?controller=plan&action=delete&id=<?= urlencode($plan['IdKeHoachSanXuat']) ?>" onclick="return confirm('Xác nhận xóa kế hoạch này?');">Xóa</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
