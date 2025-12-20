<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<style>
  /* ===== STYLE CHUNG ===== */
  .qa-container {
    font-family: "Inter", sans-serif;
    background: #f9fafc;
  }

  /* ===== DASHBOARD ===== */
  .summary-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 16px;
  }

  .summary-cards .card {
    border-radius: 12px;
    background: #fff;
    box-shadow: 0 1px 4px rgba(0, 0, 0, .08);
    padding: 20px;
    text-align: center;
    transition: .2s;
    position: relative;
    cursor: pointer;
  }

  .summary-cards .card:hover {
    transform: translateY(-3px);
    box-shadow: 0 3px 8px rgba(0, 0, 0, .12);
  }

  .summary-cards h3 {
    font-weight: 700;
    margin-bottom: 6px;
  }

  .summary-cards p {
    color: #6c757d;
    margin: 0;
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
    font-size: .9rem;
    cursor: pointer;
    transition: .15s;
  }

  .filter-btn:hover {
    background: #f1f3f5;
  }

  .filter-btn.active {
    background: #2f6bff;
    color: #fff;
    border-color: #2f6bff;
  }

  /* ===== TABLE ===== */
  .table-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 1px 6px rgba(0, 0, 0, .08);
    padding: 20px;
  }

  .table th {
    color: #6c757d;
    font-weight: 600;
  }

  .table td {
    vertical-align: middle;
  }

  .hidden-row {
    display: none !important;
  }

  /* ===== UI PILL – CHUẨN CHUNG (BUTTON + BADGE) ===== */
  .ui-pill {
    min-width: 84px;
    height: 32px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 0 12px;
    font-size: 0.8rem;
    font-weight: 500;
    line-height: 1;

    border-radius: 8px;
    white-space: nowrap;
  }

  /* ===== ACTION BUTTON ===== */
  .btn-action {
    min-width: 72px;
    /* ↓ từ 84 */
    height: 28px;
    /* ↓ từ 32 */
    padding: 0 8px;

    font-size: 0.75rem;
    /* ↓ từ 0.8 */
    font-weight: 500;
    line-height: 1;

    border-radius: 6px;
    /* nhỏ hơn cho cân */
    border: none;
    text-decoration: none !important;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    white-space: nowrap;
    transition: background 0.2s ease, transform 0.15s ease;
  }

  /* Màu nút */
  .btn-create {
    background-color: #2f6bff;
    color: #fff !important;
  }

  .btn-create:hover {
    background-color: #2556d8;
    transform: translateY(-1px);
  }

  .btn-detail {
    background-color: #6f42c1;
    color: #fff !important;
  }

  .btn-detail:hover {
    background-color: #59339b;
    transform: translateY(-1px);
  }

  .btn-delete {
    background-color: #dc3545;
    color: #fff !important;
  }

  .btn-delete:hover {
    background-color: #b02a37;
    transform: translateY(-1px);
  }

  /* ===== BADGE KẾT QUẢ (KHÔNG ĐÈ BOOTSTRAP .badge) ===== */
  .badge-result {
    min-width: 72px;
    /* khớp nút */
    height: 28px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 0 8px;
    font-size: 0.7rem;
    font-weight: 600;
    line-height: 1;

    border-radius: 6px;
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
  }
