<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Chi tiết kế hoạch SV5TOT</h3>
        <p class="text-muted mb-0">Thông tin chi tiết kế hoạch sản xuất bàn phím SV5TOT.</p>
    </div>
    <a href="?controller=plan&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php if (!$plan): ?>
    <div class="alert alert-warning">Không tìm thấy kế hoạch.</div>
<?php else: ?>
    <div class="card p-4 mb-4">
        <div class="row g-4">
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Mã kế hoạch</dt>
                    <dd class="col-sm-7 fw-semibold">#<?= htmlspecialchars($plan['IdKeHoachSanXuat']) ?></dd>
                    <dt class="col-sm-5">Đơn hàng</dt>
                    <dd class="col-sm-7">
                        <div class="fw-semibold">ĐH <?= htmlspecialchars($plan['IdDonHang']) ?></div>
                        <div class="text-muted small"><?= htmlspecialchars($plan['YeuCau'] ?? 'Không có yêu cầu bổ sung') ?></div>
                    </dd>
                    <dt class="col-sm-5">Sản phẩm</dt>
                    <dd class="col-sm-7">
                        <div class="fw-semibold"><?= htmlspecialchars($plan['TenSanPham']) ?></div>
                        <?php if (!empty($plan['TenCauHinh'])): ?>
                            <div class="text-muted small">Cấu hình: <?= htmlspecialchars($plan['TenCauHinh']) ?></div>
                        <?php endif; ?>
                    </dd>
                    <dt class="col-sm-5">Số lượng kế hoạch</dt>
                    <dd class="col-sm-7">
                        <?= htmlspecialchars($plan['SoLuong']) ?> / <?= htmlspecialchars($plan['SoLuongChiTiet'] ?? $plan['SoLuong']) ?> <?= htmlspecialchars($plan['DonVi'] ?? 'sản phẩm') ?>
                    </dd>
                </dl>
            </div>
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Trạng thái</dt>
                    <dd class="col-sm-7"><span class="badge bg-info bg-opacity-25 text-primary"><?= htmlspecialchars($plan['TrangThai']) ?></span></dd>
                    <dt class="col-sm-5">Bắt đầu</dt>
                    <dd class="col-sm-7"><?= $plan['ThoiGianBD'] ? date('d/m/Y H:i', strtotime($plan['ThoiGianBD'])) : '-' ?></dd>
                    <dt class="col-sm-5">Kết thúc</dt>
                    <dd class="col-sm-7"><?= $plan['ThoiGianKetThuc'] ? date('d/m/Y H:i', strtotime($plan['ThoiGianKetThuc'])) : '-' ?></dd>
                    <dt class="col-sm-5">Tiến độ xưởng</dt>
                    <dd class="col-sm-7">
                        <?php $totalSteps = (int) ($plan['TongCongDoan'] ?? 0); $doneSteps = (int) ($plan['CongDoanHoanThanh'] ?? 0); ?>
                        <span class="badge bg-light text-dark">
                            <?= $totalSteps ? $doneSteps . ' / ' . $totalSteps . ' công đoạn' : 'Chưa phân xưởng' ?>
                        </span>
                    </dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Phân rã kế hoạch cho xưởng</h5>
            <a class="btn btn-sm btn-primary" href="?controller=factory_plan&action=create&IdKeHoachSanXuat=<?= urlencode($plan['IdKeHoachSanXuat']) ?>">
                <i class="bi bi-plus-lg me-1"></i> Thêm công đoạn
            </a>
        </div>
        <?php if (!$workshopPlans): ?>
            <div class="alert alert-info mb-0">Chưa có kế hoạch nào được phân bổ xuống các xưởng.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>Mã kế hoạch xưởng</th>
                        <th>Xưởng thực hiện</th>
                        <th>Hạng mục</th>
                        <th>Số lượng</th>
                        <th>Thời gian</th>
                        <th>Trạng thái</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($workshopPlans as $workshopPlan): ?>
                        <tr>
                            <td><?= htmlspecialchars($workshopPlan['IdKeHoachSanXuatXuong']) ?></td>
                            <td><?= htmlspecialchars($workshopPlan['TenXuong'] ?? $workshopPlan['IdXuong']) ?></td>
                            <td><?= htmlspecialchars($workshopPlan['TenThanhThanhPhanSP']) ?></td>
                            <td><?= htmlspecialchars($workshopPlan['SoLuong']) ?></td>
                            <td>
                                <div class="text-muted small">BĐ: <?= $workshopPlan['ThoiGianBatDau'] ? date('d/m H:i', strtotime($workshopPlan['ThoiGianBatDau'])) : '-' ?></div>
                                <div class="text-muted small">KT: <?= $workshopPlan['ThoiGianKetThuc'] ? date('d/m H:i', strtotime($workshopPlan['ThoiGianKetThuc'])) : '-' ?></div>
                            </td>
                            <td><span class="badge bg-light text-dark"><?= htmlspecialchars($workshopPlan['TrangThai']) ?></span></td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-secondary" href="?controller=factory_plan&action=read&id=<?= urlencode($workshopPlan['IdKeHoachSanXuatXuong']) ?>">Xem</a>
                                <a class="btn btn-sm btn-outline-primary" href="?controller=factory_plan&action=edit&id=<?= urlencode($workshopPlan['IdKeHoachSanXuatXuong']) ?>">Sửa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
