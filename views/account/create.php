<form method="post" class="d-flex flex-column gap-4">
  <div class="form-group">
    <label for="username">Tên đăng nhập</label>
    <input class="form-control" id="username" name="username" placeholder="Enter username" required>
  </div>
  <div class="form-group">
    <label for="username">Nhân viên</label>
    <select class="form-select" id="employee" name="employee" aria-label="Employee select">
      <?php foreach ($employees as $employee): ?>
        <option value="<?= htmlspecialchars($employee['IdNhanVien']) ?>"><?= htmlspecialchars($employee['HoTen']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="form-group">
  <label for="password">Mật khẩu</label>
    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
