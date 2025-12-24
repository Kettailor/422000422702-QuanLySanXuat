<style>
  /* ====== TỔNG THỂ ====== */
  .container {
    max-width: 100%;
  }

  .report-card {
    background: #ffffff;
    border-radius: 14px;
    border: 1px solid #eef1f5;
  }

  /* ====== HEADER ====== */
  .report-header {
    padding: 20px 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #ffffff;
    border-bottom: 1px solid #eef1f5;
  }

  .report-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #0d6efd;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .report-sub {
    font-size: 1rem;
    color: #6c757d;
    margin-top: 4px;
  }

  /* ====== MÃ BIÊN BẢN ====== */
  .report-code {
    text-align: right;
    min-width: 180px;
  }

  .report-code-label {
    font-size: 1rem;
    color: #6c757d;
  }

  .report-code-box {
    font-size: 1.1;
    font-weight: 600;
    color: #212529;
  }

  /* ====== BODY ====== */
  .card-body {
    padding: 20px 22px;
  }

  /* ====== INFO GRID ====== */
  .info-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
  }

  .info-box {
    background: #f8f9fb;
    border-radius: 14px;
    padding: 14px 16px;
  }

  .info-label {
    font-size: 0.8rem;
    color: #6c757d;
  }

  .info-value {
    font-size: 1rem;
    font-weight: 600;
    margin-top: 2px;
  }

  .info-value.text-success {
    color: #198754;
    font-size: 1.2rem;
  }

  .info-value.text-danger {
    color: #dc3545;
    font-size: 1.2rem;
  }

  /* ====== KẾT QUẢ ====== */
  .result-box {
    margin-top: 20px;
    text-align: center;
  }

  .result-badge {
    display: inline-block;
    background: #198754;
    color: #ffffff;
    font-weight: 700;
    font-size: 0.95rem;
    padding: 8px 18px;
    border-radius: 999px;
  }

  .result-fail {
    background: #dc3545;
  }

  /* ====== CARD ẢNH ====== */
  .card.shadow-sm {
    border-radius: 14px;
    border: 1px solid #eef1f5;
    box-shadow: none;
  }

  .card-header.bg-white {
    padding: 14px 18px;
    font-weight: 600;
    border-bottom: 1px solid #eef1f5;
  }

  /* ====== ẢNH ====== */
  .card-body img {
    height: 140px;
    object-fit: cover;
    border-radius: 10px;
  }

  .info-box-group {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
  }

  .info-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 16px;
  }

  /* HÀNG 1: 3 ô */
  .span-2 {
    grid-column: span 2;
  }

  /* HÀNG 2: 2 ô */
  .span-3 {
    grid-column: span 3;
  }

  /* ===== KẾT QUẢ ===== */
  .result-box {
    margin-top: 20px;
    text-align: center;
  }

  .result-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 22px;
    border-radius: 999px;
    font-weight: 700;
    font-size: 1rem;
  }

  .result-pass {
    background: #198754;
    color: #fff;
  }

  .result-fail {
    background: #dc3545;
    color: #fff;
  }
</style>

<div class="container py-4">

  <?php if (!empty($report)): ?>

    <div class="report-card mb-4">

      <div class="report-header">
        <div>
          <div class="report-title">
            <i class="bi bi-clipboard-check"></i>
            Biên bản đánh giá đột xuất
          </div>
          <div class="report-sub">
            Nhân viên kiểm tra:
            <strong><?= htmlspecialchars($report['NhanVienKiemTra']) ?></strong>
          </div>
        </div>

        <div class="report-code">
          <div class="report-code-label">Mã biên bản</div>
          <div class="report-code-box">
            <?= htmlspecialchars($report['IdBienBanDanhGiaDX']) ?>
          </div>
        </div>
      </div>
      <div class="card-body">

        <div class="info-grid">

          <!-- HÀNG 1 -->
          <div class="info-box span-2">
            <div class="info-label">Thời gian</div>
            <div class="info-value">
              <?= date('d/m/Y H:i', strtotime($report['ThoiGian'])) ?>
            </div>
          </div>

          <div class="info-box span-2">
            <div class="info-label">Xưởng</div>
            <div class="info-value"><?= htmlspecialchars($report['TenXuong']) ?></div>
          </div>

          <div class="info-box span-2">
            <div class="info-label">Loại tiêu chí</div>
            <div class="info-value"><?= htmlspecialchars($report['LoaiTieuChi']) ?></div>
          </div>

          <!-- HÀNG 2 -->
          <div class="info-box span-3 text-center">
            <div class="info-label">Tiêu chí đạt</div>
            <div class="info-value text-success fs-4">
              <?= (int) $report['TongTCD'] ?>
            </div>
          </div>

          <div class="info-box span-3 text-center">
            <div class="info-label">Không đạt</div>
            <div class="info-value text-danger fs-4">
              <?= (int) $report['TongTCKD'] ?>
            </div>
          </div>
        </div>
        <div class="result-box">
          <?php if ($report['KetQua'] === 'Đạt'): ?>
            <span class="result-badge result-pass">
              ✔ KẾT QUẢ: ĐẠT
            </span>
          <?php else: ?>
            <span class="result-badge result-fail">
              ✖ KẾT QUẢ: KHÔNG ĐẠT
            </span>
          <?php endif; ?>
        </div>

      </div>
    </div>



    <!-- ẢNH -->
    <div class="card shadow-sm mt-4 border-0">
      <div class="card-header fw-semibold bg-white">
        <i class="bi bi-images me-2"></i> Hình ảnh minh chứng
      </div>

      <div class="card-body">
        <?php if (!empty($images)): ?>
          <div class="row g-3">
            <?php foreach ($images as $img): ?>
              <div class="col-md-3 col-sm-4 col-6">
                <img
                  src="storage/img/bbdgdx/<?= rawurlencode($img) ?>"
                  class="img-fluid rounded border">
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="text-muted mb-0">Không có hình ảnh minh chứng.</p>
        <?php endif; ?>
      </div>
    </div>

    <div class="text-end mt-3 d-flex justify-content-end gap-2">
      <a href="?controller=suddenly&action=index"
        class="btn btn-outline-secondary">
        ← Quay lại
      </a>

      <button type="button"
        class="btn btn-danger"
        id="btnHideReport"
        data-id="<?= htmlspecialchars($report['IdBienBanDanhGiaDX']) ?>">
        🗑 Xóa biên bản
      </button>
    </div>

</div>

<?php else: ?>
  <div class="alert alert-danger">Không tìm thấy dữ liệu biên bản.</div>
<?php endif; ?>

</div>
<script>
  document.getElementById('btnHideReport')?.addEventListener('click', function() {
    const id = this.dataset.id;

    if (!confirm('Bạn có muốn xóa biên bản này không?')) return;

    window.location.href =
      '?controller=suddenly&action=delete&id=' + encodeURIComponent(id);
  });
</script>