</style>
<div class="qa-container">


  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h3 class="fw-bold mb-1">Kiểm tra chất lượng sản phẩm</h3>
      <p class="text-muted mb-0">Theo dõi tiến độ đánh giá và lưu kết quả kiểm tra thành phẩm SV5TOT.</p>
    </div>
    <form class="d-flex" role="search">
      <input id="searchInput" type="text" class="form-control" placeholder="Tìm kiếm lô, sản phẩm...">
    </form>
  </div>

  <!-- Dashboard -->
  <div class="summary-cards">
    <div class="card card-total" onclick="activateAllFilter()">
      <h3><?= $dashboard['total'] ?></h3>
      <p>Tổng lô sản phẩm</p>
      <small class="text-muted">
        Lô đã kiểm tra: <strong><?= $dashboard['passed'] + $dashboard['failed'] ?></strong> &nbsp;|&nbsp;
        Lô chưa kiểm tra: <strong><?= $dashboard['unchecked'] ?></strong>
      </small>
    </div>

    <div class="card card-passed" onclick="applyFilter('passed')">
      <h3><?= $dashboard['passed'] ?></h3>
      <p>Lô đạt yêu cầu</p>
    </div>

    <div class="card card-failed" onclick="applyFilter('failed')">
      <h3><?= $dashboard['failed'] ?></h3>
      <p>Lô không đạt</p>
    </div>
  </div>

  <!-- Bảng + Filter phụ -->
  <div class="table-card">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <h5 class="fw-semibold mb-1">Danh sách lô sản phẩm</h5>
        <div id="filterLabel" class="text-muted" style="font-size:.9rem"></div>
      </div>
    </div>

    <!-- Bộ lọc -->
    <div class="filter-bar">
      <button class="filter-btn active" data-filter="all" onclick="applyFilter('all', this)">Tất cả</button>
      <button class="filter-btn" data-filter="checked" onclick="applyFilter('checked', this)">Lô đã kiểm tra</button>
      <button class="filter-btn" data-filter="unchecked" onclick="applyFilter('unchecked', this)">Lô chưa kiểm tra</button>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle text-nowrap">
        <thead class="table">
          <tr>
            <th class="text-center">Mã Lô</th>
            <th class="text-center">Sản phẩm</th>
            <th class="text-center">Số lượng</th>
            <th class="text-center">Ngày tạo</th>
            <th class="text-center">Xưởng</th>
            <th class="text-center">Kết quả</th>
            <th class="text-center">Hành động</th>
          </tr>
        </thead>
        <tbody id="lotTable">
          <?php if (!empty($listLo)): ?>
            <?php foreach ($listLo as $lo): ?>
              <?php
              $ketqua = trim($lo['KetQua'] ?? '');
              if ($ketqua === 'Đạt') {
                $status = 'passed';
                $checked = '1';
              } elseif ($ketqua === 'Không đạt') {
                $status = 'failed';
                $checked = '1';
              } else {
                $status = 'unchecked';
                $checked = '0';
              }
              ?>
              <tr data-status="<?= $status ?>" data-checked="<?= $checked ?>">
                <td class="fw-semibold"><?= htmlspecialchars($lo['IdLo']) ?></td>
                <td><?= htmlspecialchars($lo['TenSanPham'] ?? '-') ?></td>
                <td><?= htmlspecialchars($lo['SoLuong'] ?? 0) ?></td>
                <td><?= $lo['NgayTao'] ? date('d/m/Y', strtotime($lo['NgayTao'])) : '-' ?></td>
                <td><?= htmlspecialchars($lo['TenXuong'] ?? '-') ?></td>
                <td>
                  <?php if ($ketqua === 'Đạt'): ?>
                    <span class="badge bg-success">Đạt</span>
                  <?php elseif ($ketqua === 'Không đạt'): ?>
                    <span class="badge bg-danger">Không đạt</span>
                  <?php else: ?>
                    <span class="badge bg-secondary">Chưa kiểm tra</span>
                  <?php endif; ?>
                </td>
                <td>
                  <div class="d-flex justify-content-end gap-2">
                    <?php if ($ketqua === '' || $ketqua === null): ?>
                      <a href="?controller=quality&action=create&IdLo=<?= urlencode($lo['IdLo']) ?>"
                        class="btn-action btn-create">Đánh giá</a>
                    <?php endif; ?>

                    <a href="?controller=quality&action=read&id=<?= urlencode($lo['IdLo']) ?>"
                      class="btn-action btn-detail">Chi tiết</a>

                    <?php if (!empty($lo['IdBienBanDanhGiaSP'])): ?>
                      <a href="?controller=quality&action=delete&id=<?= urlencode($lo['IdBienBanDanhGiaSP']) ?>&IdLo=<?= urlencode($lo['IdLo']) ?>"
                        class="btn-action btn-delete btn-delete-trigger"
                        data-id="<?= htmlspecialchars($lo['IdBienBanDanhGiaSP']) ?>"
                        data-lo="<?= htmlspecialchars($lo['IdLo']) ?>">
                        Xóa
                      </a>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center text-muted py-3">Không có lô nào được tìm thấy.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Popup xác nhận xóa -->
<div id="confirmModal" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-3">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title fw-semibold">Xác nhận xóa biên bản</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p id="confirmMessage" class="mb-0"></p>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Xóa</button>
      </div>
    </div>
  </div>
</div>

<script>
  // Filter
  function setActive(btn) {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');
  }

  function applyFilter(type, btn = null) {
    setActive(btn);
    const rows = document.querySelectorAll('#lotTable tr[data-status]');
    const label = document.getElementById('filterLabel');
    const map = {
      all: "",
      passed: "Lô đạt yêu cầu",
      failed: "Lô không đạt yêu cầu",
      checked: "Lô đã kiểm tra",
      unchecked: "Lô chưa kiểm tra"
    };
    rows.forEach(row => {
      const status = row.getAttribute('data-status');
      const checked = row.getAttribute('data-checked');
      let show = true;

      if (type === 'passed') show = (status === 'passed');
      else if (type === 'failed') show = (status === 'failed');
      else if (type === 'unchecked') show = (status === 'unchecked');
      else if (type === 'checked') show = (checked === '1');
      else show = true;

      row.classList.toggle('hidden-row', !show);
    });
    label.textContent = map[type] || '';
  }

  function activateAllFilter() {
    const allBtn = document.querySelector('.filter-btn[data-filter="all"]');
    applyFilter('all', allBtn);
  }

  // Search
  document.getElementById('searchInput').addEventListener('input', function() {
    const kw = this.value.toLowerCase();
    document.querySelectorAll('#lotTable tr[data-status]').forEach(row => {
      const passFilter = !row.classList.contains('hidden-row');
      const match = row.innerText.toLowerCase().includes(kw);
      row.style.display = passFilter && match ? '' : 'none';
    });
  });

  // Modal xác nhận XÓA
  document.querySelectorAll('.btn-delete-trigger').forEach(btn => {
    btn.addEventListener('click', e => {
      e.preventDefault();
      const id = btn.dataset.id || '(chưa có mã)';
      const lo = btn.dataset.lo || '(không rõ)';
      const url = btn.getAttribute('href');

      if (!url || url.endsWith('id=&IdLo=')) {
        alert('Không tìm thấy mã biên bản để xóa!');
        return;
      }

      document.getElementById('confirmMessage').textContent =
        `Bạn có chắc muốn xóa biên bản ${id} của lô ${lo} không?`;

      const confirmBtn = document.getElementById('confirmDeleteBtn');
      confirmBtn.onclick = () => {
        window.location.href = url;
      };

      const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
      modal.show();
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