<?php

class TimekeepingController extends Controller
{
    private Timekeeping $timekeepingModel;
    private Employee $employeeModel;
    private WorkShift $workShiftModel;
    private Workshop $workshopModel;
    private WorkshopPlan $workshopPlanModel;
    private WorkshopAssignment $assignmentModel;

    public function __construct()
    {
        $this->authorize(array_merge(['VT_ADMIN', 'VT_BAN_GIAM_DOC', 'VT_KHO_TRUONG'], $this->getWorkshopManagerRoles()));
        $this->timekeepingModel = new Timekeeping();
        $this->employeeModel = new Employee();
        $this->workShiftModel = new WorkShift();
        $this->workshopModel = new Workshop();
        $this->workshopPlanModel = new WorkshopPlan();
        $this->assignmentModel = new WorkshopAssignment();
    }

    public function index(): void
    {
        $this->requireTimekeepingPermission();
        $workDate = $_GET['work_date'] ?? null;
        $workshopId = $_GET['workshop_id'] ?? null;
        $planId = $_GET['plan_id'] ?? null;
        $employeeId = $_GET['employee_id'] ?? null;
        $entries = $this->timekeepingModel->getRecentRecords(200, null, $workDate, $workshopId, $planId, $employeeId);
        $accessRole = $this->currentUser()['ActualIdVaiTro'] ?? ($this->currentUser()['IdVaiTro'] ?? null);
        if ($this->isFixedShiftRole($accessRole)) {
            $this->workShiftModel->ensureFixedShiftsForDate($workDate ?? date('Y-m-d'));
        }
        $shifts = $this->workShiftModel->getShifts($workDate);
        $workshops = $this->workshopModel->getAllWithManagers();
        $plans = $this->workshopPlanModel->getDetailedPlans(200);
        $employee = $employeeId ? $this->employeeModel->find($employeeId) : null;

        $this->render('timekeeping/index', [
            'title' => 'Nhật ký chấm công',
            'entries' => $entries,
            'workDate' => $workDate,
            'shifts' => $shifts,
            'workshopId' => $workshopId,
            'planId' => $planId,
            'employeeFilter' => $employee,
            'workshops' => $workshops,
            'plans' => $plans,
        ]);
    }

