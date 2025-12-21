<?php
$type = $type ?? ($_GET['type'] ?? 'production');
?>
<style>
  #criteria-table {
    table-layout: fixed;
  }

  /* ===== CELL ALIGN ===== */
  #criteria-table td {
    vertical-align: middle;
  }

  #criteria-table thead th {
    vertical-align: bottom;
    /* CHỐT: header nằm thấp */
    padding-bottom: 6px;
    /* ép sát input */
  }

  /* ===== MÃ ===== */
  #criteria-table th:nth-child(1),
  #criteria-table td:nth-child(1) {
    width: 90px;
    text-align: center;
    white-space: nowrap;
  }

  /* ===== TIÊU CHÍ ===== */
  #criteria-table th:nth-child(2),
  #criteria-table td:nth-child(2) {
    width: 400px;
  }

  /* ===== ĐIỂM ĐẠT ===== */
  #criteria-table th:nth-child(3),
  #criteria-table td:nth-child(3) {
    width: 100px;
    text-align: center;
  }

  /* ===== GHI CHÚ ===== */
  #criteria-table th:nth-child(4),
  #criteria-table td:nth-child(4) {
    width: 220px;
    text-align: left;
  }

  /* ĐẨY CHỮ "GHI CHÚ" SANG PHẢI NHẸ */
  #criteria-table thead th:nth-child(4) {
    padding-left: 100px;
  }


  /* ===== KẾT QUẢ ===== */
  #criteria-table th:nth-child(5),
  #criteria-table td:nth-child(5) {
    width: 130px;
    text-align: center;
  }

  /* ===== TEXT TIÊU CHÍ ===== */
  #criteria-table input[name="TieuChi[]"] {
    white-space: normal;
    line-height: 1.4;
  }


  /* ===== ALERT + SECTION TITLE GIỮ NGUYÊN ===== */
  .alert-message {
    display: none;
    margin-bottom: 15px;
    animation: fadeIn 0.3s ease-in-out;
  }

  /* ===== THU NHỎ Ô KẾT LUẬN (ĐÚNG HTML HIỆN TẠI) ===== */

  /* Container 4 ô */
  .card .d-flex.flex-wrap.mb-3 {
    gap: 12px;
    /* nhỏ hơn gap-3 mặc định */
  }

  /* Từng ô kết luận */
  .card .d-flex.flex-wrap.mb-3>.flex-fill {
    padding: 8px 10px !important;
    /* ghi đè p-3 của Bootstrap */
    min-height: 68px;
    /* ép thấp lại */
  }

  /* Tiêu đề trong ô */
  .card .d-flex.flex-wrap.mb-3>.flex-fill .text-secondary {
    font-size: 0.8rem;
    margin-bottom: 2px;
  }

  /* Giá trị số */
  .card .d-flex.flex-wrap.mb-3>.flex-fill .fs-4 {
    font-size: 1.25rem !important;
    line-height: 1.2;
  }

  .section-title {
    color: #0d6efd;
    font-weight: 700;
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

  <div class="card shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4">
    <h5 class="fw-bold section-title mb-3">Thông tin chung</h5>
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

  <div class="card shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4">
    <h5 class="fw-bold section-title mb-3">Tiêu chí kiểm tra</h5>
    <div class="table-responsive">
      <table class="table align-middle" id="criteria-table">
        <thead class="table-light">
          <tr>
            <th>Mã</th>
            <th>Tiêu chí</th>
            <th>Điểm đạt</th>
            <th>Ghi chú</th>
            <th>Kết quả</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  <div class="card shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4">
    <h5>Minh chứng hình ảnh</h5>
    <p class="text-muted mb-2">Bạn có thể tải lên một hoặc nhiều hình ảnh minh chứng.</p>

    <input
      type="file"
      name="FileMinhChungPicker"
      class="form-control"
      accept="image/jpeg,image/png"
      multiple>

    <div id="preview-list" class="mt-2" style="font-size:14px;"></div>
  </div>


  <!-- Phần kết luận -->
  <div class="card shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mb-4 shadow-sm border-0 p-4 mt-4">
    <h5 class="fw-bold section-title mb-3">Kết luận</h5>
    <div class="d-flex flex-wrap gap-3 mb-3">
      <div class="flex-fill bg-light rounded p-3 text-center">
        <div class="text-secondary fw-semibold">Tổng tiêu chí đạt</div>
        <div class="fs-4 fw-bold section-title" id="countPass">0</div>
      </div>
      <div class="flex-fill bg-light rounded p-3 text-center">
        <div class="text-secondary fw-semibold">Tổng tiêu chí không đạt</div>
        <div class="fs-4 fw-bold section-title" id="countFail">0</div>
      </div>
      <div class="flex-fill bg-light rounded p-3 text-center">
        <div class="text-secondary fw-semibold">Tổng điểm</div>
        <div class="fs-4 fw-bold section-title" id="totalScore">0</div>
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

    /* ======================
       DATA + DOM
    ====================== */
    const criteriaData = <?= json_encode($criteriaList, JSON_UNESCAPED_UNICODE) ?>;
    const loaiSelect = document.getElementById('LoaiTieuChi');
    const tableBody = document.querySelector('#criteria-table tbody');
    const messageBox = document.getElementById('formMessage');
    const form = document.getElementById('suddenlyForm');

    const fileInput = document.querySelector('input[name="FileMinhChungPicker"]');
    const previewList = document.getElementById('preview-list');
    let fileStore = [];

    /* ======================
       MESSAGE
    ====================== */
    function showMessage(msg, type = 'danger') {
      messageBox.textContent = msg;
      messageBox.className = 'alert alert-' + type + ' alert-message';
      messageBox.style.display = 'block';
      messageBox.scrollIntoView({
        behavior: 'smooth',
        block: 'center'
      });
      setTimeout(() => messageBox.style.display = 'none', 5000);
    }

    /* ======================
       LOAD TIÊU CHÍ
    ====================== */
    loaiSelect.addEventListener('change', () => {
      tableBody.innerHTML = '';
      const selected = loaiSelect.value.trim();

      if (!criteriaData[selected]) return;

      criteriaData[selected].forEach(c => {
        const row = document.createElement('tr');
        row.innerHTML = `
        <td><input type="text" name="MaTieuChi[]" class="form-control text-center fw-semibold" value="${c[0]}" readonly></td>
        <td><input type="text" name="TieuChi[]" class="form-control" value="${c[1]}" readonly></td>
        <td><input type="text" name="DiemDat[]" class="form-control diem-dat text-center" ></td>
        <td><input type="text" name="GhiChuTC[]" class="form-control ghi-chu" placeholder="Ghi chú..."></td>
        <td><input type="text" name="KetQuaTC[]" class="form-control text-center ket-qua fw-semibold" readonly></td>
      `;
        tableBody.appendChild(row);
        attachEvents(row);
      });
    });

    /* ======================
       EVENT MỖI DÒNG
    ====================== */
    function attachEvents(row) {
      const diem = row.querySelector('.diem-dat');
      const ketQua = row.querySelector('.ket-qua');
      const ghiChu = row.querySelector('.ghi-chu');

      /* ===== CHẶN TỪ PHÍM ===== */
      diem.addEventListener('keydown', e => {
        const allowKeys = [
          'Backspace', 'Tab', 'ArrowLeft', 'ArrowRight', 'Delete'
        ];

        // chỉ cho 0–9
        if (!/[0-9]/.test(e.key) && !allowKeys.includes(e.key)) {
          e.preventDefault();
        }
      });

      /* ===== CHẶN PASTE + ÉP GIÁ TRỊ ===== */
      diem.addEventListener('input', () => {
        // CHỈ GIỮ LẠI SỐ NGUYÊN
        diem.value = diem.value.replace(/[^0-9]/g, '');

        let val = Number(diem.value);

        if (isNaN(val)) return;

        if (val > 10) val = diem.value = 10;
        if (val < 1 && diem.value !== '') val = diem.value = 1;

        if (diem.value === '') {
          ketQua.value = '';
          ketQua.style.color = '';
          updateSummary();
          return;
        }

        if (val >= 9) {
          ketQua.value = "Đạt";
          ketQua.style.color = "#28a745";
        } else {
          ketQua.value = "Không đạt";
          ketQua.style.color = "#dc3545";
        }

        updateSummary();
      });

      /* ===== GHI CHÚ – CHẶN KÝ TỰ ĐẶC BIỆT ===== */
      ghiChu.addEventListener('input', () => {
        ghiChu.value = ghiChu.value.replace(/[@\?\[\],\{\}\|\\\"~]/g, '');
      });
    }


    /* ======================
       SUMMARY
    ====================== */
    function updateSummary() {
      let pass = 0,
        fail = 0,
        total = 0;

      document.querySelectorAll('.diem-dat').forEach(inp => {
        const v = Number(inp.value);
        if (!isNaN(v) && inp.value.trim() !== '') {
          total += v;
          v >= 9 ? pass++ : fail++;
        }
      });

      document.getElementById('countPass').textContent = pass;
      document.getElementById('countFail').textContent = fail;
      document.getElementById('totalScore').textContent = total;

      const overall = document.getElementById('overallStatus');
      if (pass > 0 && fail === 0) {
        overall.textContent = 'Đạt';
        overall.style.color = '#28a745';
      } else {
        overall.textContent = 'Không đạt';
        overall.style.color = '#dc3545';
      }
    }

    /* ======================
       FILE STORE (MINH CHỨNG)
    ====================== */
    fileInput.addEventListener('change', () => {
      Array.from(fileInput.files).forEach(f => fileStore.push(f));
      fileInput.value = '';
      renderPreview();
    });

    function renderPreview() {
      previewList.innerHTML = '';

      if (fileStore.length === 0) {
        previewList.innerHTML = '<span class="text-muted">Chưa chọn hình nào</span>';
        return;
      }

      const ul = document.createElement('ul');
      ul.style.paddingLeft = '18px';

      fileStore.forEach((file, i) => {
        const li = document.createElement('li');
        li.innerHTML = `
        ${i + 1}. ${file.name}
        <button type="button" data-i="${i}"
          style="margin-left:6px;color:red;border:none;background:none;cursor:pointer">✖</button>
      `;
        ul.appendChild(li);
      });

      previewList.appendChild(ul);

      previewList.querySelectorAll('button').forEach(btn => {
        btn.addEventListener('click', () => {
          fileStore.splice(btn.dataset.i, 1);
          renderPreview();
        });
      });
    }

    /* ======================
       SUBMIT – FETCH
    ====================== */
    form.addEventListener('submit', e => {
      e.preventDefault();

      if (!loaiSelect.value)
        return showMessage('⚠️ Chưa chọn loại tiêu chí.', 'warning');

      if (fileStore.length === 0)
        return showMessage('⚠️ Yêu cầu tải ít nhất một hình ảnh minh chứng.', 'warning');

      const formData = new FormData(form);
      fileStore.forEach(f => formData.append('FileMinhChung[]', f));

      fetch(form.action, {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: formData
        })

        .then(res => res.json())
        .then(data => {
          if (data.success) {
            window.location.href =
              "?controller=suddenly&action=index&msg=" +
              encodeURIComponent(data.message || "Tạo biên bản thành công") +
              "&type=success";
          } else {
            showMessage(data.message || "Không thể lưu biên bản.", data.type || "danger");
          }
        })

        .catch(() => showMessage('Lỗi kết nối server.', 'danger'));
    });

  });
</script>