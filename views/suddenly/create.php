<?php
// đảm bảo biến $type tồn tại (được truyền từ controller)
$type = $type ?? ($_GET['type'] ?? 'production');
?>
<style>
#criteria-table input[name="MaTieuChi[]"] {
  text-align: center;
  width: 70px;
  font-weight: 600;
}
#criteria-table input[name="TieuChi[]"] {
  min-width: 350px;
  white-space: normal;
  overflow-wrap: break-word;
}
#criteria-table input[name="DiemDat[]"],
#criteria-table input[name="KetQuaTC[]"] {
  text-align: center;
}
#criteria-table input.ket-qua {
  font-weight: 600;
  color: #0d6efd;
}
</style>

<div class="content-header mb-4">
  <h2 class="fw-bold mb-1">
    <?= $type === 'worker' ? 'Tạo biên bản nhân công' : 'Tạo biên bản dây chuyền sản xuất' ?>
  </h2>
  <p class="text-muted mb-0">
    <?= $type === 'worker'
      ? 'Ghi nhận thông tin kiểm tra đột xuất của nhân viên, tác phong và an toàn lao động.'
      : 'Ghi nhận thông tin kiểm tra đột xuất tại dây chuyền, thiết bị hoặc quy trình sản xuất.' ?>
  </p>
</div>

<!-- ✅ BẮT ĐẦU FORM -->
<form action="?controller=suddenly&action=store" method="POST" enctype="multipart/form-data">

  <!-- Truyền loại biên bản (worker / production) -->
