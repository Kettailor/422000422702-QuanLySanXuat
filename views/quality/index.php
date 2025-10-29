<style>
/* ===== STYLE CHUNG ===== */
.qa-container{font-family:"Inter",sans-serif;background:#f9fafc}

/* ===== DASHBOARD ===== */
.summary-cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-bottom:16px}
.summary-cards .card{border-radius:12px;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.08);padding:20px;text-align:center;transition:.2s;position:relative;cursor:pointer}
.summary-cards .card:hover{transform:translateY(-3px);box-shadow:0 3px 8px rgba(0,0,0,.12)}
.summary-cards h3{font-weight:700;margin-bottom:6px}
.summary-cards p{color:#6c757d;margin:0;font-weight:500}
.card-total h3{color:#2f6bff}
.card-passed h3{color:#28a745}
.card-failed h3{color:#dc3545}

/* ===== FILTER BAR ===== */
.filter-bar{display:flex;gap:8px;flex-wrap:wrap;margin:6px 0 18px}
.filter-btn{border:1px solid #e2e6ea;background:#fff;color:#495057;border-radius:999px;padding:6px 12px;font-size:.9rem;cursor:pointer;transition:.15s}
.filter-btn:hover{background:#f1f3f5}
.filter-btn.active{background:#2f6bff;color:#fff;border-color:#2f6bff}

/* ===== TABLE ===== */
.table-card{background:#fff;border-radius:12px;box-shadow:0 1px 6px rgba(0,0,0,.08);padding:20px}
.table th{color:#6c757d;font-weight:600}
.table td{vertical-align:middle}
.hidden-row{display:none!important}

/* ===== BUTTONS ===== */
.btn-action{border-radius:8px;padding:4px 12px;font-size:.85rem;text-decoration:none;transition:.2s}
.btn-create{background:#2f6bff;color:#fff!important}
.btn-create:hover{background:#2556d8}

/* đổi màu CHI TIẾT -> tím */
.btn-detail{background:#6f42c1;color:#fff!important}
.btn-detail:hover{background:#59339b}

/* badge kết quả */
.badge{border-radius:8px;padding:6px 10px;font-weight:600}
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
    <div class="card card-total" onclick="applyFilter('all')">
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

    <!-- Bộ lọc bổ sung: ĐÃ KIỂM TRA / CHƯA KIỂM TRA -->
    <div class="filter-bar">
      <button class="filter-btn active" data-filter="all" onclick="applyFilter('all', this)">Tất cả</button>
      <button class="filter-btn" data-filter="checked" onclick="applyFilter('checked', this)">Lô đã kiểm tra</button>
      <button class="filter-btn" data-filter="unchecked" onclick="applyFilter('unchecked', this)">Lô chưa kiểm tra</button>
      <button class="filter-btn" data-filter="passed" onclick="applyFilter('passed', this)">Lô đạt</button>
      <button class="filter-btn" data-filter="failed" onclick="applyFilter('failed', this)">Lô không đạt</button>
    </div>

    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-light">
          <tr>
            <th>Mã Lô</th>
            <th>Sản phẩm</th>
            <th>Số lượng</th>
            <th>Ngày tạo</th>
            <th>Xưởng</th>
            <th>Kết quả</th>
            <th class="text-end">Hành động</th>
          </tr>
        </thead>
        <tbody id="lotTable">
          <?php if (!empty($listLo)): ?>
            <?php foreach ($listLo as $lo): ?>
              <?php
                $ketqua = trim($lo['KetQua'] ?? '');
                if ($ketqua === 'Đạt')      { $status = 'passed';   $checked = '1'; }
                elseif ($ketqua === 'Không đạt'){ $status = 'failed';   $checked = '1'; }
                else                        { $status = 'unchecked';$checked = '0'; }
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
                <td class="text-end">
                  <a href="?controller=quality&action=create&IdLo=<?= urlencode($lo['IdLo']) ?>" class="btn-action btn-create me-2">Đánh giá</a>
                  <a href="?controller=quality&action=read&id=<?= urlencode($lo['IdLo']) ?>" class="btn-action btn-detail">Chi tiết</a>
                  <!-- Nút XÓA đã bị loại bỏ theo yêu cầu -->
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="7" class="text-center text-muted py-3">Không có lô nào được tìm thấy.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
function setActive(btn){
  document.querySelectorAll('.filter-btn').forEach(b=>b.classList.remove('active'));
  if(btn) btn.classList.add('active');
}

function applyFilter(type, btn=null){
  setActive(btn);
  const rows = document.querySelectorAll('#lotTable tr[data-status]');
  const label = document.getElementById('filterLabel');

  rows.forEach(row=>{
    const status  = row.getAttribute('data-status');     // passed | failed | unchecked
    const checked = row.getAttribute('data-checked');    // "1" | "0"
    let show = true;

    if(type === 'passed')      show = (status === 'passed');
    else if(type === 'failed') show = (status === 'failed');
    else if(type === 'unchecked') show = (status === 'unchecked');
    else if(type === 'checked')   show = (checked === '1');
    else show = true;

    row.classList.toggle('hidden-row', !show);
  });

  const map = {
    all: '', 
    checked: 'Đang hiển thị: Lô đã kiểm tra',
    unchecked: 'Đang hiển thị: Lô chưa kiểm tra',
    passed: 'Đang hiển thị: Lô đạt yêu cầu',
    failed: 'Đang hiển thị: Lô không đạt'
  };
  label.textContent = map[type] || '';
}

// search kết hợp với filter
document.getElementById('searchInput').addEventListener('input', function(){
  const kw = this.value.toLowerCase();
  document.querySelectorAll('#lotTable tr[data-status]').forEach(row=>{
    const passFilter = !row.classList.contains('hidden-row');
    const match = row.innerText.toLowerCase().includes(kw);
    row.style.display = passFilter && match ? '' : 'none';
  });
});
</script>
