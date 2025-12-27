<div class="card p-4 mb-4">
  <h5 class="fw-bold mb-3">
    Tiêu chí mặc định – <?= htmlspecialchars($tenXuong) ?>
  </h5>

  <?php if (!empty($criterias)): ?>
    <ul class="list-group">
      <?php foreach ($criterias as $tc): ?>
        <li class="list-group-item d-flex gap-3">
          <span class="badge bg-secondary"><?= htmlspecialchars($tc['id']) ?></span>
          <span><?= htmlspecialchars($tc['criterion']) ?></span>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <div class="alert alert-warning mb-0">
      Không tìm thấy tiêu chí mặc định cho xưởng này.
    </div>
  <?php endif; ?>
</div>

<form method="post" class="card p-4">
  <fieldset class="d-flex flex-column gap-4">
    <legend>Thêm tiêu chí đánh giá</legend>

    <div class="form-group">
      <label for="criterion">Tên tiêu chí</label>
      <input class="form-control"
        id="criterion"
        name="criterion"
        placeholder="Nhập tên tiêu chí"
        required>
    </div>

    <input type="hidden" name="idXuong" value="<?= htmlspecialchars($idXuong) ?>">

  </fieldset>
</form>

<?php $this->getFlash(); ?>