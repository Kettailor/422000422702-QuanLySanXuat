<?php

class Self_timekeepingController extends Controller
{
    private Timekeeping $timekeepingModel;
    private WorkShift $workShiftModel;

    public function __construct()
    {
        $user = $this->currentUser();
        if (!$user) {
            $this->setFlash('danger', 'Vui lòng đăng nhập để tiếp tục.');
            $this->redirect('?controller=auth&action=login');
        }
        $this->timekeepingModel = new Timekeeping();
        $this->workShiftModel = new WorkShift();
    }

    public function index(): void
    {
        if ($this->isMobileRequest()) {
            $this->redirect('?controller=self_timekeeping&action=mobile');
            return;
        }

        $user = $this->currentUser();
        $employeeId = $user['IdNhanVien'] ?? null;
        $now = date('Y-m-d H:i:s');
        $shift = $this->workShiftModel->findShiftForTimestamp($now);
        $workDate = date('Y-m-d');
        $openRecord = $employeeId ? $this->timekeepingModel->getOpenRecordForEmployee($employeeId, $workDate) : null;
        $geofence = $this->getGeofenceConfig();

        $this->render('self_timekeeping/index', [
            'title' => 'Tự chấm công',
            'currentUser' => $user,
            'now' => $now,
            'shift' => $shift,
            'openRecord' => $openRecord,
            'geofence' => $geofence,
        ]);
    }

    public function mobile(): void
    {
        $user = $this->currentUser();
        $employeeId = $user['IdNhanVien'] ?? null;
        $workDate = date('Y-m-d');
        $now = date('Y-m-d H:i:s');
        $shift = $this->workShiftModel->findShiftForTimestamp($now);
        $openRecord = $employeeId ? $this->timekeepingModel->getOpenRecordForEmployee($employeeId, $workDate) : null;
        $geofence = $this->getGeofenceConfig();
        $shifts = $this->workShiftModel->getShifts($workDate);
        $roleId = $user['ActualIdVaiTro'] ?? ($user['IdVaiTro'] ?? null);
        $notifications = $this->loadNotifications($employeeId, $roleId);

        $this->render('self_timekeeping/mobile', [
            'title' => 'Tự chấm công (Mobile)',
            'currentUser' => $user,
            'now' => $now,
            'shift' => $shift,
            'openRecord' => $openRecord,
            'geofence' => $geofence,
            'shifts' => $shifts,
            'notifications' => $notifications['all'],
            'importantNotifications' => $notifications['important'],
        ]);
    }

