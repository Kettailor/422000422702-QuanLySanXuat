<div class="container mt-4 card p-4">
  <h5>Nhật ký kiểm tra tài khoản</h5>
  <table class="table table-bordered table-striped align-middle">
    <thead>
      <tr>
        <th>Ngày</th>
        <th>Mức độ</th>
        <th>Người thực hiện</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($logs['logs']) && is_array($logs['logs'])): ?>
        <?php foreach ($logs['logs'] as $log): ?>
          <tr>
            <td><?php echo htmlspecialchars($log['date']); ?></td>
            <td>
              <span class="badge bg-<?php
                  if ($log['level'] === 'INFO') {
                      echo 'info';
                  } elseif ($log['level'] === 'ERROR') {
                      echo 'danger';
                  } elseif ($log['level'] === 'WARN') {
                      echo 'warning';
                  } elseif ($log['level'] === 'DEBUG') {
                      echo 'secondary';
                  }
            ?>">
                  <?php echo htmlspecialchars($log['level']); ?>
              </span>
            </td>
            <td><?php echo htmlspecialchars($log['actor']); ?></td>
            <td><?php echo htmlspecialchars($log['action']); ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="4" class="text-center">Không có nhật ký kiểm tra nào được tìm thấy.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

  <?php if (!empty($logs['total_pages']) && $logs['total_pages'] > 1): ?>
    <nav>
      <ul class="pagination justify-content-center">
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

<div class="container mt-4 card p-4">
  <h5>Số lần đăng nhập mỗi ngày</h5>
  <form class="row g-3 mb-4" method="get" action="">
    <input type="hidden" name="controller" value="account">
    <input type="hidden" name="action" value="auditLog">
    <div class="col-auto">
      <label for="start_date" class="col-form-label">Từ ngày:</label>
    </div>
    <div class="col-auto">
      <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $loginLogs['start_date']; ?>">
    </div>
    <div class="col-auto">
      <label for="end_date" class="col-form-label">Đến ngày:</label>
    </div>
    <div class="col-auto">
      <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $loginLogs['end_date'] ; ?>">
    </div>
    <div class="col-auto">
      <button type="submit" class="btn btn-primary">Lọc</button>
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

