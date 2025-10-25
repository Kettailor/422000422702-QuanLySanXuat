<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="fw-bold">Quản lý tài khoản người dùng</h1>
  <a class="btn btn-primary" href="?controller=account&action=create">
    Tạo tài khoản mới
  </a>
</div>

<section>
  <h2 class="sr-only">Quản lý tài khoản người dùng section</h2>

  <table class="table table-hover table-bordered">
    <thead>
      <tr>
        <?php foreach ($header as $col): ?>
          <th scope="col"><?= htmlspecialchars($col) ?></th>
        <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>

      <?php foreach ($users['data'] as $user): ?>
        <tr>
          <td><?= htmlspecialchars($user['IdNguoiDung']) ?></td>
          <td><?= htmlspecialchars($user['IdNhanVien']) ?></td>
          <td><?= htmlspecialchars($user['HoTen']) ?></td>
          <td><?= htmlspecialchars($user['TenVaiTro']) ?></td>
          <td><?= htmlspecialchars($user['ChucVu']) ?></td>
          <td><?= htmlspecialchars($user['TrangThai']) ?></td>
          <td class="d-flex gap-4 align-items-center justify-content-center">
            <a href="?controller=account&action=edit&id=<?= htmlspecialchars($user['IdNguoiDung']) ?>" class="btn btn-primary btn-sm">Chỉnh sửa</a>
            <a href="?controller=account&action=delete&id=<?= htmlspecialchars($user['IdNguoiDung']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?');">Xóa</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>

    <tfoot>
      <tr>
        <td colspan="2">
          Số tài khoản đang hoạt động: <?= htmlspecialchars($numberOfActiveUsers) ?>
        </td>
        <td colspan="2">
          Số nhân viên: <?= htmlspecialchars($numberOfActiveEmployees) ?>
        </td>
        <td colspan="2">
          Số nhân viên chưa có tài khoản: <?= htmlspecialchars($numberOfActiveEmployees - $numberOfActiveUsers) ?>
        </td>
        <td colspan="1" class="d-flex justify-content-center align-items-center gap-2">
          <a href="?controller=account&action=index&page=<?= max(1, $users['page'] - 1) ?>&limit=<?= $users['limit'] ?>" class="btn btn-outline-secondary btn-sm<?= ($users['page'] <= 1) ? ' disabled' : '' ?>" <?= ($users['page'] <= 1) ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
            <i class="bi bi-chevron-left"></i><span class="sr-only">Trang trước </span>
          </a>
          <span>Trang <?= htmlspecialchars($users['page']) ?> / <?= htmlspecialchars($users['totalPages']) ?></span>
          <a href="?controller=account&action=index&page=<?= $users['page'] + 1 ?>&limit=<?= $users['limit'] ?>" class="btn btn-outline-secondary btn-sm<?= ($users['page'] >= $users['totalPages']) ? ' disabled' : '' ?>" <?= ($users['page'] >= $users['totalPages']) ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
            <i class="bi bi-chevron-right"></i><span class="sr-only">Trang sau </span>
          </a>
        </td>
      </tr>
    </tfoot>
  </table>
</section>

<?php $this->getFlash(); ?>
