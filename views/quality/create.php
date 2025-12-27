<style>
    /* ================================
   BASE
================================ */

    .card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 1px 10px rgba(0, 0, 0, .06);
        padding: 22px;
        margin-bottom: 22px;
    }

    /* ===============================
   SECTION TITLE – GIỐNG SUDDENLY
================================ */
    .card h5,
    .section-title {
        color: #1f3c88;
        /* xanh đậm giống suddenly */
        font-weight: 700;
        font-size: 1.3rem;
        margin-bottom: 16px;
    }

    /* ===============================
   TABLE WRAP
================================ */
    .table-responsive {
        overflow-x: hidden;
        /* KHÔNG cho dư phải */
    }

    /* ===============================
   TABLE BASE – CĂN CỘT CHUẨN
================================ */
    #criteria-table {
        width: 100%;
        max-width: 100%;
        table-layout: fixed;
        /* BẮT BUỘC để cột đều */
        border-collapse: collapse;
    }

    /* ===============================
   HEADER
================================ */
    #criteria-table thead th {
        background: #f5f6f8;
        font-weight: 700;
        font-size: 1rem;
        color: #000;
        padding: 12px 8px;
        border-bottom: 1px solid #dee2e6;
    }

    #criteria-table thead th:nth-child(1),
    #criteria-table thead th:nth-child(3),
    #criteria-table thead th:nth-child(5) {
        text-align: center;
    }

    /* ===============================
   BODY CELL
================================ */
    #criteria-table tbody td {
        padding: 8px;
        border-bottom: 1px solid #eef0f3;
        vertical-align: middle;
    }

    /* ===============================
   WIDTH CỘT – KHÓA CỨNG
================================ */
    #criteria-table th:nth-child(1),
    #criteria-table td:nth-child(1) {
        width: 90px;
        text-align: center;
    }

    #criteria-table th:nth-child(2),
    #criteria-table td:nth-child(2) {
        width: 44%;
    }

    #criteria-table th:nth-child(3),
    #criteria-table td:nth-child(3) {
        width: 110px;
        text-align: center;
    }

    #criteria-table th:nth-child(4),
    #criteria-table td:nth-child(4) {
        width: 240px;
    }

    #criteria-table th:nth-child(5),
    #criteria-table td:nth-child(5) {
        width: 130px;
        text-align: center;
    }

    /* ĐẨY CHỮ "GHI CHÚ" SANG PHẢI NHẸ */
    #criteria-table thead th:nth-child(4) {
        padding-left: 100px;
    }

    /* ===============================
   INPUT – FULL CELL, KHÔNG LỆCH
================================ */
    #criteria-table .form-control {
        width: 100%;
        /* QUAN TRỌNG */
        height: 36px;
        font-size: 0.9rem;
        border-radius: 8px;
        border: 1px solid #dfe3e8;
        padding: 6px 10px;
        background: #fff;
        box-sizing: border-box;
        /* CHỐT căn đều */
    }

    /* focus nhẹ */
    #criteria-table .form-control:focus {
        border-color: #1f3c88;
        box-shadow: 0 0 0 .12rem rgba(31, 60, 136, .15);
    }

    /* readonly KHÔNG xám */
    #criteria-table .form-control[readonly] {
        background: #fff;
        color: #000;
    }

    /* ===============================
   TEXT ALIGN THEO CỘT
================================ */
    #criteria-table input[name="MaTieuChi[]"],
    #criteria-table input[name="DiemDat[]"] {
        font-weight: 400;
        /* chữ thường */
    }

    #criteria-table input[name="KetQuaTC[]"] {
        text-align: center;
        font-weight: 700;
    }

    /* tiêu chí xuống dòng đẹp */
    #criteria-table input[name="TieuChi[]"] {
        white-space: normal;
        line-height: 1.35;
    }

    /* ================================
   SUMMARY
================================ */
    .summary-box {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }

    .summary-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 10px 14px;
        flex: 1;
        min-width: 180px;
        text-align: center;
    }

    .summary-item h6 {
        font-size: .9rem;
        color: #6c757d;
        margin-bottom: 4px;
    }

    .summary-item .value {
        font-weight: 800;
        color: #0d6efd;
    }

    .table {
        margin-bottom: 0;
    }
</style>

