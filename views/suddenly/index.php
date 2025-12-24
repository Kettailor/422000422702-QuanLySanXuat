<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Tính tổng hợp số liệu dựa trên $listBienBan
$summary = [
    'tong_bien_ban' => count($listBienBan),
    'so_dat' => 0,
    'so_khong_dat' => 0,
    'so_production' => 0,
    'so_worker' => 0,
];

// Danh sách loại production
$productionList = ['Lắp ráp cơ khí', 'Điện tử - bo mạch', 'Đóng gói - tem nhãn', 'Kiểm thử sản phẩm', 'An toàn & vệ sinh'];

foreach ($listBienBan as $r) {
    $loai = trim($r['LoaiTieuChi'] ?? '');
    $ketqua = trim((string) ($r['KetQua'] ?? ''));

    // Kết quả đạt / không đạt
    if ($ketqua === 'Đạt') {
        $summary['so_dat']++;
    } elseif ($ketqua === 'Không đạt') {
        $summary['so_khong_dat']++;
    }

    // Loại biên bản
    if (in_array($loai, $productionList)) {
        $summary['so_production']++;
    } else {
        $summary['so_worker']++;
    }
}
?>

<style>
  /* ===== TOÀN TRANG ===== */
  body {
    overflow-x: hidden;
    background: #f9fafc;
  }

  html,
  body {
    width: 100%;
  }

  * {
    box-sizing: border-box;
  }

  /* ===== WRAPPER / LAYOUT ===== */
  .wrapper,
  .layout {
    display: flex;
    max-width: 100%;
    overflow-x: hidden;
  }

  .qa-container {
    box-sizing: border-box;
    font-family: "Inter", sans-serif;
    background: #f9fafc;
    margin: 0 auto;
  }

  /* ===== DASHBOARD (3 CARD TRÊN) ===== */
  .summary-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 16px;
  }

  .summary-cards .card {
    position: relative;
    text-align: center;
    cursor: pointer;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
    padding: 20px;
    transition: 0.2s;
  }

  .summary-cards .card:hover {
    transform: translateY(-3px);
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.12);
  }

  .summary-cards h3 {
    font-weight: 700;
    margin-bottom: 6px;
  }

  .summary-cards p {
    margin: 0;
    color: #6c757d;
    font-weight: 500;
  }

  .card-total h3 {
    color: #2f6bff;
  }

  .card-passed h3 {
    color: #28a745;
  }

  .card-failed h3 {
    color: #dc3545;
  }

  .summary-cards .card.active {
    box-shadow: 0 0 0 3px rgba(47, 107, 255, 0.25);
    transform: translateY(-2px);
  }

  /* ===== CARD BIÊN BẢN ===== */
  .summary-cards.mt-3 {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 16px;
  }

  .card-type {
    text-decoration: none !important;
    color: inherit;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
    border-top: 4px solid transparent;
    text-align: center;
    padding: 20px;
    transition: 0.2s;
    cursor: pointer;
    height: 140px;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .card-type:hover {
    transform: translateY(-3px);
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.12);
  }

  .card-production {
    border-top-color: #fd7e14;
  }

  .card-worker {
    border-top-color: #4277c1ff;
  }

  .card-type .card-count {
    font-size: 1.6rem;
    font-weight: 700;
    margin: 4px 0 6px;
  }

  .card-production .card-count {
    color: #fd7e14;
  }

  .card-worker .card-count {
    color: #4277c1ff;
  }

  .card-type h3 {
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
  }

  .card-type i {
    font-size: 1.1rem;
  }

  .card-type p {
    font-size: 0.85rem;
    color: #6c757d;
    margin: 0;
  }

  /* ===== FILTER BAR ===== */
  .filter-bar {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin: 6px 0 18px;
  }

  .filter-btn {
    border: 1px solid #e2e6ea;
    background: #fff;
    color: #495057;
    border-radius: 999px;
    padding: 6px 12px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: 0.15s;
  }

  .filter-btn:hover {
    background: #f1f3f5;
  }

  .filter-btn.active {
    background: #2f6bff;
    color: #fff;
    border-color: #2f6bff;
  }

  /* ===== BADGE ===== */
  .badge-type {
    display: inline-block;
    border-radius: 6px;
    padding: 4px 10px;
    font-weight: 600;
    font-size: 0.8rem;
    color: #fff;
    text-align: center;
    white-space: nowrap;
  }

  .badge-type.production {
    background-color: #fd7e14;
  }

  .badge-type.worker {
    background-color: #4277c1ff;
  }

  .badge-result {
    display: inline-block;
    min-width: 72px;
    text-align: center;
    border-radius: 6px;
    padding: 4px 8px;
    font-weight: 600;
    font-size: .8rem;
    color: #fff;
  }

  .badge-result.success {
    background: #28a745;
  }

  .badge-result.failed {
    background: #dc3545;
  }

  .badge-result.unchecked {
    background: #6c757d;
    color: #fff;
  }

  /* ===== NÚT ===== */
  .btn-detail {
    background-color: #6f42c1;
    color: #fff !important;
    border: none;
    border-radius: 8px;
    padding: 4px 12px;
    font-size: 0.85rem;
    font-weight: 500;
    text-decoration: none !important;
    display: inline-block;
    transition: background 0.2s ease, transform 0.15s ease;
  }

  .btn-detail:hover {
    background-color: #59339b;
    transform: translateY(-1px);
  }

  .btn-delete {
    background-color: #dc3545;
    color: #fff !important;
    border: none;
    border-radius: 8px;
    padding: 4px 12px;
    font-size: 0.85rem;
    cursor: pointer;
    transition: background 0.2s ease, transform 0.15s ease;
    text-decoration: none !important;
  }

  .btn-delete:hover {
    background-color: #b02a37;
    transform: translateY(-1px);
  }

  .hidden-row {
    display: none !important;
  }
