<div class="container mt-4">
  <h3 class="fw-bold mb-3">Chi tiết biên bản kiểm tra đột xuất</h3>

  <div class="card p-4 mb-4 shadow-sm">
    <h5>Thông tin chung</h5>
    <p><strong>Mã biên bản:</strong> <?= htmlspecialchars($report['IdBienBanDanhGiaDX']) ?></p>
    <p><strong>Xưởng:</strong> <?= htmlspecialchars($report['TenXuong']) ?></p>
    <p><strong>Người kiểm tra:</strong> <?= htmlspecialchars($report['HoTen']) ?></p>
    <p><strong>Thời gian:</strong> <?= htmlspecialchars($report['ThoiGian']) ?></p>
    <p><strong>Kết quả:</strong> <?= htmlspecialchars($report['KetQua']) ?></p>
  </div>

  <div class="card p-4 mb-4 shadow-sm">
    <h5>Chi tiết tiêu chí</h5>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Loại tiêu chí</th>
          <th>Tiêu chí</th>
          <th>Điểm ĐG</th>
          <th>Ghi chú</th>
          <th>Minh chứng</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($details as $d): ?>
          <tr>
            <td><?= htmlspecialchars($d['LoaiTieuChi']) ?></td>
            <td><?= htmlspecialchars($d['TieuChi']) ?></td>
            <td><?= htmlspecialchars($d['DiemDG']) ?></td>
            <td><?= htmlspecialchars($d['GhiChu']) ?></td>
            <td>
              <?php if (!empty($d['HinhAnh'])): ?>
                <a href="uploads/<?= htmlspecialchars($d['HinhAnh']) ?>" target="_blank">Xem ảnh</a>
              <?php else: ?>
                Không có
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="text-end">
    <a href="?controller=dotxuat&action=index" class="btn btn-secondary">Quay lại</a>
  </div>
</div>
