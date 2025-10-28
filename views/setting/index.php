<section class="card row w-100 p-4">
  <h3 class="mb-4">Thông Tin Người Dùng</h3>
  <table class="table table-bordered w-auto">
    <tbody>
      <tr>
        <th>Id Người Dùng</th>
        <td><?php echo htmlspecialchars($user['IdNguoiDung'] ?? ''); ?></td>
      </tr>
      <tr>
        <th>Id Nhân Viên</th>
        <td><?php echo htmlspecialchars($user['IdNhanVien'] ?? ''); ?></td>
      </tr>
      <tr>
        <th>Tên Đăng Nhập</th>
        <td><?php echo htmlspecialchars($user['TenDangNhap'] ?? ''); ?></td>
      </tr>
      <tr>
        <th>Trạng Thái</th>
        <td><?php echo htmlspecialchars($user['TrangThai'] ?? ''); ?></td>
      </tr>
      <tr>
        <th>Vai Trò</th>
        <td><?php echo htmlspecialchars($user['ActualTenVaiTro'] ?? ''); ?></td>
      </tr>
    </tbody>
  </table>
  <div class="d-grid gap-3 mt-4">
    <a class="btn btn-outline-primary btn-lg" href="?controller=setting&action=changePassword">
      Đổi Mật Khẩu
    </a>
    <a class="btn btn-outline-success btn-lg" href="?controller=setting&action=edit">
      Cập Nhật Thông Tin Cá Nhân
    </a>
  </div>
</section>
