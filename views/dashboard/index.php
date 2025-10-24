<div class="row g-4">
    <div class="col-xl-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-primary bg-opacity-10 text-primary"><i class="bi bi-calendar-event"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Tổng ngày làm</div>
                <div class="fs-3 fw-bold"><?= $stats['totalWorkingDays'] ?></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-success bg-opacity-10 text-success"><i class="bi bi-people"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Nhân sự tham gia</div>
                <div class="fs-3 fw-bold"><?= $stats['participationRate'] ?></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-info bg-opacity-10 text-info"><i class="bi bi-check2-circle"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Hoàn thành</div>
                <div class="fs-3 fw-bold"><?= $stats['completedPlans'] ?></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-warning bg-opacity-10 text-warning"><i class="bi bi-bell"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Thông báo mới</div>
                <div class="fs-3 fw-bold"><?= $stats['newNotifications'] ?></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-xl-8">
        <div class="card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Hoạt động trong tháng</h5>
                <span class="text-muted small">Tổng quan công việc</span>
            </div>
            <canvas id="orderChart" height="160"></canvas>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card p-4 h-100">
            <h5 class="mb-3">Thông báo quan trọng</h5>
            <div class="timeline">
                <?php foreach ($activities as $activity): ?>
                    <div class="timeline-item">
                        <div class="fw-semibold"><?= htmlspecialchars($activity['HanhDong']) ?></div>
                        <div class="text-muted small"><?= date('d/m/Y H:i', strtotime($activity['ThoiGian'])) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-xl-6">
        <div class="card p-4">
            <div class="d-flex justify-content-between mb-3">
                <h5 class="mb-0">Lịch làm việc</h5>
                <a href="?controller=plan&action=index" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>Mã kế hoạch</th>
                        <th>Sản phẩm</th>
                        <th>Trạng thái</th>
                        <th>Thời hạn</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($plans as $plan): ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($plan['IdKeHoachSanXuat']) ?></td>
                            <td><?= htmlspecialchars($plan['YeuCau'] ?? $plan['TenThanhThanhPhanSP'] ?? 'N/A') ?></td>
                            <td>
                                <?php $status = $plan['TrangThai']; ?>
                                <span class="badge <?= $status === 'Hoàn thành' ? 'badge-soft-success' : ($status === 'Đang thực hiện' ? 'badge-soft-warning' : 'badge-soft-danger') ?>">
                                    <?= htmlspecialchars($status) ?>
                                </span>
                            </td>
                            <td><?= $plan['ThoiGianKetThuc'] ? date('d/m/Y', strtotime($plan['ThoiGianKetThuc'])) : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card p-4 h-100">
            <h5 class="mb-3">Đánh giá chất lượng</h5>
            <?php if ($qualitySummary): ?>
                <div class="row text-center">
                    <div class="col">
                        <div class="fs-1 fw-bold text-primary"><?= $qualitySummary['tong_bien_ban'] ?? 0 ?></div>
                        <div class="text-muted">Biên bản</div>
                    </div>
                    <div class="col">
                        <div class="fs-1 fw-bold text-success"><?= $qualitySummary['so_dat'] ?? 0 ?></div>
                        <div class="text-muted">Đạt</div>
                    </div>
                    <div class="col">
                        <div class="fs-1 fw-bold text-danger"><?= $qualitySummary['so_khong_dat'] ?? 0 ?></div>
                        <div class="text-muted">Không đạt</div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="mt-4">
                <h6 class="fw-semibold mb-2">Đơn hàng gần đây</h6>
                <?php foreach ($orders as $order): ?>
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <div>
                            <div class="fw-semibold"><?= htmlspecialchars($order['IdDonHang']) ?> - <?= htmlspecialchars($order['TenKhachHang']) ?></div>
                            <div class="text-muted small">Ngày: <?= date('d/m/Y', strtotime($order['NgayLap'])) ?></div>
                        </div>
                        <span class="badge bg-light text-dark"><?= number_format($order['TongTien'], 0, ',', '.') ?> đ</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('orderChart');
    if (!ctx) return;
    const labels = <?= json_encode(array_column($monthlyRevenue, 'thang')) ?>.reverse();
    const dataValues = <?= json_encode(array_map('floatval', array_column($monthlyRevenue, 'tong_doanh_thu'))) ?>.reverse();

    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Doanh thu đơn hàng',
                data: dataValues,
                fill: true,
                borderColor: '#1976d2',
                backgroundColor: 'rgba(25,118,210,0.1)',
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#0d47a1'
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { callback: value => value.toLocaleString('vi-VN') } }
            }
        }
    });
});
</script>
