<?php
$orderStats = $orderStats ?? ['total_orders' => 0, 'pending_orders' => 0, 'total_revenue' => 0, 'completed_orders' => 0];
$payrollSummary = $payrollSummary ?? ['total_amount' => 0, 'pending' => 0];
$workshopSummary = $workshopSummary ?? ['utilization' => 0, 'workforce' => 0];
$pendingPayrolls = $pendingPayrolls ?? [];
?>

<div class="row g-4">
    <div class="col-xl-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-primary bg-opacity-10 text-primary"><i class="bi bi-bag-check"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Đơn hàng SV5TOT</div>
                <div class="fs-3 fw-bold text-center"><?= number_format($orderStats['total_orders']) ?></div>
                <div class="small text-muted">Chờ xử lý: <?= number_format($orderStats['pending_orders']) ?> đơn</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-success bg-opacity-10 text-success"><i class="bi bi-currency-exchange"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Doanh thu bàn phím SV5TOT</div>
                <div class="fs-3 fw-bold text-center"><?= number_format($orderStats['total_revenue'], 0, ',', '.') ?> đ</div>
                <div class="small text-muted">Đơn hoàn thành: <?= number_format($orderStats['completed_orders']) ?></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-info bg-opacity-10 text-info"><i class="bi bi-wallet2"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Quỹ lương dây chuyền SV5TOT</div>
                <div class="fs-3 fw-bold text-center"><?= number_format($payrollSummary['total_amount'], 0, ',', '.') ?> đ</div>
                <div class="small text-muted">Bảng SV5TOT chờ duyệt: <?= number_format($payrollSummary['pending']) ?> bảng</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card metric-card">
            <div class="icon-wrap bg-warning bg-opacity-10 text-warning"><i class="bi bi-gear-wide-connected"></i></div>
            <div>
                <div class="text-muted text-uppercase small">Hiệu suất xưởng SV5TOT</div>
                <div class="fs-3 fw-bold text-center"><?= $workshopSummary['utilization'] ?>%</div>
                <div class="small text-muted">Nhân sự SV5TOT: <?= number_format($workshopSummary['workforce']) ?> người</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-xl-8">
        <div class="card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Doanh thu & chi phí lương SV5TOT theo tháng</h5>
                <span class="text-muted small">So sánh dòng tiền SV5TOT 6 tháng gần nhất</span>
            </div>
            <canvas id="financeChart" height="160"></canvas>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card p-4 h-100">
            <h5 class="mb-3">Thông báo vận hành SV5TOT</h5>
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
        <div class="card p-4 h-100">
            <div class="d-flex justify-content-between mb-3">
                <h5 class="mb-0">Lịch sản xuất bàn phím SV5TOT</h5>
                <a href="?controller=plan&action=index" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>Mã kế hoạch</th>
                        <th>Hạng mục SV5TOT</th>
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
    <div class="col-xl-3">
        <div class="card p-4 h-100">
            <h5 class="mb-3">Năng lực vận hành dây chuyền SV5TOT</h5>
            <ul class="list-unstyled mb-0">
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span>Ngày làm việc chuẩn</span>
                    <span class="fw-semibold"><?= $stats['totalWorkingDays'] ?></span>
                </li>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span>Tỷ lệ nhân sự SV5TOT đang hoạt động</span>
                    <span class="fw-semibold"><?= $stats['participationRate'] ?></span>
                </li>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span>Kế hoạch SV5TOT hoàn thành</span>
                    <span class="fw-semibold"><?= $stats['completedPlans'] ?></span>
                </li>
                <li class="d-flex justify-content-between py-2">
                    <span>Thông báo mới từ xưởng</span>
                    <span class="fw-semibold"><?= $stats['newNotifications'] ?></span>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card p-4 h-100">
            <h5 class="mb-3">Bảng lương chờ xử lý</h5>
            <?php if (!$pendingPayrolls): ?>
                <div class="text-muted">Không có bảng lương nào cần duyệt.</div>
            <?php else: ?>
                <ul class="list-unstyled mb-0">
                    <?php foreach ($pendingPayrolls as $payroll): ?>
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <?php
                            $monthLabel = $payroll['ThangNam'] ?? '';
                            if (preg_match('/^(\d{4})-(\d{2})$/', (string) $monthLabel, $monthMatches)) {
                                $monthLabel = $monthMatches[2] . '/' . $monthMatches[1];
                            } elseif (preg_match('/^(\d{4})(\d{2})$/', (string) $monthLabel, $monthMatches)) {
                                $monthLabel = $monthMatches[2] . '/' . $monthMatches[1];
                            } elseif (preg_match('/^(\d{2})\/(\d{4})$/', (string) $monthLabel, $monthMatches)) {
                                $monthLabel = $monthMatches[1] . '/' . $monthMatches[2];
                            }
                            ?>
                            <div>
                                <div class="fw-semibold"><?= htmlspecialchars($payroll['HoTen']) ?></div>
                                <div class="small text-muted">Tháng <?= htmlspecialchars($monthLabel) ?></div>
                            </div>
                            <span class="badge bg-light text-dark"><?= number_format($payroll['TongThuNhap'], 0, ',', '.') ?> đ</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <a href="?controller=salary&action=index" class="btn btn-sm btn-outline-primary mt-3">Quản lý bảng lương</a>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-xl-6">
        <div class="card p-4 h-100">
            <h5 class="mb-3">Đánh giá chất lượng bàn phím SV5TOT</h5>
            <?php if ($qualitySummary): ?>
                <div class="row text-center">
                    <div class="col">
                        <div class="fs-1 fw-bold text-primary"><?= $qualitySummary['tong_bien_ban'] ?? 0 ?></div>
                        <div class="text-muted">Biên bản QA</div>
                    </div>
                    <div class="col">
                        <div class="fs-1 fw-bold text-success"><?= $qualitySummary['so_dat'] ?? 0 ?></div>
                        <div class="text-muted">Đạt SV5TOT</div>
                    </div>
                    <div class="col">
                        <div class="fs-1 fw-bold text-danger"><?= $qualitySummary['so_khong_dat'] ?? 0 ?></div>
                        <div class="text-muted">Không đạt SV5TOT</div>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-muted">Chưa có dữ liệu đánh giá chất lượng bàn phím SV5TOT.</div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card p-4 h-100">
            <h5 class="mb-3">Đơn hàng SV5TOT gần đây</h5>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const financeCanvas = document.getElementById('financeChart');
    if (!financeCanvas) return;

    const revenueRaw = <?= json_encode($monthlyRevenue) ?>;
    const payrollRaw = <?= json_encode($payrollTrend) ?>;

    const monthSet = new Set();
    revenueRaw.forEach(item => monthSet.add(item.thang));
    payrollRaw.forEach(item => monthSet.add(item.thang));

    const labels = Array.from(monthSet).sort();
    const revenueMap = {};
    const payrollMap = {};

    revenueRaw.forEach(item => { revenueMap[item.thang] = parseFloat(item.tong_doanh_thu); });
    payrollRaw.forEach(item => { payrollMap[item.thang] = parseFloat(item.tong_chi); });

    if (!labels.length) {
        financeCanvas.parentElement.classList.add('d-flex', 'align-items-center', 'justify-content-center', 'text-muted');
        financeCanvas.replaceWith('Chưa có dữ liệu tài chính.');
        return;
    }

    const revenueData = labels.map(label => revenueMap[label] ?? 0);
    const payrollData = labels.map(label => payrollMap[label] ?? 0);

    new Chart(financeCanvas, {
        data: {
            labels,
            datasets: [
                {
                    type: 'line',
                    label: 'Doanh thu đơn hàng SV5TOT',
                    data: revenueData,
                    borderColor: '#1976d2',
                    backgroundColor: 'rgba(25,118,210,0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#0d47a1'
                },
                {
                    type: 'bar',
                    label: 'Chi phí lương',
                    data: payrollData,
                    backgroundColor: 'rgba(240, 98, 146, 0.45)',
                    borderRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => value.toLocaleString('vi-VN')
                    }
                }
            }
        }
    });
});
</script>
