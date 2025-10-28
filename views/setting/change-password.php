<section>
  <div class="mb-4">
    <h3 class="fw-bold">Đổi mật khẩu</h3>
    <p class="text-muted mb-0">Thay đổi mật khẩu tài khoản của bạn</p>
  </div>

  <form class="card p-4" method="post">
    <fieldset class="d-flex flex-column gap-4">
      <div class="form-group">
        <label for="current-password">Mật khẩu hiện tại</label>
        <input type="password" class="form-control" id="current-password" name="current-password" autocomplete="current-password" required>
        <small id="current-password-error" class="form-text text-danger"></small>
      </div>
      <div class="form-group">
        <label for="new-password">Mật khẩu mới</label>
        <input type="password" class="form-control" id="new-password" name="new-password" autocomplete="new-password" required>
        <small id="new-password-error" class="form-text text-danger"></small>
      </div>
      <div class="form-group">
        <label for="confirm-password">Xác nhận mật khẩu mới</label>
        <input type="password" class="form-control" id="confirm-password" name="confirm-password" autocomplete="new-password" required>
        <small id="confirm-password-error" class="form-text text-danger"></small>
      </div>
      <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
    </fieldset>
  </form>
</section>

<script>
  document.querySelector('form').addEventListener('submit', function (e) {
    const currentPassword = document.getElementById('current-password').value;
    const newPassword = document.getElementById('new-password').value;
    const confirmPassword = document.getElementById('confirm-password').value;

    if (!/^(?=.*[A-Za-z])(?=.*\d).{8,}$/.test(newPassword)) {
      e.preventDefault();
      document.getElementById('new-password-error').textContent = 'Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ cái và số.';
    } else document.getElementById('new-password-error').textContent = '';

    if (newPassword !== confirmPassword) {
      e.preventDefault();
      document.getElementById('confirm-password-error').textContent = 'Mật khẩu xác nhận không khớp.';
    } else document.getElementById('confirm-password-error').textContent = '';
  })
</script>
