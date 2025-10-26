<form method="post" class="card p-4">
  <fieldset class="d-flex flex-column gap-4">
    <legend>Chỉnh sửa tài khoản người dùng</legend>

    <div class="form-group">
      <label for="username">Tên đăng nhập</label>
      <input 
      class="form-control" 
      id="username" 
      name="username" 
      placeholder="Enter username" 
      value="<?= htmlspecialchars($user_data['TenDangNhap'] ?? '') ?>"
      required
    />
    </div>
    <div class="form-group">
      <label for="role">Vai trò</label>
      <select 
        class="form-select" 
        id="role" 
        name="role" 
        aria-label="Role select"
      >
        <?php foreach ($roles as $role): ?>
          <option 
            value="<?= htmlspecialchars($role['IdVaiTro']) ?>"
            <?= (isset($user_data['IdVaiTro']) && $user_data['IdVaiTro'] == $role['IdVaiTro']) ? 'selected' : '' ?>
          >
            <?= htmlspecialchars($role['TenVaiTro']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label for="password">Mật khẩu</label>
      <input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
      <small class="form-text text-muted">Để trống nếu không muốn thay đổi mật khẩu.</small>
    </div>
    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
  </fieldset>
</form>

<?php $this->getFlash(); ?>
