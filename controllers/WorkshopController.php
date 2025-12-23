<?php

class WorkshopController extends Controller
{
    private Workshop $workshopModel;
    private WorkshopPlan $workshopPlanModel;
    private ProductComponentMaterial $componentMaterialModel;
    private Material $materialModel;
    private Employee $employeeModel;
    private WorkshopAssignment $assignmentModel;

    public function __construct()
    {
        $this->authorize(['VT_QUANLY_XUONG', 'VT_BAN_GIAM_DOC', 'VT_ADMIN']);
        $this->workshopModel = new Workshop();
        $this->workshopPlanModel = new WorkshopPlan();
        $this->componentMaterialModel = new ProductComponentMaterial();
        $this->materialModel = new Material();
        $this->employeeModel = new Employee();
        $this->assignmentModel = new WorkshopAssignment();
    }

    public function index(): void
    {
        $workshops = $this->attachStaffCounts($this->getVisibleWorkshops());
        $summary = $this->calculateSummary($workshops);
        $statusDistribution = $this->calculateStatusDistribution($workshops);
        $isWorkshopManager = $this->isWorkshopManager();

        $this->render('workshop/index', [
            'title' => 'Quản lý xưởng sản xuất',
            'workshops' => $workshops,
            'summary' => $summary,
            'statusDistribution' => $statusDistribution,
            'executiveOverview' => $this->buildExecutiveOverview($summary, $statusDistribution),
            'managerOverview' => $isWorkshopManager ? $this->buildManagerOverview($workshops) : null,
            'showExecutiveOverview' => !$isWorkshopManager,
            'canAssign' => $this->canAssign(),
        ]);
    }

    public function create(): void
    {
        if (!$this->canAssign()) {
            $this->setFlash('danger', 'Chỉ quản trị hệ thống hoặc ban giám đốc được phép thêm và phân công xưởng.');
            $this->redirect('?controller=workshop&action=index');
        }

        $employees = $this->employeeModel->getActiveEmployees();
        $defaultType = $this->getDefaultWorkshopType();
        $employeeGroups = $this->groupEmployeesForWorkshop($employees, $defaultType, []);
        $workshopTypeRules = $this->getWorkshopTypeRules();
        $this->render('workshop/create', [
            'title' => 'Thêm xưởng mới',
            'employees' => $employees,
            'employeeGroups' => $employeeGroups,
            'managerCandidates' => $this->getManagerCandidates(null, $defaultType),
            'selectedWarehouse' => [],
            'selectedProduction' => [],
            'workshopType' => $defaultType,
            'workshopTypes' => $this->getWorkshopTypes(),
            'workshopTypeRules' => $workshopTypeRules,
            'canAssign' => true,
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=workshop&action=index');
        }

        if (!$this->canAssign()) {
            $this->setFlash('danger', 'Bạn không có quyền phân công nhân sự cho xưởng.');
            $this->redirect('?controller=workshop&action=index');
        }

        $data = $this->extractWorkshopData($_POST);
        $assignments = $this->extractAssignments($_POST);
        $assignments = $this->filterAssignmentsByWorkshopType($assignments, $data['LoaiXuong'] ?? $this->getDefaultWorkshopType());
        $data['IdXuong'] = uniqid('XUONG');
        $data['NgayThanhLap'] = date('Y-m-d');

        if ($data['CongSuatToiDa'] < $data['CongSuatDangSuDung']) {
            $this->setFlash('danger', 'Công suất tối đa không được bé hơn công suất đang sử dụng.');
            $this->redirect('?controller=workshop&action=create');
            return;
        }

        if ($this->canAssign() && $data['XUONGTRUONG_IdNhanVien'] === '') {
            $this->setFlash('danger', 'Vui lòng chọn ít nhất 1 xưởng trưởng trước khi lưu.');
            $this->redirect('?controller=workshop&action=create');
            return;
        }
        if ($this->canAssign() && !$this->isValidManagerForType($data['XUONGTRUONG_IdNhanVien'], $data['LoaiXuong'])) {
            $this->setFlash('danger', 'Xưởng trưởng phải thuộc đúng nhóm nhân sự theo loại xưởng.');
            $this->redirect('?controller=workshop&action=create');
            return;
        }

        try {
            $this->workshopModel->create($data);
            $this->assignmentModel->syncAssignments(
                $data['IdXuong'],
                $assignments['warehouse'],
                $assignments['production']
            );
            $this->setFlash('success', 'Đã thêm xưởng sản xuất mới.');
        } catch (Throwable $exception) {
            Logger::error('Lỗi khi thêm xưởng: ' . $exception->getMessage());
            /* $this->setFlash('danger', 'Không thể thêm xưởng: ' . $exception->getMessage()); */
            $this->setFlash('danger', 'Không thể thêm xưởng, vui lòng kiểm tra log để biết thêm chi tiết.');
        }

        $this->redirect('?controller=workshop&action=index');
    }

    public function edit(): void
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->setFlash('danger', 'Không xác định được xưởng cần cập nhật.');
            $this->redirect('?controller=workshop&action=index');
        }