</style>

<div class="qa-container">
  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h3 class="fw-bold mb-1">Kiểm tra đột xuất</h3>
      <p class="text-muted mb-0">Theo dõi tiến độ đánh giá và lưu kết quả kiểm tra thành phẩm SV5TOT.</p>
    </div>
    <form class="d-flex" role="search">
      <input id="searchInput" type="text" class="form-control" placeholder="Tìm kiếm biên bản...">
    </form>
  </div>

  <!-- Dashboard -->
  <div class="summary-cards">
    <div class="card card-total" onclick="filterByResult('all')">
      <h3><?= $summary['tong_bien_ban'] ?? 0 ?></h3>
      <p>Tổng số biên bản</p>
    </div>
    <div class="card card-passed" onclick="filterByResult('passed')">
      <h3><?= $summary['so_dat'] ?? 0 ?></h3>
      <p>Biên bản đạt</p>
    </div>
    <div class="card card-failed" onclick="filterByResult('failed')">
      <h3><?= $summary['so_khong_dat'] ?? 0 ?></h3>
      <p>Biên bản không đạt</p>
    </div>
  </div>

  <!-- Các loại biên bản -->
  <div class="summary-cards mt-3">
    <a href="?controller=suddenly&action=create&type=production" class="card card-type card-production">
      <div class="card-body">
        <div class="card-header d-flex align-items-center justify-content-center mb-2">
          <i class="bi bi-gear-wide-connected me-2"></i>
          <h3 class="m-0 fw-semibold">Biên bản dây chuyền sản xuất</h3>
        </div>
        <h2 class="card-count"><?= $summary['so_production'] ?? 0 ?></h2>
        <p class="text-muted">Theo dõi, đánh giá quy trình sản xuất và lắp ráp.</p>
      </div>
    </a>

    <a href="?controller=suddenly&action=create&type=worker" class="card card-type card-worker">
      <div class="card-body">
        <div class="card-header d-flex align-items-center justify-content-center mb-2">
          <i class="bi bi-person-badge me-2"></i>
          <h3 class="m-0 fw-semibold">Biên bản nhân công</h3>
        </div>
        <h2 class="card-count"><?= $summary['so_worker'] ?? 0 ?></h2>
        <p class="text-muted">Đánh giá tác phong, an toàn và hiệu suất lao động.</p>
      </div>
    </a>
  </div>

  <!-- Danh sách biên bản -->
  <div class="table-card">
    <h5 class="fw-semibold mb-3">Danh sách biên bản</h5>
    <div class="filter-bar">
      <button class="filter-btn active" data-filter="all">Tất cả</button>
      <button class="filter-btn" data-filter="production">Biên bản dây chuyền sản xuất</button>
      <button class="filter-btn" data-filter="worker">Biên bản nhân công</button>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle text-nowrap" id="lotTable">
        <thead class="table-light">
          <tr>
            <th>Mã biên bản</th>
            <th>Xưởng</th>
            <th>Nhân viên kiểm tra</th>
            <th>Loại tiêu chí</th>
            <th class="text-center">Ngày tạo</th>
            <th class="text-center">Kết quả</th>
            <th class="text-center">Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($listBienBan as $r):
              $loai = trim($r['LoaiTieuChi'] ?? '');
              $typeGroup = in_array($loai, $productionList) ? 'production' : 'worker';
              $badgeClass = $typeGroup;
              $ketqua = trim((string) ($r['KetQua'] ?? ''));
              $status = ($ketqua === 'Đạt') ? 'passed' : (($ketqua === 'Không đạt') ? 'failed' : 'unchecked');
              ?>
            <tr data-type="<?= $typeGroup ?>" data-status="<?= $status ?>">
              <td class="fw-semibold"><?= htmlspecialchars($r['IdBienBanDanhGiaDX']) ?></td>
              <td><?= htmlspecialchars($r['TenXuong'] ?? '—') ?></td>
              <td><?= htmlspecialchars($r['NhanVienKiemTra'] ?? '—') ?></td>
              <td><span class="badge-type <?= $badgeClass ?>"><?= htmlspecialchars($loai ?: '—') ?></span></td>
              <td class="text-center"><?= !empty($r['ThoiGian']) ? date('d/m/Y', strtotime($r['ThoiGian'])) : '—' ?></td>
              <td>
                <?php
                    if ($ketqua === 'Đạt') {
                        echo '<span class="badge-result success">Đạt</span>';
                    } elseif ($ketqua === 'Không đạt') {
                        echo '<span class="badge-result failed">Không đạt</span>';
                    } else {
                        echo '<span class="badge-result unchecked">Chưa kiểm tra</span>';
                    }
              ?>
              </td>
              <td class="text-end">
                <a href="?controller=suddenly&action=read&id=<?= urlencode($r['IdBienBanDanhGiaDX']) ?>" class="btn-action btn-detail me-2">Chi tiết</a>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal xác nhận xóa -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="confirmDeleteLabel">Xác nhận xóa biên bản</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body" id="deleteMessage"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <a id="confirmDeleteBtn" href="#" class="btn btn-danger">Xóa</a>
      </div>
    </div>
  </div>
