<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-bold">Quản lý tài khoản người dùng</h3>
    <p class="text-muted mb-0">Quản lý tài khoản người dùng hệ thống</p>
  </div>
  <a class="btn btn-primary" href="?controller=account&action=create">
    Tạo tài khoản mới
  </a>
</div>

<section class="card p-4">
  <h2 class="sr-only">Quản lý tài khoản người dùng section</h2>

  <div class="overflow-x-auto">
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
            <td>
              <div class="row g-2">
                <div class="col col-auto">
                  <a href="?controller=account&action=edit&id=<?= htmlspecialchars($user['IdNguoiDung']) ?>" class="btn btn-primary btn-sm">Chỉnh sửa</a>
                </div>
                <div class="col col-auto">
                  <a href="?controller=account&action=suspense&id=<?= htmlspecialchars($user['IdNguoiDung']) ?>" class="btn <?= $user['TrangThai'] === 'Hoạt động' ? 'btn-warning' : 'btn-success' ?> btn-sm" onclick="return confirm('Bạn có chắc chắn muốn thay đổi trạng thái tài khoản này?');">
                    <?= $user['TrangThai'] === 'Hoạt động' ? 'Tạm ngưng' : 'Kích hoạt' ?>
                  </a>
                </div>
                <div class="col">
                  <a href="?controller=account&action=delete&id=<?= htmlspecialchars($user['IdNguoiDung']) ?>" class="btn btn-danger btn-sm<?= $user['TrangThai'] === 'Hoạt động' ? ' disabled' : '' ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?');" <?= $user['TrangThai'] === 'Hoạt động' ? 'tabindex="-1" aria-disabled="true"' : '' ?>>Xóa</a>
                </div>
              </div>
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
              <i class="bi bi-chevron-left"></i><span class="sr-only">Trang trước</span>
            </a>
            <span>Trang <?= htmlspecialchars($users['page']) ?> / <?= htmlspecialchars($users['totalPages']) ?></span>
            <a href="?controller=account&action=index&page=<?= $users['page'] + 1 ?>&limit=<?= $users['limit'] ?>" class="btn btn-outline-secondary btn-sm<?= ($users['page'] >= $users['totalPages']) ? ' disabled' : '' ?>" <?= ($users['page'] >= $users['totalPages']) ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
              <i class="bi bi-chevron-right"></i><span class="sr-only">Trang sau</span>
            </a>
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
</section>

<?php $this->getFlash(); ?>