        if (!$this->canManageWorkshop($id)) {
            $this->setFlash('danger', 'Bạn không được phép chỉnh sửa xưởng này.');
            $this->redirect('?controller=workshop&action=index');
        }

        $workshop = $this->workshopModel->find($id);
        $managerName = null;
        $managerId = $workshop['XUONGTRUONG_IdNhanVien'] ?? null;
        if ($managerId) {
            $manager = $this->employeeModel->find($managerId);
            $managerName = $manager['HoTen'] ?? null;
        }
        $assignments = $this->assignmentModel->getAssignmentsByWorkshop($id);
        $canAssignStaff = $this->canAssignStaff($id);
        $employees = $canAssignStaff ? $this->employeeModel->getActiveEmployees() : [];
        $selectedWarehouse = array_column($assignments['nhan_vien_kho'] ?? [], 'IdNhanVien');
        $selectedProduction = array_column($assignments['nhan_vien_san_xuat'] ?? [], 'IdNhanVien');
        $workshopType = $workshop['LoaiXuong'] ?? $this->getDefaultWorkshopType();
        $employeeGroups = $canAssignStaff
            ? $this->groupEmployeesForWorkshop(
                $employees,
                $workshopType,
                array_merge($selectedWarehouse, $selectedProduction)
            )
            : ['warehouse' => [], 'production' => []];
        $workshopTypeRules = $this->getWorkshopTypeRules();

