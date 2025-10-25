<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Kiểm soát chất lượng SV5TOT</h3>
        <p class="text-muted mb-0">Theo dõi các biên bản đánh giá bàn phím SV5TOT, PCB và lô thành phẩm.</p>
    </div>
    <a href="?controller=quality&action=create" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Lập biên bản SV5TOT</a>
</div>

<div class="row g-4">
    <div class="col-xl-4">
        <div class="card p-4 h-100">
            <h5 class="mb-3">Tổng quan QA SV5TOT</h5>
            <div class="d-flex justify-content-between">
                <div>
                    <div class="text-muted">Biên bản kiểm thử</div>
                    <div class="fs-2 fw-bold text-primary"><?= $summary['tong_bien_ban'] ?? 0 ?></div>
                </div>
                <div>
                    <div class="text-muted">Đạt SV5TOT</div>
                    <div class="fs-4 text-success fw-semibold"><?= $summary['so_dat'] ?? 0 ?></div>
                </div>
                <div>
                    <div class="text-muted">Không đạt SV5TOT</div>
                    <div class="fs-4 text-danger fw-semibold"><?= $summary['so_khong_dat'] ?? 0 ?></div>
                </div>
            </div>
            <p class="text-muted small mt-3">Dữ liệu được cập nhật tự động từ bộ phận kiểm soát chất lượng.</p>
        </div>
    </div>
    <div class="col-xl-8">
        <div class="card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Biên bản SV5TOT gần đây</h5>
                <span class="text-muted small">Danh sách mới nhất</span>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>Mã biên bản</th>
                        <th>Lô SV5TOT</th>
                        <th>Kết quả</th>
                        <th>Thời gian</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($reports as $report): ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($report['IdBienBanDanhGiaSP']) ?></td>
                            <td><?= htmlspecialchars($report['TenLo'] ?? $report['IdLo']) ?></td>
                            <td>
                                <span class="badge <?= $report['KetQua'] === 'Đạt' ? 'badge-soft-success' : 'badge-soft-danger' ?>">
                                    <?= htmlspecialchars($report['KetQua']) ?>
                                </span>
                            </td>
                            <td><?= $report['ThoiGian'] ? date('d/m/Y H:i', strtotime($report['ThoiGian'])) : '-' ?></td>
                            <td class="text-end">
                                <div class="table-actions">
                                    <a class="btn btn-sm btn-outline-secondary" href="?controller=quality&action=read&id=<?= urlencode($report['IdBienBanDanhGiaSP']) ?>">Chi tiết</a>
                                    <a class="btn btn-sm btn-outline-primary" href="?controller=quality&action=edit&id=<?= urlencode($report['IdBienBanDanhGiaSP']) ?>">Sửa</a>
                                    <a class="btn btn-sm btn-outline-danger" href="?controller=quality&action=delete&id=<?= urlencode($report['IdBienBanDanhGiaSP']) ?>" onclick="return confirm('Xác nhận xóa biên bản này?');">Xóa</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
