<?php
$currentUser = $currentUser ?? [];
$shift = $shift ?? null;
$now = $now ?? date('Y-m-d H:i:s');
$openRecord = $openRecord ?? null;
$geofence = $geofence ?? null;

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

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1">Tự chấm công</h2>
        <p class="text-muted mb-0">Nhân viên sản xuất và kho tự chấm công ngay khi đến nơi làm.</p>
    </div>
    <div class="d-flex flex-wrap gap-2 align-items-center">
        <a href="?controller=self_timekeeping&action=history" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-calendar2-check me-1"></i>Lịch sử chấm công
        </a>
        <span class="badge rounded-pill text-bg-primary px-3 py-2">
            <i class="bi bi-clock-history me-1"></i><?= htmlspecialchars($formatDate($now, 'H:i:s')) ?>
        </span>
        <span class="badge rounded-pill text-bg-light px-3 py-2">
            <i class="bi bi-calendar-event me-1"></i><?= htmlspecialchars($formatDate($now, 'd/m/Y')) ?>
        </span>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="mb-1">Trạng thái ca hiện tại</h5>
                        <div class="text-muted small">Hệ thống xác định ca làm dựa trên thời gian hiện tại.</div>
                    </div>
                    <span class="badge text-bg-info px-3 py-2"><?= $shift ? 'Đang trong ca' : 'Chưa đến ca' ?></span>
                </div>

                <div class="border rounded-3 p-3 bg-light mb-4">
                    <?php if ($shift): ?>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="text-muted small">Ca làm việc</div>
                                <div class="fw-semibold"><?= htmlspecialchars($shift['TenCa'] ?? $shift['IdCaLamViec']) ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">Khung giờ</div>
                                <div class="fw-semibold">
                                    <?= htmlspecialchars($formatDate($shift['ThoiGianBatDau'] ?? null, 'H:i')) ?>
                                    →
                                    <?= htmlspecialchars($formatDate($shift['ThoiGianKetThuc'] ?? null, 'H:i')) ?>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-muted">Hiện tại không nằm trong ca làm việc. Vui lòng kiểm tra lại lịch ca.</div>
                    <?php endif; ?>
                </div>

                <form method="post" action="?controller=self_timekeeping&action=store" id="self-timekeeping-form">
                    <input type="hidden" name="latitude" id="geo-latitude" value="">
                    <input type="hidden" name="longitude" id="geo-longitude" value="">
                    <input type="hidden" name="accuracy" id="geo-accuracy" value="">

                    <div class="d-flex flex-wrap gap-3 align-items-center">
                        <button type="submit" class="btn btn-primary px-4" <?= $shift ? '' : 'disabled' ?> id="submit-button">
                            <i class="bi bi-fingerprint me-2"></i><?= $openRecord ? 'Ghi nhận giờ ra' : 'Ghi nhận giờ vào' ?>
                        </button>
                        <div class="text-muted small">
                            <?= $openRecord
                                ? 'Lần vào gần nhất: ' . htmlspecialchars($formatDate($openRecord['ThoiGianVao'] ?? null))
                                : 'Chưa có bản ghi vào ca hôm nay.' ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">Thông tin nhân sự</h6>
            </div>
            <div class="card-body">
                <div class="fw-semibold"><?= htmlspecialchars($currentUser['HoTen'] ?? 'Nhân viên') ?></div>
                <div class="text-muted small">Mã NV: <?= htmlspecialchars($currentUser['IdNhanVien'] ?? '-') ?></div>
                <div class="text-muted small">Vai trò: <?= htmlspecialchars($currentUser['TenVaiTro'] ?? $currentUser['IdVaiTro'] ?? '-') ?></div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">Vị trí chấm công</h6>
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
    let bestAccuracy = null;
    let consecutiveGood = 0;
    let watchId = null;

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

        if (bestAccuracy === null || accuracy < bestAccuracy) {
            bestAccuracy = accuracy;
        }

        if (accuracy > accuracyThreshold) {
            consecutiveGood = 0;
            status.textContent = `Đang tăng độ chính xác vị trí (tốt nhất ±${Math.round(bestAccuracy)}m, hiện ±${accuracyInput.value}m)...`;
            updateSubmitState(false);
            return;
        }

        consecutiveGood += 1;
        status.textContent = `Vị trí: ${latInput.value}, ${lngInput.value} (±${accuracyInput.value}m)`;
        if (consecutiveGood >= 2) {
            updateSubmitState(true);
            if (watchId !== null) {
                navigator.geolocation.clearWatch(watchId);
                watchId = null;
            }
        } else {
            updateSubmitState(false);
        }
    };

    const handlePositionError = () => {
        status.textContent = 'Không thể lấy vị trí. Vui lòng bật GPS hoặc cấp quyền định vị.';
        updateSubmitState(false);
    };

    watchId = navigator.geolocation.watchPosition(
        handlePosition,
        handlePositionError,
        { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
    );
})();
</script>
