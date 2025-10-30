<?php
$plan = $plan ?? null;
$stockNeed = $stock_list_need;
// var_dump($stockNeed);

$formatDate = static function (?string $value, string $format = 'd/m/Y H:i'): string {
    if (!$value) {
        return '-';
    }
    $timestamp = strtotime($value);
    if ($timestamp === false) {
        return '-';
    }
    return date($format, $timestamp);
};

$statusBadge = static function (string $status): string {
    $normalized = strtolower(trim($status));
    if ($normalized === '') {
        return 'badge bg-light text-muted';
    }
    if (str_contains($normalized, 'hoàn thành')) {
        return 'badge bg-success-subtle text-success';
    }
    if (str_contains($normalized, 'đang')) {
        return 'badge bg-primary-subtle text-primary';
    }
    if (str_contains($normalized, 'chờ')) {
        return 'badge bg-warning-subtle text-warning';
    }
    if (str_contains($normalized, 'tạm')) {
        return 'badge bg-secondary-subtle text-secondary';
    }
    return 'badge bg-info-subtle text-info';
};
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1">Chi tiết nhiệm vụ xưởng</h2>
        <?php if ($plan): ?>
            <p class="text-muted mb-0">Theo dõi công đoạn <?= htmlspecialchars($plan['TenThanhThanhPhanSP'] ?? '-') ?> của đơn hàng <?= htmlspecialchars($plan['IdDonHang'] ?? '-') ?>.</p>
        <?php else: ?>
            <p class="text-muted mb-0">Không tìm thấy thông tin nhiệm vụ.</p>
        <?php endif; ?>
    </div>
    <a href="?controller=factory_plan&action=index" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Quay về kế hoạch xưởng
    </a>
</div>

<?php if (!$plan): ?>
    <div class="alert alert-warning">Không có dữ liệu cho nhiệm vụ này.</div>
<?php else: ?>
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase">Hạng mục</div>
                    <div class="fs-4 fw-semibold mt-2"><?= htmlspecialchars($plan['TenThanhThanhPhanSP'] ?? '-') ?></div>
                    <?php if (!empty($plan['TenXuong'])): ?>
                        <div class="text-muted small mt-2">Xưởng phụ trách: <?= htmlspecialchars($plan['TenXuong']) ?></div>
                    <?php endif; ?>
                    <div class="mt-3">
                        <span class="<?= $statusBadge((string) ($plan['TrangThai'] ?? '')) ?>">
                            <?= htmlspecialchars($plan['TrangThai'] ?? 'Chưa cập nhật') ?>
                        </span>
                        <?php if (!empty($plan['TinhTrangVatTu'])): ?>
                            <span class="badge bg-light text-muted ms-2">Vật tư: <?= htmlspecialchars($plan['TinhTrangVatTu']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase">Thời gian thực hiện</div>
                    <div class="fw-semibold mt-2">Bắt đầu: <?= $formatDate($plan['ThoiGianBatDau'] ?? null) ?></div>
                    <div class="fw-semibold">Hạn chót: <?= $formatDate($plan['ThoiGianKetThuc'] ?? null) ?></div>
                    <div class="text-muted small mt-2">Số lượng: <?= htmlspecialchars((string) ($plan['SoLuong'] ?? 0)) ?> <?= htmlspecialchars($plan['DonVi'] ?? 'sp') ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase">Thông tin liên quan</div>
                    <div class="fw-semibold mt-2">Mã kế hoạch tổng: <?= htmlspecialchars($plan['IdKeHoachSanXuat'] ?? '-') ?></div>
                    <?php if (!empty($plan['TenSanPham'])): ?>
                        <div class="text-muted small mt-2">Sản phẩm: <?= htmlspecialchars($plan['TenSanPham']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($plan['TenCauHinh'])): ?>
                        <div class="text-muted small">Cấu hình: <?= htmlspecialchars($plan['TenCauHinh']) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0">Quản Lý Nguyên Liệu</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tên nguyên liệu</th>
                            <th class="text-end">Số lượng cần</th>
                            <th class="text-end">Số lượng tồn</th>
                            <th>Tên lô</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  foreach($stockNeed as $tmp ) { ?>
                        <tr>
                            <td>
                                <div class="fw-semibold"><?= $tmp['TenNL'] ?></div>
                                <div class="small text-muted">Mã: <?= $tmp['IdNguyenLieu'] ?></div>
                            </td>
                            <td class="text-end"><?= $tmp['SoLuongCan'] ?></td>
                            <td class="text-end"><?= $tmp['SoLuongTon'] ?></td>
                            <td><?= $tmp['TenLo'] ?></td>
                            <td class="text-center">
                                <?php if( $tmp['SoLuongTon'] <= $tmp['SoLuongCan'] ) { ?>
                                <button class="btn btn-sm btn-outline-primary send-notification-btn"
                                        data-id-nguyen-lieu="<?= htmlspecialchars($tmp['IdNguyenLieu']) ?>"
                                        data-ten-nl="<?= htmlspecialchars($tmp['TenNL']) ?>"
                                        data-so-luong-can="<?= htmlspecialchars($tmp['SoLuongCan']) ?>"
                                        data-so-luong-ton="<?= htmlspecialchars($tmp['SoLuongTon']) ?>"
                                        data-ten-lo="<?= htmlspecialchars($tmp['TenLo']) ?>"
                                        data-id-ke-hoach-san-xuat-xuong="<?= htmlspecialchars($plan['IdKeHoachSanXuatXuong']) ?>">
                                    Gửi thông báo
                                </button>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.send-notification-btn');

    buttons.forEach(button => {
        button.addEventListener('click', async function() {
            const materialData = {
                IdNguyenLieu: this.dataset.idNguyenLieu,
                TenNL: this.dataset.tenNl,
                SoLuongCan: this.dataset.soLuongCan,
                SoLuongTon: this.dataset.soLuongTon,
                TenLo: this.dataset.tenLo,
                IdKeHoachSanXuatXuong: this.dataset.idKeHoachSanXuatXuong
            };

            try {
                const response = await fetch('?controller=factory_plan&action=sendMaterialNotification', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(materialData)
                });

                const result = await response.json();

                if (response.ok) {
                    alert('Thông báo đã được gửi thành công: ' + result.message);
                } else {
                    alert('Lỗi khi gửi thông báo: ' + result.message);
                }
            } catch (error) {
                console.error('Lỗi mạng hoặc lỗi khác:', error);
                alert('Đã xảy ra lỗi khi gửi thông báo.');
            }
        });
    });
});
</script>
<?php endif; ?>
