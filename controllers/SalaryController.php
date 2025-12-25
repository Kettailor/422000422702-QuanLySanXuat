<?php

class SalaryController extends Controller
{
    private const WIZARD_SESSION_KEY = 'payroll_wizard';

    private Salary $salaryModel;
    private Employee $employeeModel;
    private Attendance $attendanceModel;

    public function __construct()
    {
        $this->authorize(['VT_KETOAN', 'VT_BAN_GIAM_DOC']);
        $this->salaryModel = new Salary();
        $this->employeeModel = new Employee();
        $this->attendanceModel = new Attendance();
    }

    private function canManagePayrolls(?string $role): bool
    {
        return in_array($role, ['VT_KETOAN'], true);
    }

    private function canApprovePayrolls(?string $role): bool
    {
        return in_array($role, ['VT_BAN_GIAM_DOC'], true);
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
        $employeeId = $_GET['employee_id'] ?? null;
        $payrolls = $this->salaryModel->getPayrolls(50, $employeeId);
        $summary = $this->salaryModel->getPayrollSummary($employeeId);
        $pending = $this->salaryModel->getPendingPayrolls(5, $employeeId);
        $user = $this->currentUser();
        $role = $user['IdVaiTro'] ?? null;
        $employee = $employeeId ? $this->employeeModel->find($employeeId) : null;

        $this->render('salary/index', [
            'title' => 'Bảng lương',
            'payrolls' => $payrolls,
            'summary' => $summary,
            'pending' => $pending,
            'employeeFilter' => $employee,
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
        if (!$payroll) {
            $this->setFlash('warning', 'Không tìm thấy bảng lương.');
            $this->redirect('?controller=salary&action=index');
            return;
        }
        if ($payroll && !isset($payroll['HoTen'])) {
            $employeeId = $payroll[Salary::EMPLOYEE_COLUMN] ?? null;
            if ($employeeId) {
                $employee = $this->employeeModel->find($employeeId);
                if ($employee) {
                    $payroll['HoTen'] = $employee['HoTen'];
                }
            }
        }
        if ($payroll && !$this->salaryModel->supportsWorkingDays()) {
            if (!isset($payroll['SoNgayCong']) || $payroll['SoNgayCong'] === null) {
                $payroll['SoNgayCong'] = $this->salaryModel->deriveWorkingDays($payroll);
            }
        }
        $figures = $payroll ? Salary::calculateFigures($payroll) : null;
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

        if (isset($_GET['reset']) && $_GET['reset'] === '1') {
            $this->resetWizard();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $period = $this->normalizeMonthInput((string) ($_POST['period'] ?? ''));
            if ($period === '') {
                $this->setFlash('danger', 'Vui lòng chọn kỳ tính lương hợp lệ.');
            } else {
                $wizard = $this->getWizardState();
                $wizard['period'] = $period;
                unset($wizard['attendance'], $wizard['compensation'], $wizard['insurance']);
                $this->saveWizardState($wizard);
                $this->redirect('?controller=salary&action=wizardAttendance');
                return;
            }
        }

        $periods = $this->attendanceModel->getAvailablePeriods();
        $currentMonth = date('Y-m');
        if ($currentMonth && !in_array($currentMonth, $periods, true)) {
            array_unshift($periods, $currentMonth);
        }

        $this->render('salary/create', [
            'title' => 'Chọn kỳ tính lương',
            'periods' => $periods,
            'selectedPeriod' => $this->getWizardState()['period'] ?? $currentMonth,
        ]);
    }

    public function wizardAttendance(): void
    {
        $this->requireManagePermission();
        $wizard = $this->getWizardState();
        $period = $wizard['period'] ?? null;

        if (!$period) {
            $this->setFlash('danger', 'Vui lòng chọn kỳ tính lương trước.');
            $this->redirect('?controller=salary&action=create');
        }

        $attendance = $this->attendanceModel->getMonthlySummary($period);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $eligibleAttendance = array_values(array_filter(
                $attendance,
                static function (array $row): bool {
                    return ($row['working_days'] ?? 0) > 0;
                },
            ));

            if (!$eligibleAttendance) {
                $this->setFlash('danger', 'Không có nhân viên nào có ngày công trong kỳ này để tính lương.');
            } else {
                if (count($eligibleAttendance) < count($attendance)) {
                    $this->setFlash('warning', 'Nhân viên có số ngày công bằng 0 đã được loại khỏi các bước tiếp theo.');
                }

                $wizard['attendance'] = $eligibleAttendance;
                unset($wizard['compensation'], $wizard['insurance']);
                $this->saveWizardState($wizard);
                $this->redirect('?controller=salary&action=wizardCompensation');
                return;
            }
        }

        $this->render('salary/wizard_attendance', [
            'title' => 'Tổng hợp chấm công',
            'period' => $period,
            'attendance' => $attendance,
        ]);
    }

    public function wizardCompensation(): void
    {
        $this->requireManagePermission();
        $wizard = $this->getWizardState();
        $period = $wizard['period'] ?? null;
        $attendance = $wizard['attendance'] ?? [];

        $attendance = array_values(array_filter(
            $attendance,
            static function (array $row): bool {
                return ($row['working_days'] ?? 0) > 0;
            },
        ));

        if ($attendance !== ($wizard['attendance'] ?? [])) {
            $wizard['attendance'] = $attendance;
            if (!empty($wizard['compensation'])) {
                $allowedIds = array_column($attendance, 'employee_id');
                $wizard['compensation'] = array_intersect_key(
                    $wizard['compensation'],
                    array_fill_keys($allowedIds, true),
                );
            }
            $this->saveWizardState($wizard);
        }

        if (!$period || !$attendance) {
            $this->setFlash('danger', 'Thiếu dữ liệu chấm công. Vui lòng thực hiện lại.');
            $this->redirect('?controller=salary&action=create');
        }

        $defaults = $wizard['compensation'] ?? [];
        if (!$defaults) {
            $employeeMap = $this->getEmployeeMap();
            foreach ($attendance as $row) {
                $id = $row['employee_id'];
                $employeeInfo = $employeeMap[$id] ?? [];
                $base = $this->resolveBaseSalary($employeeInfo);
                $dailyRate = $this->getDefaultDailyRate();
                $workingDays = (float) ($row['working_days'] ?? 0);
                $dayIncome = round($dailyRate * $workingDays, 2);
                $defaults[$id] = [
                    'employee_id' => $id,
                    'employee_name' => $row['employee_name'],
                    'working_days' => $workingDays,
                    'total_hours' => (float) ($row['total_hours'] ?? 0),
                    'base_salary' => $base,
                    'allowance' => 0,
                    'daily_rate' => $dailyRate,
                    'day_income' => $dayIncome,
                    'bonus' => 0,
                    'total_salary' => $base + $dayIncome,
                ];
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $allowances = $_POST['PhuCap'] ?? [];
            $bonuses = $_POST['Thuong'] ?? [];
            $employeeMap = $this->getEmployeeMap();

            $compensation = [];
            foreach ($attendance as $row) {
                $id = $row['employee_id'];
                $workingDays = (float) $row['working_days'];
                $employeeInfo = $employeeMap[$id] ?? [];
                $base = $this->resolveBaseSalary($employeeInfo);
                $allowance = max((float) ($allowances[$id] ?? 0), 0.0);
                $dailyRate = $this->getDefaultDailyRate();
                $bonus = max((float) ($bonuses[$id] ?? 0), 0.0);
                $dayIncome = round($dailyRate * $workingDays, 2);
                $total = round($base + $allowance + $dayIncome + $bonus, 2);
                $compensation[$id] = [
                    'employee_id' => $id,
                    'employee_name' => $row['employee_name'],
                    'working_days' => $workingDays,
                    'total_hours' => (float) $row['total_hours'],
                    'base_salary' => $base,
                    'allowance' => $allowance,
                    'daily_rate' => $dailyRate,
                    'day_income' => $dayIncome,
                    'bonus' => $bonus,
                    'total_salary' => $total,
                ];
            }

            $wizard['compensation'] = $compensation;
            unset($wizard['insurance']);
            $this->saveWizardState($wizard);
            $this->redirect('?controller=salary&action=wizardInsurance');
            return;
        }

        $this->render('salary/wizard_compensation', [
            'title' => 'Nhập phụ cấp & thưởng',
            'period' => $period,
            'attendance' => $attendance,
            'compensation' => $defaults,
        ]);
    }

    public function wizardInsurance(): void
    {
        $this->requireManagePermission();
        $wizard = $this->getWizardState();
        $period = $wizard['period'] ?? null;
        $compensation = $wizard['compensation'] ?? [];

        if (!$period || !$compensation) {
            $this->setFlash('danger', 'Thiếu dữ liệu phụ cấp và thưởng.');
            $this->redirect('?controller=salary&action=create');
        }

        $insuranceRate = 0.105;
        $insurance = [];
        foreach ($compensation as $id => $row) {
            $insuranceBase = (float) ($row['base_salary'] ?? 0) + (float) ($row['day_income'] ?? 0);
            $totalInsurance = round($insuranceBase * $insuranceRate, 2);
            $tax = 0.0;

            $insurance[$id] = [
                'tax' => $tax,
                'total' => $totalInsurance,
                'tax_rate' => 0,
            ];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $wizard['insurance'] = $insurance;
            $this->saveWizardState($wizard);
            $this->redirect('?controller=salary&action=wizardReview');
            return;
        }

        $this->render('salary/wizard_insurance', [
            'title' => 'Tính bảo hiểm & thuế',
            'period' => $period,
            'compensation' => $compensation,
            'insurance' => $insurance,
        ]);
    }

    public function wizardReview(): void
    {
        $this->requireManagePermission();
        $wizard = $this->getWizardState();
        $period = $wizard['period'] ?? null;
        $compensation = $wizard['compensation'] ?? [];
        $insurance = $wizard['insurance'] ?? [];

        if (!$period || !$compensation || !$insurance) {
            $this->setFlash('danger', 'Thiếu dữ liệu tính lương. Vui lòng thực hiện lại.');
            $this->redirect('?controller=salary&action=create');
        }

        $rows = [];
        $totalNet = 0.0;
        foreach ($compensation as $id => $row) {
            $ins = $insurance[$id] ?? ['total' => 0, 'tax' => 0];
            $net = round($row['total_salary'] - ($ins['total'] ?? 0) - ($ins['tax'] ?? 0), 2);
            $rows[] = [
                'employee_id' => $row['employee_id'],
                'employee_name' => $row['employee_name'],
                'working_days' => $row['working_days'],
                'base_salary' => $row['base_salary'],
                'daily_rate' => $row['daily_rate'],
                'day_income' => $row['day_income'],
                'allowance' => $row['allowance'],
                'bonus' => $row['bonus'],
                'insurance' => $ins['total'] ?? 0,
                'tax' => $ins['tax'] ?? 0,
                'net_income' => $net,
            ];
            $totalNet += $net;
        }

        $summary = [
            'employees' => count($rows),
            'total_net' => round($totalNet, 2),
        ];

        $this->render('salary/wizard_review', [
            'title' => 'Tổng hợp tính lương',
            'period' => $period,
            'rows' => $rows,
            'summary' => $summary,
        ]);
    }

    public function wizardFinalize(): void
    {
        $this->requireManagePermission();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=salary&action=wizardReview');
        }

        $wizard = $this->getWizardState();
        $period = $wizard['period'] ?? null;
        $compensation = $wizard['compensation'] ?? [];
        $insurance = $wizard['insurance'] ?? [];

        if (!$period || !$compensation || !$insurance) {
            $this->setFlash('danger', 'Không thể hoàn tất vì thiếu dữ liệu.');
            $this->redirect('?controller=salary&action=create');
        }

        $user = $this->currentUser();
        $accountantId = $user['IdNhanVien'] ?? 'SYSTEM';
        $monthValue = (int) str_replace('-', '', $period);

        $created = 0;
        foreach ($compensation as $id => $row) {
            $ins = $insurance[$id] ?? ['total' => 0, 'tax' => 0];
            $netIncome = round($row['total_salary'] - ($ins['total'] ?? 0) - ($ins['tax'] ?? 0), 2);
            $payload = [
                'IdBangLuong' => uniqid('BL'),
                Salary::ACCOUNTANT_COLUMN => $accountantId,
                Salary::EMPLOYEE_COLUMN => $id,
                'ThangNam' => $monthValue,
                'LuongCoBan' => $row['base_salary'],
                'PhuCap' => $row['allowance'],
                'DonGiaNgayCong' => $row['daily_rate'],
                'SoNgayCong' => $row['working_days'],
                'TongLuongNgayCong' => $row['day_income'],
                'Thuong' => $row['bonus'],
                'KhauTru' => $ins['total'] ?? 0,
                'TongBaoHiem' => $ins['total'] ?? 0,
                'ThueTNCN' => $ins['tax'] ?? 0,
                'TongThuNhap' => $netIncome,
                'TrangThai' => 'Chờ duyệt',
                'NgayLap' => date('Y-m-d'),
                'ChuKy' => sprintf('Tự động tạo kỳ %s', $period),
            ];

            try {
                $this->salaryModel->create($payload);
                $created++;
            } catch (Throwable $e) {
                $this->setFlash('danger', 'Lỗi khi lưu bảng lương cho nhân viên ' . $id . ': ' . $e->getMessage());
                $this->redirect('?controller=salary&action=wizardReview');
            }
        }

        $this->resetWizard();
        $this->setFlash('success', sprintf('Đã tạo %d bảng lương cho kỳ %s.', $created, $period));
        $this->redirect('?controller=salary&action=index');
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
            Logger::error('Lỗi khi tạo bảng lương: ' . $e->getMessage());
            $this->setFlash('danger', 'Không thể tạo bảng lương: ' . $e->getMessage());
        }

        $this->redirect('?controller=salary&action=index');
    }

