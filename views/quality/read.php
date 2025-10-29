<div class="container py-4">

  <?php if (!empty($isReport) && $isReport): ?>
      <h4 class="fw-bold mb-3 text-primary">Biên bản đánh giá thành phẩm</h4>
      <table class="table table-bordered">
        <tr><th>Mã biên bản</th><td><?= htmlspecialchars($report['IdBienBanDanhGiaSP']) ?></td></tr>
        <tr><th>Thời gian</th><td><?= htmlspecialchars($report['ThoiGian']) ?></td></tr>
        <tr><th>Kết quả</th><td>
          <?php if ($report['KetQua'] === 'Đạt'): ?>
              <span class="badge bg-success">Đạt</span>
          <?php else: ?>
              <span class="badge bg-danger">Không đạt</span>
          <?php endif; ?>
        </td></tr>
        <tr><th>Tổng tiêu chí đạt</th><td><?= htmlspecialchars($report['TongTCD']) ?></td></tr>
        <tr><th>Tổng tiêu chí không đạt</th><td><?= htmlspecialchars($report['TongTCKD']) ?></td></tr>
        <tr><th>Mã lô</th><td><?= htmlspecialchars($report['IdLo']) ?></td></tr>
      </table>
      <a href="?controller=quality&action=index" class="btn btn-secondary mt-2">← Quay lại</a>

  <?php elseif (!empty($loInfo)): ?>
      <h4 class="fw-bold mb-3 text-primary">Thông tin lô sản phẩm</h4>
      <table class="table table-bordered">
        <tr><th>Mã lô</th><td><?= htmlspecialchars($loInfo['IdLo']) ?></td></tr>
        <tr><th>Tên lô</th><td><?= htmlspecialchars($loInfo['TenLo'] ?? '-') ?></td></tr>
        <tr><th>Sản phẩm</th><td><?= htmlspecialchars($loInfo['TenSanPham'] ?? '-') ?></td></tr>
        <tr><th>Xưởng</th><td><?= htmlspecialchars($loInfo['TenXuong'] ?? '-') ?></td></tr>
      </table>
      <div class="alert alert-warning mt-3">
        Lô này <strong>chưa được kiểm tra</strong>. Bạn có thể tiến hành đánh giá ngay.
      </div>
      <a href="?controller=quality&action=create&IdLo=<?= urlencode($loInfo['IdLo']) ?>" class="btn btn-primary mt-2">
        Đánh giá ngay
      </a>
      <a href="?controller=quality&action=index" class="btn btn-outline-secondary mt-2">← Quay lại</a>

  <?php else: ?>
      <div class="alert alert-danger">Không tìm thấy thông tin lô.</div>
  <?php endif; ?>

</div>