    public function create(): void
    {
        $role = $this->requireTimekeepingPermission();
        $shiftId = $_GET['shift_id'] ?? null;
        $workDate = date('Y-m-d');
        $shift = $shiftId ? $this->workShiftModel->find($shiftId) : null;
        $user = $this->currentUser();
        $employees = $this->getAssignableEmployees($role, $user['IdNhanVien'] ?? null);
        $accessRole = $user['ActualIdVaiTro'] ?? ($user['IdVaiTro'] ?? null);
        if ($this->isFixedShiftRole($accessRole)) {
            $this->workShiftModel->ensureFixedShiftsForDate($workDate);
        }
        $shifts = $this->workShiftModel->getShifts($workDate);
        $entries = $this->timekeepingModel->getRecentRecords(200, null, $workDate, null, null, null);

        $this->render('timekeeping/create', [
            'title' => 'Ghi nhận chấm công',
            'shift' => $shift,
            'shiftId' => $shiftId,
            'workDate' => $workDate,
            'shifts' => $shifts,
            'employees' => $employees,
            'entries' => $entries,
            'defaultCheckIn' => date('Y-m-d\TH:i'),
            'defaultCheckOut' => date('Y-m-d\TH:i'),
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=factory_plan&action=index');
            return;
        }

        $role = $this->requireTimekeepingPermission();
        $employeeInput = $_POST['employee_id'] ?? [];
        $shiftId = trim($_POST['shift_id'] ?? '');
        $checkIn = $_POST['check_in'] ?? '';
        $checkOut = $_POST['check_out'] ?? null;
        $note = trim($_POST['note'] ?? '');

        $employeeIds = is_array($employeeInput) ? $employeeInput : [$employeeInput];
        $employeeIds = array_values(array_filter(array_map('trim', $employeeIds), static fn($value) => $value !== ''));
        $employeeIds = $this->filterAssignableEmployeeIds($employeeIds, $role);

        if ($employeeIds === [] || $checkIn === '' || $shiftId === '') {
            $this->setFlash('danger', 'Vui lòng chọn ca làm việc, nhân viên và thời gian vào ca.');
            $this->redirect($this->buildRedirect(null, null));
            return;
        }

        $shift = $this->workShiftModel->find($shiftId);
        if (!$shift) {
            $this->setFlash('danger', 'Ca làm việc không hợp lệ.');
            $this->redirect($this->buildRedirect(null, null));
            return;
        }

        $normalizedCheckIn = $this->normalizeDateTime($checkIn);
        $normalizedCheckOut = $checkOut ? $this->normalizeDateTime($checkOut) : null;

        if (!$normalizedCheckIn) {
            $this->setFlash('danger', 'Thời gian vào ca không hợp lệ.');
            $this->redirect($this->buildRedirect(null, null));
            return;
        }

        $workDate = date('Y-m-d');
        $checkInTimestamp = strtotime($normalizedCheckIn);
        $workDateTimestamp = strtotime($workDate . ' 00:00:00');
        if ($checkInTimestamp === false || $workDateTimestamp === false) {
            $this->setFlash('danger', 'Không thể xác định thời gian chấm công hợp lệ.');
            $this->redirect($this->buildRedirect(null, null));
            return;
        }

        if (date('Y-m-d', $checkInTimestamp) !== $workDate || $checkInTimestamp < $workDateTimestamp) {
            $this->setFlash('danger', 'Giờ vào phải nằm trong khoảng từ 00:00 đến 23:59 của ngày hôm nay.');
            $this->redirect($this->buildRedirect($shiftId, $workDate));
            return;
        }

        [$shiftStartTs, $shiftEndTs] = $this->resolveShiftBounds($shift) ?? [null, null];
        if ($shiftStartTs !== null && $shiftEndTs !== null) {
            if ($checkInTimestamp < $shiftStartTs) {
                $normalizedCheckIn = date('Y-m-d H:i:s', $shiftStartTs);
                $checkInTimestamp = $shiftStartTs;
            }
            if ($checkInTimestamp > $shiftEndTs) {
                $this->setFlash('danger', 'Giờ vào vượt quá thời gian ca làm.');
                $this->redirect($this->buildRedirect($shiftId, $workDate));
                return;
            }
            if ($normalizedCheckOut) {
                $checkOutTimestamp = strtotime($normalizedCheckOut);
                if ($checkOutTimestamp === false) {
                    $this->setFlash('danger', 'Thời gian ra ca không hợp lệ.');
                    $this->redirect($this->buildRedirect($shiftId, $workDate));
                    return;
                }
                if ($checkOutTimestamp < $shiftStartTs) {
                    $this->setFlash('danger', 'Giờ ra không được trước giờ bắt đầu ca.');
                    $this->redirect($this->buildRedirect($shiftId, $workDate));
                    return;
                }
                if ($checkOutTimestamp > $shiftEndTs) {
                    $normalizedCheckOut = date('Y-m-d H:i:s', $shiftEndTs);
                    $checkOutTimestamp = $shiftEndTs;
                }
                if ($checkOutTimestamp < $checkInTimestamp) {
                    $this->setFlash('danger', 'Giờ ra phải sau giờ vào.');
                    $this->redirect($this->buildRedirect($shiftId, $workDate));
                    return;
                }
            }
        }

        try {
            $currentUser = $this->currentUser();
            $supervisorId = $currentUser['IdNhanVien'] ?? null;
            foreach ($employeeIds as $employeeId) {
                $created = $this->timekeepingModel->createForShift(
                    $employeeId,
                    $normalizedCheckIn,
                    $normalizedCheckOut,
                    $shiftId,
                    $note,
                    $supervisorId,
                );
                if (!$created) {
                    throw new RuntimeException('Không thể lưu chấm công cho nhân viên ' . $employeeId);
                }
            }
            $this->setFlash('success', 'Đã ghi nhận chấm công cho ' . count($employeeIds) . ' nhân sự.');
        } catch (Throwable $exception) {
            Logger::error('Không thể ghi nhận chấm công: ' . $exception->getMessage());
            $this->setFlash('danger', 'Không thể ghi nhận chấm công. Vui lòng thử lại.');
        }

        $this->redirect($this->buildRedirect($shiftId, null));
    }

    private function normalizeDateTime(string $value): ?string
    {
        $timestamp = strtotime($value);
        if ($timestamp === false) {
            return null;
        }

        return date('Y-m-d H:i:s', $timestamp);
    }

    private function resolveShiftBounds(array $shift): ?array
    {
        $start = $shift['ThoiGianBatDau'] ?? null;
        $end = $shift['ThoiGianKetThuc'] ?? null;
        if (!$start || !$end) {
            return null;
        }

        $startTs = strtotime($start);
        $endTs = strtotime($end);
        if ($startTs === false || $endTs === false) {
            return null;
        }

        return [$startTs, $endTs];
    }

    private function buildRedirect(?string $shiftId, ?string $workDate): string
    {
        if ($shiftId) {
            return '?controller=timekeeping&action=index&shift_id=' . urlencode($shiftId);
        }

        if ($workDate) {
            return '?controller=timekeeping&action=index&work_date=' . urlencode($workDate);
        }

        return '?controller=timekeeping&action=index';
    }

    private function requireTimekeepingPermission(): string
    {
        $user = $this->currentUser();
        $role = $user['ActualIdVaiTro'] ?? ($user['IdVaiTro'] ?? null);

        if (!in_array($role, array_merge(['VT_ADMIN', 'VT_BAN_GIAM_DOC', 'VT_KHO_TRUONG'], $this->getWorkshopManagerRoles()), true)) {
            $this->setFlash('danger', 'Bạn không có quyền thực hiện chức năng phân công và chấm công.');
            $this->redirect('?controller=dashboard&action=index');
        }

        return $role ?? '';
    }

    private function getAssignableEmployees(string $role, ?string $employeeId): array
    {
        if ($role === 'VT_BAN_GIAM_DOC') {
            return $this->employeeModel->getActiveEmployees();
        }

        if ($role === 'VT_KHO_TRUONG' || $role === 'VT_TRUONG_XUONG_LUU_TRU') {
            return $this->employeeModel->getEmployeesByRoleIds(['VT_NHANVIEN_KHO'], 'Đang làm việc');
        }

        if (!$employeeId) {
            return [];
        }

        $managedWorkshops = $this->assignmentModel->getWorkshopsManagedBy($employeeId);
        if (empty($managedWorkshops)) {
            return [];
        }

        $employees = [];
        foreach ($managedWorkshops as $workshopId) {
            $assignments = $this->assignmentModel->getAssignmentsByWorkshop($workshopId);
            $groups = ($role === 'VT_TRUONG_XUONG_LUU_TRU') ? ['nhan_vien_kho'] : ['nhan_vien_kho', 'nhan_vien_san_xuat'];
            foreach ($groups as $group) {
                foreach ($assignments[$group] ?? [] as $employee) {
                    $id = $employee['IdNhanVien'] ?? null;
                    if (!$id) {
                        continue;
                    }
                    $employees[$id] = $employee;
                }
            }
        }

        $employees = array_values($employees);
        usort($employees, static function (array $a, array $b): int {
            return strcmp($a['HoTen'] ?? '', $b['HoTen'] ?? '');
        });

        return $employees;
    }

    private function filterAssignableEmployeeIds(array $employeeIds, string $role): array
    {
        if ($role === 'VT_BAN_GIAM_DOC') {
            return $employeeIds;
        }

        $user = $this->currentUser();
        $allowedEmployees = $this->getAssignableEmployees($role, $user['IdNhanVien'] ?? null);
        if (empty($allowedEmployees)) {
            $this->setFlash('danger', $this->resolveAssignmentRestrictionMessage($role));
            $this->redirect('?controller=timekeeping&action=index');
        }

        $allowedIds = array_fill_keys(array_column($allowedEmployees, 'IdNhanVien'), true);
        $filtered = array_values(array_filter($employeeIds, static function (string $id) use ($allowedIds): bool {
            return isset($allowedIds[$id]);
        }));

        if (empty($filtered)) {
            $this->setFlash('danger', $this->resolveAssignmentRestrictionMessage($role));
            $this->redirect('?controller=timekeeping&action=index');
        }

        return $filtered;
    }

    private function resolveAssignmentRestrictionMessage(string $role): string
    {
        if ($role === 'VT_KHO_TRUONG' || $role === 'VT_TRUONG_XUONG_LUU_TRU') {
            return 'Bạn chỉ có thể chấm công và phân công cho nhân viên kho thuộc phạm vi quản lý.';
        }

        if (in_array($role, $this->getWorkshopManagerRoles(), true)) {
            return 'Bạn chỉ có thể chấm công và phân công cho nhân sự thuộc các xưởng bạn quản lý.';
        }

        return 'Bạn chỉ có thể chấm công cho nhân sự thuộc phạm vi được phân quyền.';
    }

    private function getWorkshopManagerRoles(): array
    {
        return [
            'VT_TRUONG_XUONG_KIEM_DINH',
            'VT_TRUONG_XUONG_LAP_RAP_DONG_GOI',
            'VT_TRUONG_XUONG_SAN_XUAT',
            'VT_TRUONG_XUONG_LUU_TRU',
        ];
    }
}
