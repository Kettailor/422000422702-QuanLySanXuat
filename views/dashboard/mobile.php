<?php
$currentUser = $currentUser ?? [];
$shift = $shift ?? null;
$now = $now ?? date('Y-m-d H:i:s');
$openRecord = $openRecord ?? null;
$geofence = $geofence ?? null;
$role = $role ?? null;
$productionPlans = $productionPlans ?? [];
$workshopPlans = $workshopPlans ?? [];
$orders = $orders ?? [];
$qualityLots = $qualityLots ?? [];
$notifications = $notifications ?? [];
$importantNotifications = $importantNotifications ?? [];

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
?>

<div class="d-lg-none">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="text-muted small">Xin chào</div>
            <h4 class="fw-bold mb-0"><?= htmlspecialchars($currentUser['HoTen'] ?? 'Nhân viên') ?></h4>
            <div class="text-muted small">Mã NV: <?= htmlspecialchars($currentUser['IdNhanVien'] ?? '-') ?></div>
        </div>
        <a href="?controller=auth&action=profile" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-person-circle me-1"></i>Hồ sơ
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">Ca hiện tại</div>
                    <div class="fw-semibold"><?= $shift ? htmlspecialchars($shift['TenCa'] ?? $shift['IdCaLamViec']) : 'Chưa đến ca' ?></div>
                    <div class="text-muted small">
                        <?= $shift
                            ? htmlspecialchars($formatDate($shift['ThoiGianBatDau'] ?? null, 'H:i'))
                                . ' → ' . htmlspecialchars($formatDate($shift['ThoiGianKetThuc'] ?? null, 'H:i'))
                            : 'Chưa có ca làm việc phù hợp' ?>
                    </div>
                </div>
                <a href="?controller=self_timekeeping&action=index" class="btn btn-primary btn-sm">
                    <?= $openRecord ? 'Giờ ra' : 'Giờ vào' ?>
                </a>
            </div>
            <div class="text-muted small mt-2">
                <?= $openRecord
                    ? 'Lần vào gần nhất: ' . htmlspecialchars($formatDate($openRecord['ThoiGianVao'] ?? null))
                    : 'Chưa có bản ghi vào ca hôm nay.' ?>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Vị trí chấm công</h6>
                <a href="?controller=self_timekeeping&action=index" class="btn btn-outline-primary btn-sm">
                    Tự chấm công
                </a>
            </div>
        </div>
        <div class="card-body">
            <div id="geo-status" class="text-muted small">Đang xác định vị trí...</div>
            <?php if ($geofence): ?>
                <div class="text-muted small mt-2">
                    Phạm vi cho phép: bán kính <?= htmlspecialchars((string) $geofence['radius']) ?>m.
                </div>
            <?php else: ?>
                <div class="text-muted small mt-2">Hệ thống sẽ ghi lại toạ độ khi có thể.</div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($role === 'VT_BAN_GIAM_DOC'): ?>
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">Gửi thông báo nhanh</h6>
            </div>
            <div class="card-body">
                <form method="post" action="?controller=dashboard&action=sendNotification">
                    <div class="mb-2">
                        <input type="text" name="title" class="form-control" placeholder="Tiêu đề thông báo">
                    </div>
                    <div class="mb-2">
                        <textarea name="message" rows="3" class="form-control" placeholder="Nội dung gửi đến toàn bộ nhân viên"></textarea>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <select name="priority" class="form-select form-select-sm w-auto">
                            <option value="normal">Bình thường</option>
                            <option value="important">Quan trọng</option>
                            <option value="urgent">Khẩn</option>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-send me-1"></i>Gửi đi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($productionPlans)): ?>
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Kế hoạch sản xuất</h6>
                <a href="?controller=plan&action=index" class="btn btn-outline-secondary btn-sm">Xem tất cả</a>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <?php foreach ($productionPlans as $plan): ?>
                        <div class="list-group-item px-0">
                            <div class="fw-semibold">
                                <?= htmlspecialchars($plan['TenSanPham'] ?? 'Kế hoạch sản xuất') ?>
                            </div>
                            <div class="text-muted small">
                                ĐH <?= htmlspecialchars($plan['IdDonHang'] ?? '-') ?>
                                · <?= htmlspecialchars($formatDate($plan['ThoiGianBD'] ?? null, 'd/m/Y')) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($role === 'VT_KINH_DOANH'): ?>
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Đơn hàng của tôi</h6>
                <a href="?controller=order&action=index" class="btn btn-outline-secondary btn-sm">Xem tất cả</a>
            </div>
            <div class="card-body">
                <?php if (empty($orders)): ?>
                    <div class="text-muted small">Chưa có đơn hàng được ghi nhận.</div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($orders as $order): ?>
                            <div class="list-group-item px-0">
                                <div class="fw-semibold">ĐH <?= htmlspecialchars($order['IdDonHang'] ?? '-') ?></div>
                                <div class="text-muted small">
                                    <?= htmlspecialchars($order['TenKhachHang'] ?? '-') ?>
                                    · <?= htmlspecialchars($formatDate($order['NgayLap'] ?? null, 'd/m/Y')) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($role === 'VT_KIEM_SOAT_CL'): ?>
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Lô cần đánh giá</h6>
                <a href="?controller=quality&action=index" class="btn btn-outline-secondary btn-sm">Xem tất cả</a>
            </div>
            <div class="card-body">
                <?php if (empty($qualityLots)): ?>
                    <div class="text-muted small">Không có lô chờ đánh giá.</div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($qualityLots as $lot): ?>
                            <div class="list-group-item px-0">
                                <div class="fw-semibold"><?= htmlspecialchars($lot['TenLo'] ?? '-') ?></div>
                                <div class="text-muted small">
                                    <?= htmlspecialchars($lot['TenXuong'] ?? '-') ?>
                                    · SL <?= htmlspecialchars((string) ($lot['SoLuong'] ?? '-')) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="text-muted small mt-2">Có thể đánh giá chất lượng và kiểm tra đột xuất trên thiết bị di động.</div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($workshopPlans)): ?>
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Kế hoạch xưởng</h6>
                <a href="?controller=factory_plan&action=index" class="btn btn-outline-secondary btn-sm">Xem tất cả</a>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <?php foreach (array_slice($workshopPlans, 0, 5) as $plan): ?>
                        <div class="list-group-item px-0">
                            <div class="fw-semibold"><?= htmlspecialchars($plan['TenThanhThanhPhanSP'] ?? 'Kế hoạch xưởng') ?></div>
                            <div class="text-muted small">
                                <?= htmlspecialchars($plan['TenXuong'] ?? '-') ?>
                                · <?= htmlspecialchars($formatDate($plan['ThoiGianBatDau'] ?? null, 'd/m/Y')) ?>
                                <?php if (!empty($plan['TinhTrangVatTu'])): ?>
                                    · <?= htmlspecialchars($plan['TinhTrangVatTu']) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($importantNotifications)): ?>
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">Thông báo quan trọng</h6>
            </div>
            <div class="card-body">
                <?php foreach (array_slice($importantNotifications, 0, 3) as $notification): ?>
                    <?php $message = $notification['message'] ?? $notification['title'] ?? 'Thông báo'; ?>
                    <div class="alert alert-warning small mb-2">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Thông báo</h6>
            <a href="?controller=notifications&action=index" class="btn btn-outline-secondary btn-sm">Xem tất cả</a>
        </div>
        <div class="card-body">
            <?php if (empty($notifications)): ?>
                <div class="text-muted small">Chưa có thông báo mới.</div>
            <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach (array_slice($notifications, 0, 5) as $notification): ?>
                        <?php if (!is_array($notification)) {
                            continue;
                        } ?>
                        <div class="list-group-item px-0">
                            <div class="fw-semibold"><?= htmlspecialchars($notification['title'] ?? 'Thông báo hệ thống') ?></div>
                            <?php if (!empty($notification['message'])): ?>
                                <div class="text-muted small"><?= htmlspecialchars($notification['message']) ?></div>
                            <?php endif; ?>
                            <?php if (!empty($notification['time'])): ?>
                                <div class="text-muted small"><?= htmlspecialchars($notification['time']) ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
(() => {
    const status = document.getElementById('geo-status');
    if (!status) {
        return;
    }
    if (!navigator.geolocation) {
        status.textContent = 'Trình duyệt không hỗ trợ định vị.';
        return;
    }

    navigator.geolocation.watchPosition(
        (position) => {
            const { latitude, longitude, accuracy } = position.coords;
            status.textContent = `Vị trí: ${latitude.toFixed(6)}, ${longitude.toFixed(6)} (±${Math.round(accuracy)}m)`;
        },
        () => {
            status.textContent = 'Không thể lấy vị trí. Vui lòng bật GPS hoặc cấp quyền định vị.';
        },
        { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
    );
})();
</script>
