<div class="container py-4" style="background:#f8f9fb; min-height:100vh;">

  <?php if (!empty($report)): ?>
      <h4 class="fw-bold mb-4" style="color:#2F6BFF;">Biên bản đánh giá đột xuất</h4>

      <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
          <table class="table table-bordered mb-0 align-middle" style="table-layout: fixed;">
            <colgroup>
              <col style="width: 30%;">
              <col style="width: 70%;">
            </colgroup>
            <tbody>
              <tr>
                <th class="bg-light">Mã biên bản</th>
                <td><?= htmlspecialchars($report['IdBienBanDanhGiaDX']) ?></td>
              </tr>
              <tr>
                <th class="bg-light">Xưởng</th>
                <td><?= htmlspecialchars($report['TenXuong'] ?? '-') ?></td>
              </tr>
              <tr>
                <th class="bg-light">Nhân viên kiểm tra</th>
                <td><?= htmlspecialchars($report['NhanVienKiemTra'] ?? '-') ?></td>
              </tr>
              <tr>
                <th class="bg-light">Loại tiêu chí</th>
                <td><?= htmlspecialchars($report['LoaiTieuChi'] ?? '-') ?></td>
              </tr>
              <tr>
                <th class="bg-light">Kết quả</th>
                <td>
                  <?php if (($report['KetQua'] ?? '') === 'Đạt'): ?>
                    <span class="badge bg-success px-3 py-2">Đạt</span>
                  <?php else: ?>
                    <span class="badge bg-danger px-3 py-2">Không đạt</span>
                  <?php endif; ?>
                </td>
              </tr>
              <tr>
                <th class="bg-light">Ngày tạo</th>
                <td><?= !empty($report['ThoiGian']) ? date('Y-m-d H:i:s', strtotime($report['ThoiGian'])) : '-' ?></td>
              </tr>
              <tr>
            </tbody>
          </table>
        </div>
      </div>

      <a href="?controller=suddenly&action=index" 
         class="btn btn-secondary mt-4" 
         style="background:#5c636a; border:none;">
        ← Quay lại
      </a>

  <?php else: ?>
      <div class="alert alert-danger">Không tìm thấy dữ liệu biên bản.</div>
      <a href="?controller=suddenly&action=index" class="btn btn-outline-secondary mt-3">← Quay lại</a>
  <?php endif; ?>

</div>

<style>
.table th, .table td {
  vertical-align: middle !important;
  padding: 10px 14px;
  font-size: 0.95rem;
}
.table th {
  background: #f9fafc;
  font-weight: 600;
  color: #333;
}
.badge {
  font-size: 0.9rem;
}
</style>