        $this->render('workshop/edit', [
            'title' => 'Cập nhật thông tin xưởng',
            'workshop' => $workshop,
            'employees' => $employees,
            'employeeGroups' => $employeeGroups,
            'managerCandidates' => $canAssignStaff ? $this->getManagerCandidates($workshop, $workshopType) : [],
            'workshopManagerName' => $managerName,
            'selectedWarehouse' => $selectedWarehouse,
            'selectedProduction' => $selectedProduction,
            'canAssign' => $canAssignStaff,
            'canAssignManager' => $this->canAssign(),
            'canViewAssignments' => $canAssignStaff,
            'staffList' => $this->buildStaffList($assignments, $workshopType),
            'workshopType' => $workshopType,
            'workshopTypes' => $this->getWorkshopTypes(),
            'workshopTypeRules' => $workshopTypeRules,
        ]);
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=workshop&action=index');
        }

        $id = $_POST['IdXuong'] ?? null;
        if (!$id) {
            $this->setFlash('danger', 'Không xác định được xưởng cần cập nhật.');
            $this->redirect('?controller=workshop&action=index');
        }

        if (!$this->canManageWorkshop($id)) {
            $this->setFlash('danger', 'Bạn không được phép chỉnh sửa xưởng này.');
            $this->redirect('?controller=workshop&action=index');
        }

        $data = $this->extractWorkshopData($_POST);
        $assignments = $this->extractAssignments($_POST);
        $assignments = $this->filterAssignmentsByWorkshopType($assignments, $data['LoaiXuong'] ?? $this->getDefaultWorkshopType());
        $canAssign = $this->canAssignStaff($id);
        unset($data['IdXuong']);
        unset($data['NgayThanhLap']);

        if ($data['CongSuatToiDa'] < $data['CongSuatDangSuDung']) {
            $this->setFlash('danger', 'Công suất tối đa không được bé hơn công suất đang sử dụng.');
            $this->redirect('?controller=workshop&action=edit&id=' . urlencode($id));
            return;
        }

        if ($this->canAssign()) {
            if ($data['XUONGTRUONG_IdNhanVien'] === '') {
                $this->setFlash('danger', 'Vui lòng chọn ít nhất 1 xưởng trưởng trước khi lưu.');
                $this->redirect('?controller=workshop&action=edit&id=' . urlencode($id));
                return;
            }
            if (!$this->isValidManagerForType($data['XUONGTRUONG_IdNhanVien'], $data['LoaiXuong'])) {
                $this->setFlash('danger', 'Xưởng trưởng phải thuộc đúng nhóm nhân sự theo loại xưởng.');
                $this->redirect('?controller=workshop&action=edit&id=' . urlencode($id));
                return;
            }
        } else {
            unset($data['XUONGTRUONG_IdNhanVien']);
        }

        try {
            $this->workshopModel->update($id, $data);
            if ($canAssign) {
                $this->assignmentModel->syncAssignments(
                    $id,
                    $assignments['warehouse'],
                    $assignments['production']
                );
            }
            $this->setFlash('success', 'Cập nhật thông tin xưởng thành công.');
        } catch (Throwable $exception) {
            Logger::error('Lỗi khi cập nhật xưởng ' . $id . ': ' . $exception->getMessage());
            /* $this->setFlash('danger', 'Không thể cập nhật xưởng: ' . $exception->getMessage()); */
            $this->setFlash('danger', 'Không thể cập nhật xưởng, vui lòng kiểm tra log để biết thêm chi tiết.');
        }

        $this->redirect('?controller=workshop&action=index');
    }

    public function delete(): void
    {
        $id = $_GET['id'] ?? null;
        if (!$this->canAssign()) {
            $this->setFlash('danger', 'Bạn không có quyền xóa xưởng.');
            $this->redirect('?controller=workshop&action=index');
        }

        if ($id) {
            try {
                $this->workshopModel->delete($id);
                $this->setFlash('success', 'Đã xóa xưởng sản xuất.');
            } catch (Throwable $exception) {
                Logger::error('Lỗi khi xóa xưởng ' . $id . ': ' . $exception->getMessage());
                /* $this->setFlash('danger', 'Không thể xóa xưởng: ' . $exception->getMessage()); */
                $this->setFlash('danger', 'Không thể xóa xưởng, vui lòng kiểm tra log để biết thêm chi tiết.');
            }
        }

        $this->redirect('?controller=workshop&action=index');
    }

    public function read(): void
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->setFlash('danger', 'Không xác định được xưởng cần xem.');
            $this->redirect('?controller=workshop&action=index');
        }

        if (!$this->canViewWorkshop($id)) {
            $this->setFlash('danger', 'Bạn không được phép xem thông tin xưởng này.');
            $this->redirect('?controller=workshop&action=index');
        }

        $workshop = $id ? $this->workshopModel->find($id) : null;
        $assignments = $id ? $this->assignmentModel->getAssignmentsByWorkshop($id) : [];
        $canViewAssignments = $this->canAssignStaff($id);
        $workshopType = $workshop['LoaiXuong'] ?? $this->getDefaultWorkshopType();

        $this->render('workshop/read', [
            'title' => 'Chi tiết xưởng sản xuất',
            'workshop' => $workshop,
            'assignments' => $assignments,
            'canViewAssignments' => $canViewAssignments,
            'staffList' => $this->buildStaffList($assignments, $workshopType),
            'workshopType' => $workshopType,
        ]);
    }

    public function dashboard(): void
    {
        $workshops = $this->getVisibleWorkshops();
        $selectedWorkshop = $_GET['workshop'] ?? null;
        $selectedWorkshop = $selectedWorkshop !== '' ? $selectedWorkshop : null;
        $selectedWorkshop = $this->normalizeSelectedWorkshop($selectedWorkshop, $workshops);

        $plans = $this->workshopPlanModel->getDashboardPlans($selectedWorkshop);
        $plans = $this->filterMaterialPlans($plans);
        $plans = $this->filterPlansByVisibleWorkshops($plans, $workshops);

        $configurationIds = array_values(array_filter(array_map(
            fn ($plan) => $plan['AssignmentConfigurationId'] ?? $plan['IdCauHinh'] ?? null,
            $plans
        )));
        $materialsByComponent = $this->componentMaterialModel->getMaterialsForComponents($configurationIds);

        $materialIds = [];
        foreach ($materialsByComponent as $materials) {
            foreach ($materials as $material) {
                $materialId = $material['id'] ?? null;
                if ($materialId) {
                    $materialIds[$materialId] = true;
                }
            }
        }

        $inventory = [];
        if (!empty($materialIds)) {
            try {
                $inventory = $this->materialModel->findMany(array_keys($materialIds));
            } catch (Throwable $exception) {
                $inventory = [];
            }
        }

        $grouped = [];
        $metrics = [
            'total_plans' => 0,
            'shortage_plans' => 0,
            'total_materials' => 0,
        ];

        foreach ($plans as $plan) {
            $planId = $plan['IdKeHoachSanXuatXuong'];
            $workshopId = $plan['IdXuong'];
            $configurationId = $plan['AssignmentConfigurationId'] ?? $plan['IdCauHinh'] ?? null;
            $planMaterials = [];
            $hasShortage = false;

            if ($configurationId && isset($materialsByComponent[$configurationId])) {
                foreach ($materialsByComponent[$configurationId] as $material) {
                    $materialId = $material['id'];
                    $ratio = is_numeric($material['quantity_per_unit'] ?? null) ? (float) $material['quantity_per_unit'] : 1.0;
                    $required = (int) round(((int) ($plan['SoLuong'] ?? 0)) * $ratio);
                    $stock = (int) ($inventory[$materialId]['SoLuong'] ?? 0);
                    $deficit = max(0, $required - $stock);

                    $planMaterials[] = [
                        'id' => $materialId,
                        'label' => $material['label'] ?? $materialId,
                        'unit' => $material['unit'] ?? null,
                        'required' => $required,
                        'stock' => $stock,
                        'deficit' => $deficit,
                    ];

                    if ($deficit > 0) {
                        $hasShortage = true;
                    }
                }
            }

            $metrics['total_plans']++;
            $metrics['total_materials'] += count($planMaterials);
            if ($hasShortage) {
                $metrics['shortage_plans']++;
            }

            $status = $plan['TinhTrangVatTu'] ?? null;
            if (!$status) {
                $status = $hasShortage ? 'Thiếu vật tư' : (empty($planMaterials) ? 'Không yêu cầu vật tư' : 'Đủ vật tư');
            }

            if (!isset($grouped[$workshopId])) {
                $grouped[$workshopId] = [
                    'info' => [
                        'IdXuong' => $workshopId,
                        'TenXuong' => $plan['TenXuong'] ?? 'Không xác định',
                    ],
                    'plans' => [],
                ];
            }

            $grouped[$workshopId]['plans'][] = [
                'data' => $plan,
                'materials' => $planMaterials,
                'material_status' => $status,
                'has_shortage' => $hasShortage,
            ];
        }

        $this->render('workshop/dashboard', [
            'title' => 'Dashboard cấp phát vật tư xưởng',
            'workshops' => $workshops,
            'selectedWorkshop' => $selectedWorkshop,
            'groupedPlans' => $grouped,
            'metrics' => $metrics,
        ]);
    }

    public function confirmPlan(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=workshop&action=dashboard');
        }

        $planId = $_POST['IdKeHoachSanXuatXuong'] ?? null;
        $redirectWorkshop = $_POST['redirect_workshop'] ?? null;
        $quantity = isset($_POST['SoLuong']) ? (int) $_POST['SoLuong'] : null;
        $materialStatus = trim($_POST['TinhTrangVatTu'] ?? '');
        $progressStatus = trim($_POST['TrangThai'] ?? '');

        if (!$planId) {
            $this->setFlash('danger', 'Không xác định được kế hoạch xưởng cần cập nhật.');
            $this->redirect($this->buildDashboardRedirect($redirectWorkshop));
        }

        $payload = [];

        if ($quantity !== null) {
            if ($quantity <= 0) {
                $this->setFlash('danger', 'Số lượng cần lớn hơn 0.');
                $this->redirect($this->buildDashboardRedirect($redirectWorkshop));
            }
            $payload['SoLuong'] = $quantity;
        }

        if ($materialStatus !== '') {
            $payload['TinhTrangVatTu'] = $materialStatus;
        }

        if ($progressStatus !== '') {
            $payload['TrangThai'] = $progressStatus;
        }

        if (empty($payload)) {
            $this->setFlash('info', 'Không có dữ liệu nào được cập nhật.');
            $this->redirect($this->buildDashboardRedirect($redirectWorkshop));
        }

        try {
            $this->workshopPlanModel->update($planId, $payload);
            $this->setFlash('success', 'Đã cập nhật kế hoạch xưởng.');
        } catch (Throwable $exception) {
            Logger::error('Lỗi khi cập nhật kế hoạch xưởng ' . $planId . ': ' . $exception->getMessage());
            /* $this->setFlash('danger', 'Không thể cập nhật kế hoạch: ' . $exception->getMessage()); */
            $this->setFlash('danger', 'Không thể cập nhật kế hoạch, vui lòng kiểm tra log để biết thêm chi tiết.');
        }

        $this->redirect($this->buildDashboardRedirect($redirectWorkshop));
    }

    private function buildDashboardRedirect(?string $workshopId): string
    {
        $params = ['controller' => 'workshop', 'action' => 'dashboard'];
        if ($workshopId) {
            $params['workshop'] = $workshopId;
        }

        return '?' . http_build_query($params);
    }

    private function canAssign(): bool
    {
        $user = $this->currentUser();
        $role = $user ? $this->resolveAccessRole($user) : null;

        return in_array($role, ['VT_BAN_GIAM_DOC', 'VT_ADMIN'], true);
    }

    private function canAssignStaff(string $workshopId): bool
    {
        if ($this->canAssign()) {
            return true;
        }

        $user = $this->currentUser();
        $role = $user ? $this->resolveAccessRole($user) : null;
        if ($role !== 'VT_QUANLY_XUONG') {
            return false;
        }

        return $this->canViewWorkshop($workshopId);
    }

    private function isWorkshopManager(): bool
    {
        $user = $this->currentUser();
        $role = $user ? $this->resolveAccessRole($user) : null;

        return $role === 'VT_QUANLY_XUONG' && !$this->canAssign();
    }

    private function canViewWorkshop(string $workshopId): bool
    {
        $user = $this->currentUser();
        if (!$user) {
            return false;
        }

        $role = $this->resolveAccessRole($user);
        if (in_array($role, ['VT_BAN_GIAM_DOC', 'VT_ADMIN'], true)) {
            return true;
        }

        if ($role === 'VT_QUANLY_XUONG') {
            return true;
        }

        return false;
    }

    private function canManageWorkshop(string $workshopId): bool
    {
        return $this->canAssign() || $this->canViewWorkshop($workshopId);
    }

    private function getVisibleWorkshops(): array
    {
        $user = $this->currentUser();
        $role = $user ? $this->resolveAccessRole($user) : null;

        if (in_array($role, ['VT_BAN_GIAM_DOC', 'VT_ADMIN'], true)) {
            return $this->workshopModel->getAllWithManagers();
        }

        if ($role === 'VT_QUANLY_XUONG') {
            $employeeId = $user['IdNhanVien'] ?? null;
            if (!$employeeId) {
                return [];
            }

            return $this->workshopModel->getByManager($employeeId);
        }

        return [];
    }

    private function normalizeSelectedWorkshop(?string $selectedWorkshop, array $visibleWorkshops): ?string
    {
        if ($selectedWorkshop === null || $selectedWorkshop === '') {
            return null;
        }

        foreach ($visibleWorkshops as $workshop) {
            if (($workshop['IdXuong'] ?? null) === $selectedWorkshop) {
                return $selectedWorkshop;
            }
        }

        return null;
    }

    private function filterPlansByVisibleWorkshops(array $plans, array $visibleWorkshops): array
    {
        $allowedIds = array_column($visibleWorkshops, 'IdXuong');
        if (empty($allowedIds)) {
            return [];
        }

        return array_values(array_filter($plans, static function (array $plan) use ($allowedIds): bool {
            return in_array($plan['IdXuong'] ?? null, $allowedIds, true);
        }));
    }

    private function filterMaterialPlans(array $plans): array
    {
        return array_values(array_filter($plans, function (array $plan): bool {
            $config = $this->getWorkshopTypeConfig($plan['LoaiXuong'] ?? $this->getDefaultWorkshopType());
            return $config['supports_materials'] ?? true;
        }));
    }

    private function calculateSummary(array $workshops): array
    {
        $summary = [
            'total_workshops' => 0,
            'max_capacity' => 0.0,
            'current_capacity' => 0.0,
            'workforce' => 0,
            'max_workforce' => 0,
            'utilization' => 0.0,
            'workforce_utilization' => 0.0,
        ];

        foreach ($workshops as $row) {
            $summary['total_workshops']++;
            $summary['max_capacity'] += (float) ($row['CongSuatToiDa'] ?? 0);
            $summary['current_capacity'] += (float) ($row['CongSuatDangSuDung'] ?? $row['CongSuatHienTai'] ?? 0);
            $summary['workforce'] += (int) ($row['staff_current'] ?? $row['SoLuongCongNhan'] ?? 0);
            $summary['max_workforce'] += (int) ($row['staff_max'] ?? $row['SlNhanVien'] ?? 0);
        }

        if ($summary['max_capacity'] > 0) {
            $summary['utilization'] = round(($summary['current_capacity'] / $summary['max_capacity']) * 100, 2);
        }

        if ($summary['max_workforce'] > 0) {
            $summary['workforce_utilization'] = round(($summary['workforce'] / $summary['max_workforce']) * 100, 2);
        }

        return $summary;
    }

    private function buildManagerOverview(array $workshops): ?array
    {
        if (empty($workshops)) {
            return null;
        }

        $workshop = $workshops[0];
        $capacityMax = (float) ($workshop['CongSuatToiDa'] ?? 0);
        $capacityCurrent = (float) ($workshop['CongSuatDangSuDung'] ?? $workshop['CongSuatHienTai'] ?? 0);
        $capacityRate = $capacityMax > 0 ? round(($capacityCurrent / $capacityMax) * 100, 1) : null;

        return [
            'name' => $workshop['TenXuong'] ?? ($workshop['IdXuong'] ?? 'Xưởng của bạn'),
            'status' => $workshop['TrangThai'] ?? 'Không xác định',
            'staff_current' => (int) ($workshop['staff_current'] ?? $workshop['SoLuongCongNhan'] ?? 0),
            'staff_max' => (int) ($workshop['staff_max'] ?? $workshop['SlNhanVien'] ?? 0),
            'capacity_rate' => $capacityRate,
        ];
    }

    private function calculateStatusDistribution(array $workshops): array
    {
        $distribution = [];
        foreach ($workshops as $workshop) {
            $status = $workshop['TrangThai'] ?? 'Không xác định';
            $distribution[$status] = ($distribution[$status] ?? 0) + 1;
        }

        return $distribution;
    }

    private function buildExecutiveOverview(array $summary, array $statusDistribution): array
    {
        $active = $statusDistribution['Đang hoạt động'] ?? 0;
        $paused = $statusDistribution['Tạm dừng'] ?? 0;
        $maintenance = $statusDistribution['Bảo trì'] ?? 0;
        $others = array_sum($statusDistribution) - ($active + $paused + $maintenance);

        $total = max(1, $summary['total_workshops']);
        $avgCapacity = $summary['max_capacity'] > 0 ? round($summary['max_capacity'] / $total, 1) : 0.0;
        $avgHeadcount = $summary['max_workforce'] > 0 ? round($summary['max_workforce'] / $total, 1) : 0.0;

        return [
            'active' => $active,
            'paused' => $paused,
            'maintenance' => $maintenance,
            'others' => $others > 0 ? $others : 0,
            'utilization' => $summary['utilization'],
            'workforce_utilization' => $summary['workforce_utilization'],
            'avg_capacity' => $avgCapacity,
            'avg_headcount' => $avgHeadcount,
        ];
    }

    private function extractAssignments(array $input): array
    {
        $warehouse = $input['warehouse_staff'] ?? [];
        $production = $input['production_staff'] ?? [];

        return [
            'warehouse' => is_array($warehouse) ? array_filter(array_map('trim', $warehouse)) : [],
            'production' => is_array($production) ? array_filter(array_map('trim', $production)) : [],
        ];
    }

    private function groupEmployeesByRole(array $employees): array
    {
        return $this->groupEmployeesForWorkshop($employees, $this->getDefaultWorkshopType(), []);
    }

    private function groupEmployeesForWorkshop(array $employees, string $workshopType, array $includeIds): array
    {
        $assignedIds = $this->assignmentModel->getAssignedEmployeeIds();
        $assignedLookup = array_fill_keys($assignedIds, true);
        $includeLookup = array_fill_keys($includeIds, true);
        $config = $this->getWorkshopTypeConfig($workshopType);

        $groups = [
            'warehouse' => [],
            'production' => [],
        ];

        $useQuality = $config['use_quality'] ?? false;
        $includeWarehouse = $config['allow_warehouse'] ?? true;
        $includeProduction = $config['allow_production'] ?? true;

        foreach ($employees as $employee) {
            $employeeId = $employee['IdNhanVien'] ?? null;
            $title = mb_strtolower($employee['ChucVu'] ?? '');
            $isWarehouse = str_contains($title, 'kho') || str_contains($title, 'logistics');
            $isProduction = str_contains($title, 'sản xuất')
                || str_contains($title, 'lắp ráp')
                || str_contains($title, 'vận hành');
            $isQuality = str_contains($title, 'kiểm soát')
                || str_contains($title, 'kiểm định')
                || str_contains($title, 'qa')
                || str_contains($title, 'qc');

            if ($includeWarehouse && $isWarehouse) {
                $groups['warehouse'][] = $employee;
            }

            if ($includeProduction && ($useQuality ? $isQuality : $isProduction)) {
                if ($employeeId && isset($assignedLookup[$employeeId]) && !isset($includeLookup[$employeeId])) {
                    continue;
                }
                $groups['production'][] = $employee;
            }
        }

        return $groups;
    }

    private function getWorkshopTypes(): array
    {
        return [
            'Xưởng kiểm định',
            'Xưởng lắp ráp và đóng gói',
            'Xưởng sản xuất',
            'Xưởng lưu trữ hàng hóa',
        ];
    }

    private function getDefaultWorkshopType(): string
    {
        return 'Xưởng sản xuất';
    }

    private function getManagerCandidates(?array $workshop = null, ?string $workshopType = null): array
    {
        $workshopType = $workshopType ?? ($workshop['LoaiXuong'] ?? $this->getDefaultWorkshopType());
        $config = $this->getWorkshopTypeConfig($workshopType);
        $roleIds = $config['manager_roles'] ?? [];
        $managers = $roleIds
            ? $this->employeeModel->getEmployeesByRoleIds($roleIds, 'Đang làm việc')
            : [];
        if (empty($managers)) {
            $managers = $this->employeeModel->getActiveEmployees();
        }

        $assignedManagers = $this->workshopModel->getAssignedManagerIds();
        $assignedLookup = array_fill_keys($assignedManagers, true);
        $selectedId = $workshop['XUONGTRUONG_IdNhanVien'] ?? null;
        if ($selectedId) {
            $exists = false;
            foreach ($managers as $manager) {
                if (($manager['IdNhanVien'] ?? null) === $selectedId) {
                    $exists = true;
                    break;
                }
            }

            if (!$exists) {
                $current = $this->employeeModel->find($selectedId);
                if ($current) {
                    $managers[] = $current;
                }
            }
        }

        return array_values(array_filter($managers, function (array $manager) use ($assignedLookup, $selectedId): bool {
            $managerId = $manager['IdNhanVien'] ?? null;
            if (!$managerId) {
                return false;
            }
            if ($selectedId && $managerId === $selectedId) {
                return true;
            }
            return !isset($assignedLookup[$managerId]);
        }));
    }

    private function extractWorkshopData(array $input): array
    {
        return [
            'IdXuong' => trim($input['IdXuong'] ?? ''),
            'TenXuong' => trim($input['TenXuong'] ?? ''),
            'DiaDiem' => trim($input['DiaDiem'] ?? ''),
            'LoaiXuong' => trim($input['LoaiXuong'] ?? $this->getDefaultWorkshopType()),
            'XUONGTRUONG_IdNhanVien' => trim($input['XUONGTRUONG_IdNhanVien'] ?? ''),
            'SlNhanVien' => (int) ($input['SlNhanVien'] ?? 0),
            'SoLuongCongNhan' => 0,
            'CongSuatToiDa' => (float) ($input['CongSuatToiDa'] ?? 0),
            'CongSuatDangSuDung' => (float) ($input['CongSuatDangSuDung'] ?? 0),
            'NgayThanhLap' => $input['NgayThanhLap'] ?? null,
            'TrangThai' => $input['TrangThai'] ?? 'Đang hoạt động',
            'MoTa' => trim($input['MoTa'] ?? ''),
        ];
    }

    private function attachStaffCounts(array $workshops): array
    {
        foreach ($workshops as &$workshop) {
            $assignments = $this->assignmentModel->getAssignmentsByWorkshop($workshop['IdXuong']);
            $derivedCount = count($assignments['nhan_vien_kho'] ?? []) + count($assignments['nhan_vien_san_xuat'] ?? []);

            $current = $derivedCount;

            $max = (int) ($workshop['SlNhanVien'] ?? 0);
            if ($max <= 0) {
                $max = max($current, $derivedCount);
            }

            $workshop['staff_current'] = $current;
            $workshop['staff_max'] = $max;
        }

        return $workshops;
    }

    private function buildStaffList(array $assignments, ?string $workshopType = null): array
    {
        $list = [];
        $config = $this->getWorkshopTypeConfig($workshopType ?? $this->getDefaultWorkshopType());
        $productionLabel = $config['production_label'] ?? 'Sản xuất';

        foreach ($assignments['nhan_vien_kho'] ?? [] as $row) {
            $list[] = [
                'id' => $row['IdNhanVien'] ?? '',
                'name' => $row['HoTen'] ?? '',
                'role' => 'Kho',
            ];
        }

        foreach ($assignments['nhan_vien_san_xuat'] ?? [] as $row) {
            $list[] = [
                'id' => $row['IdNhanVien'] ?? '',
                'name' => $row['HoTen'] ?? '',
                'role' => $productionLabel,
            ];
        }

        usort($list, fn ($a, $b) => strcmp($a['name'], $b['name']));

        return $list;
    }

    private function getWorkshopTypeConfig(string $workshopType): array
    {
        $normalized = mb_strtolower(trim($workshopType));

        if ($normalized === 'xưởng kiểm định') {
            return [
                'use_quality' => true,
                'allow_warehouse' => true,
                'allow_production' => true,
                'manager_roles' => ['VT_KIEM_SOAT_CL'],
                'production_label' => 'Kiểm soát chất lượng',
                'supports_materials' => false,
                'supports_progress' => false,
            ];
        }

        if ($normalized === 'xưởng lưu trữ hàng hóa') {
            return [
                'use_quality' => false,
                'allow_warehouse' => true,
                'allow_production' => false,
                'manager_roles' => ['VT_NHANVIEN_KHO'],
                'production_label' => 'Sản xuất',
                'supports_materials' => false,
                'supports_progress' => false,
            ];
        }

        if ($normalized === 'xưởng lắp ráp và đóng gói') {
            return [
                'use_quality' => false,
                'allow_warehouse' => true,
                'allow_production' => true,
                'manager_roles' => ['VT_NHANVIEN_SANXUAT'],
                'production_label' => 'Sản xuất',
                'supports_materials' => true,
                'supports_progress' => true,
            ];
        }

        return [
            'use_quality' => false,
            'allow_warehouse' => true,
            'allow_production' => true,
            'manager_roles' => ['VT_NHANVIEN_SANXUAT'],
            'production_label' => 'Sản xuất',
            'supports_materials' => true,
            'supports_progress' => true,
        ];
    }

    private function getWorkshopTypeRules(): array
    {
        $rules = [];
        foreach ($this->getWorkshopTypes() as $type) {
            $rules[$type] = $this->getWorkshopTypeConfig($type);
        }

        return $rules;
    }

    private function filterAssignmentsByWorkshopType(array $assignments, string $workshopType): array
    {
        $config = $this->getWorkshopTypeConfig($workshopType);

        if (empty($config['allow_warehouse'])) {
            $assignments['warehouse'] = [];
        }

        if (empty($config['allow_production'])) {
            $assignments['production'] = [];
        }

        return $assignments;
    }

    private function isValidManagerForType(string $managerId, string $workshopType): bool
    {
        $manager = $this->employeeModel->find($managerId);
        if (!$manager) {
            return false;
        }

        $config = $this->getWorkshopTypeConfig($workshopType);
        $allowedRoles = $config['manager_roles'] ?? [];
        if (empty($allowedRoles)) {
            return true;
        }

        return in_array($manager['IdVaiTro'] ?? null, $allowedRoles, true);
    }
}