    public function edit(): void
    {
        $this->requireManagePermission();
        $id = $_GET['id'] ?? null;
        $payroll = $id ? $this->salaryModel->find($id) : null;
        if ($payroll && !$this->salaryModel->supportsWorkingDays()) {
            if (!isset($payroll['SoNgayCong']) || $payroll['SoNgayCong'] === null) {
                $payroll['SoNgayCong'] = $this->salaryModel->deriveWorkingDays($payroll);
            }
        }
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
            Logger::error('Lỗi khi cập nhật bảng lương ' . $id . ': ' . $e->getMessage());
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

        $insurance = round(((float) ($payroll['LuongCoBan'] ?? 0) + (float) ($payroll['TongLuongNgayCong'] ?? 0)) * 0.105, 2);
        $figures = Salary::calculateFigures($payroll);

        try {
            $this->salaryModel->update($id, [
                'TongThuNhap' => $figures['net'],
                'KhauTru' => $insurance,
                'TongBaoHiem' => $insurance,
            ]);
            $this->setFlash('success', 'Đã tính lại lương thực nhận.');
        } catch (Throwable $e) {
            Logger::error('Lỗi khi tính lại bảng lương ' . $id . ': ' . $e->getMessage());
            /* $this->setFlash('danger', 'Không thể tính lại: ' . $e->getMessage()); */
            $this->setFlash('danger', 'Không thể tính lại bảng lương, vui lòng kiểm tra log để biết thêm chi tiết.');
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
            $this->setFlash('warning', 'Chức năng xóa bảng lương đã bị vô hiệu. Vui lòng cập nhật trạng thái hoặc ghi chú.');
        }

        $this->redirect('?controller=salary&action=index');
    }

