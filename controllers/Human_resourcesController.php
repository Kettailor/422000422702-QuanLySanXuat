<?php

class Human_resourcesController extends Controller
{
    private Employee $employeeModel;
    private Role $roleModel;
    private Salary $salaryModel;
    private Timekeeping $timekeepingModel;
    private WorkshopPlanAssignment $planAssignmentModel;
    private WorkshopPlan $workshopPlanModel;

    public function __construct()
    {
        $this->authorize(['VT_BAN_GIAM_DOC', 'VT_KHO_TRUONG']);
        $this->employeeModel = new Employee();
        $this->roleModel = new Role();
        $this->salaryModel = new Salary();
        $this->timekeepingModel = new Timekeeping();
        $this->planAssignmentModel = new WorkshopPlanAssignment();
        $this->workshopPlanModel = new WorkshopPlan();
    }

    public function index(): void
    {
        $currentRole = $this->currentRole();
        $employees = $this->isWarehouseManager($currentRole)
            ? $this->employeeModel->getEmployeesByRoleIds($this->getWarehouseStaffRoleIds())
            : $this->employeeModel->all(200);
        $roles = $this->roleModel->all(200);
        $roleMap = [];
        foreach ($roles as $roleRow) {
            $roleId = $roleRow['IdVaiTro'] ?? null;
            if ($roleId) {
                $roleMap[$roleId] = $roleRow;
            }
        }
        $this->render('human_resources/index', [
            'title' => 'Quản lý nhân sự',
            'employees' => $employees,
            'roleMap' => $roleMap,
            'scopeNote' => $this->isWarehouseManager($currentRole)
                ? 'Bạn đang quản lý danh sách nhân sự kho tổng. Chỉ nhân viên kho được phép cập nhật.'
                : null,
            'showSalaryLink' => in_array($currentRole, ['VT_BAN_GIAM_DOC'], true),
            'showTimekeepingLink' => in_array($currentRole, ['VT_BAN_GIAM_DOC', 'VT_KHO_TRUONG'], true),
        ]);
    }

    public function create(): void
    {
        $role = $this->currentRole();
        $this->render('human_resources/create', [
            'title' => 'Thêm nhân sự',
            'roles' => $this->filterRolesForWarehouseManager($this->roleModel->all(200), $role),
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=human_resources&action=index');
        }

        $role = $this->currentRole();
        $selectedRole = $_POST['IdVaiTro'] ?? null;
        if ($this->isWarehouseManager($role)) {
            $selectedRole = 'VT_NHANVIEN_KHO';
        } elseif (!$this->isRoleAllowed($selectedRole)) {
            $this->setFlash('danger', 'Vai trò nhân sự không hợp lệ.');
            $this->redirect('?controller=human_resources&action=create');
        }

        $data = [
            'IdNhanVien' => $_POST['IdNhanVien'] ?: uniqid('NV'),
            'HoTen' => $_POST['HoTen'] ?? null,
            'NgaySinh' => $_POST['NgaySinh'] ?? null,
            'GioiTinh' => $_POST['GioiTinh'] ?? 1,
            'ChucVu' => $_POST['ChucVu'] ?? null,
            'HeSoLuong' => $_POST['HeSoLuong'] ?? 1,
            'TrangThai' => $_POST['TrangThai'] ?? 'Đang làm việc',
            'DiaChi' => $_POST['DiaChi'] ?? null,
            'ThoiGianLamViec' => $_POST['ThoiGianLamViec'] ?? date('Y-m-d H:i:s'),
            'IdVaiTro' => $selectedRole,
            'ChuKy' => null,
        ];

        try {
            $this->employeeModel->create($data);
            $this->setFlash('success', 'Thêm nhân sự thành công.');
        } catch (Throwable $e) {
            Logger::error('Lỗi khi thêm nhân sự: ' . $e->getMessage());
            /* $this->setFlash('danger', 'Không thể thêm nhân sự: ' . $e->getMessage()); */
            $this->setFlash('danger', 'Không thể thêm nhân sự, vui lòng kiểm tra log để biết thêm chi tiết.');
        }

        $this->redirect('?controller=human_resources&action=index');
    }

    public function edit(): void
    {
        $id = $_GET['id'] ?? null;
        $employee = $id ? $this->employeeModel->find($id) : null;
        $role = $this->currentRole();

        if ($employee && $this->isWarehouseManager($role) && !$this->isEmployeeWithinWarehouseScope($employee)) {
            $this->setFlash('danger', 'Bạn chỉ có thể chỉnh sửa nhân viên kho.');
            $this->redirect('?controller=human_resources&action=index');
        }

        $this->render('human_resources/edit', [
            'title' => 'Cập nhật nhân sự',
            'employee' => $employee,
            'roles' => $this->filterRolesForWarehouseManager($this->roleModel->all(200), $role),
        ]);
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=human_resources&action=index');
        }

        $id = $_POST['IdNhanVien'];
        $role = $this->currentRole();
        $employee = $id ? $this->employeeModel->find($id) : null;
        if ($employee && $this->isWarehouseManager($role) && !$this->isEmployeeWithinWarehouseScope($employee)) {
            $this->setFlash('danger', 'Bạn chỉ có thể chỉnh sửa nhân viên kho.');
            $this->redirect('?controller=human_resources&action=index');
        }

        $selectedRole = $_POST['IdVaiTro'] ?? null;
        if ($this->isWarehouseManager($role)) {
            $selectedRole = 'VT_NHANVIEN_KHO';
        } elseif (!$this->isRoleAllowed($selectedRole)) {
            $this->setFlash('danger', 'Vai trò nhân sự không hợp lệ.');
            $this->redirect('?controller=human_resources&action=edit&id=' . urlencode($id));
        }

        $data = [
            'HoTen' => $_POST['HoTen'] ?? null,
            'NgaySinh' => $_POST['NgaySinh'] ?? null,
            'GioiTinh' => $_POST['GioiTinh'] ?? 1,
            'ChucVu' => $_POST['ChucVu'] ?? null,
            'HeSoLuong' => $_POST['HeSoLuong'] ?? 1,
            'TrangThai' => $_POST['TrangThai'] ?? 'Đang làm việc',
            'DiaChi' => $_POST['DiaChi'] ?? null,
            'ThoiGianLamViec' => $_POST['ThoiGianLamViec'] ?? date('Y-m-d H:i:s'),
            'IdVaiTro' => $selectedRole,
        ];

        try {
            $this->employeeModel->update($id, $data);
            $this->setFlash('success', 'Cập nhật nhân sự thành công.');
        } catch (Throwable $e) {
            Logger::error('Lỗi khi cập nhật nhân sự ' . $id . ': ' . $e->getMessage());
            /* $this->setFlash('danger', 'Không thể cập nhật nhân sự: ' . $e->getMessage()); */
            $this->setFlash('danger', 'Không thể cập nhật nhân sự, vui lòng kiểm tra log để biết thêm chi tiết.');
        }

        $this->redirect('?controller=human_resources&action=index');
    }

