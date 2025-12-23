<?php

class DashboardController extends Controller
{
    public function index(): void
    {
        if ($this->isMobileRequest()) {
            $this->renderMobileDashboard();
            return;
        }

        $currentUser = $this->currentUser();
        $employeeId = $currentUser['IdNhanVien'] ?? null;
        $role = $currentUser['ActualIdVaiTro'] ?? ($currentUser['IdVaiTro'] ?? null);

        $employeeModel = new Employee();
        $timekeepingModel = new Timekeeping();
        $workShiftModel = new WorkShift();
        $salaryModel = new Salary();

        $employee = $employeeId ? $employeeModel->find($employeeId) : null;
        $workshopId = $employee['IdXuong'] ?? null;

        $now = date('Y-m-d H:i:s');
        $workDate = date('Y-m-d');
        $shift = $workShiftModel->findShiftForTimestamp($now);
        $shiftList = $workShiftModel->getShifts($workDate, 6);
        $workSummary = $employeeId ? $timekeepingModel->getMonthlySummary($employeeId) : [
            'month' => date('Y-m'),
            'total_records' => 0,
            'total_hours' => 0,
            'total_minutes' => 0,
        ];

        $employeePayrollSummary = $employeeId ? $salaryModel->getPayrollSummary($employeeId) : [
            'total' => 0,
            'pending' => 0,
            'approved' => 0,
            'paid' => 0,
            'total_amount' => 0,
        ];
        $latestPayrolls = $employeeId ? $salaryModel->getPayrolls(3, $employeeId) : [];
        $notifications = $this->loadNotifications($employeeId, $role);

        $orders = [];
        $employees = [];
        $plans = [];
        $activities = [];
        $qualitySummary = [];
        $monthlyRevenue = [];
        $payrollTrend = [];
        $orderStats = ['total_orders' => 0, 'pending_orders' => 0, 'total_revenue' => 0, 'completed_orders' => 0];
        $payrollSummary = ['total_amount' => 0, 'pending' => 0];
        $workshopSummary = ['utilization' => 0, 'workforce' => 0];
        $pendingPayrolls = [];
        $warehouseSummary = [];
        $warehouses = [];
        $workshopPlans = [];
        $qualityLots = [];
        $qualityReports = [];
        $tickets = [];
        $ticketSummary = ['total' => 0, 'open' => 0];
        $activeUserCount = 0;
        $roles = [];
        $stats = [
            'totalWorkingDays' => 22,
            'participationRate' => 0,
            'completedPlans' => 0,
            'newNotifications' => 0,
        ];

        if ($role === 'VT_BAN_GIAM_DOC') {
            $orderModel = new Order();
            $planModel = new ProductionPlan();
            $activityModel = new SystemActivity();
            $qualityModel = new QualityReport();
            $workshopModel = new Workshop();
            $roleModel = new Role();

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
            $roles = $roleModel->all(50);

            $stats = [
                'totalWorkingDays' => 22,
                'participationRate' => count($employees),
                'completedPlans' => array_reduce($plans, fn ($carry, $plan) => $carry + ($plan['TrangThai'] === 'Hoàn thành' ? 1 : 0), 0),
                'newNotifications' => count($activities),
            ];
        } elseif (in_array($role, ['VT_KHO_TRUONG', 'VT_NHANVIEN_KHO'], true)) {
            $warehouseModel = new Warehouse();
            $workshopPlanModel = new WorkshopPlan();

            $warehouses = $warehouseModel->getWithSupervisor();
            if ($role === 'VT_NHANVIEN_KHO' && $employeeId) {
                $warehouses = array_values(array_filter($warehouses, static function (array $warehouse) use ($employeeId): bool {
                    return ($warehouse['NHAN_VIEN_KHO_IdNhanVien'] ?? null) === $employeeId;
                }));
            }
            $warehouseSummary = $warehouseModel->getWarehouseSummary($warehouses);
            $workshopPlans = $workshopPlanModel->getDashboardPlans($role === 'VT_KHO_TRUONG' ? null : $workshopId);
        } elseif ($role === 'VT_KINH_DOANH') {
            $orderModel = new Order();
            $orders = $orderModel->getOrdersWithCustomer(8);
            $orderStats = $orderModel->getOrderStatistics();
        } elseif ($role === 'VT_QUANLY_XUONG') {
            $workshopPlanModel = new WorkshopPlan();
            $workshopPlans = $workshopPlanModel->getDashboardPlans($workshopId);
        } elseif ($role === 'VT_NHANVIEN_SANXUAT') {
            $workshopPlanModel = new WorkshopPlan();
            $planAssignmentModel = new WorkshopPlanAssignment();
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
        } elseif ($role === 'VT_KIEM_SOAT_CL') {
            $qualityModel = new QualityReport();
            $qualitySummary = $qualityModel->getQualitySummary();
            $qualityLots = array_values(array_filter(
                $qualityModel->getDanhSachLo(),
                static fn (array $row): bool => empty($row['IdBienBanDanhGiaSP'])
            ));
            $qualityLots = array_slice($qualityLots, 0, 8);
            $qualityReports = $qualityModel->getLatestReports(6);
        } elseif ($role === 'VT_KETOAN') {
            $orderModel = new Order();
            $orderStats = $orderModel->getOrderStatistics();
            $orders = $orderModel->getOrdersWithCustomer(5);
            $pendingPayrolls = $salaryModel->getPendingPayrolls();
        } elseif ($role === 'VT_ADMIN') {
            $userModel = new User();
            $ticketData = Ticket::getTickets(1, 10);
            $tickets = $ticketData['tickets'] ?? [];
            $ticketSummary['total'] = count($tickets);
            foreach ($tickets as $ticket) {
                if (($ticket['status'] ?? '') === 'open') {
                    $ticketSummary['open']++;
                }
            }
            $activeUserCount = $userModel->countActiveUsers();
        }

        $this->render('dashboard/index', [
            'title' => 'Tổng quan hệ thống',
            'currentUser' => $currentUser,
            'role' => $role,
            'employee' => $employee,
            'now' => $now,
            'shift' => $shift,
            'shiftList' => $shiftList,
            'workSummary' => $workSummary,
            'employeePayrollSummary' => $employeePayrollSummary,
            'latestPayrolls' => $latestPayrolls,
            'notifications' => $notifications['all'],
            'importantNotifications' => $notifications['important'],
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
            'warehouseSummary' => $warehouseSummary,
            'warehouses' => $warehouses,
            'workshopPlans' => $workshopPlans,
            'qualityLots' => $qualityLots,
            'qualityReports' => $qualityReports,
            'tickets' => $tickets,
            'ticketSummary' => $ticketSummary,
            'activeUserCount' => $activeUserCount,
            'roles' => $roles,
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

        $notifications = $this->loadNotifications($employeeId, $role);

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
        $scope = trim($_POST['scope'] ?? 'general');
        $recipientRole = trim($_POST['recipient_role'] ?? '');
        $recipient = trim($_POST['recipient'] ?? '');

        if ($title === '' && $message === '') {
            $this->setFlash('danger', 'Vui lòng nhập tiêu đề hoặc nội dung thông báo.');
            $this->redirect('?controller=dashboard&action=index');
            return;
        }

        if ($scope === 'role' && $recipientRole === '') {
            $this->setFlash('danger', 'Vui lòng chọn vai trò nhận thông báo.');
            $this->redirect('?controller=dashboard&action=index');
            return;
        }

        if ($scope === 'personal' && $recipient === '') {
            $this->setFlash('danger', 'Vui lòng chọn người nhận thông báo.');
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
            $payload = [
                'title' => $title !== '' ? $title : 'Thông báo từ Ban giám đốc',
                'message' => $message !== '' ? $message : null,
                'sender' => $user['HoTen'] ?? 'Ban giám đốc',
                'metadata' => $metadata,
            ];
            if ($scope === 'role' && $recipientRole !== '') {
                $payload['recipient_role'] = $recipientRole;
            } elseif ($scope === 'personal' && $recipient !== '') {
                $payload['recipient'] = $recipient;
            }
            $store->push($payload);
            $scopeMessage = match ($scope) {
                'role' => 'Đã gửi thông báo đến nhóm vai trò được chọn.',
                'personal' => 'Đã gửi thông báo đến nhân sự được chọn.',
                default => 'Đã gửi thông báo đến toàn bộ nhân viên.',
            };
            $this->setFlash('success', $scopeMessage);
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
}