<div class="qa-container">

    <form action="?controller=quality&action=store" method="post" enctype="multipart/form-data">
        <input type="hidden" name="IdLo" value="<?= htmlspecialchars($loInfo['IdLo'] ?? '') ?>">

        <!-- Tiêu đề -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Đánh giá chất lượng thành phẩm</h3>
                <p class="text-muted mb-0">Ghi nhận kết quả kiểm tra và biên bản đánh giá cho lô bàn phím SV5TOT.</p>
            </div>
        </div>

        <!-- Thông tin chung -->
        <div class="card">
            <h5 class="fw-bold section-title mb-3">Thông tin chung</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Mã kế hoạch</label>
                    <input type="text" class="form-control" name="MaKeHoach"
                        value="<?= 'KH-' . date('Y-m-d') . '-HD' . date('YmdHis') ?>" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Xưởng</label>
                    <input type="text" class="form-control" name="TenXuong"
                        value="<?= htmlspecialchars($loInfo['TenXuong'] ?? '') ?>" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Thời gian kiểm tra</label>
                    <input type="datetime-local" class="form-control" name="ThoiGian"
                        value="<?= date('Y-m-d\TH:i') ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tên lô</label>
                    <input type="text" class="form-control"
                        value="<?= htmlspecialchars($loInfo['TenLo'] ?? '') ?>" readonly>
                </div>

            </div>
        </div>

        <div class="card">
            <h5 class="fw-bold section-title mb-3">Tiêu chí kiểm tra</h5>


            <?php if (!empty($criteria)): ?>
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
                        <tbody id="criteria-body">
                            <?php foreach ($criteria as $item): ?>
                                <tr>
                                    <td><input type="text" name="MaTieuChi[]" class="form-control" value="<?= htmlspecialchars($item['id']) ?>" readonly></td>
                                    <td><input type="text" name="TieuChi[]" class="form-control" value="<?= htmlspecialchars($item['criterion']) ?>" readonly></td>
                                    <td><input type="text" name="DiemDat[]" class="form-control diem-dat text-center"
                                            inputmode="numeric" pattern="[0-9]*" autocomplete="off" autocorrect="off"
                                            autocapitalize="off" spellcheck="false"></td>
                                    <td><input type="text" name="GhiChuTC[]" class="form-control" placeholder="Ghi chú"></td>
                                    <td><input type="text" name="KetQuaTC[]" class="form-control ket-qua" readonly></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-warning mb-0">
                    Không tìm thấy tiêu chí tương ứng cho xưởng:
                    <strong><?= htmlspecialchars($loInfo['TenXuong'] ?? 'Không xác định') ?></strong>
                </div>
            <?php endif; ?>

        </div>

        <div class="card">
            <h5 class="fw-bold section-title mb-3">Minh chứng hình ảnh</h5>
            <p class="text-muted mb-2">Bạn có thể tải lên một hoặc nhiều hình ảnh minh chứng.</p>

            <input type="file"
                name="FileMinhChung[]"
                class="form-control"
                accept="image/jpeg,image/png"
                multiple>
            <div id="preview-list" style="margin-top:10px; font-size:14px;"></div>

        </div>

        <!-- Kết luận -->
        <div class="card">
            <h5 class="fw-bold section-title mb-3">Kết luận</h5>
            <div class="summary-box mb-3">
                <div class="summary-item">
                    <h6>Tổng tiêu chí đạt</h6>
                    <div class="value" id="countPass">0</div>
                </div>
                <div class="summary-item">
                    <h6>Tổng tiêu chí không đạt</h6>
                    <div class="value" id="countFail">0</div>
                </div>
                <div class="summary-item">
                    <h6>Tổng điểm</h6>
                    <div class="value" id="totalScore">0</div>
                </div>
                <div class="summary-item">
                    <h6>Trạng thái tổng</h6>
                    <div class="value" id="overallStatus">—</div>
                </div>
            </div>
            <div class="text-end">
                <a href="?controller=quality&action=index" class="btn btn-outline-secondary me-2">Quay lại</a>
                <button type="submit" class="btn btn-primary">Lưu kết quả kiểm tra</button>
            </div>
        </div>

    </form>
