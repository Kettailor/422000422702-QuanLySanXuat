<div class="mb-4">
  <?php if (!empty($workshops)): ?>
    <h3 class="fw-bold mb-1">Chon xuong de quan ly tieu chi chat luong</h3>
    <p class="text-muted mb-0">Quản lý tiêu chí đánh giá chất lượng sản phẩm theo từng xưởng.</p>
  <?php else: ?>
    <h3 class="fw-bold mb-1">Quản lý tiêu chí xưởng <?= htmlspecialchars($_GET['id'] ?? '') ?> </h3>
  <?php endif; ?>
</div>

<?php if (!empty($workshops)): ?>
<div class="card p-4">
    <table class="table table-responsive">
        <thead>
            <tr>
                <th scope="col">Mã Xưởng</th>
                <th scope="col">Tên Xưởng</th>
                <th scope="col">Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($workshops as $workshop): ?>
                <tr>
                    <td><?= htmlspecialchars($workshop['IdXuong'] ?? '') ?></td>
                    <td><?= htmlspecialchars($workshop['TenXuong'] ?? '') ?></td>
                    <td>
                        <a href="?controller=quality&action=criterias&id=<?= urlencode($workshop['IdXuong'] ?? '') ?>" class="btn btn-sm btn-primary">
                            Quản Lý Tiêu Chí
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php elseif (!empty($criterias)): ?>
    <div class="card p-4">
        <div class="d-flex justify-content-end mb-3">
            <a href="?controller=quality&action=createCriteria&id=<?= urlencode($_GET['id'] ?? '') ?>" class="btn btn-success btn-sm">
                <i class="bi bi-plus"></i> Thêm Tiêu Chí Mới
            </a>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Mã Tiêu Chí</th>
                    <th scope="col">Tên Tiêu Chí</th>
                    <th scope="col">Mô Tả</th>
                    <th scope="col">Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($criterias as $criteria): ?>
                    <tr>
                        <td><?= htmlspecialchars($criteria['id'] ?? '') ?></td>
                        <td><?= htmlspecialchars($criteria['criterion'] ?? '') ?></td>
                        <td><?= htmlspecialchars($criteria['description'] ?? '') ?></td>
                        <td>
                            <a href="?controller=quality&action=deleteCriteria&idXuong=<?= urlencode($_GET['id'] ?? '') ?>&criteriaId=<?= urlencode($criteria['id'] ?? '') ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xác nhận xóa tiêu chí này?');">
                                Xoá
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">Không có tiêu chí nào cho xưởng này.</div>
    <a href="?controller=quality&action=createCriteria&id=<?= urlencode($_GET['id'] ?? '') ?>" class="btn btn-success btn-sm">
        <i class="bi bi-plus"></i> Thêm Tiêu Chí Mới
    </a>
<?php endif; ?>
