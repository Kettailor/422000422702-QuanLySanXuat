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

    public function index(): void
    {
        $payrolls = $this->salaryModel->getPayrolls();
        $summary = $this->salaryModel->getPayrollSummary();
        $pending = $this->salaryModel->getPendingPayrolls();
        $this->render('salary/index', [
            'title' => 'Bảng lương',
            'payrolls' => $payrolls,
            'summary' => $summary,
            'pending' => $pending,
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
        ]);
    }

    public function create(): void
    {
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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=salary&action=index');
        }

        $data = $this->mapPayrollInput($_POST);
        $data['IdBangLuong'] = $data['IdBangLuong'] ?: uniqid('BL');

        try {
            $this->salaryModel->create($data);
            $this->setFlash('success', 'Đã tạo bảng lương.');
        } catch (Throwable $e) {
            Logger::error('Lỗi khi tạo bảng lương: ' . $e->getMessage());
            /* $this->setFlash('danger', 'Không thể tạo bảng lương: ' . $e->getMessage()); */
            $this->setFlash('danger', 'Không thể tạo bảng lương, vui lòng kiểm tra log để biết thêm chi tiết.');
        }

        $this->redirect('?controller=salary&action=index');
    }

    public function edit(): void
    {
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
            Logger::error('Lỗi khi cập nhật bảng lương ' . $id . ': ' . $e->getMessage());
            /* $this->setFlash('danger', 'Không thể cập nhật bảng lương: ' . $e->getMessage()); */
            $this->setFlash('danger', 'Không thể cập nhật bảng lương, vui lòng kiểm tra log để biết thêm chi tiết.');
        }

        $this->redirect('?controller=salary&action=index');
    }

    public function approve(): void
    {
        $this->changeStatus('Đã duyệt');
    }

    public function finalize(): void
    {
        $this->changeStatus('Đã chi');
    }

    public function revert(): void
    {
        $this->changeStatus('Chờ duyệt');
    }

    public function recalculate(): void
    {
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
            Logger::error('Lỗi khi tính lại bảng lương ' . $id . ': ' . $e->getMessage());
            /* $this->setFlash('danger', 'Không thể tính lại: ' . $e->getMessage()); */
            $this->setFlash('danger', 'Không thể tính lại bảng lương, vui lòng kiểm tra log để biết thêm chi tiết.');
        }

        $this->redirect('?controller=salary&action=read&id=' . urlencode($id));
    }

    public function delete(): void
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                $this->salaryModel->delete($id);
                $this->setFlash('success', 'Đã xóa bảng lương.');
            } catch (Throwable $e) {
                Logger::error('Lỗi khi xóa bảng lương ' . $id . ': ' . $e->getMessage());
                /* $this->setFlash('danger', 'Không thể xóa bảng lương: ' . $e->getMessage()); */
                $this->setFlash('danger', 'Không thể xóa bảng lương, vui lòng kiểm tra log để biết thêm chi tiết.');
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
            Logger::error('Lỗi khi cập nhật trạng thái bảng lương ' . $id . ': ' . $e->getMessage());
            /* $this->setFlash('danger', 'Không thể cập nhật trạng thái: ' . $e->getMessage()); */
            $this->setFlash('danger', 'Không thể cập nhật trạng thái bảng lương, vui lòng kiểm tra log để biết thêm chi tiết.');
        }

        $this->redirect('?controller=salary&action=index');
    }
}
