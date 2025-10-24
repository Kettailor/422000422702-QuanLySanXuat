<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Nhân sự</h3>
        <p class="text-muted mb-0">Quản lý thông tin nhân viên và tình trạng làm việc.</p>
    </div>
    <a href="?controller=human_resources&action=create" class="btn btn-primary"><i class="bi bi-person-plus me-2"></i>Thêm nhân sự</a>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr>
                <th>Mã NV</th>
                <th>Họ tên</th>
                <th>Chức vụ</th>
                <th>Ngày sinh</th>
                <th>Trạng thái</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($employees as $employee): ?>
                <tr>
                    <td class="fw-semibold"><?= htmlspecialchars($employee['IdNhanVien']) ?></td>
                    <td>
                        <div class="fw-medium"><?= htmlspecialchars($employee['HoTen']) ?></div>
                        <div class="text-muted small">Hệ số: <?= htmlspecialchars($employee['HeSoLuong']) ?></div>
                    </td>
                    <td><?= htmlspecialchars($employee['ChucVu']) ?></td>
                    <td><?= date('d/m/Y', strtotime($employee['NgaySinh'])) ?></td>
                    <td>
                        <span class="badge <?= $employee['TrangThai'] === 'Đang làm việc' ? 'badge-soft-success' : 'badge-soft-warning' ?>">
                            <?= htmlspecialchars($employee['TrangThai']) ?>
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="table-actions">
                            <a class="btn btn-sm btn-outline-secondary" href="?controller=human_resources&action=read&id=<?= urlencode($employee['IdNhanVien']) ?>">Chi tiết</a>
                            <a class="btn btn-sm btn-outline-primary" href="?controller=human_resources&action=edit&id=<?= urlencode($employee['IdNhanVien']) ?>">Sửa</a>
                            <a class="btn btn-sm btn-outline-danger" href="?controller=human_resources&action=delete&id=<?= urlencode($employee['IdNhanVien']) ?>" onclick="return confirm('Xác nhận xóa nhân sự này?');">Xóa</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