    private function mapPayrollInput(array $input): array
    {
        $employeeId = trim($input['IdNhanVien'] ?? '');
        $employee = $employeeId ? $this->employeeModel->find($employeeId) : null;
        $baseSalary = $this->resolveBaseSalary($employee ?? []);
        $allowance = max((float) ($input['PhuCap'] ?? 0), 0);
        $dailyRate = $this->getDefaultDailyRate();
        $workingDays = max((float) ($input['SoNgayCong'] ?? 0), 0);
        $bonus = max((float) ($input['Thuong'] ?? 0), 0);
        $tax = max((float) ($input['ThueTNCN'] ?? 0), 0);
        $monthValue = $this->normalizeMonthInput((string) ($input['ThangNam'] ?? ''));

        $dayIncome = round($dailyRate * $workingDays, 2);
        $insurance = round(($baseSalary + $dayIncome) * 0.105, 2);
        $figures = Salary::calculateFigures([
            'LuongCoBan' => $baseSalary,
            'PhuCap' => $allowance,
            'TongLuongNgayCong' => $dayIncome,
            'Thuong' => $bonus,
            'KhauTru' => $insurance,
            'ThueTNCN' => $tax,
        ]);

        $status = $input['TrangThai'] ?? 'Chờ duyệt';
        if (!in_array($status, ['Chờ duyệt', 'Đã duyệt', 'Đã chi'], true)) {
            $status = 'Chờ duyệt';
        }

        $user = $this->currentUser();
        $role = $user['IdVaiTro'] ?? null;
        if (!$this->canApprovePayrolls($role)) {
            $status = 'Chờ duyệt';
        }

        $accountantId = trim($input['KeToan'] ?? '');
        if ($accountantId === '') {
            $user = $this->currentUser();
            $accountantId = $user['IdNhanVien'] ?? 'SYSTEM';
        }

        return [
            'IdBangLuong' => trim($input['IdBangLuong'] ?? ''),
            Salary::ACCOUNTANT_COLUMN => $accountantId,
            Salary::EMPLOYEE_COLUMN => $employeeId,
            'ThangNam' => $this->formatMonthForStorage($monthValue),
            'LuongCoBan' => $baseSalary,
            'PhuCap' => $allowance,
            'DonGiaNgayCong' => $dailyRate,
            'SoNgayCong' => $workingDays,
            'TongLuongNgayCong' => $dayIncome,
            'Thuong' => $bonus,
            'KhauTru' => $insurance,
            'TongBaoHiem' => max((float) ($input['TongBaoHiem'] ?? $insurance), 0),
            'ThueTNCN' => $tax,
            'TongThuNhap' => $figures['net'],
            'TrangThai' => $status,
            'NgayLap' => $input['NgayLap'] ?? date('Y-m-d'),
            'ChuKy' => trim((string) ($input['ChuKy'] ?? '')) ?: null,
        ];
    }

