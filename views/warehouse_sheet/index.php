<?php
$summary = $summary ?? [];
$documents = $documents ?? [];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Phiếu xuất nhập kho</h3>
        <p class="text-muted mb-0">Tổng hợp luồng nhập - xuất và hiệu suất xử lý chứng từ kho.</p>
    </div>
</div>

<?php if (!empty($summary)): ?>
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card metric-card">
                <div class="icon-wrap bg-primary bg-opacity-10 text-primary"><i class="bi bi-receipt"></i></div>
                <div>
                    <div class="text-muted text-uppercase small">Tổng số phiếu</div>
                    <div class="fs-3 fw-bold"><?= number_format($summary['total_documents']) ?></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card metric-card">
                <div class="icon-wrap bg-success bg-opacity-10 text-success"><i class="bi bi-box-arrow-in-down"></i></div>
                <div>
                    <div class="text-muted text-uppercase small">Phiếu nhập</div>
                    <div class="fs-3 fw-bold"><?= number_format($summary['inbound_documents']) ?></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card metric-card">
                <div class="icon-wrap bg-danger bg-opacity-10 text-danger"><i class="bi bi-box-arrow-up"></i></div>
                <div>
                    <div class="text-muted text-uppercase small">Phiếu xuất</div>
                    <div class="fs-3 fw-bold"><?= number_format($summary['outbound_documents']) ?></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card metric-card">
                <div class="icon-wrap bg-warning bg-opacity-10 text-warning"><i class="bi bi-cash-stack"></i></div>
                <div>
                    <div class="text-muted text-uppercase small">Tổng giá trị</div>
                    <div class="fs-3 fw-bold"><?= number_format($summary['total_value'], 0, ',', '.') ?> đ</div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($summary['monthly_trend'])): ?>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-semibold mb-0">Xu hướng 6 tháng gần nhất</h6>
                    <span class="text-muted small">Theo tháng lập phiếu</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                        <tr>
                            <th>Tháng</th>
                            <th>Số phiếu</th>
                            <th>Tổng giá trị</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($summary['monthly_trend'] as $trend): ?>
                            <tr>
                                <td><?= htmlspecialchars($trend['thang']) ?></td>
                                <td><?= number_format($trend['so_phieu']) ?></td>
                                <td><?= number_format($trend['tong_tien'], 0, ',', '.') ?> đ</td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr>
                <th>Mã phiếu</th>
                <th>Kho</th>
                <th>Loại phiếu</th>
                <th>Ngày lập</th>
                <th>Ngày xác nhận</th>
                <th>Tổng tiền</th>
                <th>Người lập</th>
                <th>Người xác nhận</th>
                <th>Mặt hàng</th>
                <th>Số lượng</th>
                <th>Thực nhận</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($documents as $document): ?>
                <?php
                $type = $document['LoaiPhieu'] ?? '';
                $typeClass = 'bg-secondary bg-opacity-10 text-secondary';
                $typeNormalized = function_exists('mb_strtolower') ? mb_strtolower($type, 'UTF-8') : strtolower($type);
                if (str_contains($typeNormalized, 'nhập')) {
                    $typeClass = 'badge-soft-success';
                } elseif (str_contains($typeNormalized, 'xuất')) {
                    $typeClass = 'badge-soft-danger';
                }
                ?>
                <tr>
                    <td class="fw-semibold"><?= htmlspecialchars($document['IdPhieu']) ?></td>
                    <td><?= htmlspecialchars($document['TenKho']) ?></td>
                    <td><span class="badge <?= $typeClass ?>"><?= htmlspecialchars($document['LoaiPhieu']) ?></span></td>
                    <td><?= $document['NgayLP'] ? date('d/m/Y', strtotime($document['NgayLP'])) : '-' ?></td>
                    <td><?= $document['NgayXN'] ? date('d/m/Y', strtotime($document['NgayXN'])) : '-' ?></td>
                    <td class="fw-semibold text-primary"><?= number_format($document['TongTien'], 0, ',', '.') ?> đ</td>
                    <td><?= htmlspecialchars($document['NguoiLap'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($document['NguoiXacNhan'] ?? '-') ?></td>
                    <td><?= number_format($document['TongMatHang']) ?></td>
                    <td><?= number_format($document['TongSoLuong']) ?></td>
                    <td><?= number_format($document['TongThucNhan']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