</div>
<script>
    document.querySelectorAll("#criteria-body tr").forEach(tr => attachEvents(tr));

    function attachEvents(row) {
        const diem = row.querySelector('.diem-dat');
        const ketQua = row.querySelector('.ket-qua');
        const ghiChu = row.querySelector('[name="GhiChuTC[]"]');

        if (!diem || !ketQua) return;

        /* ===== CHẶN TỪ PHÍM ===== */
        diem.addEventListener('keydown', e => {
            const allowKeys = [
                'Backspace', 'Tab', 'ArrowLeft', 'ArrowRight', 'Delete'
            ];

            if (!/[0-9]/.test(e.key) && !allowKeys.includes(e.key)) {
                e.preventDefault();
            }
        });

        /* ===== CHẶN PASTE + ÉP GIÁ TRỊ ===== */
        diem.addEventListener('input', () => {
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
        if (ghiChu) {
            ghiChu.addEventListener('input', () => {
                ghiChu.value = ghiChu.value.replace(/[@\?\[\],\{\}\|\\\"~]/g, '');
            });
        }
    }

    function updateSummary() {
        let pass = 0,
            fail = 0,
            totalScore = 0;

        document.querySelectorAll(".diem-dat").forEach(inp => {
            const val = Number(inp.value);
            if (!isNaN(val) && inp.value.trim() !== "") {
                totalScore += val;
                if (val >= 9) pass++;
                else fail++;
            }
        });

        document.getElementById("countPass").textContent = pass;
        document.getElementById("countFail").textContent = fail;
        document.getElementById("totalScore").textContent = totalScore;

        const status = document.getElementById("overallStatus");
        if (fail === 0 && pass > 0) {
            status.textContent = "Đạt";
            status.style.color = "#28a745";
        } else {
            status.textContent = "Không đạt";
            status.style.color = "#dc3545";
        }
    }




    let fileStore = [];
    const fileInput = document.querySelector('input[name="FileMinhChung[]"]');
    const previewList = document.getElementById("preview-list");

    fileInput.addEventListener("change", () => {

        Array.from(fileInput.files).forEach(f => fileStore.push(f));
        renderPreview();
    });

    function renderPreview() {
        previewList.innerHTML = "";

        if (fileStore.length === 0) {
            previewList.innerHTML = "<span class='text-muted'>Chưa chọn hình nào</span>";
            return;
        }

        const ul = document.createElement("ul");
        ul.style.paddingLeft = "20px";

        fileStore.forEach((file, index) => {
            const li = document.createElement("li");
            li.innerHTML = `
            ${index + 1}. ${file.name}
            <button type="button" data-i="${index}"
                style="margin-left:8px; color:red; border:none; background:none; cursor:pointer;">
                ✖
            </button>
        `;
            ul.appendChild(li);
        });

        previewList.appendChild(ul);

        previewList.querySelectorAll("button").forEach(btn => {
            btn.addEventListener("click", () => {
                const i = btn.getAttribute("data-i");
                fileStore.splice(i, 1);
                renderPreview();
            });
        });
    }



    document.querySelector("form").addEventListener("submit", e => {

        for (let inp of document.querySelectorAll(".diem-dat")) {
            const val = Number(inp.value);
            if (inp.value.trim() === "" || isNaN(val) || val < 1 || val > 10) {
                e.preventDefault();
                showMessage("⚠️ Yêu cầu nhập điểm cho tiêu chí.", "danger");
                inp.focus();
                return;
            }
        }

        if (fileStore.length === 0) {
            e.preventDefault();
            showMessage("⚠️ Yêu cầu tải lên ít nhất một hình ảnh minh chứng.", "warning");
            return;
        }

        document.querySelector("form").addEventListener("submit", e => {

            for (let inp of document.querySelectorAll(".diem-dat")) {
                const val = Number(inp.value);
                if (inp.value.trim() === "" || isNaN(val) || val < 1 || val > 10) {
                    e.preventDefault();
                    showMessage("⚠️ Yêu cầu nhập điểm cho tiêu chí.", "danger");
                    inp.focus();
                    return;
                }
            }

            if (fileStore.length === 0) {
                e.preventDefault();
                showMessage("⚠️ Yêu cầu tải lên ít nhất một hình ảnh minh chứng.", "warning");
                return;
            }
        });



    });

    function showMessage(msg, type = "warning") {
        let box = document.getElementById("form-message");

        if (!box) {
            const container = document.querySelector(".text-end");
            box = document.createElement("div");
            box.id = "form-message";
            box.className = "alert mt-3";
            container.parentNode.insertBefore(box, container);
        }

        box.textContent = msg;
        box.className = `alert alert-${type} mt-3`;
        box.style.display = "block";

        setTimeout(() => {
            box.style.display = "none";
        }, 5000);
    }
</script>