    private function getEmployeeMap(): array
    {
        $employees = $this->employeeModel->all(500);
        $map = [];
        foreach ($employees as $employee) {
            $id = $employee['IdNhanVien'] ?? null;
            if ($id) {
                $map[$id] = $employee;
            }
        }

        return $map;
    }

    private function resolveBaseSalary(array $employee): float
    {
        $position = mb_strtolower((string) ($employee['ChucVu'] ?? ''));
        $role = $employee['IdVaiTro'] ?? null;

        $roleMap = [
            'VT_BAN_GIAM_DOC' => 10000000,
            'VT_KETOAN' => 7000000,
            'VT_KINH_DOANH' => 6500000,
            'VT_TRUONG_XUONG_KIEM_DINH' => 8000000,
            'VT_TRUONG_XUONG_LAP_RAP_DONG_GOI' => 8000000,
            'VT_TRUONG_XUONG_SAN_XUAT' => 8000000,
            'VT_TRUONG_XUONG_LUU_TRU' => 8000000,
            'VT_NHANVIEN_KHO' => 5000000,
            'VT_NHANVIEN_SANXUAT' => 4500000,
            'VT_KIEM_SOAT_CL' => 5500000,
            'VT_NHANVIEN_KIEM_DINH' => 5500000,
        ];

        if ($role && isset($roleMap[$role])) {
            return (float) $roleMap[$role];
        }

        $positionMap = [
            'giám đốc' => 10000000,
            'pho giam doc' => 9000000,
            'phó giám đốc' => 9000000,
            'trưởng' => 8000000,
            'kế toán' => 7000000,
            'ketoan' => 7000000,
            'kho' => 5000000,
            'sản xuất' => 4500000,
            'kiem dinh' => 5500000,
            'kiểm định' => 5500000,
        ];

        foreach ($positionMap as $keyword => $salary) {
            if ($position !== '' && str_contains($position, $keyword)) {
                return (float) $salary;
            }
        }

        return 4000000;
    }

