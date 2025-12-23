<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Chi tiết xưởng sản xuất</h3>
        <p class="text-muted mb-0">Tổng hợp thông tin nhân sự, công suất và trạng thái vận hành.</p>
    </div>
    <a href="?controller=workshop&action=index" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
</div>

<?php if (!$workshop): ?>
    <div class="alert alert-warning">Không tìm thấy thông tin xưởng.</div>
<?php else: ?>
    <?php
    $assignments = $assignments ?? [];
    $warehouseNames = array_column($assignments['nhan_vien_kho'] ?? [], 'HoTen');
    $productionNames = array_column($assignments['nhan_vien_san_xuat'] ?? [], 'HoTen');
    $canViewAssignments = $canViewAssignments ?? false;
    $staffList = $staffList ?? [];
    ?>
    <div class="card p-4 shadow-sm">
        <div class="row g-4">
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Mã xưởng</dt>
                    <dd class="col-sm-8 fw-semibold text-primary"><?= htmlspecialchars($workshop['IdXuong']) ?></dd>
                    <dt class="col-sm-4">Tên xưởng</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($workshop['TenXuong']) ?></dd>
                    <dt class="col-sm-4">Địa điểm</dt>
                    <dd class="col-sm-8 text-muted"><?= htmlspecialchars($workshop['DiaDiem'] ?? '-') ?></dd>
                    <dt class="col-sm-4">Trạng thái</dt>
                    <dd class="col-sm-8">
                        <span class="badge <?= ($workshop['TrangThai'] ?? '') === 'Đang hoạt động' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' ?>">
                            <?= htmlspecialchars($workshop['TrangThai'] ?? 'Không xác định') ?>
                        </span>
                    </dd>
                    <dt class="col-sm-4">Ngày thành lập</dt>
                    <dd class="col-sm-8"><?= !empty($workshop['NgayThanhLap']) ? date('d/m/Y', strtotime($workshop['NgayThanhLap'])) : '-' ?></dd>
                </dl>
            </div>
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-6">Công suất tối đa</dt>
                    <dd class="col-sm-6 fw-semibold text-primary"><?= number_format($workshop['CongSuatToiDa'] ?? 0, 0, ',', '.') ?></dd>
                    <dt class="col-sm-6">Công suất đang dùng</dt>
                    <dd class="col-sm-6"><?= number_format($workshop['CongSuatDangSuDung'] ?? $workshop['CongSuatHienTai'] ?? 0, 0, ',', '.') ?></dd>
                    <dt class="col-sm-6">Nhân sự tối đa</dt>
                    <dd class="col-sm-6"><?= number_format($workshop['staff_max'] ?? $workshop['SlNhanVien'] ?? 0) ?></dd>
                    <dt class="col-sm-6">Nhân sự hiện tại</dt>
                    <dd class="col-sm-6"><?= number_format(count($staffList)) ?></dd>
                    <dt class="col-sm-6">Tỷ lệ sử dụng</dt>
                    <?php
                    $usage = 0;
    if (!empty($workshop['CongSuatToiDa'])) {
        $usage = round((($workshop['CongSuatDangSuDung'] ?? $workshop['CongSuatHienTai'] ?? 0) / $workshop['CongSuatToiDa']) * 100, 2);
    }
    ?>
                    <dd class="col-sm-6"><?= $usage ?>%</dd>
                </dl>
            </div>
        </div>
        <?php if (!empty($workshop['MoTa'])): ?>
            <div class="mt-4">
                <h6 class="fw-semibold">Ghi chú</h6>
                <p class="mb-0 text-muted"><?= nl2br(htmlspecialchars($workshop['MoTa'])) ?></p>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($canViewAssignments): ?>
        <?php
        $warehouseCount = count($warehouseNames);
        $productionCount = count($productionNames);
        ?>
        <div class="card mt-4 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                    <div>
                        <div class="badge bg-secondary-subtle text-secondary mb-2">Phân công nhân sự</div>
                        <h5 class="fw-bold mb-1">Trạng thái phân công xưởng</h5>
                        <p class="text-muted small mb-0">
                            Thông tin chỉ hiển thị nhân sự thuộc xưởng này.
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="chip text-success bg-success-subtle">Kho: <?= $warehouseCount ?></span>
                        <span class="chip text-info bg-info-subtle">Sản xuất: <?= $productionCount ?></span>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="assignment-card h-100">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="icon-circle bg-success-subtle text-success"><i class="bi bi-box-seam"></i></span>
                                    <div>
                                        <div class="text-muted small">Nhân viên kho</div>
                                        <div class="fw-semibold"><?= $warehouseCount ?> người</div>
                                    </div>
                                </div>
                                <span class="badge bg-success-subtle text-success">Kho</span>
                            </div>
                            <ul class="list-unstyled mb-0 assignment-stack">
                                <?php if ($warehouseNames): ?>
                                    <?php foreach ($assignments['nhan_vien_kho'] as $staff): ?>
                                        <li class="assignment-stack-item">
                                            <div>
                                                <div class="fw-semibold"><?= htmlspecialchars($staff['HoTen']) ?></div>
                                                <div class="text-muted small"><?= htmlspecialchars($staff['IdNhanVien']) ?></div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li class="text-muted small fst-italic">Chưa phân công nhân sự kho.</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="assignment-card h-100">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="icon-circle bg-info-subtle text-info"><i class="bi bi-gear"></i></span>
                                    <div>
                                        <div class="text-muted small">Nhân viên sản xuất</div>
                                        <div class="fw-semibold"><?= $productionCount ?> người</div>
                                    </div>
                                </div>
                                <span class="badge bg-info-subtle text-info">Sản xuất</span>
                            </div>
                            <ul class="list-unstyled mb-0 assignment-stack">
                                <?php if ($productionNames): ?>
                                    <?php foreach ($assignments['nhan_vien_san_xuat'] as $staff): ?>
                                        <li class="assignment-stack-item">
                                            <div>
                                                <div class="fw-semibold"><?= htmlspecialchars($staff['HoTen']) ?></div>
                                                <div class="text-muted small"><?= htmlspecialchars($staff['IdNhanVien']) ?></div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li class="text-muted small fst-italic">Chưa phân công nhân sự sản xuất.</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="card mt-4 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
                    <div>
                        <div class="badge bg-secondary-subtle text-secondary mb-2">Nhân sự xưởng</div>
                        <h5 class="fw-bold mb-1">Danh sách nhân sự thuộc xưởng</h5>
                        <p class="text-muted small mb-0">Quản lý xưởng có thể xem và cập nhật phân công nhân sự trong phạm vi xưởng phụ trách.</p>
                    </div>
                    <?php
                    $warehouseCount = count(array_filter($staffList, fn($m) => ($m['role'] ?? '') === 'Kho'));
        $productionCount = count(array_filter($staffList, fn($m) => ($m['role'] ?? '') === 'Sản xuất'));
        ?>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="chip text-primary bg-primary-subtle"><?= count($staffList) ?> nhân sự</span>
                        <span class="chip text-success bg-success-subtle">Kho: <?= $warehouseCount ?></span>
                        <span class="chip text-info bg-info-subtle">Sản xuất: <?= $productionCount ?></span>
                    </div>
                </div>
                <?php if (!empty($staffList)): ?>
                    <div class="row g-2">
                        <?php foreach ($staffList as $member): ?>
                            <div class="col-md-6">
                                <div class="staff-card d-flex justify-content-between align-items-start h-100">
                                    <div>
                                        <div class="fw-semibold"><?= htmlspecialchars($member['name']) ?></div>
                                        <div class="text-muted small"><?= htmlspecialchars($member['id']) ?></div>
                                    </div>
                                    <span class="badge bg-light text-dark"><?= htmlspecialchars($member['role']) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mb-0">Chưa có nhân sự nào được gán cho xưởng.</div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<style>
.assignment-card {
    border: 1px solid #edf1f7;
    border-radius: 12px;
    padding: 16px;
    background: #fff;
}
.assignment-stack {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.assignment-stack-item {
    padding: 10px;
    border: 1px solid #f1f2f6;
    border-radius: 10px;
    background: #fafbff;
}
.icon-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}
.chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border-radius: 999px;
    font-weight: 600;
    font-size: 12px;
    border: 1px solid transparent;
}
.staff-card {
    border: 1px solid #eef2f7;
    border-radius: 12px;
    padding: 12px 14px;
    background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
    box-shadow: 0 4px 14px rgba(17, 38, 146, 0.05);
}
</style>
