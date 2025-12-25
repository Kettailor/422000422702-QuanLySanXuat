<style>
  .card-header h5 {
    letter-spacing: .5px;
  }

  .card-header .text-muted {
    font-size: 1rem;
  }

  .report-header {
    background: linear-gradient(135deg, #0d6efd, #0b5ed7);
    color: #fff;
    font-weight: 700;

  }

  .info-box {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px 16px;
    height: 110%;
  }

  .info-label {
    font-size: 1rem;
    color: #6c757d;
  }

  .info-value {
    font-weight: 700;
    font-size: 1rem;
  }

  .result-badge {
    font-size: 1rem;
    padding: 10px 16px;
    border-radius: 20px;
  }

  .image-thumb {
    height: 160px;
    object-fit: cover;
    transition: transform .2s;
  }

  .image-thumb:hover {
    transform: scale(1.05);
  }

  .report-id-box {
    background: #f1f3f5;
    border: 1px dashed #0d6efd;
    border-radius: 12px;
    padding: 10px 16px;
    font-family: monospace;
    font-size: 1.25rem;
    color: #0d6efd;
  }

  .report-id-label {
    font-size: 1rem;
    color: #6c757d;
    font-family: system-ui;
  }
</style>
<div class="container py-4">



  <?php if (!empty($isReport) && $isReport): ?>

    <!-- ===== BIÊN BẢN ĐÁNH GIÁ ===== -->
    <div class="card shadow-sm mb-4 border-0">

      <!-- HEADER -->
      <div class="card-header bg-white border-bottom">
        <div class="d-flex justify-content-between align-items-center">

          <!-- TRÁI: TIÊU ĐỀ + NGƯỜI LẬP -->
          <div>
            <h5 class="mb-1 fw-semibold text-primary">
              <i class="bi bi-clipboard-check me-2"></i>
              Biên bản đánh giá thành phẩm
            </h5>
            <small class="text-muted">
              Người lập:
              <strong><?= htmlspecialchars($nguoiLap) ?></strong>
            </small>
          </div>

          <!-- PHẢI: MÃ BIÊN BẢN -->
          <div class="text-end">
            <div class="text-muted small">Mã biên bản</div>
            <div class="fw-semibold text-dark">
              <?= htmlspecialchars($report['IdBienBanDanhGiaSP']) ?>
            </div>
          </div>

        </div>
      </div>


      <!-- BODY -->
      <div class="card-body">
        <div class="row g-3">

          <div class="col-md-3">
            <div class="info-box">
              <div class="info-label">Thời gian</div>
              <div class="info-value">
                <?= date('d/m/Y H:i', strtotime($report['ThoiGian'])) ?>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="info-box">
              <div class="info-label">Mã lô</div>
              <div class="info-value">
                <?= htmlspecialchars($report['IdLo']) ?>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="info-box text-center">
              <div class="info-label">Tiêu chí đạt</div>
              <div class="info-value text-success fs-5">
                <?= (int) $report['TongTCD'] ?>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="info-box text-center">
              <div class="info-label">Không đạt</div>
              <div class="info-value text-danger fs-5">
                <?= (int) $report['TongTCKD'] ?>
              </div>
            </div>
          </div>

        </div>

        <!-- KẾT QUẢ -->
        <div class="text-center mt-4">
          <?php if ($report['KetQua'] === 'Đạt'): ?>
            <span class="badge bg-success result-badge">
              ✔ KẾT QUẢ: ĐẠT
            </span>
          <?php else: ?>
            <span class="badge bg-danger result-badge">
              ✖ KẾT QUẢ: KHÔNG ĐẠT
            </span>
          <?php endif; ?>
        </div>
      </div>

    </div>
    <!-- ===== ẢNH MINH CHỨNG ===== -->
    <div class="card shadow-sm mt-4">
      <div class="card-header fw-semibold">
        <i class="bi bi-images me-2"></i>
        Hình ảnh minh chứng
      </div>

      <div class="card-body">
        <?php if (!empty($images)): ?>
          <div class="row g-3">
            <?php foreach ($images as $img): ?>
              <div class="col-md-3">
                <img
                  src="storage/img/bbdgtp/<?= rawurlencode($img) ?>"
                  class="img-fluid rounded border"
                  alt="Ảnh minh chứng">
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="text-muted mb-0">Không có hình ảnh minh chứng.</p>
        <?php endif; ?>
      </div>
    </div>

    <?php if (!empty($isReport) && $isReport): ?>

      <div class="mt-4 d-flex justify-content-end gap-2">
        <a href="?controller=quality&action=index"
          class="btn btn-outline-secondary">
          ← Quay lại
        </a>

      </div>

    <?php endif; ?>



  <?php elseif (!empty($loInfo)): ?>

    <!-- ===== THÔNG TIN LÔ (CHƯA KIỂM TRA) ===== -->
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-secondary text-white fw-semibold">
        Thông tin lô sản phẩm
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-6"><strong>Mã lô:</strong> <?= htmlspecialchars($loInfo['IdLo']) ?></div>
          <div class="col-md-6"><strong>Tên lô:</strong> <?= htmlspecialchars($loInfo['TenLo'] ?? '-') ?></div>
          <div class="col-md-6"><strong>Sản phẩm:</strong> <?= htmlspecialchars($loInfo['TenSanPham'] ?? '-') ?></div>
          <div class="col-md-6"><strong>Xưởng:</strong> <?= htmlspecialchars($loInfo['TenXuong'] ?? '-') ?></div>
        </div>
      </div>
    </div>

    <div class="alert alert-warning">
      Lô này <strong>chưa được kiểm tra</strong>. Bạn có thể tiến hành đánh giá ngay.
    </div>

    <a href="?controller=quality&action=create&IdLo=<?= urlencode($loInfo['IdLo']) ?>"
      class="btn btn-primary me-2">
      Đánh giá ngay
    </a>

    <a href="?controller=quality&action=index" class="btn btn-outline-secondary">
      ← Quay lại
    </a>

  <?php else: ?>

    <div class="alert alert-danger">
      Không tìm thấy thông tin lô hoặc biên bản.
    </div>

  <?php endif; ?>

</div>
<script>
  const STORAGE_KEY = 'hidden_los';

  function getHiddenLos() {
    return JSON.parse(sessionStorage.getItem(STORAGE_KEY) || '[]');
  }

  function setHiddenLos(list) {
    sessionStorage.setItem(STORAGE_KEY, JSON.stringify(list));
  }

</script>