    private function getDefaultDailyRate(): float
    {
        return 300000;
    }

    private function getWizardState(): array
    {
        return $_SESSION[self::WIZARD_SESSION_KEY] ?? [];
    }

    private function saveWizardState(array $state): void
    {
        $_SESSION[self::WIZARD_SESSION_KEY] = $state;
    }

    private function resetWizard(): void
    {
        unset($_SESSION[self::WIZARD_SESSION_KEY]);
    }

    private function formatMonthForStorage(string $value)
    {
        if ($value === '') {
            return '';
        }

        if (preg_match('/^(\d{4})-(\d{2})$/', $value)) {
            return (int) str_replace('-', '', $value);
        }

        if (preg_match('/^(\d{4})(\d{2})$/', $value, $matches)) {
            return (int) ($matches[1] . $matches[2]);
        }

        if (preg_match('/^(\d{2})\/(\d{4})$/', $value, $matches)) {
            return (int) ($matches[2] . $matches[1]);
        }

        return $value;
    }

    private function normalizeMonthInput(string $value): string
    {
        $value = trim($value);

        if ($value === '') {
            return '';
        }

        if (preg_match('/^\d{4}-\d{2}$/', $value)) {
            return $value;
        }

        if (preg_match('/^(\d{4})(\d{2})$/', $value, $matches)) {
            return sprintf('%s-%s', $matches[1], $matches[2]);
        }

        if (preg_match('/^(\d{2})\/(\d{4})$/', $value, $matches)) {
            return sprintf('%s-%s', $matches[2], $matches[1]);
        }

        return $value;
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
            $this->setFlash('danger', 'Không thể cập nhật trạng thái: ' . $e->getMessage());
        }

        $this->redirect('?controller=salary&action=index');
    }
}
