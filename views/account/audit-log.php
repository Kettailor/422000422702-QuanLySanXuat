<?php
$logs = $logs ?? ['logs' => [], 'total_pages' => 0, 'page' => 1, 'limit' => 10];
$loginLogs = $loginLogs ?? ['start_date' => '', 'end_date' => '', 'data' => []];

$resolveBadge = static function (?string $level): array {
    return match ($level) {
        'ERROR' => ['bg-danger', 'Lỗi'],
        'WARN' => ['bg-warning text-dark', 'Cảnh báo'],
        'DEBUG' => ['bg-secondary', 'Gỡ lỗi'],
        default => ['bg-info', 'Thông tin'],
    };
};
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-bold mb-1">Nhật ký hoạt động</h3>
    <p class="text-muted mb-0">Theo dõi các hành động hệ thống và thống kê đăng nhập theo ngày.</p>
  </div>
  <a href="?controller=dashboard&action=index" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left"></i> Quay lại
  </a>
</div>

<div class="row g-4">
  <div class="col-xl-8">
    <div class="card p-4 h-100">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Hoạt động gần đây</h5>
        <span class="badge bg-light text-dark">Trang <?= htmlspecialchars((string) ($logs['page'] ?? 1)) ?></span>
      </div>

      <?php if (!empty($logs['logs']) && is_array($logs['logs'])): ?>
        <div class="d-flex flex-column gap-3">
          <?php foreach ($logs['logs'] as $index => $log): ?>
            <?php [$badgeClass, $label] = $resolveBadge($log['level'] ?? null); ?>
            <?php
              $actionText = (string) ($log['action'] ?? '');
              $shortText = mb_strimwidth($actionText, 0, 120, '…', 'UTF-8');
              $collapseId = 'log-detail-' . ($log['id'] ?? ($logs['page'] ?? 1) . '-' . $index);
              ?>
            <div class="border rounded-4 p-3 bg-white">
              <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div>
                  <div class="fw-semibold mb-1"><?= htmlspecialchars($log['actor'] ?? '-') ?></div>
                  <div class="text-muted small"><?= htmlspecialchars($log['date'] ?? '-') ?></div>
                </div>
                <span class="badge <?= $badgeClass ?> align-self-start"><?= htmlspecialchars($label) ?></span>
              </div>
              <div class="mt-2 text-muted small"><?= htmlspecialchars($shortText) ?></div>
              <button class="btn btn-link p-0 small mt-2" data-bs-toggle="collapse" data-bs-target="#<?= htmlspecialchars($collapseId) ?>" aria-expanded="false">
                Xem chi tiết
              </button>
              <div class="collapse mt-2" id="<?= htmlspecialchars($collapseId) ?>">
                <div class="border rounded-3 bg-light p-3 small">
                  <?= nl2br(htmlspecialchars($actionText)) ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="text-muted">Không có nhật ký nào được tìm thấy.</div>
      <?php endif; ?>

      <?php if (!empty($logs['total_pages']) && $logs['total_pages'] > 1): ?>
        <nav class="mt-4">
          <ul class="pagination justify-content-center flex-wrap gap-1 mb-0">
            <li class="page-item<?php if ($logs['page'] <= 1) {
                echo ' disabled';
            } ?>">
              <a class="page-link" href="?controller=account&action=auditLog&limit=<?php echo $logs['limit'] ?>&page=<?php echo $logs['page'] - 1; ?>" tabindex="-1">
                <i class="bi bi-chevron-left"></i>
              </a>
            </li>
            <?php for ($i = 1; $i <= $logs['total_pages']; $i++): ?>
              <li class="page-item<?php if ($i == $logs['page']) {
                  echo ' active';
              } ?>">
                <a class="page-link" href="?controller=account&action=auditLog&limit=<?php echo $logs['limit'] ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
              </li>
            <?php endfor; ?>
            <li class="page-item<?php if ($logs['page'] >= $logs['total_pages']) {
                echo ' disabled';
            } ?>">
              <a class="page-link" href="?controller=account&action=auditLog&limit=<?php echo $logs['limit'] ?>&page=<?php echo $logs['page'] + 1; ?>">
                <i class="bi bi-chevron-right"></i>
              </a>
            </li>
          </ul>
        </nav>
      <?php endif; ?>
    </div>
  </div>

  <div class="col-xl-4">
    <div class="card p-4 h-100">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h5 class="mb-1">Thống kê đăng nhập</h5>
          <div class="text-muted small">Lọc theo khoảng thời gian.</div>
        </div>
      </div>
      <form class="row g-3 mb-4" method="get" action="">
        <input type="hidden" name="controller" value="account">
        <input type="hidden" name="action" value="auditLog">
        <div class="col-12">
          <label for="start_date" class="form-label">Từ ngày</label>
          <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $loginLogs['start_date']; ?>">
        </div>
        <div class="col-12">
          <label for="end_date" class="form-label">Đến ngày</label>
          <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $loginLogs['end_date'] ; ?>">
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary w-100">Lọc dữ liệu</button>
        </div>
      </form>
      <canvas id="loginAttemptsChart" height="160"></canvas>
    </div>
  </div>
</div>
<?php
$loginCounts = [];
if (!empty($loginLogs['data']) && is_array($loginLogs['data'])) {
    foreach ($loginLogs['data'] as $log) {
        $day = $log['day'];
        if (!isset($loginCounts[$day])) {
            $loginCounts[$day] = 0;
        }
        $loginCounts[$day]++;
    }
}
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const loginData = <?php echo json_encode([
    'labels' => array_keys($loginCounts),
    'counts' => array_values($loginCounts),
]); ?>;

const ctx = document.getElementById('loginAttemptsChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: loginData.labels,
        datasets: [{
            label: 'Số lần đăng nhập',
            data: loginData.counts,
            backgroundColor: 'rgba(54, 162, 235, 0.7)',
            borderRadius: 6
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true }
        },
        plugins: {
            legend: { display: false }
        }
    }
});
</script>
