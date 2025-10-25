<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Chi tiết đơn Aurora</h3>
        <p class="text-muted mb-0">Thông tin chi tiết về đơn bàn phím Aurora và khách hàng liên quan.</p>
    </div>
    <a href="?controller=order&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php if (!$order): ?>
    <div class="alert alert-warning">Không tìm thấy đơn hàng.</div>
<?php else: ?>
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card p-4">
                <h5 class="fw-semibold mb-3">Thông tin đơn Aurora</h5>
                <dl class="row mb-0">
                    <dt class="col-sm-5">Mã đơn hàng</dt>
                    <dd class="col-sm-7">#<?= htmlspecialchars($order['IdDonHang']) ?></dd>
                    <dt class="col-sm-5">Ngày lập</dt>
                    <dd class="col-sm-7"><?= date('d/m/Y', strtotime($order['NgayLap'])) ?></dd>
                    <dt class="col-sm-5">Trạng thái</dt>
                    <dd class="col-sm-7"><span class="badge bg-info bg-opacity-25 text-primary"><?= htmlspecialchars($order['TrangThai']) ?></span></dd>
                    <dt class="col-sm-5">Tổng tiền</dt>
                    <dd class="col-sm-7 fw-semibold text-primary"><?= number_format($order['TongTien'], 0, ',', '.') ?> đ</dd>
                </dl>
                <div class="mt-3">
                    <h6 class="fw-semibold">Yêu cầu cấu hình Aurora</h6>
                    <p class="text-muted mb-0"><?= nl2br(htmlspecialchars($order['YeuCau'])) ?></p>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card p-4">
                <h5 class="fw-semibold mb-3">Khách hàng</h5>
                <?php if ($customer): ?>
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Họ tên</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($customer['HoTen']) ?></dd>
                        <dt class="col-sm-5">Loại khách hàng</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($customer['LoaiKhachHang']) ?></dd>
                        <dt class="col-sm-5">Số điện thoại</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($customer['SoDienThoai']) ?></dd>
                        <dt class="col-sm-5">Địa chỉ</dt>
                        <dd class="col-sm-7"><?= htmlspecialchars($customer['DiaChi']) ?></dd>
                    </dl>
                <?php else: ?>
                    <p class="text-muted">Không có thông tin khách hàng.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="row g-4 mt-1">
        <div class="col-12">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-semibold mb-0">Danh sách cấu hình Aurora</h5>
                    <span class="badge bg-light text-dark">Tổng số dòng: <?= count($orderDetails) ?></span>
                </div>
                <?php if (!empty($orderDetails)): ?>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                            <tr>
                                <th>Cấu hình Aurora</th>
                                <th class="text-center">Số lượng</th>
                                <th>Đơn giá</th>
                                <th>VAT</th>
                                <th>Thời gian giao</th>
                                <th>Yêu cầu Aurora</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $totalAmount = 0; ?>
                            <?php foreach ($orderDetails as $detail): ?>
                                <?php $totalAmount += (float) $detail['ThanhTien']; ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><?= htmlspecialchars($detail['TenSanPham'] ?? $detail['IdSanPham']) ?></div>
                                        <div class="text-muted small">Mã: <?= htmlspecialchars($detail['IdSanPham']) ?></div>
                                    </td>
                                    <td class="text-center"><?= number_format($detail['SoLuong']) ?></td>
                                    <td><?= number_format($detail['DonGia'], 0, ',', '.') ?> đ</td>
                                    <td><?= isset($detail['VAT']) ? (float) $detail['VAT'] * 100 : 0 ?>%</td>
                                    <td>
                                        <?php if (!empty($detail['NgayGiao'])): ?>
                                            <?= date('d/m/Y H:i', strtotime($detail['NgayGiao'])) ?>
                                        <?php else: ?>
                                            <span class="text-muted">Chưa xác định</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-muted small">
                                        <?= nl2br(htmlspecialchars($detail['YeuCau'] ?? '')) ?>
                                        <?php if (!empty($detail['GhiChu'])): ?>
                                            <div class="mt-1">Ghi chú: <?= nl2br(htmlspecialchars($detail['GhiChu'])) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end fw-semibold text-primary"><?= number_format($detail['ThanhTien'], 0, ',', '.') ?> đ</td>
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
                    <p class="text-muted mb-0">Đơn Aurora chưa có cấu hình nào.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="row g-4 mt-1">
        <div class="col-12">
            <form action="?controller=order&action=updateStatuses" method="post" class="card p-4">
                <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['IdDonHang']) ?>">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-semibold mb-0">Liên kết kế hoạch &amp; hóa đơn</h5>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-repeat me-2"></i>Cập nhật trạng thái</button>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="border rounded p-3 h-100">
                            <h6 class="fw-semibold mb-3">Trạng thái đơn Aurora</h6>
                            <select name="order_status" class="form-select">
                                <option value="">-- Giữ nguyên --</option>
                                <?php foreach ($orderStatuses as $status): ?>
                                    <option value="<?= htmlspecialchars($status) ?>" <?= $status === $order['TrangThai'] ? 'selected' : '' ?>><?= htmlspecialchars($status) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p class="text-muted small mb-0 mt-2">Cập nhật trạng thái chung của đơn hàng.</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="border rounded p-3 h-100">
                            <h6 class="fw-semibold mb-3">Kế hoạch Aurora</h6>
                            <?php if (!empty($plans)): ?>
                                <?php foreach ($plans as $plan): ?>
                                    <div class="mb-3">
                                        <div class="fw-semibold">#<?= htmlspecialchars($plan['IdKeHoachSanXuat']) ?></div>
                                        <div class="text-muted small mb-2">Cấu hình Aurora: <?= htmlspecialchars($plan['IdSanPham'] ?? '---') ?> | SL: <?= number_format($plan['SoLuongChiTiet'] ?? 0) ?></div>
                                        <select name="plan_statuses[<?= htmlspecialchars($plan['IdKeHoachSanXuat']) ?>]" class="form-select">
                                            <?php foreach ($planStatuses as $status): ?>
                                                <option value="<?= htmlspecialchars($status) ?>" <?= $status === $plan['TrangThai'] ? 'selected' : '' ?>><?= htmlspecialchars($status) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted mb-0">Chưa có kế hoạch Aurora cho đơn này.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="border rounded p-3 h-100">
                            <h6 class="fw-semibold mb-3">Hóa đơn Aurora liên quan</h6>
                            <?php if (!empty($bills)): ?>
                                <?php foreach ($bills as $bill): ?>
                                    <div class="mb-3">
                                        <div class="fw-semibold">#<?= htmlspecialchars($bill['IdHoaDon']) ?></div>
                                        <div class="text-muted small mb-2">Ngày lập: <?= !empty($bill['NgayLap']) ? date('d/m/Y', strtotime($bill['NgayLap'])) : '---' ?></div>
                                        <select name="bill_statuses[<?= htmlspecialchars($bill['IdHoaDon']) ?>]" class="form-select">
                                            <?php foreach ($billStatuses as $status): ?>
                                                <option value="<?= htmlspecialchars($status) ?>" <?= $status === $bill['TrangThai'] ? 'selected' : '' ?>><?= htmlspecialchars($status) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted mb-0">Chưa phát sinh hóa đơn cho đơn hàng này.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>