</div>

<script>
  // Lọc theo loại biên bản
  document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const filter = btn.dataset.filter;
      document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      document.querySelectorAll('#lotTable tbody tr').forEach(row => {
        const type = row.dataset.type;
        row.style.display = (filter === 'all' || type === filter) ? '' : 'none';
      });
    });
  });

  // Tìm kiếm
  document.getElementById('searchInput').addEventListener('input', function() {
    const kw = this.value.toLowerCase();
    document.querySelectorAll('#lotTable tbody tr').forEach(row => {
      row.style.display = row.innerText.toLowerCase().includes(kw) ? '' : 'none';
    });
  });

  // Lọc theo kết quả
  function filterByResult(type) {
    const rows = document.querySelectorAll('#lotTable tr[data-status]');
    rows.forEach(row => {
      const status = row.dataset.status;
      if (type === 'all') row.classList.remove('hidden-row');
      else row.classList.toggle('hidden-row', status !== type);
    });
  }

  // Modal xóa
  document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('confirmDeleteModal');
    const message = document.getElementById('deleteMessage');
    const confirmBtn = document.getElementById('confirmDeleteBtn');

    modal.addEventListener('show.bs.modal', function(event) {
      const button = event.relatedTarget;
      const id = button.getAttribute('data-id');
      const xuong = button.getAttribute('data-xuong');
      message.innerHTML = `Bạn có chắc muốn xóa biên bản <strong>${id}</strong> của xưởng <strong>${xuong}</strong> không?`;
      confirmBtn.href = `?controller=suddenly&action=delete&id=${encodeURIComponent(id)}`;
    });
  });
</script>

<?php if (!empty($_GET['msg'])): ?>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: '<?= ($_GET['type'] ?? 'success') === 'danger' ? 'error' : (($_GET['type'] ?? 'success') === 'warning' ? 'warning' : 'success') ?>',
        title: '<?= addslashes(strip_tags($_GET['msg'])) ?>',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
      });
    });
  </script>
<?php endif; ?>