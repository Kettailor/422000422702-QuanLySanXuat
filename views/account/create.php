<form method="post" class="card p-4">
  <fieldset class="d-flex flex-column gap-4">
    <legend>Tạo tài khoản người dùng mới</legend>
    <div class="form-group">
      <label for="username">Tên đăng nhập</label>
      <input class="form-control" id="username" name="username" placeholder="Enter username" required>
    </div>
    <div class="form-group">
      <label for="employee">Nhân viên</label>
      <select class="form-select" id="employee" name="employee" aria-label="Employee select" required>
        <?php foreach ($employees as $employee): ?>
          <option value="<?= htmlspecialchars($employee['IdNhanVien']) ?>"><?= htmlspecialchars($employee['HoTen']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label for="role">Vai trò</label>
      <select class="form-select" id="role" name="role" aria-label="Role select" required>
        <?php foreach ($roles as $role): ?>
          <option value="<?= htmlspecialchars($role['IdVaiTro']) ?>"><?= htmlspecialchars($role['TenVaiTro']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <button type="submit" class="btn btn-primary">Tạo tài khoản</button>
  </fieldset>
</form>

<?php $this->getFlash(); ?>
