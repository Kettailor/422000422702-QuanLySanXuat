<?php

class TimekeepingController extends Controller
{
    private Timekeeping $timekeepingModel;
    private Employee $employeeModel;
    private WorkShift $workShiftModel;
    private Workshop $workshopModel;
    private WorkshopPlan $workshopPlanModel;

    public function __construct()
    {
        $this->authorize(['VT_ADMIN', 'VT_BAN_GIAM_DOC', 'VT_QUANLY_XUONG']);
        $this->timekeepingModel = new Timekeeping();
        $this->employeeModel = new Employee();
        $this->workShiftModel = new WorkShift();
        $this->workshopModel = new Workshop();
        $this->workshopPlanModel = new WorkshopPlan();
    }

    public function index(): void
    {
        $workDate = $_GET['work_date'] ?? null;
        $workshopId = $_GET['workshop_id'] ?? null;
        $planId = $_GET['plan_id'] ?? null;
        $entries = $this->timekeepingModel->getRecentRecords(200, null, $workDate, $workshopId, $planId);
        $shifts = $this->workShiftModel->getShifts($workDate);
        $workshops = $this->workshopModel->getAllWithManagers();
        $plans = $this->workshopPlanModel->getDetailedPlans(200);

        $this->render('timekeeping/index', [
            'title' => 'Nhật ký chấm công',
            'entries' => $entries,
            'workDate' => $workDate,
            'shifts' => $shifts,
            'workshopId' => $workshopId,
            'planId' => $planId,
            'workshops' => $workshops,
            'plans' => $plans,
        ]);
    }

    public function create(): void
    {
        $shiftId = $_GET['shift_id'] ?? null;
        $workDate = date('Y-m-d');
        $shift = $shiftId ? $this->workShiftModel->find($shiftId) : null;
        $employees = $this->employeeModel->getActiveEmployees();
        $shifts = $this->workShiftModel->getShifts($workDate);
        $entries = $this->timekeepingModel->getRecentRecords(200, null, $workDate);

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

        $employeeInput = $_POST['employee_id'] ?? [];
        $shiftId = trim($_POST['shift_id'] ?? '');
        $checkIn = $_POST['check_in'] ?? '';
        $checkOut = $_POST['check_out'] ?? null;
        $note = trim($_POST['note'] ?? '');

        $employeeIds = is_array($employeeInput) ? $employeeInput : [$employeeInput];
        $employeeIds = array_values(array_filter(array_map('trim', $employeeIds), static fn($value) => $value !== ''));

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
                    $supervisorId
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
}
