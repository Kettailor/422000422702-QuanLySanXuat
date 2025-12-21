<?php

class TimekeepingController extends Controller
{
    private Timekeeping $timekeepingModel;
    private Employee $employeeModel;
    private WorkshopPlan $workshopPlanModel;

    public function __construct()
    {
        $this->authorize(['VT_ADMIN', 'VT_BAN_GIAM_DOC', 'VT_QUANLY_XUONG']);
        $this->timekeepingModel = new Timekeeping();
        $this->employeeModel = new Employee();
        $this->workshopPlanModel = new WorkshopPlan();
    }

    public function index(): void
    {
        $planId = $_GET['plan_id'] ?? null;
        $plan = $planId ? $this->workshopPlanModel->findWithRelations($planId) : null;

        $entries = $this->timekeepingModel->getRecentRecords(200, $plan ? $planId : null);
        $plans = $this->workshopPlanModel->getDetailedPlans(200);

        $this->render('timekeeping/index', [
            'title' => 'Nhật ký chấm công',
            'entries' => $entries,
            'plan' => $plan,
            'planId' => $planId,
            'plans' => $plans,
        ]);
    }

    public function create(): void
    {
        $planId = $_GET['plan_id'] ?? null;
        $plan = $planId ? $this->workshopPlanModel->findWithRelations($planId) : null;
        $employees = $this->employeeModel->getActiveEmployees();

        $this->render('timekeeping/create', [
            'title' => 'Ghi nhận chấm công',
            'plan' => $plan,
            'planId' => $planId,
            'employees' => $employees,
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

        $planId = trim($_POST['plan_id'] ?? '');
        $employeeId = trim($_POST['employee_id'] ?? '');
        $checkIn = $_POST['check_in'] ?? '';
        $checkOut = $_POST['check_out'] ?? null;
        $note = trim($_POST['note'] ?? '');

        if ($employeeId === '' || $checkIn === '') {
            $this->setFlash('danger', 'Vui lòng chọn nhân viên và thời gian vào ca.');
            $this->redirect($this->buildRedirect($planId));
            return;
        }

        $normalizedCheckIn = $this->normalizeDateTime($checkIn);
        $normalizedCheckOut = $checkOut ? $this->normalizeDateTime($checkOut) : null;

        if (!$normalizedCheckIn) {
            $this->setFlash('danger', 'Thời gian vào ca không hợp lệ.');
            $this->redirect($this->buildRedirect($planId));
            return;
        }

        try {
            $currentUser = $this->currentUser();
            $supervisorId = $currentUser['IdNhanVien'] ?? null;
            $this->timekeepingModel->createForPlan(
                $employeeId,
                $normalizedCheckIn,
                $normalizedCheckOut,
                $planId !== '' ? $planId : null,
                $note,
                $supervisorId,
                null
            );
            $this->setFlash('success', 'Đã ghi nhận chấm công cho nhân sự.');
        } catch (Throwable $exception) {
            Logger::error('Không thể ghi nhận chấm công: ' . $exception->getMessage());
            $this->setFlash('danger', 'Không thể ghi nhận chấm công. Vui lòng thử lại.');
        }

        $this->redirect($this->buildRedirect($planId));
    }

    private function normalizeDateTime(string $value): ?string
    {
        $timestamp = strtotime($value);
        if ($timestamp === false) {
            return null;
        }

        return date('Y-m-d H:i:s', $timestamp);
    }

    private function buildRedirect(?string $planId): string
    {
        if ($planId) {
            return '?controller=factory_plan&action=read&id=' . urlencode($planId);
        }

        return '?controller=factory_plan&action=index';
    }
}
