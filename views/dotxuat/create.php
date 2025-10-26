<div class="container mt-4">
  <h3 class="fw-bold mb-3">Tạo biên bản kiểm tra đột xuất</h3>

  <form action="?controller=dotxuat&action=store" method="post" enctype="multipart/form-data">
    <input type="hidden" name="IdBienBanDanhGiaDX" value="<?= uniqid('BBDX') ?>">

    <!-- Thông tin kiểm tra -->
    <div class="card p-4 mb-4 shadow-sm">
  <h5>Thông tin kiểm tra</h5>
  <div class="row g-3">
    <div class="col-md-4">
      <label class="form-label">Ngày kiểm tra</label>
      <input type="date" name="ThoiGian" class="form-control" value="<?= date('Y-m-d') ?>" required>
    </div>

    <div class="col-md-4">
      <label class="form-label">Xưởng</label>
      <select name="IdXuong" class="form-select" required>
        <option disabled selected>-- Chọn xưởng kiểm tra --</option>
        <?php foreach ($xuongs as $x): ?>
          <option value="<?= htmlspecialchars($x['IdXuong']) ?>">
            <?= htmlspecialchars($x['IdXuong'] . ' - ' . $x['TenXuong']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-4">
      <label class="form-label">Người kiểm tra</label>
      <select name="IdNhanVien" class="form-select" required>
        <option disabled selected>-- Chọn nhân viên kiểm tra --</option>
        <?php foreach ($nhanviens as $nv): ?>
          <option value="<?= htmlspecialchars($nv['IdNhanVien']) ?>">
            <?= htmlspecialchars($nv['HoTen']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
</div>


    <!-- Tiêu chí kiểm tra -->
    <div class="card p-4 mb-4 shadow-sm">
      <h5>Tiêu chí kiểm tra</h5>
      <table class="table" id="criteria-table">
        <thead class="table-light">
          <tr>
            <th>Tiêu chí</th>
            <th>Mức độ</th>
            <th>Kết quả</th>
            <th>Ghi chú</th>
            <th>Minh chứng</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><input type="text" name="TieuChi[]" class="form-control" required></td>
            <td><input type="text" name="MucDo[]" class="form-control"></td>
            <td>
              <select name="KetQuaTC[]" class="form-select">
                <option value="Đạt">Đạt</option>
                <option value="Không đạt">Không đạt</option>
              </select>
            </td>
            <td><input type="text" name="GhiChu[]" class="form-control"></td>
            <td><input type="file" name="HinhAnh[]" class="form-control"></td>
            <td><button type="button" class="btn btn-danger btn-sm remove-row">Xóa</button></td>
          </tr>
        </tbody>
      </table>
      <button type="button" class="btn btn-primary" id="addRow">+ Thêm tiêu chí</button>
    </div>

    <!-- Kết luận -->
    <div class="card p-4 shadow-sm">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Tổng TCD</label>
          <input type="number" name="TongTCD" class="form-control" value="0">
        </div>
        <div class="col-md-4">
          <label class="form-label">Tổng TCKD</label>
          <input type="number" name="TongTCKD" class="form-control" value="0">
        </div>
        <div class="col-md-4">
          <label class="form-label">Kết quả</label>
          <select name="KetQua" class="form-select">
            <option value="Đạt">Đạt</option>
            <option value="Không đạt">Không đạt</option>
          </select>
        </div>
      </div>
      <div class="text-end mt-3">
        <a href="?controller=dotxuat&action=index" class="btn btn-secondary">Quay lại</a>
        <button type="submit" class="btn btn-success">Lưu biên bản</button>
      </div>
    </div>
  </form>
</div>

<script>
document.getElementById('addRow').addEventListener('click', function() {
  const tbody = document.querySelector('#criteria-table tbody');
  const row = document.createElement('tr');
  row.innerHTML = `
    <td><input type="text" name="TieuChi[]" class="form-control" required></td>
    <td><input type="text" name="MucDo[]" class="form-control"></td>
    <td><select name="KetQuaTC[]" class="form-select"><option>Đạt</option><option>Không đạt</option></select></td>
    <td><input type="text" name="GhiChu[]" class="form-control"></td>
    <td><input type="file" name="HinhAnh[]" class="form-control"></td>
    <td><button type="button" class="btn btn-danger btn-sm remove-row">Xóa</button></td>`;
  tbody.appendChild(row);
});
document.addEventListener('click', e => {
  if (e.target.classList.contains('remove-row')) e.target.closest('tr').remove();
});
</script>
