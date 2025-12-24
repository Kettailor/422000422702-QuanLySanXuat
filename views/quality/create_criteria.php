<form method="post" class="card p-4">
  <fieldset class="d-flex flex-column gap-4">
    <legend>Them tieu chi danh gia</legend>
    <div class="form-group">
      <label for="criterion">Tên tieu chi</label>
      <input class="form-control" id="criterion" name="criterion" placeholder="Enter criterion" required>
    </div>
    <div class="form-group">
      <label for="description">Mo ta</label>
      <textarea class="form-control" id="description" name="description" rows="4" placeholder="Mo ta tieu chi" required></textarea>
    </div>

    <input type="hidden" name="idXuong" value="<?= htmlspecialchars($_GET['id'] ?? '') ?>">
    <button type="submit" class="btn btn-primary">Them tieu chi</button>
  </fieldset>
</form>

<?php $this->getFlash(); ?>
