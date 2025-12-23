<?php

class DashboardController extends Controller
{
    public function index(): void
    {
        if ($this->isMobileRequest()) {
            $this->renderMobileDashboard();
            return;
        }

        $orderModel = new Order();
        $employeeModel = new Employee();
        $planModel = new ProductionPlan();
        $activityModel = new SystemActivity();
        $qualityModel = new QualityReport();
        $salaryModel = new Salary();
        $workshopModel = new Workshop();

        $orders = $orderModel->getOrdersWithCustomer(5);
        $employees = $employeeModel->getActiveEmployees();
        $plans = $planModel->getPlansWithOrders(5);
        $activities = $activityModel->latest(6);
        $qualitySummary = $qualityModel->getQualitySummary();
        $monthlyRevenue = $orderModel->getMonthlyRevenue();
        $payrollTrend = $salaryModel->getMonthlyPayoutTrend();
        $orderStats = $orderModel->getOrderStatistics();
        $payrollSummary = $salaryModel->getPayrollSummary();
        $workshopSummary = $workshopModel->getCapacitySummary();
        $pendingPayrolls = $salaryModel->getPendingPayrolls();

        $stats = [
            'totalWorkingDays' => 22,
            'participationRate' => count($employees),
            'completedPlans' => array_reduce($plans, fn ($carry, $plan) => $carry + ($plan['TrangThai'] === 'Hoàn thành' ? 1 : 0), 0),
            'newNotifications' => count($activities),
        ];

        $this->render('dashboard/index', [
            'title' => 'Tổng quan hệ thống',
            'orders' => $orders,
            'employees' => $employees,
            'plans' => $plans,
            'activities' => $activities,
            'qualitySummary' => $qualitySummary,
            'stats' => $stats,
            'monthlyRevenue' => $monthlyRevenue,
            'payrollTrend' => $payrollTrend,
            'orderStats' => $orderStats,
            'payrollSummary' => $payrollSummary,
            'workshopSummary' => $workshopSummary,
            'pendingPayrolls' => $pendingPayrolls,
        ]);
    }

    private function renderMobileDashboard(): void
    {
        $currentUser = $this->currentUser();
        $employeeId = $currentUser['IdNhanVien'] ?? null;
        $role = $currentUser['ActualIdVaiTro'] ?? ($currentUser['IdVaiTro'] ?? null);

        $workShiftModel = new WorkShift();
        $timekeepingModel = new Timekeeping();
        $planAssignmentModel = new WorkshopPlanAssignment();
        $workshopPlanModel = new WorkshopPlan();
        $productionPlanModel = new ProductionPlan();
        $orderModel = new Order();
        $qualityModel = new QualityReport();

        $now = date('Y-m-d H:i:s');
        $shift = $workShiftModel->findShiftForTimestamp($now);
        $workDate = date('Y-m-d');
        $openRecord = $employeeId ? $timekeepingModel->getOpenRecordForEmployee($employeeId, $workDate) : null;
        $geofence = $this->getGeofenceConfig();

        $productionPlans = [];
        if (in_array($role, ['VT_BAN_GIAM_DOC', 'VT_KHO_TRUONG'], true)) {
            $productionPlans = $productionPlanModel->getPlansWithOrders(6);
        }

        $workshopPlans = [];
        if (in_array($role, ['VT_QUANLY_XUONG', 'VT_NHANVIEN_SANXUAT'], true)) {
            $planIds = $employeeId ? $planAssignmentModel->getPlanIdsByEmployee($employeeId) : [];
            $workshopPlans = $workshopPlanModel->getDetailedPlans(50);
            if ($planIds) {
                $allowed = array_fill_keys($planIds, true);
                $workshopPlans = array_values(array_filter($workshopPlans, static function (array $plan) use ($allowed): bool {
                    $planId = $plan['IdKeHoachSanXuatXuong'] ?? null;
                    return $planId !== null && isset($allowed[$planId]);
                }));
            } else {
                $workshopPlans = [];
            }
        } elseif (in_array($role, ['VT_KHO_TRUONG', 'VT_NHANVIEN_KHO', 'VT_BAN_GIAM_DOC'], true)) {
            $workshopPlans = $workshopPlanModel->getDetailedPlans(10);
        }

        $orders = [];
        if ($role === 'VT_KINH_DOANH') {
            $orders = $orderModel->getOrdersWithCustomer(6);
        }

        $qualityLots = [];
        if ($role === 'VT_KIEM_SOAT_CL') {
            $qualityLots = array_values(array_filter(
                $qualityModel->getDanhSachLo(),
                static fn (array $row): bool => empty($row['IdBienBanDanhGiaSP'])
            ));
            $qualityLots = array_slice($qualityLots, 0, 6);
        }

        $notifications = $this->loadNotifications($employeeId);

        $this->render('dashboard/mobile', [
            'title' => 'Bảng điều khiển di động',
            'currentUser' => $currentUser,
            'role' => $role,
            'now' => $now,
            'shift' => $shift,
            'openRecord' => $openRecord,
            'geofence' => $geofence,
            'productionPlans' => $productionPlans,
            'workshopPlans' => $workshopPlans,
            'orders' => $orders,
            'qualityLots' => $qualityLots,
            'notifications' => $notifications['all'],
            'importantNotifications' => $notifications['important'],
        ]);
    }

