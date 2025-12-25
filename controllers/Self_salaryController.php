<?php

class Self_salaryController extends Controller
{
    private Salary $salaryModel;
    private Employee $employeeModel;

    public function __construct()
    {
        $this->authorize([
            'VT_ADMIN',
            'VT_BAN_GIAM_DOC',
            'VT_KETOAN',
            'VT_KINH_DOANH',
            'VT_TRUONG_XUONG_KIEM_DINH',
            'VT_TRUONG_XUONG_LAP_RAP_DONG_GOI',
            'VT_TRUONG_XUONG_SAN_XUAT',
            'VT_TRUONG_XUONG_LUU_TRU',
            'VT_NHANVIEN_SANXUAT',
            'VT_NHANVIEN_KHO',
            'VT_KIEM_SOAT_CL',
            'VT_NHANVIEN_KIEM_DINH',
            'VT_KHO_TRUONG',
        ]);
        $this->salaryModel = new Salary();
        $this->employeeModel = new Employee();
    }

    public function index(): void
    {
        $user = $this->currentUser();
        $employeeId = $user['IdNhanVien'] ?? null;
        if (!$employeeId) {
            $this->setFlash('warning', 'Không xác định được nhân viên.');
            $this->redirect('?controller=dashboard&action=index');
        }

        $employee = $this->employeeModel->find($employeeId);
        $payrolls = $this->salaryModel->getPayrolls(50, $employeeId);
        $summary = $this->salaryModel->getPayrollSummary($employeeId);

        $this->render('self_salary/index', [
            'title' => 'Bảng lương cá nhân',
            'employee' => $employee,
            'payrolls' => $payrolls,
            'summary' => $summary,
        ]);
    }
}
