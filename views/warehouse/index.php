<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Kho Aurora</h3>
        <p class="text-muted mb-0">Theo dõi kho linh kiện, kho thành phẩm Aurora và sức chứa hiện tại.</p>
    </div>
    <a href="?controller=warehouse&action=create" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Thêm kho Aurora</a>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr>
                <th>Mã kho</th>
                <th>Tên kho</th>
                <th>Loại kho Aurora</th>
                <th>Quản kho</th>
                <th>Tổng lô Aurora</th>
                <th>Tổng SL Aurora</th>
                <th>Trạng thái</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($warehouses as $warehouse): ?>
                <tr>
                    <td class="fw-semibold"><?= htmlspecialchars($warehouse['IdKho']) ?></td>
                    <td><?= htmlspecialchars($warehouse['TenKho']) ?></td>
                    <td><?= htmlspecialchars($warehouse['TenLoaiKho']) ?></td>
                    <td><?= htmlspecialchars($warehouse['TenQuanKho'] ?? '') ?></td>
                    <td><?= htmlspecialchars($warehouse['TongSLLo']) ?></td>
                    <td><?= htmlspecialchars($warehouse['TongSL']) ?></td>
                    <td><span class="badge bg-light text-dark"><?= htmlspecialchars($warehouse['TrangThai']) ?></span></td>
                    <td class="text-end">
                        <div class="table-actions">
                            <a class="btn btn-sm btn-outline-secondary" href="?controller=warehouse&action=read&id=<?= urlencode($warehouse['IdKho']) ?>">Chi tiết</a>
                            <a class="btn btn-sm btn-outline-primary" href="?controller=warehouse&action=edit&id=<?= urlencode($warehouse['IdKho']) ?>">Sửa</a>
                            <a class="btn btn-sm btn-outline-danger" href="?controller=warehouse&action=delete&id=<?= urlencode($warehouse['IdKho']) ?>" onclick="return confirm('Xác nhận xóa kho này?');">Xóa</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
