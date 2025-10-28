<style>
.qa-container {
    font-family: "Inter", sans-serif;
}
.card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.08);
    padding: 20px;
    margin-bottom: 24px;
}
.card h5 {
    font-weight: 600;
    margin-bottom: 16px;
}
.table td, .table th {
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
    color: #2f6bff;
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
        <a href="?controller=quality&action=index" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
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

    <!-- Tiêu chí kiểm tra -->
<div class="card">
    <h5>Tiêu chí kiểm tra</h5>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th>Mã</th>
                    <th>Tiêu chí</th>
                    <th>Điểm đạt </th>
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
                        <td><input type="number" name="DiemDat[]" class="form-control diem-dat" min="0" max="10"></td>
                        <td><input type="text" name="GhiChuTC[]" class="form-control" placeholder="Ghi chú"></td>
                        <td><input type="file" name="FileMinhChung[]" class="form-control"></td>
                        <td><input type="text" name="KetQuaTC[]" class="form-control ket-qua" readonly></td>
                        <td><button type="button" class="btn btn-delete btn-sm">Xóa</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
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
function attachEvents(row) {
    const inputDiem = row.querySelector(".diem-dat");
    const ketQua = row.querySelector(".ket-qua");
    const btnDel = row.querySelector(".btn-delete");

    // Xóa dòng
    btnDel.addEventListener("click", () => { 
        row.remove(); 
        updateSummary(); 
    });

    // Khi nhập điểm
    inputDiem.addEventListener("input", () => {
        let val = parseFloat(inputDiem.value);
        if (isNaN(val)) val = 0;
        if (val > 10) val = 10;
        if (val < 0) val = 0;
        inputDiem.value = val;

        // ✅ Đạt nếu >= 9, ngược lại không đạt
        ketQua.value = val >= 9 ? "Đạt" : "Không đạt";
        updateSummary();
    });
}

function updateSummary() {
    let pass = 0, fail = 0, totalScore = 0, count = 0;

    document.querySelectorAll("#criteria-table tr").forEach(tr => {
        const diem = parseFloat(tr.querySelector(".diem-dat")?.value) || 0;
        const result = tr.querySelector(".ket-qua")?.value?.trim();

        if (result === "Đạt") pass++;
        else if (result === "Không đạt") fail++;

        totalScore += diem;
        count++;
    });

    // ✅ Cập nhật hiển thị
    document.getElementById("countPass").textContent = pass;
    document.getElementById("countFail").textContent = fail;
    document.getElementById("totalScore").textContent = totalScore.toFixed(0);

    // ✅ Tính trạng thái tổng
    const overall = document.getElementById("overallStatus");
    if (count === 0) {
        overall.textContent = "—";
        overall.style.color = "#6c757d";
    } else if (fail === 0 && pass > 0) {
        overall.textContent = "Đạt";
        overall.style.color = "#28a745"; // xanh lá
    } else {
        overall.textContent = "Không đạt";
        overall.style.color = "#d93025"; // đỏ
    }
}

// Thêm dòng mới (nếu có nút “Thêm”)
const btnAdd = document.getElementById("addRow");
if (btnAdd) {
    btnAdd.addEventListener("click", function() {
        const tbody = document.getElementById("criteria-table");
        const row = document.createElement("tr");
        const index = tbody.rows.length + 1;
        row.innerHTML = `
            <td><input type="text" name="MaTieuChi[]" class="form-control" value="TC0${index}" readonly></td>
            <td><input type="text" name="TieuChi[]" class="form-control" placeholder="Tên tiêu chí" readonly></td>
            <td><input type="number" name="DiemDat[]" class="form-control diem-dat" min="0" max="10"></td>
            <td><input type="text" name="GhiChuTC[]" class="form-control" placeholder="Ghi chú"></td>
            <td><input type="file" name="FileMinhChung[]" class="form-control"></td>
            <td><input type="text" name="KetQuaTC[]" class="form-control ket-qua" readonly></td>
            <td><button type="button" class="btn btn-delete btn-sm">Xóa</button></td>
        `;
        tbody.appendChild(row);
        attachEvents(row);
    });
}

// Khởi tạo sự kiện cho các dòng sẵn có
document.querySelectorAll("#criteria-table tr").forEach(tr => attachEvents(tr));
</script>