    public function history(): void
    {
        $user = $this->currentUser();
        $employeeId = $user['IdNhanVien'] ?? null;

        if (!$employeeId) {
            $this->setFlash('danger', 'Không xác định được nhân sự.');
            $this->redirect('?controller=self_timekeeping&action=index');
            return;
        }

        $records = $this->timekeepingModel->getRecentRecords(200, null, null, null, null, $employeeId);

        $this->render('self_timekeeping/history', [
            'title' => 'Lịch sử chấm công',
            'records' => $records,
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=self_timekeeping&action=index');
            return;
        }

        $user = $this->currentUser();
        $employeeId = $user['IdNhanVien'] ?? null;
        if (!$employeeId) {
            $this->setFlash('danger', 'Không xác định được nhân sự để chấm công.');
            $this->redirect('?controller=self_timekeeping&action=index');
            return;
        }

        $now = date('Y-m-d H:i:s');
        $shift = $this->workShiftModel->findShiftForTimestamp($now);
        if (!$shift) {
            $this->setFlash('danger', 'Hiện tại không nằm trong ca làm việc nào.');
            $this->redirect('?controller=self_timekeeping&action=index');
            return;
        }

        $latitude = trim($_POST['latitude'] ?? '');
        $longitude = trim($_POST['longitude'] ?? '');
        $accuracy = trim($_POST['accuracy'] ?? '');
        $location = $this->normalizeLocation($latitude, $longitude, $accuracy);
        $geofence = $this->getGeofenceConfig();

        if ($geofence) {
            if ($location['lat'] === null || $location['lng'] === null) {
                $this->setFlash('danger', 'Không xác định được vị trí. Vui lòng bật định vị để chấm công.');
                $this->redirect('?controller=self_timekeeping&action=index');
                return;
            }

            $distance = $this->distanceMeters($location['lat'], $location['lng'], $geofence['lat'], $geofence['lng']);
            if ($distance > $geofence['radius']) {
                $this->setFlash('danger', 'Bạn đang ở ngoài phạm vi chấm công cho phép.');
                $this->redirect('?controller=self_timekeeping&action=index');
                return;
            }
        }

        $noteParts = ['Tự chấm công'];
        if ($latitude !== '' && $longitude !== '') {
            $noteParts[] = sprintf('Vị trí: %s, %s', $latitude, $longitude);
        }
        if ($accuracy !== '') {
            $noteParts[] = sprintf('Sai số: %sm', $accuracy);
        }
        $note = implode(' | ', $noteParts);

        try {
        $workDate = date('Y-m-d');
        $openRecord = $this->timekeepingModel->getOpenRecordForEmployee($employeeId, $workDate);
        if ($openRecord) {
            $recordId = $openRecord['IdChamCong'] ?? null;
            if (!$recordId) {
                throw new RuntimeException('Không thể xác định bản ghi chấm công.');
            }
            $this->timekeepingModel->updateCheckOut(
                $recordId,
                $now,
                $location['lat'],
                $location['lng'],
                $location['accuracy']
            );
            $this->setFlash('success', 'Đã ghi nhận giờ ra ca.');
        } else {
            $this->timekeepingModel->createForShift(
                $employeeId,
                $now,
                null,
                $shift['IdCaLamViec'],
                $note,
                $employeeId,
                $location['lat'],
                $location['lng'],
                $location['accuracy']
            );
            $this->setFlash('success', 'Đã ghi nhận giờ vào ca.');
        }
        } catch (Throwable $exception) {
            Logger::error('Không thể tự chấm công: ' . $exception->getMessage());
            $this->setFlash('danger', 'Không thể ghi nhận chấm công. Vui lòng thử lại.');
        }

        $this->redirect('?controller=self_timekeeping&action=index');
    }

    private function getGeofenceConfig(): ?array
    {
        $lat = getenv('TIMEKEEPING_GEOFENCE_LAT');
        $lng = getenv('TIMEKEEPING_GEOFENCE_LNG');
        $radius = getenv('TIMEKEEPING_GEOFENCE_RADIUS');

        if ($lat === false || $lng === false || $radius === false) {
            return null;
        }

        if (!is_numeric($lat) || !is_numeric($lng) || !is_numeric($radius)) {
            return null;
        }

        return [
            'lat' => (float) $lat,
            'lng' => (float) $lng,
            'radius' => (float) $radius,
        ];
    }

    private function distanceMeters(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000;
        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);
        $a = sin($latDelta / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($lonDelta / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    private function normalizeLocation(string $latitude, string $longitude, string $accuracy): array
    {
        $lat = is_numeric($latitude) ? (float) $latitude : null;
        $lng = is_numeric($longitude) ? (float) $longitude : null;
        $acc = is_numeric($accuracy) ? (float) $accuracy : null;

        return [
            'lat' => $lat,
            'lng' => $lng,
            'accuracy' => $acc,
        ];
    }

    private function loadNotifications(?string $employeeId, ?string $roleId): array
    {
        $store = new NotificationStore();
        $entries = $store->readAll();
        $filtered = $store->filterForUser($entries, $employeeId, $roleId);

        usort($filtered, static function ($a, $b): int {
            $aTime = strtotime($a['created_at'] ?? '') ?: 0;
            $bTime = strtotime($b['created_at'] ?? '') ?: 0;
            return $bTime <=> $aTime;
        });

        $important = array_values(array_filter($filtered, static function ($entry): bool {
            $metadata = $entry['metadata'] ?? [];
            $priority = $metadata['priority'] ?? $metadata['level'] ?? null;
            if (is_string($priority) && in_array(strtolower($priority), ['high', 'important', 'urgent'], true)) {
                return true;
            }
            return !empty($metadata['important']);
        }));

        return [
            'all' => $filtered,
            'important' => $important,
        ];
    }

    private function isMobileRequest(): bool
    {
        $userAgent = strtolower((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''));
        if ($userAgent === '') {
            return false;
        }

        $keywords = [
            'iphone', 'ipod', 'android', 'blackberry', 'nokia', 'opera mini',
            'windows phone', 'mobile', 'tablet', 'ipad',
        ];

        foreach ($keywords as $keyword) {
            if (str_contains($userAgent, $keyword)) {
                return true;
            }
        }

        return false;
    }
}
