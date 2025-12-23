<?php

class Self_salaryController extends Controller
{
    private Salary $salaryModel;
    private Employee $employeeModel;

    public function __construct()
    {
        if (!$this->currentUser()) {
            $this->setFlash('danger', 'Vui lòng đăng nhập để tiếp tục.');
            $this->redirect('?controller=auth&action=login');
        }
        $this->salaryModel = new Salary();
        $this->employeeModel = new Employee();
    }

    public function index(): void
    {
        $user = $this->currentUser();
        $employeeId = $user['IdNhanVien'] ?? null;

        if (!$employeeId) {
            $this->setFlash('danger', 'Không xác định được nhân sự.');
            $this->redirect('?controller=dashboard&action=index');
            return;
        }

        $payrolls = $this->salaryModel->getPayrolls(50, $employeeId);
        $summary = $this->salaryModel->getPayrollSummary($employeeId);
        $employee = $this->employeeModel->find($employeeId);

        $this->render('self_salary/index', [
            'title' => 'Bảng lương cá nhân',
            'payrolls' => $payrolls,
            'summary' => $summary,
            'employee' => $employee,
        ]);
    }

    public function read(): void
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->setFlash('warning', 'Thiếu mã bảng lương.');
            $this->redirect('?controller=self_salary&action=index');
            return;
        }

        $user = $this->currentUser();
        $employeeId = $user['IdNhanVien'] ?? null;
        $payroll = $this->salaryModel->find($id);

        if (!$payroll || !$employeeId || ($payroll[Salary::EMPLOYEE_COLUMN] ?? null) !== $employeeId) {
            $this->setFlash('danger', 'Bạn không được phép xem bảng lương này.');
            $this->redirect('?controller=self_salary&action=index');
            return;
        }

        if (!isset($payroll['HoTen'])) {
            $employee = $this->employeeModel->find($employeeId);
            if ($employee) {
                $payroll['HoTen'] = $employee['HoTen'];
            }
        }
        if (!$this->salaryModel->supportsWorkingDays()) {
            if (!isset($payroll['SoNgayCong']) || $payroll['SoNgayCong'] === null) {
                $payroll['SoNgayCong'] = $this->salaryModel->deriveWorkingDays($payroll);
            }
        }

        $figures = Salary::calculateFigures($payroll);

        $this->render('self_salary/read', [
            'title' => 'Chi tiết bảng lương cá nhân',
            'payroll' => $payroll,
            'figures' => $figures,
        ]);
    }
}
