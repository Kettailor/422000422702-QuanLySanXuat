<h2 class="fw-bold mb-3">Kiểm tra đột xuất</h2>
<p>Theo dõi, quản lý các biên bản kiểm tra đột xuất tại các xưởng.</p>

<div class="row mb-4 text-center">
  <div class="col-md-3">
    <div class="card p-3 shadow-sm">
      <h5 class="text-secondary">Tổng biên bản</h5>
      <h3 class="fw-bold text-primary"><?= $summary['total'] ?? 0 ?></h3>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card p-3 shadow-sm">
      <h5 class="text-secondary">Đạt yêu cầu</h5>
      <h3 class="fw-bold text-success"><?= $summary['dat'] ?? 0 ?></h3>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card p-3 shadow-sm">
      <h5 class="text-secondary">Không đạt</h5>
      <h3 class="fw-bold text-danger"><?= $summary['khongdat'] ?? 0 ?></h3>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card p-3 shadow-sm">
      <h5 class="text-secondary">Gần nhất</h5>
      <h6 class="fw-semibold"><?= $summary['ganNhat'] ? htmlspecialchars($summary['ganNhat']) : '—' ?></h6>
    </div>
  </div>
</div>


  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-semibold">Danh sách biên bản</h5>
    <a href="?controller=dotxuat&action=create" class="btn btn-primary">
      <i class="bi bi-plus"></i> Tạo biên bản mới
    </a>
  </div>

  <table class="table table-striped table-hover align-middle">
    <thead class="table-light">
      <tr>
        <th>Mã biên bản</th>
        <th>Ngày kiểm tra</th>
        <th>Kết quả</th>
        <th>Xưởng</th>
        <th>Người kiểm tra</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($reports)): ?>
        <?php foreach ($reports as $r): ?>
          <tr>
            <td><strong><?= htmlspecialchars($r['IdBienBanDanhGiaDX']) ?></strong></td>
            <td><?= htmlspecialchars($r['ThoiGian']) ?></td>
            <td>
              <?php if (str_contains($r['KetQua'], 'Đạt')): ?>
                <span class="badge bg-success"><?= htmlspecialchars($r['KetQua']) ?></span>
              <?php else: ?>
                <span class="badge bg-danger"><?= htmlspecialchars($r['KetQua']) ?></span>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($r['TenXuong']) ?></td>
            <td><?= htmlspecialchars($r['TenNhanVien']) ?></td>
            <td>
              <a href="?controller=dotxuat&action=read&id=<?= urlencode($r['IdBienBanDanhGiaDX']) ?>" class="btn btn-info btn-sm">
  Chi tiết
</a>
<a href="?controller=dotxuat&action=delete&id=<?= urlencode($r['IdBienBanDanhGiaDX']) ?>" class="btn btn-danger btn-sm"
   onclick="return confirm('Xác nhận xóa biên bản này?')">Xóa</a>

            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="6" class="text-center text-muted">Chưa có biên bản nào</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
