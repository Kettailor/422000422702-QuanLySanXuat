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
          <th>Phản hồi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($tickets['tickets']) && is_array($tickets['tickets'])): ?>
          <?php foreach ($tickets['tickets'] as $ticket): ?>
            <tr>
              <td><?php echo htmlspecialchars($ticket['date']); ?></td>
              <td><?php echo htmlspecialchars($ticket['user']); ?></td>
              <td>
                <div class="fw-semibold"><?php echo htmlspecialchars($ticket['status']); ?></div>
                <?php if (!empty($ticket['role'])): ?>
                  <div class="text-muted small"><?php echo htmlspecialchars($ticket['role']); ?></div>
                <?php endif; ?>
              </td>
              <td class="text-wrap">
                <div class="fw-semibold mb-1"><?php echo htmlspecialchars($ticket['request']); ?></div>
                <?php if (!empty($ticket['response'])): ?>
                  <div class="text-muted small">Phản hồi trước: <?php echo htmlspecialchars($ticket['response']); ?></div>
                <?php endif; ?>
              </td>
              <td>
                <form method="post" action="?controller=admin&action=closeTicket" class="d-flex flex-column gap-2">
                  <input type="hidden" name="ticket_id" value="<?php echo htmlspecialchars($ticket['id'] ?? ''); ?>">
                  <textarea name="response" class="form-control form-control-sm" rows="2" placeholder="Nhập phản hồi"><?php echo htmlspecialchars($ticket['response'] ?? ''); ?></textarea>
                  <select name="status" class="form-select form-select-sm">
                    <option value="open" <?php echo ($ticket['status'] ?? '') === 'open' ? 'selected' : ''; ?>>Đang mở</option>
                    <option value="processing" <?php echo ($ticket['status'] ?? '') === 'processing' ? 'selected' : ''; ?>>Đang xử lý</option>
                    <option value="answered" <?php echo ($ticket['status'] ?? '') === 'answered' ? 'selected' : ''; ?>>Đã phản hồi</option>
                    <option value="close" <?php echo ($ticket['status'] ?? '') === 'close' ? 'selected' : ''; ?>>Đã đóng</option>
                  </select>
                  <button type="submit" class="btn btn-sm btn-primary">Cập nhật</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" class="text-center">Không có yêu cầu hỗ trợ nào được tìm thấy.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <?php if (!empty($tickets['total_pages']) && $tickets['total_pages'] > 1): ?>
    <nav>
      <ul class="pagination justify-content-center">
        <li class="page-item<?php if ($tickets['page'] <= 1) {
            echo ' disabled';
        } ?>">
        <a class="page-link" href="?controller=admin&action=ticket&limit=<?php echo $tickets['limit'] ?>&page=<?php echo $tickets['page'] - 1; ?>" tabindex="-1">
          <i class="bi bi-chevron-left"></i>
          <span class="sr-only">Trước</span>
        </a>
        </li>
        <?php for ($i = 1; $i <= $tickets['total_pages']; $i++): ?>
          <li class="page-item<?php if ($i == $tickets['page']) {
              echo ' active';
          } ?>">
            <a class="page-link" href="?controller=admin&action=ticket&limit=<?php echo $tickets['limit'] ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
          </li>
        <?php endfor; ?>
        <li class="page-item<?php if ($tickets['page'] >= $tickets['total_pages']) {
            echo ' disabled';
        } ?>">
        <a class="page-link" href="?controller=admin&action=ticket&limit=<?php echo $tickets['limit'] ?>&page=<?php echo $tickets['page'] + 1; ?>">
          <i class="bi bi-chevron-right"></i>
          <span class="sr-only">Sau</span>
        </a>
        </li>
      </ul>
    </nav>
  <?php endif; ?>
</div>
