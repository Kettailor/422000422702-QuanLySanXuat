    <style>
        .qa-container {
            font-family: "Inter", sans-serif;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.08);
            padding: 20px;
            margin-bottom: 24px;
        }

        .card h5 {
            font-weight: 600;
            margin-bottom: 16px;
        }

        .table td,
        .table th {
            vertical-align: middle;
            text-align: left;
        }

        .table th {
            white-space: nowrap;
        }

        .table input.form-control {
            height: 36px;
            font-size: 0.9rem;
        }

        /* --- Cân chỉnh độ rộng các cột --- */
        .table input[name="MaTieuChi[]"] {
            width: 70px;
            text-align: center;
        }

        .table input[name="TieuChi[]"] {
            width: 320px;
        }

        .table input[name="DiemDat[]"] {
            width: 90px;
            text-align: center;
        }

        .table input[name="GhiChuTC[]"] {
            width: 180px;
        }

        .table input[name="FileMinhChung[]"] {
            width: 200px;
        }

        .table input[name="KetQuaTC[]"] {
            width: 100px;
            text-align: center;
            font-weight: 600;
        }

        .btn-add {
            background: #2f6bff;
            color: #fff;
            font-size: 0.85rem;
            border-radius: 6px;
        }

        .btn-add:hover {
            background: #2556d8;
        }

        .btn-delete {
            background: #f44336;
            color: #fff;
            border-radius: 6px;
        }

        .btn-delete:hover {
            background: #d32f2f;
        }

        .summary-box {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .summary-item {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 10px 14px;
            flex: 1;
            min-width: 180px;
            text-align: center;
        }

        .summary-item h6 {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 4px;
        }

        .summary-item .value {
            font-weight: 700;
            color: #2f6bff;
        }

        /* Ẩn spinner trên mọi trình duyệt */
        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Vô hiệu hóa highlight và gợi ý */
        input.diem-dat::-webkit-calendar-picker-indicator {
            display: none !important;
        }

        input.diem-dat {
            caret-color: auto;
            background-image: none !important;
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
                <h5>Thông tin chung</h5>
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
                            value="<?= date('Y-m-d\TH:i') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Sản phẩm</label>
                        <input type="text" class="form-control" name="TenSanPham"
                            value="<?= htmlspecialchars($loInfo['TenSanPham'] ?? '') ?>" readonly>
                    </div>
                </div>
            </div>

            <?php
            // Nạp danh sách tiêu chí mặc định
            $criteriaData = include __DIR__ . '/../../core/QualityCriteria.php';

            // Lấy tên xưởng từ thông tin lô
            $factoryName = $loInfo['TenXuong'] ?? '';
            $criteria = [];

            // Kiểm tra xem xưởng thuộc nhóm nào trong file QualityCriteria
            foreach ($criteriaData['factory'] as $xuong => $list) {
                if ($factoryName === $xuong) {
                    $criteria = $list;
                    break;
                }
            }
            ?>

            <div class="card">
                <h5>Tiêu chí kiểm tra</h5>

                <?php if (!empty($criteria)): ?>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã</th>
                                    <th>Tiêu chí</th>
                                    <th>Điểm đạt</th>
                                    <th>Ghi chú</th>
                                    <th>Minh chứng</th>
                                    <th>Kết quả</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="criteria-table">
                                <?php foreach ($criteria as $item): ?>
                                    <tr>
                                        <td><input type="text" name="MaTieuChi[]" class="form-control" value="<?= htmlspecialchars($item[0]) ?>" readonly></td>
                                        <td><input type="text" name="TieuChi[]" class="form-control" value="<?= htmlspecialchars($item[1]) ?>" readonly></td>
                                        <td><input type="text" name="DiemDat[]" class="form-control diem-dat text-center"
                                                inputmode="numeric" pattern="[0-9]*" autocomplete="off" autocorrect="off"
                                                autocapitalize="off" spellcheck="false"></td>
                                        <td><input type="text" name="GhiChuTC[]" class="form-control" placeholder="Ghi chú"></td>
                                        <td><input type="file" name="FileMinhChung[]" class="form-control"></td>
                                        <td><input type="text" name="KetQuaTC[]" class="form-control ket-qua" readonly></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning mb-0">
                        Không tìm thấy tiêu chí tương ứng cho xưởng:
                        <strong><?= htmlspecialchars($factoryName ?: 'Không xác định') ?></strong>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Kết luận -->
            <div class="card">
                <h5>Kết luận</h5>
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
        // Gắn sự kiện cho từng dòng tiêu chí
        document.querySelectorAll("#criteria-table tr").forEach(tr => attachEvents(tr));

        function attachEvents(row) {
            const inputDiem = row.querySelector(".diem-dat");
            const ketQua = row.querySelector(".ket-qua");

            // Chặn ký tự không hợp lệ
            inputDiem.addEventListener("keydown", e => {
                const invalid = ["e", "E", "+", "-", ",", ".", " "];
                if (invalid.includes(e.key)) e.preventDefault();
            });

            // Khi người dùng nhập
            inputDiem.addEventListener("input", () => {
                const raw = inputDiem.value.trim();

                if (raw === "") {
                    ketQua.value = "";
                    ketQua.style.color = "";
                    updateSummary();
                    return;
                }

                const val = Number(raw);
                if (!Number.isNaN(val)) {
                    if (val >= 9 && val <= 10) {
                        ketQua.value = "Đạt";
                        ketQua.style.color = "#28a745";
                    } else if (val >= 1 && val < 9) {
                        ketQua.value = "Không đạt";
                        ketQua.style.color = "#dc3545";
                    } else {
                        ketQua.value = "";
                        ketQua.style.color = "";
                    }
                }
                updateSummary();
            });

            // Khi rời ô
            inputDiem.addEventListener("blur", () => {
                const raw = inputDiem.value.trim();
                if (raw === "") {
                    ketQua.value = "";
                    ketQua.style.color = "";
                    updateSummary();
                    return;
                }

                let val = Number(raw);
                if (Number.isNaN(val)) {
                    inputDiem.value = "";
                    ketQua.value = "";
                    ketQua.style.color = "";
                    updateSummary();
                    return;
                }

                if (val < 1) val = 1;
                if (val > 10) val = 10;
                inputDiem.value = val;
                ketQua.value = val >= 9 ? "Đạt" : "Không đạt";
                ketQua.style.color = val >= 9 ? "#28a745" : "#dc3545";
                updateSummary();
            });
        }

        function updateSummary() {
            let pass = 0,
                fail = 0,
                totalScore = 0,
                filled = 0;
            const rows = document.querySelectorAll("#criteria-table tr");
            const totalCriteria = rows.length;

            rows.forEach(tr => {
                const input = tr.querySelector(".diem-dat");
                const ketQua = tr.querySelector(".ket-qua");
                const val = Number(input.value.trim());

                if (input.value.trim() === "" || Number.isNaN(val)) return; // bỏ qua ô trống

                totalScore += val;
                filled++;

                if (ketQua.value === "Đạt") pass++;
                else if (ketQua.value === "Không đạt") fail++;
            });

            document.getElementById("countPass").textContent = pass;
            document.getElementById("countFail").textContent = fail;
            document.getElementById("totalScore").textContent = totalScore.toFixed(0);

            const overall = document.getElementById("overallStatus");
            if (filled === 0) {
                overall.textContent = "—";
                overall.style.color = "#6c757d";
            } else if (filled < totalCriteria) {
                overall.textContent = "Chưa đủ dữ liệu";
                overall.style.color = "#ffc107";
            } else if (fail === 0 && pass > 0) {
                overall.textContent = "Đạt";
                overall.style.color = "#28a745";
            } else {
                overall.textContent = "Không đạt";
                overall.style.color = "#dc3545";
            }
        }

        // Hiển thị thông báo cảnh báo trên form
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
            setTimeout(() => (box.style.display = "none"), 5000);
        }

        // Chặn submit khi chưa nhập đủ điểm
        document.querySelector("form").addEventListener("submit", e => {
            const inputs = document.querySelectorAll(".diem-dat");
            let allFilled = true;

            inputs.forEach(inp => {
                if (inp.value.trim() === "") {
                    allFilled = false;
                    inp.focus();
                }
            });

            if (!allFilled) {
                e.preventDefault();
                showMessage("⚠️ Vui lòng nhập điểm cho tất cả tiêu chí trước khi lưu biên bản.", "warning");
                return;
            }

            // Kiểm tra giá trị hợp lệ
            let valid = true;
            inputs.forEach(inp => {
                const val = Number(inp.value);
                if (val < 1 || val > 10 || !Number.isInteger(val)) {
                    valid = false;
                    inp.focus();
                }
            });
            if (!valid) {
                e.preventDefault();
                showMessage(" Điểm đạt phải là số nguyên từ 1 đến 10.", "danger");
            }
        });
    </script>