<input type="hidden" name="LoaiTieuChi" value="<?= htmlspecialchars($_GET['type'] ?? '') ?>">

  <!-- Thông tin chung -->
  <div class="card shadow-sm border-0 p-4 mb-4">
    <h5 class="fw-bold text-primary mb-3">Thông tin chung</h5>
    <div class="row mb-3">
      <div class="col-md-4">
        <label class="form-label fw-semibold">Mã biên bản</label>
        <input type="text" class="form-control" name="IdBienBanDanhGiaDX"
               value="<?= htmlspecialchars($maBienBan) ?>" readonly>
      </div>

      <div class="col-md-4">
        <label class="form-label fw-semibold">Thời gian kiểm tra</label>
        <input type="datetime-local" name="ThoiGian" class="form-control"
               value="<?= date('Y-m-d\TH:i') ?>">
      </div>

      <div class="col-md-4">
        <label class="form-label fw-semibold">Xưởng</label>
        <select name="IdXuong" class="form-select" required>
          <option value="">-- Chọn xưởng kiểm tra --</option>
          <?php foreach ($xuongList as $x): ?>
            <option value="<?= htmlspecialchars($x['IdXuong']) ?>">
              <?= htmlspecialchars($x['TenXuong']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <label class="form-label fw-semibold">Loại tiêu chí</label>
        <select id="LoaiTieuChi" name="LoaiTieuChi" class="form-select" required>
          <option value="">-- Chọn loại tiêu chí --</option>
          <?php foreach (array_keys($criteriaList) as $key): ?>
            <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($key) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label fw-semibold">Nhân viên kiểm tra</label>
        <select name="IdNhanVien" class="form-select" required>
          <option value="">-- Chọn nhân viên kiểm tra --</option>
          <?php foreach ($nhanVienList as $nv): ?>
            <option value="<?= htmlspecialchars($nv['IdNhanVien']) ?>">
              <?= htmlspecialchars($nv['HoTen']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </div>

  <!-- Tiêu chí kiểm tra -->
  <div class="card shadow-sm border-0 p-4">
    <h5 class="fw-bold text-primary mb-3">Tiêu chí kiểm tra</h5>
    <div class="table-responsive">
      <table class="table align-middle" id="criteria-table">
        <thead class="table-light">
          <tr>
            <th style="width: 70px;">Mã</th>
            <th style="min-width: 350px;">Tiêu chí</th>
            <th style="width: 100px;">Điểm đạt</th>
            <th style="width: 160px;">Ghi chú</th>
            <th style="width: 200px;">Minh chứng</th>
            <th style="width: 120px;">Kết quả</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  <!-- Kết luận -->
  <div class="card shadow-sm border-0 p-4 mt-4">
    <h5 class="fw-bold text-primary mb-3">Kết luận</h5>

    <div class="d-flex flex-wrap gap-3 mb-3">
      <div class="flex-fill bg-light rounded p-3 text-center">
        <div class="text-secondary fw-semibold">Tổng tiêu chí đạt</div>
        <div class="fs-4 fw-bold text-primary" id="countPass">0</div>
      </div>
      <div class="flex-fill bg-light rounded p-3 text-center">
        <div class="text-secondary fw-semibold">Tổng tiêu chí không đạt</div>
        <div class="fs-4 fw-bold text-primary" id="countFail">0</div>
      </div>
      <div class="flex-fill bg-light rounded p-3 text-center">
        <div class="text-secondary fw-semibold">Tổng điểm</div>
        <div class="fs-4 fw-bold text-primary" id="totalScore">0</div>
      </div>
      <div class="flex-fill bg-light rounded p-3 text-center">
        <div class="text-secondary fw-semibold">Trạng thái tổng</div>
        <div class="fs-4 fw-bold" id="overallStatus">—</div>
      </div>
    </div>

    <div class="text-end">
      <a href="?controller=suddenly&action=index" class="btn btn-outline-secondary me-2">Quay lại</a>
      <button type="submit" class="btn btn-primary">Lưu kết quả kiểm tra</button>
    </div>
  </div>
</form>
<!-- KẾT THÚC FORM -->

<script>
const criteriaData = <?= json_encode($criteriaList, JSON_UNESCAPED_UNICODE) ?>;
const loaiSelect = document.getElementById('LoaiTieuChi');
const tableBody = document.querySelector('#criteria-table tbody');

loaiSelect.addEventListener('change', () => {
  const selected = loaiSelect.value.trim();
  tableBody.innerHTML = '';

  if (criteriaData[selected]) {
    criteriaData[selected].forEach(c => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td><input type="text" name="MaTieuChi[]" class="form-control text-center fw-semibold" value="${c[0]}" readonly></td>
        <td><input type="text" name="TieuChi[]" class="form-control text-wrap" value="${c[1]}" readonly></td>
        <td><input type="number" name="DiemDat[]" class="form-control diem-dat text-center" min="0" max="10" ></td>
        <td><input type="text" name="GhiChuTC[]" class="form-control" ></td>
        <td><input type="file" name="FileMinhChung[]" class="form-control"></td>
        <td><input type="text" name="KetQuaTC[]" class="form-control text-center ket-qua fw-semibold" readonly></td>
      `;
      tableBody.appendChild(row);
    });
    attachEvents();
  }
});

function attachEvents() {
  document.querySelectorAll(".diem-dat").forEach(input => {
    input.addEventListener("input", () => {
      let val = parseFloat(input.value);
      if (isNaN(val)) val = 0;
      if (val > 10) val = 10;
      if (val < 0) val = 0;
      input.value = val;

      const ketQua = input.closest("tr").querySelector(".ket-qua");
      if (val >= 9) {
        ketQua.value = "Đạt";
        ketQua.style.color = "#198754";
      } else {
        ketQua.value = "Không đạt";
        ketQua.style.color = "#dc3545";
      }
      updateSummary();
    });
  });
}

function updateSummary() {
  let pass = 0, fail = 0, total = 0;

  document.querySelectorAll(".diem-dat").forEach(input => {
    const val = parseFloat(input.value) || 0;
    total += val;
    const ketQua = input.closest("tr").querySelector(".ket-qua").value;
    if (ketQua === "Đạt") pass++;
    else if (ketQua === "Không đạt") fail++;
  });

  document.getElementById("countPass").textContent = pass;
  document.getElementById("countFail").textContent = fail;
  document.getElementById("totalScore").textContent = Math.round(total);

  const overall = document.getElementById("overallStatus");
  if (pass === 0 && fail === 0) {
    overall.textContent = "—";
    overall.style.color = "#756c7dff";
  } else if (fail === 0) {
    overall.textContent = "Đạt";
    overall.style.color = "#198754";
  } else {
    overall.textContent = "Không đạt";
    overall.style.color = "#dc3545";
  }
}
</script>
