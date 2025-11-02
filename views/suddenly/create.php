<?php
$type = $type ?? ($_GET['type'] ?? 'production');
?>
<style>
  #criteria-table th,
  #criteria-table td {
    vertical-align: middle;
  }

  #criteria-table th:nth-child(1),
  #criteria-table td:nth-child(1) {
    width: 80px;
    text-align: center;
  }

  #criteria-table th:nth-child(2),
  #criteria-table td:nth-child(2) {
    min-width: 320px;
  }

  #criteria-table th:nth-child(3),
  #criteria-table td:nth-child(3) {
    width: 100px;
    text-align: center;
  }

  #criteria-table th:nth-child(4),
  #criteria-table td:nth-child(4) {
    width: 160px;
  }

  #criteria-table th:nth-child(5),
  #criteria-table td:nth-child(5) {
    width: 200px;
  }

  #criteria-table th:nth-child(6),
  #criteria-table td:nth-child(6) {
    width: 120px;
    text-align: center;
  }

  .alert-message {
    display: none;
    margin-bottom: 15px;
    animation: fadeIn 0.3s ease-in-out;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>
<div class="content-header mb-4">
  <h2 class="fw-bold mb-1">
    <?= $type === 'worker' ? 'Tạo biên bản nhân công' : 'Tạo biên bản dây chuyền sản xuất' ?>
  </h2>
</div>

<form id="suddenlyForm" action="?controller=suddenly&action=store" method="POST" enctype="multipart/form-data">
  <div id="formMessage" class="alert alert-danger alert-message"></div>
  <input type="hidden" name="LoaiTieuChiHidden" value="<?= htmlspecialchars($type) ?>">

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
        <select name="IdXuong" class="form-select">
          <option value="">-- Chọn xưởng kiểm tra --</option>
          <?php foreach ($xuongList as $x): ?>
            <option value="<?= htmlspecialchars($x['IdXuong']) ?>"><?= htmlspecialchars($x['TenXuong']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <label class="form-label fw-semibold">Loại tiêu chí</label>
        <select id="LoaiTieuChi" name="LoaiTieuChi" class="form-select">
          <option value="">-- Chọn loại tiêu chí --</option>
          <?php foreach (array_keys($criteriaList) as $key): ?>
            <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($key) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label fw-semibold">Nhân viên kiểm tra</label>
        <select name="IdNhanVien" class="form-select">
          <option value="">-- Chọn nhân viên kiểm tra --</option>
          <?php foreach ($nhanVienList as $nv): ?>
            <option value="<?= htmlspecialchars($nv['IdNhanVien']) ?>"><?= htmlspecialchars($nv['HoTen']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </div>

  <div class="card shadow-sm border-0 p-4">
    <h5 class="fw-bold text-primary mb-3">Tiêu chí kiểm tra</h5>
    <div class="table-responsive">
      <table class="table align-middle" id="criteria-table">
        <thead class="table-light">
          <tr>
            <th>Mã</th>
            <th>Tiêu chí</th>
            <th>Điểm đạt</th>
            <th>Ghi chú</th>
            <th>Minh chứng</th>
            <th>Kết quả</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  <!-- Phần kết luận -->
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

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const criteriaData = <?= json_encode($criteriaList, JSON_UNESCAPED_UNICODE) ?>;
    const loaiSelect = document.getElementById('LoaiTieuChi');
    const tableBody = document.querySelector('#criteria-table tbody');
    const messageBox = document.getElementById('formMessage');

    function showMessage(msg, type = 'danger') {
      messageBox.textContent = msg;
      messageBox.className = 'alert alert-' + type + ' alert-message';
      messageBox.style.display = 'block';
      messageBox.scrollIntoView({
        behavior: 'smooth',
        block: 'center'
      });
      setTimeout(() => {
        messageBox.style.display = 'none';
      }, 5000);
    }

    loaiSelect.addEventListener('change', () => {
      const selected = loaiSelect.value.trim();
      tableBody.innerHTML = '';
      if (criteriaData[selected]) {
        criteriaData[selected].forEach(c => {
          const row = document.createElement('tr');
          row.innerHTML = `
          <td><input type="text" name="MaTieuChi[]" class="form-control text-center fw-semibold" value="${c[0]}" readonly></td>
          <td><input type="text" name="TieuChi[]" class="form-control" value="${c[1]}" readonly></td>
          <td><input type="text" name="DiemDat[]" maxlength="2" class="form-control diem-dat text-center" placeholder="1-10"></td>
          <td><input type="text" name="GhiChuTC[]" class="form-control ghi-chu" placeholder="Ghi chú..."></td>
          <td><input type="file" name="FileMinhChung[]" class="form-control file-proof" accept="image/jpeg,image/png"></td>
          <td><input type="text" name="KetQuaTC[]" class="form-control text-center ket-qua fw-semibold" readonly></td>`;
          tableBody.appendChild(row);
          attachEvents(row);
        });
      }
    });

    function attachEvents(row) {
      const inputDiem = row.querySelector('.diem-dat');
      const ketQua = row.querySelector('.ket-qua');
      const ghiChu = row.querySelector('.ghi-chu');

      inputDiem.addEventListener('keydown', e => {
        const allow = ['Backspace', 'Tab', 'ArrowLeft', 'ArrowRight', 'Delete'];
        if (!/[0-9]/.test(e.key) && !allow.includes(e.key)) e.preventDefault();
      });

      inputDiem.addEventListener('input', () => {
        let val = Number(inputDiem.value);
        if (isNaN(val)) return;
        if (val > 10) {
          val = 10;
          inputDiem.value = 10;
        }
        if (val < 1 && inputDiem.value !== '') {
          val = 1;
          inputDiem.value = 1;
        }
        if (val >= 9) {
          ketQua.value = 'Đạt';
          ketQua.style.color = '#28a745';
        } else if (val >= 1) {
          ketQua.value = 'Không đạt';
          ketQua.style.color = '#dc3545';
        } else {
          ketQua.value = '';
          ketQua.style.color = '';
        }
        updateSummary();
      });

      // tô viền đỏ nếu có ký tự đặc biệt (bao gồm / và \)
      ghiChu.addEventListener('input', () => {
        if (/[#@\$%<>\{\}\[\];\/\\]/.test(ghiChu.value)) {
          ghiChu.style.borderColor = '#dc3545';
        } else {
          ghiChu.style.borderColor = '';
        }
      });
    }

    function updateSummary() {
      let pass = 0,
        fail = 0,
        total = 0;
      const inputs = document.querySelectorAll('.diem-dat');
      inputs.forEach(inp => {
        const val = Number(inp.value);
        if (!isNaN(val) && inp.value.trim() !== '') {
          total += val;
          if (val >= 9) pass++;
          else fail++;
        }
      });
      document.getElementById('countPass').textContent = pass;
      document.getElementById('countFail').textContent = fail;
      document.getElementById('totalScore').textContent = total;
      const overall = document.getElementById('overallStatus');
      if (inputs.length === 0) {
        overall.textContent = '—';
        overall.style.color = '#6c757d';
      } else if (fail === 0 && pass > 0) {
        overall.textContent = 'Đạt';
        overall.style.color = '#28a745';
      } else {
        overall.textContent = 'Không đạt';
        overall.style.color = '#dc3545';
      }
    }

    document.getElementById('suddenlyForm').addEventListener('submit', e => {
      e.preventDefault();
      const loai = document.getElementById('LoaiTieuChi');
      const xuong = document.querySelector('select[name="IdXuong"]');
      const nhanVien = document.querySelector('select[name="IdNhanVien"]');
      const diemInputs = document.querySelectorAll('.diem-dat');
      const ghiChuInputs = document.querySelectorAll('.ghi-chu');
      const fileInputs = document.querySelectorAll('.file-proof');

      // regex mở rộng thêm / và \
      const invalidNote = /[#@\$%<>\{\}\[\];\/\\]/;
      let valid = true;

      if (!loai.value) {
        showMessage('⚠️ Yêu cầu chọn Loại tiêu chí.', 'warning');
        loai.focus();
        valid = false;
      } else if (!xuong.value) {
        showMessage('⚠️ Yêu cầu chọn Xưởng kiểm tra.', 'warning');
        xuong.focus();
        valid = false;
      } else if (!nhanVien.value) {
        showMessage('⚠️ Yêu cầu chọn Nhân viên kiểm tra.', 'warning');
        nhanVien.focus();
        valid = false;
      }

      // 🔴 kiểm tra ghi chú có ký tự đặc biệt khi bấm Lưu
      if (valid && ghiChuInputs.length > 0) {
        for (let note of ghiChuInputs) {
          if (invalidNote.test(note.value)) {
            showMessage('🔴 Ghi chú chứa kí tự không hợp lệ, nhập lại Ghi chú.', 'danger');
            note.focus();
            valid = false;
            break;
          }
        }
      }

      if (valid && diemInputs.length > 0) {
        for (let inp of diemInputs) {
          const val = Number(inp.value);
          if (inp.value.trim() === '' || isNaN(val) || val < 1 || val > 10) {
            showMessage('⚠️ Điểm đạt phải là số từ 1 đến 10.', 'warning');
            inp.focus();
            valid = false;
            break;
          }
        }
      }

      if (valid && fileInputs.length > 0) {
        for (let file of fileInputs) {
          if (file.files.length === 0) {
            showMessage('⚠️ Yêu cầu tải ảnh minh chứng cho tất cả tiêu chí.', 'warning');
            file.focus();
            valid = false;
            break;
          }
        }
      }

      if (valid) e.target.submit();
    });
  });
</script>