    public function sendNotification(): void
    {
        $user = $this->currentUser();
        if (!$user) {
            $this->setFlash('danger', 'Vui lòng đăng nhập để tiếp tục.');
            $this->redirect('?controller=auth&action=login');
            return;
        }

        $role = $user['ActualIdVaiTro'] ?? ($user['IdVaiTro'] ?? null);
        if ($role !== 'VT_BAN_GIAM_DOC') {
            $this->setFlash('danger', 'Bạn không có quyền gửi thông báo.');
            $this->redirect('?controller=dashboard&action=index');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=dashboard&action=index');
            return;
        }

        $title = trim($_POST['title'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $priority = trim($_POST['priority'] ?? 'normal');

        if ($title === '' && $message === '') {
            $this->setFlash('danger', 'Vui lòng nhập tiêu đề hoặc nội dung thông báo.');
            $this->redirect('?controller=dashboard&action=index');
            return;
        }

        $metadata = [];
        if ($priority !== '' && $priority !== 'normal') {
            $metadata['priority'] = $priority;
            $metadata['important'] = in_array($priority, ['high', 'important', 'urgent'], true);
        }

        try {
            $store = new NotificationStore();
            $store->push([
                'title' => $title !== '' ? $title : 'Thông báo từ Ban giám đốc',
                'message' => $message !== '' ? $message : null,
                'sender' => $user['HoTen'] ?? 'Ban giám đốc',
                'metadata' => $metadata,
            ]);
            $this->setFlash('success', 'Đã gửi thông báo đến toàn bộ nhân viên.');
        } catch (Throwable $exception) {
            Logger::error('Không thể gửi thông báo: ' . $exception->getMessage());
            $this->setFlash('danger', 'Không thể gửi thông báo. Vui lòng thử lại.');
        }

        $this->redirect('?controller=dashboard&action=index');
    }

    private function isMobileRequest(): bool
    {
        $userAgent = strtolower((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''));

        return str_contains($userAgent, 'mobile')
            || str_contains($userAgent, 'android')
            || str_contains($userAgent, 'iphone')
            || str_contains($userAgent, 'ipad');
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

    private function loadNotifications(?string $employeeId): array
    {
        $store = new NotificationStore();
        $entries = $store->readAll();

        $filtered = array_values(array_filter($entries, static function ($entry) use ($employeeId): bool {
            if (!is_array($entry)) {
                return false;
            }
            $recipient = $entry['recipient'] ?? null;
            if (!$recipient) {
                return true;
            }
            return $employeeId !== null && $recipient === $employeeId;
        }));

        usort($filtered, static function ($a, $b): int {
            $aTime = strtotime($a['created_at'] ?? '') ?: 0;
            $bTime = strtotime($b['created_at'] ?? '') ?: 0;
            return $bTime <=> $aTime;
        });

        $important = array_values(array_filter($filtered, static function ($entry): bool {
            if (!is_array($entry)) {
                return false;
            }
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
}
