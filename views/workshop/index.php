<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Danh sách xưởng sản xuất</h3>
        <p class="text-muted mb-0">Quản lý thông tin cơ bản, công suất và nhân sự của từng xưởng.</p>
    </div>
    <?php $canAssign = $canAssign ?? false; ?>
    <div class="d-flex gap-2">
        <a href="?controller=workshop&action=dashboard" class="btn btn-outline-primary">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard vật tư
        </a>
        <?php if ($canAssign): ?>
            <a href="?controller=workshop&action=create" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>Thêm xưởng
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-primary bg-opacity-10 text-primary"><i class="bi bi-buildings"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Tổng số xưởng</div>
                <div class="fs-3 fw-bold"><?= $summary['total_workshops'] ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-success bg-opacity-10 text-success"><i class="bi bi-lightning-charge"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Công suất tối đa</div>
                <div class="fs-3 fw-bold"><?= number_format($summary['max_capacity'], 0, ',', '.') ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-info bg-opacity-10 text-info"><i class="bi bi-speedometer"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Đang sử dụng</div>
                <div class="fs-3 fw-bold"><?= number_format($summary['current_capacity'], 0, ',', '.') ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-warning bg-opacity-10 text-warning"><i class="bi bi-people"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Nhân sự</div>
                <div class="fs-5 fw-bold">Hiện tại: <?= number_format($summary['workforce']) ?></div>
                <div class="fs-6">Tối đa: <?= number_format($summary['max_workforce']) ?></div>
                <div class="small text-muted">Tỷ lệ sử dụng: <?= $summary['workforce_utilization'] ?>%</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Thông tin chi tiết</h5>
                <span class="text-muted small">Theo dõi tình trạng từng xưởng</span>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>Mã xưởng</th>
                        <th>Tên xưởng</th>
                        <th>Quản lý</th>
                        <th>Công suất</th>
                        <th>Nhân sự (hiện tại / tối đa)</th>
                        <th>Trạng thái</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($workshops as $workshop): ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($workshop['IdXuong']) ?></td>
                            <td><?= htmlspecialchars($workshop['TenXuong']) ?></td>
                            <td><?= htmlspecialchars($workshop['TruongXuong'] ?? 'Chưa phân công') ?></td>
                            <td>
                                <?= number_format($workshop['CongSuatDangSuDung'] ?? $workshop['CongSuatHienTai'] ?? 0, 0, ',', '.') ?>
                                / <?= number_format($workshop['CongSuatToiDa'] ?? 0, 0, ',', '.') ?>
                            </td>
                            <td>
                                <?= number_format($workshop['SoLuongCongNhan'] ?? 0) ?>
                                / <?= number_format($workshop['SlNhanVien'] ?? 0) ?>
                            </td>
                            <td>
                                <?php $status = $workshop['TrangThai'] ?? 'Không xác định'; ?>
                                <?php
                                $badgeClass = 'badge-soft-warning';
                                if ($status === 'Đang hoạt động') {
                                    $badgeClass = 'badge-soft-success';
                                } elseif ($status === 'Bảo trì') {
                                    $badgeClass = 'badge-soft-warning';
                                } elseif ($status === 'Tạm dừng') {
                                    $badgeClass = 'badge-soft-danger';
                                }
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
                            </td>
                            <td class="text-end">
                                <div class="table-actions">
                                    <a class="btn btn-sm btn-outline-secondary" href="?controller=workshop&action=read&id=<?= urlencode($workshop['IdXuong']) ?>">Chi tiết</a>
                                    <a class="btn btn-sm btn-outline-primary" href="?controller=workshop&action=edit&id=<?= urlencode($workshop['IdXuong']) ?>">Sửa</a>
                                    <a class="btn btn-sm btn-outline-danger" href="?controller=workshop&action=delete&id=<?= urlencode($workshop['IdXuong']) ?>" onclick="return confirm('Xác nhận xóa xưởng này?');">Xóa</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card p-4 h-100">
            <h5 class="mb-3">Phân bổ trạng thái</h5>
            <canvas id="workshopStatusChart" height="220"></canvas>
            <ul class="list-unstyled mt-4 mb-0">
                <?php foreach ($statusDistribution as $label => $value): ?>
                    <li class="d-flex justify-content-between py-1 border-bottom">
                        <span><?= htmlspecialchars($label) ?></span>
                        <span class="fw-semibold"><?= $value ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('workshopStatusChart');
    if (!canvas) return;

    const data = <?= json_encode(array_values($statusDistribution)) ?>;
    const labels = <?= json_encode(array_keys($statusDistribution)) ?>;

    if (!data.length) {
        canvas.parentElement.classList.add('d-flex', 'align-items-center', 'justify-content-center', 'text-muted');
        canvas.replaceWith('Chưa có dữ liệu.');
        return;
    }

    const palette = ['#1976d2', '#0d47a1', '#26a69a', '#ffb300', '#ef5350'];

    new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data,
                backgroundColor: labels.map((_, index) => palette[index % palette.length]),
                borderWidth: 0,
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
