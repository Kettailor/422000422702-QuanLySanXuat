<?php
$currentUser = $currentUser ?? [];
$shift = $shift ?? null;
$now = $now ?? date('Y-m-d H:i:s');
$openRecord = $openRecord ?? null;
$geofence = $geofence ?? null;
$shifts = $shifts ?? [];
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
            <h3 class="fw-bold mb-1">Tự chấm công</h3>
            <div class="text-muted small"><?= htmlspecialchars($formatDate($now, 'd/m/Y')) ?></div>
        </div>
        <a href="?controller=self_timekeeping&action=index" class="btn btn-light border">
            <i class="bi bi-layout-text-window-reverse"></i>
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">Xin chào</div>
                    <div class="fw-semibold"><?= htmlspecialchars($currentUser['HoTen'] ?? 'Nhân viên') ?></div>
                </div>
                <span class="badge text-bg-primary px-3 py-2"><?= htmlspecialchars($formatDate($now, 'H:i:s')) ?></span>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-semibold">Chấm công nhanh</div>
                <span class="badge text-bg-info"><?= $shift ? 'Trong ca' : 'Ngoài ca' ?></span>
            </div>
            <div class="text-muted small mb-3">
                <?= $shift
                    ? 'Ca ' . htmlspecialchars($shift['TenCa'] ?? $shift['IdCaLamViec']) . ' (' .
                        htmlspecialchars($formatDate($shift['ThoiGianBatDau'] ?? null, 'H:i')) . ' - ' .
                        htmlspecialchars($formatDate($shift['ThoiGianKetThuc'] ?? null, 'H:i')) . ')'
                    : 'Chưa đến ca làm hoặc đã hết ca.' ?>
            </div>

            <form method="post" action="?controller=self_timekeeping&action=store" id="self-timekeeping-mobile-form">
                <input type="hidden" name="latitude" id="geo-latitude" value="">
                <input type="hidden" name="longitude" id="geo-longitude" value="">
                <input type="hidden" name="accuracy" id="geo-accuracy" value="">
                <button type="submit" class="btn btn-primary w-100 py-3 fw-semibold" <?= $shift ? '' : 'disabled' ?> id="submit-button">
                    <i class="bi bi-fingerprint me-2"></i><?= $openRecord ? 'Ghi nhận giờ ra' : 'Ghi nhận giờ vào' ?>
                </button>
            </form>
            <div class="text-muted small mt-2">
                <?= $openRecord
                    ? 'Lần vào gần nhất: ' . htmlspecialchars($formatDate($openRecord['ThoiGianVao'] ?? null))
                    : 'Chưa có bản ghi vào ca hôm nay.' ?>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="fw-semibold mb-2">Vị trí chấm công</div>
            <div id="geo-status" class="text-muted small">Đang xác định vị trí...</div>
            <?php if ($geofence): ?>
                <div class="text-muted small mt-2">Bán kính cho phép: <?= htmlspecialchars((string) $geofence['radius']) ?>m.</div>
            <?php else: ?>
                <div class="text-muted small mt-2">Hệ thống sẽ lưu toạ độ khi có thể.</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white border-0">
            <h6 class="mb-0">Lịch kế hoạch hôm nay</h6>
        </div>
        <div class="card-body">
            <?php if (empty($shifts)): ?>
                <div class="text-muted small">Chưa có ca làm việc cho hôm nay.</div>
            <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($shifts as $item): ?>
                        <div class="list-group-item px-0">
                            <div class="fw-semibold"><?= htmlspecialchars($item['TenCa'] ?? $item['IdCaLamViec']) ?></div>
                            <div class="text-muted small">
                                <?= htmlspecialchars($formatDate($item['ThoiGianBatDau'] ?? null, 'H:i')) ?>
                                →
                                <?= htmlspecialchars($formatDate($item['ThoiGianKetThuc'] ?? null, 'H:i')) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white border-0">
            <h6 class="mb-0">Thông báo quan trọng</h6>
        </div>
        <div class="card-body">
            <?php if (empty($importantNotifications)): ?>
                <div class="text-muted small">Không có thông báo quan trọng.</div>
            <?php else: ?>
                <?php foreach ($importantNotifications as $notification): ?>
                    <?php $message = $notification['message'] ?? $notification['title'] ?? 'Thông báo'; ?>
                    <div class="alert alert-warning small mb-2">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0">
            <h6 class="mb-0">Thông báo mới</h6>
        </div>
        <div class="card-body">
            <?php if (empty($notifications)): ?>
                <div class="text-muted small">Chưa có thông báo nào.</div>
            <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach (array_slice($notifications, 0, 6) as $notification): ?>
                        <?php $message = $notification['message'] ?? $notification['title'] ?? 'Thông báo'; ?>
                        <div class="list-group-item px-0">
                            <div class="fw-semibold"><?= htmlspecialchars($message) ?></div>
                            <div class="text-muted small"><?= htmlspecialchars($formatDate($notification['created_at'] ?? null)) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <a href="?controller=notifications&action=index" class="btn btn-link px-0">Xem tất cả thông báo</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="d-none d-lg-block">
    <div class="alert alert-info">
        Giao diện này được tối ưu cho thiết bị di động. Vui lòng dùng trình duyệt di động hoặc quay lại
        <a href="?controller=self_timekeeping&action=index" class="alert-link">giao diện đầy đủ</a>.
    </div>
</div>

<script>
(() => {
    const status = document.getElementById('geo-status');
    const latInput = document.getElementById('geo-latitude');
    const lngInput = document.getElementById('geo-longitude');
    const accuracyInput = document.getElementById('geo-accuracy');
    const submitButton = document.getElementById('submit-button');
    const accuracyThreshold = 100;
    const baseDisabled = submitButton ? submitButton.hasAttribute('disabled') : true;

    if (!navigator.geolocation) {
        status.textContent = 'Trình duyệt không hỗ trợ định vị.';
        return;
    }

    if (submitButton) {
        submitButton.disabled = true;
        submitButton.classList.add('disabled');
    }

    const updateSubmitState = (isReady) => {
        if (!submitButton) {
            return;
        }
        if (baseDisabled) {
            submitButton.disabled = true;
            submitButton.classList.add('disabled');
            return;
        }
        submitButton.disabled = !isReady;
        submitButton.classList.toggle('disabled', !isReady);
    };

    const handlePosition = (position) => {
        const { latitude, longitude, accuracy } = position.coords;
        latInput.value = latitude.toFixed(6);
        lngInput.value = longitude.toFixed(6);
        accuracyInput.value = Math.round(accuracy);

        if (accuracy > accuracyThreshold) {
            status.textContent = `Đang tăng độ chính xác vị trí (±${accuracyInput.value}m)...`;
            updateSubmitState(false);
            return;
        }

        status.textContent = `Vị trí: ${latInput.value}, ${lngInput.value} (±${accuracyInput.value}m)`;
        updateSubmitState(true);
    };

    const handlePositionError = () => {
        status.textContent = 'Không thể lấy vị trí. Vui lòng bật GPS hoặc cấp quyền định vị.';
        updateSubmitState(false);
    };

    navigator.geolocation.watchPosition(
        handlePosition,
        handlePositionError,
        { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
    );
})();
</script>
