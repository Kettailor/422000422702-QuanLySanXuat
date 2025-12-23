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
    <h3 class="fw-bold mb-1">Nhật ký hệ thống</h3>
    <p class="text-muted mb-0">Theo dõi các hành động quan trọng và thống kê đăng nhập.</p>
  </div>
  <a href="?controller=dashboard&action=index" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left"></i> Quay lại
  </a>
</div>

<div class="card p-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Hoạt động gần đây</h5>
    <span class="badge bg-light text-dark"><?= count($logs['logs'] ?? []) ?> bản ghi</span>
  </div>

  <?php if (!empty($logs['logs']) && is_array($logs['logs'])): ?>
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>Thời gian</th>
            <th>Mức độ</th>
            <th>Người thực hiện</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($logs['logs'] as $log): ?>
            <?php [$badgeClass, $label] = $resolveBadge($log['level'] ?? null); ?>
            <tr>
              <td class="text-nowrap"><?php echo htmlspecialchars($log['date']); ?></td>
              <td>
                <span class="badge <?= $badgeClass ?>"><?php echo htmlspecialchars($label); ?></span>
                <div class="text-muted small"><?php echo htmlspecialchars($log['level']); ?></div>
              </td>
              <td><?php echo htmlspecialchars($log['actor']); ?></td>
              <td>
                <?php
                  $actionText = (string) ($log['action'] ?? '');
                  $shortText = mb_strimwidth($actionText, 0, 110, '…', 'UTF-8');
                ?>
                <div class="text-muted small"><?= htmlspecialchars($shortText) ?></div>
                <button type="button" class="btn btn-link p-0 small" data-bs-toggle="modal" data-bs-target="#log-detail-<?= htmlspecialchars($log['id'] ?? md5($actionText . ($log['date'] ?? ''))) ?>">
                  Xem chi tiết
                </button>
                <div class="modal fade" id="log-detail-<?= htmlspecialchars($log['id'] ?? md5($actionText . ($log['date'] ?? ''))) ?>" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Chi tiết hành động</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                      </div>
                      <div class="modal-body">
                        <div class="mb-2"><span class="text-muted small">Thời gian:</span> <?= htmlspecialchars($log['date'] ?? '-') ?></div>
                        <div class="mb-2"><span class="text-muted small">Người thực hiện:</span> <?= htmlspecialchars($log['actor'] ?? '-') ?></div>
                        <div class="mb-2"><span class="text-muted small">Mức độ:</span> <?= htmlspecialchars($log['level'] ?? '-') ?></div>
                        <div class="border rounded-3 p-3 bg-light small"><?= nl2br(htmlspecialchars($actionText)) ?></div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="text-muted">Không có nhật ký kiểm tra nào được tìm thấy.</div>
  <?php endif; ?>

  <?php if (!empty($logs['total_pages']) && $logs['total_pages'] > 1): ?>
    <nav class="mt-3">
      <ul class="pagination justify-content-center mb-0">
        <li class="page-item<?php if ($logs['page'] <= 1) {
            echo ' disabled';
        } ?>">
          <a class="page-link" href="?controller=account&action=auditLog&limit=<?php echo $logs['limit'] ?>&page=<?php echo $logs['page'] - 1; ?>" tabindex="-1">
            <i class="bi bi-chevron-left"></i>
            <span class="sr-only">Trước</span>
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
            <span class="sr-only">Sau</span>
          </a>
        </li>
      </ul>
    </nav>
  <?php endif; ?>
</div>

<div class="card p-4 mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h5 class="mb-1">Thống kê đăng nhập</h5>
      <div class="text-muted small">Số lần đăng nhập theo ngày trong khoảng được chọn.</div>
    </div>
  </div>
  <form class="row g-3 mb-4" method="get" action="">
    <input type="hidden" name="controller" value="account">
    <input type="hidden" name="action" value="auditLog">
    <div class="col-md-4">
      <label for="start_date" class="form-label">Từ ngày</label>
      <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $loginLogs['start_date']; ?>">
    </div>
    <div class="col-md-4">
      <label for="end_date" class="form-label">Đến ngày</label>
      <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $loginLogs['end_date'] ; ?>">
    </div>
    <div class="col-md-4 d-flex align-items-end">
      <button type="submit" class="btn btn-primary w-100">Lọc dữ liệu</button>
    </div>
  </form>
  <canvas id="loginAttemptsChart" height="100"></canvas>
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
            'counts' => array_values($loginCounts)
        ]); ?>;

const ctx = document.getElementById('loginAttemptsChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: loginData.labels,
        datasets: [{
            label: 'Số lần đăng nhập',
            data: loginData.counts,
            backgroundColor: 'rgba(54, 162, 235, 0.7)'
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
