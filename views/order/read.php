<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Chi tiết đơn hàng</h3>
        <p class="text-muted mb-0">Thông tin chi tiết về đơn hàng và khách hàng liên quan.</p>
    </div>
    <a href="?controller=order&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php if (!$order): ?>
    <div class="alert alert-warning">Không tìm thấy đơn hàng.</div>
<?php else: ?>
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card p-4">
                <h5 class="fw-semibold mb-3">Thông tin đơn hàng</h5>
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
                    <h6 class="fw-semibold">Yêu cầu sản xuất</h6>
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
<?php endif; ?>
