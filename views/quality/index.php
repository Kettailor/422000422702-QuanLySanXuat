<style>
/* ===== STYLE CHUNG ===== */
.qa-container {
    font-family: "Inter", sans-serif;
    background-color: #f9fafc;
}

/* ===== DASHBOARD ===== */
.qa-summary .card {
    border-radius: 12px;
    background: #fff;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    padding: 20px;
    text-align: center;
    transition: all 0.2s ease-in-out;
}
.qa-summary .card:hover {
    transform: translateY(-3px);
}
.qa-summary h6 {
    color: #6c757d;
    font-weight: 500;
    margin-bottom: 6px;
}
.qa-summary .value {
    font-size: 1.8rem;
    font-weight: 700;
    color: #2f6bff;
}

/* ===== BẢNG DANH SÁCH ===== */
.table-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.08);
    padding: 20px;
}
.table th {
    color: #6c757d;
    font-weight: 600;
}
.table td {
    vertical-align: middle;
}

/* ===== TRẠNG THÁI ===== */
.badge-status {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 600;
}
.badge-done {
    background-color: #e8f6ee;
    color: #1c8a4b;
}
.badge-fail {
    background-color: #fdecec;
    color: #d93025;
}

/* ===== NÚT ===== */
.btn-action {
    border-radius: 8px;
    padding: 4px 12px;
    font-size: 0.85rem;
}
.btn-create {
    background: #2f6bff;
    color: #fff;
}
.btn-create:hover {
    background: #2556d8;
}
.btn-detail {
    background: #17a2b8;
    color: #fff;
}
.btn-detail:hover {
    background: #138496;
}
.btn-delete {
    background: #f44336;
    color: #fff;
}
.btn-delete:hover {
    background: #d32f2f;
}
</style>

<div class="qa-container">

    <!-- Tiêu đề + Thanh tìm kiếm -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Kiểm tra chất lượng sản phẩm</h3>
            <p class="text-muted mb-0">Theo dõi tiến độ đánh giá và lưu kết quả kiểm tra thành phẩm SV5TOT.</p>
        </div>
        <form class="d-flex" role="search">
            <input type="text" class="form-control" placeholder="Tìm kiếm lô, sản phẩm...">
        </form>
    </div>

    <!-- DASHBOARD 4 Ô -->
    <div class="row g-3 qa-summary mb-4">
        <div class="col-md-3">
            <div class="card">
                <h6>Tổng lô sản phẩm</h6>
                <div class="value"><?= $summary['tong_bien_ban'] ?? 0 ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <h6>Lô đạt yêu cầu</h6>
                <div class="value text-success"><?= $summary['so_dat'] ?? 0 ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <h6>Lô không đạt</h6>
                <div class="value text-danger"><?= $summary['so_khong_dat'] ?? 0 ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <h6>Còn lại chưa kiểm tra</h6>
                <div class="value text-warning"><?= ($summary['tong_bien_ban'] ?? 0) - (($summary['so_dat'] ?? 0) + ($summary['so_khong_dat'] ?? 0)) ?></div>
            </div>
        </div>
    </div>

    <!-- BẢNG DANH SÁCH -->
    <div class="table-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-semibold mb-0">Danh sách lô sản phẩm cần kiểm tra</h5>
            <a href="?controller=quality&action=create" class="btn btn-create">
                <i class="bi bi-plus-lg me-2"></i> Tạo biên bản kiểm tra
            </a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Mã Lô</th>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Ngày kiểm tra</th>
                        <th>Trạng thái</th>
                        <th>Xưởng</th>
                        <th class="text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($reports)): ?>
                        <?php foreach ($reports as $r): ?>
                            <tr>
                                <td class="fw-semibold"><?= htmlspecialchars($r['IdLo']) ?></td>
                                <td><?= htmlspecialchars($r['TenSanPham'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($r['SoLuong'] ?? 0) ?></td>
                                <td><?= $r['ThoiGian'] ? date('d/m/Y', strtotime($r['ThoiGian'])) : '-' ?></td>
                                <td>
                                    <?php
                                        $status = trim($r['KetQua'] ?? 'Không đạt');
                                        $class = (mb_strtolower($status) === 'đạt') ? 'badge-done' : 'badge-fail';
                                    ?>
                                    <span class="badge-status <?= $class ?>">
                                        <?= htmlspecialchars($status) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($r['TenXuong'] ?? '-') ?></td>
                                <td class="text-end">
                                    <a href="?controller=quality&action=edit&id=<?= urlencode($r['IdBienBanDanhGiaSP']) ?>"
                                       class="btn btn-action btn-create me-2">Đánh giá</a>
                                    <a href="?controller=quality&action=read&id=<?= urlencode($r['IdBienBanDanhGiaSP']) ?>"
                                       class="btn btn-action btn-detail me-2">Chi tiết</a>
                                    <a href="?controller=quality&action=delete&id=<?= urlencode($r['IdBienBanDanhGiaSP']) ?>"
                                       class="btn btn-action btn-delete"
                                       onclick="return confirm('Xác nhận xóa lô này?');">Xóa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center text-muted py-3">Không có lô cần kiểm tra.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
