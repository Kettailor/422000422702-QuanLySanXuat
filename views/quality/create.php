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
.progress {
    height: 8px;
    background-color: #e9ecef;
    border-radius: 6px;
    overflow: hidden;
}
.progress-bar {
    background-color: #28a745;
    width: 0%;
    transition: width 0.4s ease;
}
</style>

<div class="qa-container">

<form action="?controller=quality&action=store" method="post" enctype="multipart/form-data">
    <input type="hidden" name="IdBienBanDanhGiaSP" value="<?= uniqid('BBTP') ?>">
    <input type="hidden" name="IdLo" value="LOTP202311">


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
                <input type="text" class="form-control" name="MaKeHoach" value="KH-2025-10-21-HD20251001" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label">Xưởng</label>
                <input type="text" class="form-control" name="TenXuong" value="Xưởng 3 - QC & Đóng gói" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label">Ngày kiểm tra</label>
                <input type="date" class="form-control" name="ThoiGian" value="<?= date('Y-m-d') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Sản phẩm</label>
                <input type="text" class="form-control" name="TenSanPham" value="Bàn phím cơ A" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Lô kiểm tra</label>
                <input type="text" class="form-control" name="TenLo" value="Lô 3" readonly>
            </div>
        </div>
    </div>

    <!-- Tiêu chí kiểm tra -->
    <div class="card">
        <h5>Tiêu chí kiểm tra</h5>
        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th>Mã</th>
                    <th>Tiêu chí</th>
                    <th>Điểm tối đa</th>
                    <th>Điểm đạt</th>
                    <th>Ghi chú</th>
                    <th>Minh chứng</th>
                    <th>KQ</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="criteria-table">
                <tr>
                    <td><input type="text" name="MaTieuChi[]" class="form-control" value="TC01"></td>
                    <td><input type="text" name="TenTieuChi[]" class="form-control" value="Kiểm tra ngoại hình"></td>
                    <td><input type="number" name="DiemToiDa[]" class="form-control" value="10"></td>
                    <td><input type="number" name="DiemDat[]" class="form-control" value="10"></td>
                    <td><input type="text" name="GhiChuTC[]" class="form-control" value="Không trầy xước"></td>
                    <td><input type="file" name="FileMinhChung[]" class="form-control"></td>
                    <td>
                        <select name="KetQuaTC[]" class="form-select">
                            <option value="Đạt">Đạt</option>
                            <option value="Không đạt">Không đạt</option>
                        </select>
                    </td>
                    <td><button type="button" class="btn btn-delete btn-sm">Xóa</button></td>
                </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-add mt-2" id="addRow"><i class="bi bi-plus"></i> Thêm tiêu chí</button>
    </div>

    <!-- Kết luận -->
    <div class="card">
        <h5>Kết luận</h5>
        <div class="row g-3 align-items-center mb-3">
            <div class="col-md-3">
                <label class="form-label">Tổng điểm</label>
                <input type="text" class="form-control" id="totalScore" name="TongDiem" value="27 / 30 (90%)" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label">Trạng thái tổng</label>
                <select name="KetQua" class="form-select">
                    <option value="Đạt">Đạt</option>
                    <option value="Không đạt">Không đạt</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Ghi chú bổ sung</label>
                <input type="text" class="form-control" name="GhiChu" placeholder="Ví dụ: Bao bì hơi móp, kiểm tra lại trước khi xuất kho">
            </div>
        </div>
        <div class="progress mb-3">
            <div class="progress-bar" style="width:90%"></div>
        </div>
        <div class="text-end">
            <a href="?controller=quality&action=index" class="btn btn-outline-secondary me-2">Quay lại</a>
            <button type="submit" class="btn btn-primary">Lưu kết quả kiểm tra</button>
        </div>
    </div>

</form>
</div>

<script>
document.getElementById("addRow").addEventListener("click", function() {
    const tbody = document.getElementById("criteria-table");
    const row = document.createElement("tr");
    const index = tbody.rows.length + 1;
    row.innerHTML = `
        <td><input type="text" name="MaTieuChi[]" class="form-control" value="TC0${index}"></td>
        <td><input type="text" name="TenTieuChi[]" class="form-control" placeholder="Tên tiêu chí"></td>
        <td><input type="number" name="DiemToiDa[]" class="form-control" value="10"></td>
        <td><input type="number" name="DiemDat[]" class="form-control" value="0"></td>
        <td><input type="text" name="GhiChuTC[]" class="form-control" placeholder="Ghi chú"></td>
        <td><input type="file" name="FileMinhChung[]" class="form-control"></td>
        <td>
            <select name="KetQuaTC[]" class="form-select">
                <option value="Đạt">Đạt</option>
                <option value="Không đạt">Không đạt</option>
            </select>
        </td>
        <td><button type="button" class="btn btn-delete btn-sm">Xóa</button></td>
    `;
    tbody.appendChild(row);

    // Thêm sự kiện xóa dòng mới
    row.querySelector(".btn-delete").addEventListener("click", () => row.remove());
});

// Xóa dòng tiêu chí hiện có
document.querySelectorAll(".btn-delete").forEach(btn => {
    btn.addEventListener("click", function() {
        this.closest("tr").remove();
    });
});
</script>
