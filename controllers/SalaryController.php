<?php

class SalaryController extends Controller
{
    private Salary $salaryModel;
    private Employee $employeeModel;

    public function __construct()
    {
        $this->authorize(['VT_KETOAN', 'VT_BAN_GIAM_DOC']);
        $this->salaryModel = new Salary();
        $this->employeeModel = new Employee();
    }

    private function canManagePayrolls(?string $role): bool
    {
        return in_array($role, ['VT_KETOAN', 'VT_ADMIN'], true);
    }

    private function canApprovePayrolls(?string $role): bool
    {
        return in_array($role, ['VT_BAN_GIAM_DOC', 'VT_ADMIN'], true);
    }

    private function requireManagePermission(): void
    {
        $user = $this->currentUser();
        $role = $user['IdVaiTro'] ?? null;

        if (!$this->canManagePayrolls($role)) {
            $this->setFlash('danger', 'Chỉ kế toán mới được phép quản lý bảng lương.');
            $this->redirect('?controller=salary&action=index');
        }
    }

    private function requireApprovalPermission(): void
    {
        $user = $this->currentUser();
        $role = $user['IdVaiTro'] ?? null;

        if (!$this->canApprovePayrolls($role)) {
            $this->setFlash('danger', 'Chỉ ban giám đốc mới được phép phê duyệt bảng lương.');
            $this->redirect('?controller=salary&action=index');
        }
    }

    public function index(): void
    {
        $payrolls = $this->salaryModel->getPayrolls();
        $summary = $this->salaryModel->getPayrollSummary();
        $pending = $this->salaryModel->getPendingPayrolls();
        $user = $this->currentUser();
        $role = $user['IdVaiTro'] ?? null;
        $this->render('salary/index', [
            'title' => 'Bảng lương',
            'payrolls' => $payrolls,
            'summary' => $summary,
            'pending' => $pending,
            'permissions' => [
                'canManage' => $this->canManagePayrolls($role),
                'canApprove' => $this->canApprovePayrolls($role),
            ],
        ]);
    }

    public function read(): void
    {
        $id = $_GET['id'] ?? null;
        $payroll = $id ? $this->salaryModel->find($id) : null;
        $figures = null;
        if ($payroll) {
            $figures = Salary::calculateFigures(
                (float) ($payroll['LuongCoBan'] ?? 0),
                (float) ($payroll['PhuCap'] ?? 0),
                (float) ($payroll['KhauTru'] ?? 0),
                (float) ($payroll['ThueTNCN'] ?? 0)
            );
        }
        $this->render('salary/read', [
            'title' => 'Chi tiết bảng lương',
            'payroll' => $payroll,
            'figures' => $figures,
            'permissions' => (function (): array {
                $user = $this->currentUser();
                $role = $user['IdVaiTro'] ?? null;

                return [
                    'canManage' => $this->canManagePayrolls($role),
                    'canApprove' => $this->canApprovePayrolls($role),
                ];
            })(),
        ]);
    }

    public function create(): void
    {
        $this->requireManagePermission();
        $employees = $this->employeeModel->getActiveEmployees();
        $accountants = array_filter($employees, static function (array $employee): bool {
            $position = mb_strtolower($employee['ChucVu'] ?? '');
            return str_contains($position, 'kế toán') || str_contains($position, 'ketoan');
        });

        if (!$accountants) {
            $accountants = $employees;
        }

        $this->render('salary/create', [
            'title' => 'Tạo bảng lương',
            'employees' => $employees,
            'accountants' => $accountants,
        ]);
    }

    public function store(): void
    {
        $this->requireManagePermission();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=salary&action=index');
        }

        $data = $this->mapPayrollInput($_POST);
        $data['IdBangLuong'] = $data['IdBangLuong'] ?: uniqid('BL');

        try {
            $this->salaryModel->create($data);
            $this->setFlash('success', 'Đã tạo bảng lương.');
        } catch (Throwable $e) {
            $this->setFlash('danger', 'Không thể tạo bảng lương: ' . $e->getMessage());
        }

