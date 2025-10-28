<div class="container mt-4 card p-4">
  <h5>Yêu cầu hỗ trợ</h5>

  <div class="table-responsive mt-4">
    <table class="table table-bordered table-striped align-middle text-nowrap">
      <thead>
        <tr>
          <th>Ngày</th>
          <th>Người gửi</th>
          <th>Trạng thái</th>
          <th>Yêu cầu</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($tickets['tickets']) && is_array($tickets['tickets'])): ?>
          <?php foreach ($tickets['tickets'] as $ticket): ?>
            <tr>
              <td><?php echo htmlspecialchars($ticket['date']); ?></td>
              <td><?php echo htmlspecialchars($ticket['user']); ?></td>
              <td><?php echo htmlspecialchars($ticket['status']); ?></td>
              <td class="text-wrap"><?php echo htmlspecialchars($ticket['request']); ?></td>
              <td>
                <?php if ($ticket['status'] === 'open'): ?>
                  <form method="post" action="?controller=account&action=closeTicket" onsubmit="return confirm('Bạn có chắc chắn muốn đóng yêu cầu này không?');">
                    <input type="hidden" name="ticket_id" value="<?php echo htmlspecialchars($ticket['id'] ?? ''); ?>">
                    <button type="submit" class="btn btn-sm btn-primary">Đóng yêu cầu</button>
                  </form>
                <?php else: ?>
                  <span class="text-muted">Đã đóng</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="4" class="text-center">Không có nhật ký kiểm tra nào được tìm thấy.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

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
