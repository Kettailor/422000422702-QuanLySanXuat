<form method="post" class="card p-4">
  <fieldset class="d-flex flex-column gap-4">
    <legend>Thêm tiêu chí đánh giá</legend>
    <div class="form-group">
      <label for="criterion">Tên tiêu chí</label>
      <input class="form-control" id="criterion" name="criterion" placeholder="Nhập tên tiêu chí" required>
    </div>
    <div class="form-group">
      <label for="description">Mô tả</label>
      <textarea class="form-control" id="description" name="description" rows="4" placeholder="Mô tả tiêu chí" required></textarea>
    </div>

    <input type="hidden" name="idXuong" value="<?= htmlspecialchars($_GET['id'] ?? '') ?>">
    <button type="submit" class="btn btn-primary">Thêm tiêu chí</button>
  </fieldset>
</form>

<?php $this->getFlash(); ?>