        $this->redirect('?controller=salary&action=index');
    }

    public function edit(): void
    {
        $this->requireManagePermission();
        $id = $_GET['id'] ?? null;
        $payroll = $id ? $this->salaryModel->find($id) : null;
        $employees = $this->employeeModel->getActiveEmployees();
        $accountants = array_filter($employees, static function (array $employee): bool {
            $position = mb_strtolower($employee['ChucVu'] ?? '');
            return str_contains($position, 'kế toán') || str_contains($position, 'ketoan');
        });

        if (!$accountants) {
            $accountants = $employees;
        }

        $this->render('salary/edit', [
            'title' => 'Cập nhật bảng lương',
            'payroll' => $payroll,
            'employees' => $employees,
            'accountants' => $accountants,
        ]);
    }

    public function update(): void
    {
        $this->requireManagePermission();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=salary&action=index');
        }

        $id = $_POST['IdBangLuong'];
        $data = $this->mapPayrollInput($_POST);
        unset($data['IdBangLuong']);

        try {
            $this->salaryModel->update($id, $data);
            $this->setFlash('success', 'Cập nhật bảng lương thành công.');
        } catch (Throwable $e) {
            $this->setFlash('danger', 'Không thể cập nhật bảng lương: ' . $e->getMessage());
        }

        $this->redirect('?controller=salary&action=index');
    }

    public function approve(): void
    {
        $this->requireApprovalPermission();
        $this->changeStatus('Đã duyệt');
    }

    public function finalize(): void
    {
        $this->requireApprovalPermission();
        $this->changeStatus('Đã chi');
    }

    public function revert(): void
    {
        $this->requireApprovalPermission();
        $this->changeStatus('Chờ duyệt');
    }

    public function recalculate(): void
    {
        $this->requireManagePermission();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->setFlash('danger', 'Không xác định được bảng lương.');
            $this->redirect('?controller=salary&action=index');
        }

        $payroll = $this->salaryModel->find($id);
        if (!$payroll) {
            $this->setFlash('danger', 'Không tìm thấy bảng lương.');
            $this->redirect('?controller=salary&action=index');
        }

        $figures = Salary::calculateFigures(
            (float) ($payroll['LuongCoBan'] ?? 0),
            (float) ($payroll['PhuCap'] ?? 0),
            (float) ($payroll['KhauTru'] ?? 0),
            (float) ($payroll['ThueTNCN'] ?? 0)
        );

        try {
            $this->salaryModel->update($id, ['TongThuNhap' => $figures['net']]);
            $this->setFlash('success', 'Đã tính lại lương thực nhận.');
        } catch (Throwable $e) {
            $this->setFlash('danger', 'Không thể tính lại: ' . $e->getMessage());
        }

        $this->redirect('?controller=salary&action=read&id=' . urlencode($id));
    }

    public function recalculateAll(): void
    {
        $this->requireManagePermission();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->setFlash('danger', 'Phương thức không hợp lệ.');
            $this->redirect('?controller=salary&action=index');
        }

        try {
            $updated = $this->salaryModel->recalculateAll();
            $message = $updated > 0
                ? sprintf('Đã tính lại lương cho %d bảng lương.', $updated)
                : 'Không có bảng lương nào cần cập nhật.';
            $this->setFlash('success', $message);
        } catch (Throwable $e) {
            $this->setFlash('danger', 'Không thể tính lại hàng loạt: ' . $e->getMessage());
        }

        $this->redirect('?controller=salary&action=index');
    }

    public function delete(): void
    {
        $this->requireManagePermission();
        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                $this->salaryModel->delete($id);
                $this->setFlash('success', 'Đã xóa bảng lương.');
            } catch (Throwable $e) {
                $this->setFlash('danger', 'Không thể xóa bảng lương: ' . $e->getMessage());
            }
        }

        $this->redirect('?controller=salary&action=index');
    }

    private function mapPayrollInput(array $input): array
    {
        $baseSalary = (float) ($input['LuongCoBan'] ?? 0);
        $allowance = (float) ($input['PhuCap'] ?? 0);
        $deduction = (float) ($input['KhauTru'] ?? 0);
        $personalIncomeTax = (float) ($input['ThueTNCN'] ?? 0);
        $figures = Salary::calculateFigures($baseSalary, $allowance, $deduction, $personalIncomeTax);

        $status = $input['TrangThai'] ?? 'Chờ duyệt';
        if (!in_array($status, ['Chờ duyệt', 'Đã duyệt', 'Đã chi'], true)) {
            $status = 'Chờ duyệt';
        }

        $user = $this->currentUser();
        $role = $user['IdVaiTro'] ?? null;
        if (!$this->canApprovePayrolls($role)) {
            $status = 'Chờ duyệt';
        }

        return [
            'IdBangLuong' => trim($input['IdBangLuong'] ?? ''),
            Salary::ACCOUNTANT_COLUMN => trim($input['KeToan'] ?? ''),
            Salary::EMPLOYEE_COLUMN => trim($input['IdNhanVien'] ?? ''),
            'ThangNam' => trim($input['ThangNam'] ?? ''),
            'LuongCoBan' => $baseSalary,
            'PhuCap' => $allowance,
            'KhauTru' => $deduction,
            'ThueTNCN' => $personalIncomeTax,
            'TongThuNhap' => $figures['net'],
            'TrangThai' => $status,
            'NgayLap' => $input['NgayLap'] ?? date('Y-m-d'),
            'ChuKy' => trim((string) ($input['ChuKy'] ?? '')) ?: null,
        ];
    }

    private function changeStatus(string $status): void
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->setFlash('danger', 'Không xác định được bảng lương.');
            $this->redirect('?controller=salary&action=index');
        }

        $signature = null;
        $user = $this->currentUser();
        if ($user) {
            $signature = $user['TenDangNhap'] ?? $user['HoTen'] ?? ($user['IdNhanVien'] ?? null);
        }

        try {
            $this->salaryModel->updateStatus($id, $status, $signature);
            $message = match ($status) {
                'Đã duyệt' => 'Đã phê duyệt bảng lương.',
                'Đã chi' => 'Đã đánh dấu bảng lương đã chi trả.',
                default => 'Đã cập nhật trạng thái bảng lương.',
            };
            $this->setFlash('success', $message);
        } catch (Throwable $e) {
            $this->setFlash('danger', 'Không thể cập nhật trạng thái: ' . $e->getMessage());
        }

        $this->redirect('?controller=salary&action=index');
    }
}