    public function delete(): void
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $role = $this->currentRole();
            $employee = $this->employeeModel->find($id);
            if ($employee && $this->isWarehouseManager($role) && !$this->isEmployeeWithinWarehouseScope($employee)) {
                $this->setFlash('danger', 'Bạn chỉ có thể xóa nhân viên kho.');
                $this->redirect('?controller=human_resources&action=index');
            }
            try {
                $this->employeeModel->delete($id);
                $this->setFlash('success', 'Đã xóa nhân sự.');
            } catch (Throwable $e) {
                Logger::error('Lỗi khi xóa nhân sự ' . $id . ': ' . $e->getMessage());
                /* $this->setFlash('danger', 'Không thể xóa nhân sự: ' . $e->getMessage()); */
                $this->setFlash('danger', 'Không thể xóa nhân sự, vui lòng kiểm tra log để biết thêm chi tiết.');
            }
        }

        $this->redirect('?controller=human_resources&action=index');
    }

    public function read(): void
    {
        $id = $_GET['id'] ?? null;
        $employee = $id ? $this->employeeModel->find($id) : null;
        $currentRole = $this->currentRole();
        if ($employee && $this->isWarehouseManager($currentRole) && !$this->isEmployeeWithinWarehouseScope($employee)) {
            $this->setFlash('danger', 'Bạn chỉ có thể xem nhân viên kho.');
            $this->redirect('?controller=human_resources&action=index');
        }
        $role = null;
        if ($employee && !empty($employee['IdVaiTro'])) {
            $role = $this->roleModel->find($employee['IdVaiTro']);
        }

        $payrolls = [];
        $salarySummary = null;
        $timekeepingEntries = [];
        $plans = [];

        if ($employee) {
            $payrolls = $this->salaryModel->getPayrolls(50, $employee['IdNhanVien']);
            $salarySummary = $this->salaryModel->getPayrollSummary($employee['IdNhanVien']);
            $timekeepingEntries = $this->timekeepingModel->getRecentRecords(
                200,
                null,
                null,
                null,
                null,
                $employee['IdNhanVien']
            );

            $planIds = $this->planAssignmentModel->getPlanIdsByEmployee($employee['IdNhanVien']);
            $plans = $this->workshopPlanModel->getDetailedPlans(200);
            if ($planIds) {
                $allowed = array_fill_keys($planIds, true);
                $plans = array_values(array_filter($plans, static function (array $plan) use ($allowed): bool {
                    $planId = $plan['IdKeHoachSanXuatXuong'] ?? null;
                    return $planId !== null && isset($allowed[$planId]);
                }));
            } else {
                $plans = [];
            }
        }
        $this->render('human_resources/read', [
            'title' => 'Chi tiết nhân sự',
            'employee' => $employee,
            'role' => $role,
            'payrolls' => $payrolls,
            'salarySummary' => $salarySummary,
            'timekeepingEntries' => $timekeepingEntries,
            'plans' => $plans,
        ]);
    }

    private function currentRole(): ?string
    {
        $user = $this->currentUser();

        return $user['ActualIdVaiTro'] ?? ($user['IdVaiTro'] ?? null);
    }

    private function isWarehouseManager(?string $role): bool
    {
        return $role === 'VT_KHO_TRUONG';
    }

    private function getWarehouseStaffRoleIds(): array
    {
        return ['VT_NHANVIEN_KHO'];
    }

    private function filterRolesForWarehouseManager(array $roles, ?string $role): array
    {
        if (!$this->isWarehouseManager($role)) {
            return $roles;
        }

        $allowed = array_fill_keys($this->getWarehouseStaffRoleIds(), true);

        return array_values(array_filter($roles, static function (array $roleRow) use ($allowed): bool {
            $roleId = $roleRow['IdVaiTro'] ?? null;

            return $roleId !== null && isset($allowed[$roleId]);
        }));
    }

    private function isEmployeeWithinWarehouseScope(array $employee): bool
    {
        $roleId = $employee['IdVaiTro'] ?? null;

        return $roleId !== null && in_array($roleId, $this->getWarehouseStaffRoleIds(), true);
    }

    private function isRoleAllowed(?string $roleId): bool
    {
        if ($roleId === null || $roleId === '') {
            return false;
        }

        return (bool) $this->roleModel->find($roleId);
    }
}
