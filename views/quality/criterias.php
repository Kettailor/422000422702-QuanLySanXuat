<style>
    /* ===== DÂY CHUYỀN SẢN XUẤT ===== */
    .card-production {
        border-top: 5px solid #ff7a00;
    }

    /* tiêu đề */
    .card-production h5,
    .card-production .card-title {
        color: #ff7a00;
    }

    /* mô tả */
    .card-production p {
        color: #6b7280;
    }

    /* nút xem chi tiết */
    .card-production .btn {
        background-color: #ff7a00;
        border-color: #ff7a00;
        color: #fff;
    }

    .card-production .btn:hover {
        background-color: #e96f00;
        border-color: #e96f00;
    }

    /* ===== NHÂN CÔNG ===== */
    .card-worker {
        border-top: 5px solid #2563eb;
    }

    /* tiêu đề */
    .card-worker h5,
    .card-worker .card-title {
        color: #2563eb;
    }

    /* mô tả */
    .card-worker p {
        color: #6b7280;
    }

    /* nút xem chi tiết */
    .card-worker .btn {
        background-color: #2563eb;
        border-color: #2563eb;
        color: #fff;
    }

    .card-worker .btn:hover {
        background-color: #1e4fd8;
        border-color: #1e4fd8;
    }
</style>
<div class="mb-4">
    <?php if (empty($type)): ?>
        <h3 class="fw-bold mb-1">Chọn xưởng để quản lý tiêu chí chất lượng</h3>
        <p class="text-muted mb-0">
            Quản lý tiêu chí đánh giá chất lượng sản phẩm theo từng xưởng.
        </p>
    <?php elseif ($type === 'factory'): ?>
        <h3 class="fw-bold mb-1">
            Quản lý tiêu chí xưởng <?= htmlspecialchars($tenXuong ?? '') ?>
        </h3>
    <?php elseif ($type === 'production'): ?>
        <h3 class="fw-bold mb-1">Tiêu chí dây chuyền sản xuất</h3>
    <?php elseif ($type === 'worker'): ?>
        <h3 class="fw-bold mb-1">Tiêu chí đánh giá nhân công</h3>
    <?php endif; ?>
</div>

<?php
/* ======================================================
   TRẠNG THÁI 1: TRANG CHỌN (KHÔNG CÓ TYPE)
====================================================== */
if (empty($type)):
?>

    <!-- ===== DANH SÁCH XƯỞNG ===== -->
    <div class="card p-4 mb-4">
        <table class="table table-responsive mb-0">
            <thead>
                <tr>
                    <th>Mã Xưởng</th>
                    <th>Tên Xưởng</th>
                    <th style="width:160px">Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($workshops as $workshop): ?>
                    <tr>
                        <td><?= htmlspecialchars($workshop['IdXuong']) ?></td>
                        <td><?= htmlspecialchars($workshop['TenXuong']) ?></td>
                        <td>
                            <span class="badge bg-light text-dark">Đang áp dụng</span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- ===== CARD DÂY CHUYỀN & NHÂN CÔNG ===== -->
    <div class="row mt-4">

        <div class="col-md-6 card card-production">
            <div class="card p-4 h-100">
                <h5 class="fw-bold ">Dây chuyền sản xuất</h5>
                <p class="text-muted mb-3">
                    Tiêu chí kiểm tra thiết bị, quy trình, đóng gói, kiểm thử
                </p>
                <a href="?controller=quality&action=criterias&type=production"
                    class="btn btn-success btn btn-sm">
                    Xem chi tiết
                </a>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-4 h-100 card card-worker">
                <h5 class="fw-bold ">Nhân công</h5>
                <p class="text-muted mb-3">
                    Tiêu chí đánh giá tác phong, kỹ năng, an toàn lao động
                </p>
                <a href="?controller=quality&action=criterias&type=worker"
                    class="btn btn-warning btn-sm">
                    Xem chi tiết
                </a>
            </div>
        </div>

    </div>

<?php
/* ======================================================
   TRẠNG THÁI 2: TIÊU CHÍ XƯỞNG
====================================================== */
elseif ($type === 'factory'):
?>

    <div class="card p-4 btn-success">
        <?php if (!empty($criterias)): ?>
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th style="width:120px btn btn-success">Mã</th>
                        <th>Nội dung tiêu chí</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($criterias as $c): ?>
                        <tr>
                            <td><?= htmlspecialchars($c['id']) ?></td>
                            <td><?= htmlspecialchars($c['criterion']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info mb-0">
                Không có tiêu chí cho xưởng này.
            </div>
        <?php endif; ?>
    </div>

<?php
/* ======================================================
   TRẠNG THÁI 3: TIÊU CHÍ DÂY CHUYỀN
====================================================== */
elseif ($type === 'production'):
?>

    <div class="card p-4">
        <?php foreach ($productionCriterias as $group => $items): ?>
            <h6 class="fw-semibold text-warning mt-3">
                <?= htmlspecialchars($group) ?>
            </h6>
            <table class="table table-sm table-bordered mb-3">
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td style="width:120px"><?= htmlspecialchars($item[0]) ?></td>
                            <td><?= htmlspecialchars($item[1]) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    </div>

<?php
/* ======================================================
   TRẠNG THÁI 4: TIÊU CHÍ NHÂN CÔNG
====================================================== */
elseif ($type === 'worker'):
?>

    <div class="card p-4">
        <?php foreach ($workerCriterias as $group => $items): ?>
            <h6 class="fw-semibold text-primary mt-3">
                <?= htmlspecialchars($group) ?>
            </h6>
            <table class="table table-sm table-bordered mb-3">
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td style="width:120px"><?= htmlspecialchars($item[0]) ?></td>
                            <td><?= htmlspecialchars($item[1]) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    </div>

<?php endif; ?>