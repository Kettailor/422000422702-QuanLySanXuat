<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Chi tiết đơn hàng</h3>
        <p class="text-muted mb-0">Thông tin tổng quan đơn hàng và các cấu hình sản phẩm đã đặt.</p>
    </div>
    <a href="?controller=order&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php if (!$order): ?>
    <div class="alert alert-warning">Không tìm thấy đơn hàng.</div>
<?php else: ?>
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card p-4 h-100">
                <h5 class="fw-semibold mb-3">Thông tin đơn hàng</h5>
                <dl class="row mb-0">
                    <dt class="col-sm-5">Mã đơn hàng</dt>
                    <dd class="col-sm-7">#<?= htmlspecialchars($order['IdDonHang']) ?></dd>
                    <dt class="col-sm-5">Ngày lập</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars(!empty($order['NgayLap']) ? date('d/m/Y', strtotime($order['NgayLap'])) : '---') ?></dd>
                    <dt class="col-sm-5">Trạng thái</dt>
                    <dd class="col-sm-7"><span class="badge bg-info bg-opacity-25 text-primary"><?= htmlspecialchars($order['TrangThai'] ?? '---') ?></span></dd>
                    <dt class="col-sm-5">Tổng tiền</dt>
                    <dd class="col-sm-7 fw-semibold text-primary"><?= number_format((float) ($order['TongTien'] ?? 0), 0, ',', '.') ?> đ</dd>
                    <dt class="col-sm-5">Email liên hệ</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($order['EmailLienHe'] ?? '---') ?></dd>
                </dl>
                <?php if (!empty($order['YeuCau'])): ?>
                    <div class="mt-3">
                        <h6 class="fw-semibold">Yêu cầu chung</h6>
                        <p class="text-muted mb-0"><?= nl2br(htmlspecialchars($order['YeuCau'])) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card p-4 h-100">
                <h5 class="fw-semibold mb-3">Khách hàng</h5>
                <?php if ($customer): ?>
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Họ tên</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($customer['HoTen'] ?? '---') ?></dd>
                        <dt class="col-sm-5">Công ty dự án</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($customer['TenCongTy'] ?? '---') ?></dd>
                        <dt class="col-sm-5">Loại khách hàng</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($customer['LoaiKhachHang'] ?? '---') ?></dd>
                        <dt class="col-sm-5">Số điện thoại</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($customer['SoDienThoai'] ?? '---') ?></dd>
                        <dt class="col-sm-5">Email</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($customer['Email'] ?? '---') ?></dd>
                        <dt class="col-sm-5">Địa chỉ</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($customer['DiaChi'] ?? '---') ?></dd>
                    </dl>
                <?php else: ?>
                    <p class="text-muted mb-0">Không có thông tin khách hàng.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card p-4 mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-semibold mb-0">Danh sách sản phẩm</h5>
            <span class="badge bg-light text-dark">Tổng số dòng: <?= count($orderDetails) ?></span>
        </div>
        <?php if (!empty($orderDetails)): ?>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Cấu hình</th>
                        <th class="text-center">Số lượng</th>
                        <th>Đơn giá</th>
                        <th>VAT</th>
                        <th>Ngày giao</th>
                        <th class="text-end">Thành tiền</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $totalAmount = 0; ?>
                    <?php foreach ($orderDetails as $detail): ?>
                        <?php
                        $totalAmount += (float) ($detail['ThanhTien'] ?? 0);
                        $meta = $detail['meta'] ?? [];
                        $config = $meta['configuration'] ?? [];
                        $note = $meta['note'] ?? '';
                        ?>
                        <tr>
                            <td>
                                <div class="fw-semibold"><?= htmlspecialchars($detail['TenSanPham'] ?? '---') ?></div>
                                <?php if (!empty($detail['YeuCau'])): ?>
                                    <div class="text-muted small">Yêu cầu: <?= htmlspecialchars($detail['YeuCau']) ?></div>
                                <?php endif; ?>
                                <?php if (!empty($note)): ?>
                                    <div class="text-muted small">Ghi chú: <?= nl2br(htmlspecialchars($note)) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-medium"><?= htmlspecialchars($detail['TenCauHinh'] ?? 'Cấu hình tùy chỉnh') ?></div>
                                <ul class="list-unstyled text-muted small mb-0">
                                    <?php if (!empty($config['description'])): ?><li>Mô tả: <?= htmlspecialchars($config['description']) ?></li><?php endif; ?>
                                    <?php if (!empty($config['layout'])): ?><li>Layout: <?= htmlspecialchars($config['layout']) ?></li><?php endif; ?>
                                    <?php if (!empty($config['switch_type'])): ?><li>Switch: <?= htmlspecialchars($config['switch_type']) ?></li><?php endif; ?>
                                    <?php if (!empty($config['case_type'])): ?><li>Case: <?= htmlspecialchars($config['case_type']) ?></li><?php endif; ?>
                                    <?php if (!empty($config['foam'])): ?><li>Foam: <?= htmlspecialchars($config['foam']) ?></li><?php endif; ?>
                                </ul>
                            </td>
                            <td class="text-center"><?= number_format((int) ($detail['SoLuong'] ?? 0)) ?></td>
                            <td><?= number_format((float) ($detail['DonGia'] ?? 0), 0, ',', '.') ?> đ</td>
                            <td><?= isset($detail['VAT']) ? number_format((float) $detail['VAT'] * 100, 1) : '0.0' ?>%</td>
                            <td>
                                <?php if (!empty($detail['NgayGiao'])): ?>
                                    <?= date('d/m/Y H:i', strtotime($detail['NgayGiao'])) ?>
                                <?php else: ?>
                                    <span class="text-muted">Chưa xác định</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end fw-semibold text-primary"><?= number_format((float) ($detail['ThanhTien'] ?? 0), 0, ',', '.') ?> đ</td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="text-end mt-2">
                <span class="text-muted me-2">Tổng cộng:</span>
                <span class="fw-bold fs-5 text-primary"><?= number_format($totalAmount, 0, ',', '.') ?> đ</span>
            </div>
        <?php else: ?>
            <p class="text-muted mb-0">Đơn hàng chưa có sản phẩm nào.</p>
        <?php endif; ?>
    </div>
<?php endif; ?>
