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

        $workShiftModel = new WorkShift();
        $timekeepingModel = new Timekeeping();
        $planAssignmentModel = new WorkshopPlanAssignment();
        $workshopPlanModel = new WorkshopPlan();

        $now = date('Y-m-d H:i:s');
        $shift = $workShiftModel->findShiftForTimestamp($now);
        $workDate = date('Y-m-d');
        $openRecord = $employeeId ? $timekeepingModel->getOpenRecordForEmployee($employeeId, $workDate) : null;
        $geofence = $this->getGeofenceConfig();

        $planIds = $employeeId ? $planAssignmentModel->getPlanIdsByEmployee($employeeId) : [];
        $plans = $workshopPlanModel->getDetailedPlans(50);
        if ($planIds) {
            $allowed = array_fill_keys($planIds, true);
            $plans = array_values(array_filter($plans, static function (array $plan) use ($allowed): bool {
                $planId = $plan['IdKeHoachSanXuatXuong'] ?? null;
                return $planId !== null && isset($allowed[$planId]);
            }));
        } else {
            $plans = [];
        }

        $notifications = $this->loadNotifications($employeeId);

        $this->render('dashboard/mobile', [
            'title' => 'Bảng điều khiển di động',
            'currentUser' => $currentUser,
            'now' => $now,
            'shift' => $shift,
            'openRecord' => $openRecord,
            'geofence' => $geofence,
            'plans' => $plans,
            'notifications' => $notifications,
        ]);
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

        return $filtered;
    }
}
