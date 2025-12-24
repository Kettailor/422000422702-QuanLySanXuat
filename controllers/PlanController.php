<?php

class PlanController extends Controller
{
    private ProductionPlan $planModel;
    private OrderDetail $orderDetailModel;
    private WorkshopPlan $workshopPlanModel;
    private ProductComponent $componentModel;
    private Workshop $workshopModel;
    private  WorkshopPlanMaterialDetail $workShopPlanDetail;
    private Order $orderModel;
    private Employee $employeeModel;
    private User $userModel;
    private ProductComponentMaterial $componentMaterialModel;
    private Material $materialModel;

    public function __construct()
    {
        $this->authorize(['VT_BAN_GIAM_DOC', 'VT_ADMIN']);
        $this->planModel = new ProductionPlan();
        $this->orderDetailModel = new OrderDetail();
        $this->workshopPlanModel = new WorkshopPlan();
        $this->componentModel = new ProductComponent();
        $this->workshopModel = new Workshop();
        $this->workShopPlanDetail = new WorkshopPlanMaterialDetail();
        $this->orderModel = new Order();
        $this->employeeModel = new Employee();
        $this->userModel = new User();
        $this->componentMaterialModel = new ProductComponentMaterial();
        $this->materialModel = new Material();
    }

    public function index(): void
    {
        $role = $this->currentRole();
        $plans = $this->planModel->getPlansWithOrders();
        $pendingDetails = $this->orderDetailModel->getPendingForPlanning();
        $pendingOrders = $this->groupPendingOrders($pendingDetails);

        $stats = [
            'total_plans' => count($plans),
            'pending_orders' => count($pendingOrders),
            'pending_details' => count($pendingDetails),
            'workshop_tasks' => $this->countWorkshopAssignments($plans),
        ];

        $this->render('plan/index', [
            'title' => 'Kế hoạch sản xuất',
            'plans' => $plans,
            'pendingOrders' => $pendingOrders,
            'stats' => $stats,
            'canManagePlan' => $this->canManagePlans($role),
        ]);
    }

    public function create(): void
    {
        if (!$this->canManagePlans($this->currentRole())) {
            $this->setFlash('danger', 'Bạn chỉ có thể xem kế hoạch sản xuất.');
            $this->redirect('?controller=plan&action=index');
            return;
        }

        $pendingDetails = $this->orderDetailModel->getPendingForPlanning();
        $pendingOrders = $this->groupPendingOrders($pendingDetails);

        $selectedOrderDetailId = $_GET['order_detail_id'] ?? null;
        $selectedOrderDetail = null;
        $componentAssignments = [];

        if ($selectedOrderDetailId) {
            $selectedOrderDetail = $this->orderDetailModel->getPlanningContext($selectedOrderDetailId);
            if ($selectedOrderDetail) {
                $componentAssignments = $this->buildComponentAssignments($selectedOrderDetail);
            }
        }

        $currentUser = $this->resolvePlannerUser($this->currentUser());

        $this->render('plan/create', [
            'title' => 'Lập kế hoạch sản xuất',
            'pendingOrders' => $pendingOrders,
            'selectedOrderDetailId' => $selectedOrderDetailId,
            'selectedOrderDetail' => $selectedOrderDetail,
            'componentAssignments' => $componentAssignments,
            'configurationDetails' => $selectedOrderDetail ? $this->buildConfigurationDetails($selectedOrderDetail) : [],
            'materialOverview' => $selectedOrderDetail ? $this->buildMaterialOverview($selectedOrderDetail) : [],
            'workshops' => $this->workshopModel->getAllWithManagers(),
            'currentUser' => $currentUser,
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=plan&action=index');
            return;
        }

        if (!$this->canManagePlans($this->currentRole())) {
            $this->setFlash('danger', 'Bạn không có quyền lập kế hoạch sản xuất.');
            $this->redirect('?controller=plan&action=index');
            return;
        }

        $orderDetailId = $_POST['IdTTCTDonHang'] ?? null;
        $orderDetail = $orderDetailId ? $this->orderDetailModel->getPlanningContext($orderDetailId) : null;

        if (!$orderDetail) {
            $this->setFlash('danger', 'Vui lòng chọn đơn hàng hợp lệ trước khi lập kế hoạch.');
            $this->redirect('?controller=plan&action=create');
            return;
        }

        $currentUser = $this->resolvePlannerUser($this->currentUser());
        $actorId = $currentUser['IdNhanVien'] ?? ($_POST['BanGiamDoc'] ?? null);

        if (!$actorId) {
            $this->setFlash('danger', 'Không xác định được người lập kế hoạch. Vui lòng đăng nhập lại.');
            $this->redirect('?controller=plan&action=create&order_detail_id=' . urlencode($orderDetailId));
            return;
        }

        $planIdInput = $_POST['IdKeHoachSanXuat'] ?? null;
        $planId = $planIdInput ?: uniqid('KH');
        $quantity = (int) ($_POST['SoLuong'] ?? $orderDetail['SoLuong'] ?? 0);
        if ($quantity <= 0) {
            $quantity = (int) ($orderDetail['SoLuong'] ?? 0);
        }

        $startTime = $this->normalizeDateTimeInput($_POST['ThoiGianBD'] ?? null);
        $endTime = $this->normalizeDateTimeInput($_POST['ThoiGianKetThuc'] ?? null);
        $status = 'Đang chuẩn bị';

        if (!$this->validatePlanDates($startTime, $endTime)) {
            $this->setFlash('danger', 'Ngày bắt đầu không được bé hơn ngày hiện tại và hạn chót phải lớn hơn hoặc bằng ngày bắt đầu.');
            $this->redirect('?controller=plan&action=create&order_detail_id=' . urlencode($orderDetailId));
            return;
        }

        $planData = [
            'IdKeHoachSanXuat' => $planId,
            'IdTTCTDonHang' => $orderDetailId,
            'SoLuong' => $quantity,
            'ThoiGianBD' => $startTime,
            'ThoiGianKetThuc' => $endTime,
            'TrangThai' => $status,
            '`IdNguoiLap`' => $actorId,
        ];

        $assignmentsInput = $_POST['component_assignments'] ?? [];
        $assignments = $this->extractAssignments($assignmentsInput, [
            'plan_id' => $planId,
            'quantity' => $quantity,
            'start' => $startTime,
        ]);

        if (!$this->validateAssignmentDates($assignments)) {
            $this->setFlash('danger', 'Ngày bắt đầu/hạn chót của hạng mục không hợp lệ. Ngày bắt đầu không được bé hơn ngày hiện tại và hạn chót phải lớn hơn hoặc bằng ngày bắt đầu.');
            $this->redirect('?controller=plan&action=create&order_detail_id=' . urlencode($orderDetailId));
            return;
        }

        if (empty($assignments)) {
            $this->setFlash('warning', 'Vui lòng phân công ít nhất một xưởng phụ trách cho sản phẩm.');
            $this->redirect('?controller=plan&action=create&order_detail_id=' . urlencode($orderDetailId));
            return;
        }

        try {
            $this->planModel->create($planData);
            foreach ($assignments as $assignment) {
                $this->workshopPlanModel->create($assignment);
                if (isset($assignment['IdKeHoachSanXuatXuong'])) {
                    try {
                        $this->workShopPlanDetail->createWorkshopPlanDetail(
                            $assignment['IdKeHoachSanXuatXuong'],
                            $orderDetail['IdCauHinh'],
                            $assignment['SoLuong']
                        );
                    } catch (Throwable $exception) {
                        Logger::warn(
                            'Không tìm thấy cấu hình nguyên liệu cho mã: ' . $orderDetail['IdCauHinh']
                        );
                    }
                }
            }

            $orderId = $orderDetail['IdDonHang'] ?? null;
            if ($orderId) {
                $this->orderModel->update($orderId, ['TrangThai' => 'Đang xử lý']);
            }

            $this->notifyWorkshopManagers($assignments);

            $this->setFlash('success', 'Đã lập kế hoạch sản xuất và giao nhiệm vụ cho các xưởng.');
        } catch (PDOException $exception) {
            Logger::error('Lỗi khi tạo kế hoạch sản xuất: ' . $exception->getMessage());
            $this->setFlash('danger', $this->resolveDateRuleMessage($exception->getMessage()));
            $this->redirect('?controller=plan&action=create&order_detail_id=' . urlencode($orderDetailId));
            return;
        } catch (Throwable $exception) {
            Logger::error('Lỗi khi tạo kế hoạch sản xuất: ' . $exception->getMessage());
            /* $this->setFlash('danger', 'Không thể tạo kế hoạch: ' . $exception->getMessage()); */
            $this->setFlash('danger', 'Không thể tạo kế hoạch, vui lòng kiểm tra log để biết thêm chi tiết.');
            $this->redirect('?controller=plan&action=create&order_detail_id=' . urlencode($orderDetailId));
            return;
        }

        $this->redirect('?controller=plan&action=read&id=' . urlencode($planId));
    }

    public function read(): void
    {
        $id = $_GET['id'] ?? null;
        $plan = $id ? $this->planModel->getPlanWithRelations($id) : null;
        $workshopPlans = $plan ? $this->workshopPlanModel->getByPlan($plan['IdKeHoachSanXuat']) : [];

        $this->render('plan/read', [
            'title' => 'Chi tiết kế hoạch sản xuất',
            'plan' => $plan,
            'workshopPlans' => $workshopPlans,
        ]);
    }

    public function updateDeadline(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=plan&action=index');
            return;
        }

        if (!$this->canManagePlans($this->currentRole())) {
            $this->setFlash('danger', 'Bạn không có quyền chỉnh sửa kế hoạch sản xuất.');
            $this->redirect('?controller=plan&action=index');
            return;
        }

        $id = $_POST['IdKeHoachSanXuat'] ?? null;
        $plan = $id ? $this->planModel->getPlanWithRelations($id) : null;
        if (!$plan) {
            $this->setFlash('warning', 'Không tìm thấy kế hoạch sản xuất.');
            $this->redirect('?controller=plan&action=index');
            return;
        }

        $currentStatus = $plan['TrangThai'] ?? '';
        if (in_array($currentStatus, ['Hoàn thành', 'Hủy'], true)) {
            $this->setFlash('danger', 'Không thể chỉnh sửa hạn chót khi kế hoạch đã hoàn tất hoặc bị hủy.');
            $this->redirect('?controller=plan&action=read&id=' . urlencode($id));
            return;
        }

        $endTime = $this->normalizeDateTimeInput($_POST['ThoiGianKetThuc'] ?? null);
        if (!$endTime) {
            $this->setFlash('danger', 'Vui lòng nhập hạn chót hợp lệ.');
            $this->redirect('?controller=plan&action=read&id=' . urlencode($id));
            return;
        }

        $startTime = $plan['ThoiGianBD'] ?? null;
        if ($startTime && strtotime($endTime) < strtotime($startTime)) {
            $this->setFlash('danger', 'Hạn chót phải lớn hơn hoặc bằng thời gian bắt đầu.');
            $this->redirect('?controller=plan&action=read&id=' . urlencode($id));
            return;
        }

        try {
            $this->planModel->update($id, ['ThoiGianKetThuc' => $endTime]);
            $this->workshopPlanModel->updateEndTimeByPlan($id, $endTime);
            $this->setFlash('success', 'Đã cập nhật hạn chót kế hoạch.');
        } catch (Throwable $exception) {
            Logger::error('Lỗi khi cập nhật hạn chót kế hoạch ' . $id . ': ' . $exception->getMessage());
            $this->setFlash('danger', 'Không thể cập nhật hạn chót, vui lòng kiểm tra log.');
        }

        $this->redirect('?controller=plan&action=read&id=' . urlencode($id));
    }

    public function cancel(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=plan&action=index');
            return;
        }

        if (!$this->canManagePlans($this->currentRole())) {
            $this->setFlash('danger', 'Bạn không có quyền hủy kế hoạch sản xuất.');
            $this->redirect('?controller=plan&action=index');
            return;
        }

        $id = $_POST['IdKeHoachSanXuat'] ?? null;
        $plan = $id ? $this->planModel->getPlanWithRelations($id) : null;
        if (!$plan) {
            $this->setFlash('warning', 'Không tìm thấy kế hoạch sản xuất.');
            $this->redirect('?controller=plan&action=index');
            return;
        }

        $currentStatus = $plan['TrangThai'] ?? '';
        if ($currentStatus === 'Hủy') {
            $this->setFlash('warning', 'Kế hoạch đã được hủy trước đó.');
            $this->redirect('?controller=plan&action=read&id=' . urlencode($id));
            return;
        }
        $allowedStatuses = ['Đang chuẩn bị', 'Đang sản xuất'];
        if (!in_array($currentStatus, $allowedStatuses, true)) {
            $message = $currentStatus === 'Hoàn thành'
                ? 'Không thể hủy kế hoạch đã hoàn thành.'
                : 'Chỉ được hủy kế hoạch khi đang chuẩn bị hoặc đang sản xuất.';
            $this->setFlash('danger', $message);
            $this->redirect('?controller=plan&action=read&id=' . urlencode($id));
            return;
        }

        $note = trim($_POST['cancel_note'] ?? '');
        if ($note === '') {
            $this->setFlash('danger', 'Vui lòng nhập ghi chú khi hủy kế hoạch.');
            $this->redirect('?controller=plan&action=read&id=' . urlencode($id));
            return;
        }

        try {
            $this->planModel->update($id, ['TrangThai' => 'Hủy', 'GhiChu' => $note]);
            $this->workshopPlanModel->updateStatusByPlan($id, 'Hủy', $note);
            $this->setFlash('success', 'Đã hủy kế hoạch sản xuất.');
        } catch (Throwable $exception) {
            Logger::error('Lỗi khi hủy kế hoạch sản xuất ' . $id . ': ' . $exception->getMessage());
            $this->setFlash('danger', 'Không thể hủy kế hoạch, vui lòng kiểm tra log.');
        }

        $this->redirect('?controller=plan&action=read&id=' . urlencode($id));
    }

    public function delete(): void
    {
        if (!$this->canManagePlans($this->currentRole())) {
            $this->setFlash('danger', 'Bạn không có quyền xóa kế hoạch sản xuất.');
            $this->redirect('?controller=plan&action=index');
            return;
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('?controller=plan&action=index');
            return;
        }

        try {
            $this->planModel->delete($id);
            $this->setFlash('success', 'Đã xóa kế hoạch sản xuất.');
        } catch (Throwable $exception) {
            Logger::error('Lỗi khi xóa kế hoạch sản xuất ' . $id . ': ' . $exception->getMessage());
            /* $this->setFlash('danger', 'Không thể xóa kế hoạch: ' . $exception->getMessage()); */
            $this->setFlash('danger', 'Không thể xóa kế hoạch, vui lòng kiểm tra log để biết thêm chi tiết.');
        }

        $this->redirect('?controller=plan&action=index');
    }

    private function groupPendingOrders(array $details): array
    {
        if (empty($details)) {
            return [];
        }

        $grouped = [];
        foreach ($details as $detail) {
            $orderId = $detail['IdDonHang'] ?? 'UNKNOWN';
            if (!isset($grouped[$orderId])) {
                $grouped[$orderId] = [
                    'IdDonHang' => $orderId,
                    'NgayLap' => $detail['NgayLap'] ?? null,
                    'TenKhachHang' => $detail['TenKhachHang'] ?? null,
                    'TenCongTy' => $detail['TenCongTy'] ?? null,
                    'SoDienThoai' => $detail['SoDienThoai'] ?? null,
                    'EmailLienHe' => $detail['EmailLienHe'] ?? null,
                    'details' => [],
                ];
            }
            $grouped[$orderId]['details'][] = $detail;
        }

        return array_values($grouped);
    }

    private function buildComponentAssignments(array $orderDetail): array
    {
        $quantity = (int) ($orderDetail['SoLuong'] ?? 0);
        $productId = $orderDetail['IdSanPham'] ?? null;
        $configurationId = $orderDetail['IdCauHinh'] ?? null;

        $components = $this->componentModel->getComponentsForProductConfiguration($productId, $configurationId);
        $assignments = [];
        $configurationDetails = $this->buildConfigurationDetails($orderDetail);

        foreach ($components as $component) {
            $ratioRaw = $component['TyLeSoLuong'] ?? $component['quantity_per_unit'] ?? 1;
            $ratio = is_numeric($ratioRaw) ? (float) $ratioRaw : 1.0;
            if ($ratio <= 0) {
                $ratio = 1.0;
            }

            $componentQuantity = (int) round(max(1, $quantity) * $ratio);
            $assignments[] = [
                'id' => $component['IdCongDoan'] ?? null,
                'configuration_id' => $component['IdCauHinh'] ?? null,
                'label' => $component['TenCongDoan'] ?? $component['TenPhanCong'] ?? 'Hạng mục sản xuất',
                'category' => $component['LoaiCongDoan'] ?? null,
                'default_quantity' => max(1, $componentQuantity),
                'quantity_ratio' => $ratio,
                'default_workshop' => $component['IdXuong'] ?? null,
                'configuration_label' => $component['TenCauHinh'] ?? null,
                'unit' => $component['DonVi'] ?? 'sp',
                'default_status' => $component['TrangThaiMacDinh'] ?? null,
                'allowed_workshop_types' => $this->resolveAllowedWorkshopTypes(
                    $component['TenCongDoan'] ?? $component['TenPhanCong'] ?? '',
                    $component['LoaiCongDoan'] ?? null,
                    null
                ),
            ];
        }

        if (empty($assignments)) {
            $assignments[] = [
                'id' => null,
                'configuration_id' => $configurationId,
                'label' => 'Gia công sản phẩm',
                'category' => 'production',
                'default_quantity' => max(1, $quantity),
                'quantity_ratio' => 1.0,
                'default_workshop' => null,
                'configuration_label' => $orderDetail['TenCauHinh'] ?? null,
                'unit' => $orderDetail['DonVi'] ?? 'sp',
                'default_status' => 'Đang chuẩn bị',
                'configuration_details' => $configurationDetails,
                'detail_key' => null,
                'detail_value' => null,
                'allowed_workshop_types' => $this->resolveAllowedWorkshopTypes('Gia công sản phẩm', 'production', null),
            ];
        }

        $assignments = $this->appendConfigurationDetailAssignments($assignments, $configurationDetails, max(1, $quantity), $orderDetail);
        return $this->ensureInspectionAssignment($assignments, max(1, $quantity), $orderDetail);
    }

    private function buildMaterialOverview(array $orderDetail): array
    {
        $configurationId = $orderDetail['IdCauHinh'] ?? null;
        if (!$configurationId) {
            return [];
        }

        $materials = $this->componentMaterialModel->getMaterialsForComponent($configurationId);
        if (empty($materials)) {
            return [];
        }

        $quantity = (int) ($orderDetail['SoLuong'] ?? 0);
        $quantity = max(1, $quantity);

        $materialIds = array_values(array_filter(array_unique(array_column($materials, 'id'))));
        $inventory = $this->materialModel->findMany($materialIds);

        $overview = [];
        foreach ($materials as $material) {
            $materialId = $material['id'] ?? null;
            if (!$materialId) {
                continue;
            }

            $ratioRaw = $material['quantity_per_unit'] ?? $material['standard_quantity'] ?? 1;
            $ratio = is_numeric($ratioRaw) ? (float) $ratioRaw : 1.0;
            if ($ratio <= 0) {
                $ratio = 1.0;
            }

            $required = (int) ceil($quantity * $ratio);
            $stockRow = $inventory[$materialId] ?? [];
            $available = (int) ($stockRow['SoLuong'] ?? 0);
            $overview[] = [
                'id' => $materialId,
                'name' => $stockRow['TenNL'] ?? null,
                'label' => $material['label'] ?? null,
                'unit' => $material['unit'] ?? ($stockRow['DonVi'] ?? null),
                'required' => $required,
                'available' => $available,
                'shortage' => max(0, $required - $available),
            ];
        }

        return $overview;
    }

    private function buildConfigurationDetails(array $orderDetail): array
    {
        $mapping = [
            'Keycap' => 'Keycap',
            'Mainboard' => 'Mainboard',
            'Layout' => 'Layout',
            'SwitchType' => 'Switch',
            'CaseType' => 'Case',
            'Foam' => 'Foam',
        ];

        $details = [];

        foreach ($mapping as $field => $label) {
            $value = trim((string) ($orderDetail[$field] ?? ''));
            if ($value === '') {
                continue;
            }

            $details[] = [
                'key' => $field,
                'label' => $label,
                'value' => $value,
            ];
        }

        return $details;
    }

    private function appendConfigurationDetailAssignments(array $assignments, array $configurationDetails, int $quantity, array $orderDetail): array
    {
        if (empty($configurationDetails)) {
            return $assignments;
        }

        $assignmentKeys = ['Keycap', 'Mainboard', 'SwitchType', 'CaseType', 'Foam'];
        $normalizer = static function (string $value): string {
            return function_exists('mb_strtolower') ? mb_strtolower($value) : strtolower($value);
        };

        $existingLabels = array_map(function (array $assignment) use ($normalizer): string {
            return $normalizer((string) ($assignment['label'] ?? ''));
        }, $assignments);

        foreach ($configurationDetails as $detail) {
            if (!in_array($detail['key'] ?? '', $assignmentKeys, true)) {
                continue;
            }

            $displayLabel = sprintf('%s: %s', $detail['label'], $detail['value']);
            if (in_array($normalizer($displayLabel), $existingLabels, true)) {
                continue;
            }

            $assignments[] = [
                'id' => null,
                'configuration_id' => $orderDetail['IdCauHinh'] ?? null,
                'label' => $displayLabel,
                'category' => 'configuration-detail',
                'default_quantity' => $quantity,
                'quantity_ratio' => 1.0,
                'default_workshop' => $orderDetail['IdXuongChinh'] ?? null,
                'configuration_label' => $orderDetail['TenCauHinh'] ?? null,
                'unit' => $orderDetail['DonVi'] ?? 'sp',
                'default_status' => 'Đang chuẩn bị',
                'configuration_details' => $configurationDetails,
                'detail_key' => $detail['key'],
                'detail_value' => $detail['value'],
                'allowed_workshop_types' => $this->resolveAllowedWorkshopTypes($displayLabel, 'configuration-detail', $detail['key'] ?? null),
            ];

            $existingLabels[] = $normalizer($displayLabel);
        }

        return $assignments;
    }

    private function ensureInspectionAssignment(array $assignments, int $quantity, array $orderDetail): array
    {
        $hasInspection = false;
        foreach ($assignments as $assignment) {
            if (($assignment['category'] ?? null) === 'inspection') {
                $hasInspection = true;
                break;
            }
        }

        if ($hasInspection) {
            return $assignments;
        }

        $assignments[] = [
            'id' => null,
            'configuration_id' => $orderDetail['IdCauHinh'] ?? null,
            'label' => 'Kiểm định sản phẩm',
            'category' => 'inspection',
            'default_quantity' => $quantity,
            'quantity_ratio' => 1.0,
            'default_workshop' => null,
            'configuration_label' => $orderDetail['TenCauHinh'] ?? null,
            'unit' => $orderDetail['DonVi'] ?? 'sp',
            'default_status' => 'Đang chuẩn bị',
            'configuration_details' => $this->buildConfigurationDetails($orderDetail),
            'detail_key' => null,
            'detail_value' => null,
            'allowed_workshop_types' => $this->resolveAllowedWorkshopTypes('Kiểm định sản phẩm', 'inspection', null),
        ];

        return $assignments;
    }

    private function notifyWorkshopManagers(array $assignments): void
    {
        if (empty($assignments)) {
            return;
        }

        $workshopIds = [];
        foreach ($assignments as $assignment) {
            $workshopId = $assignment['IdXuong'] ?? null;
            if ($workshopId) {
                $workshopIds[$workshopId] = true;
            }
        }

        if (empty($workshopIds)) {
            return;
        }

        $managerMap = $this->workshopModel->getManagersByWorkshopIds(array_keys($workshopIds));
        $entries = [];
        foreach ($assignments as $assignment) {
            $workshopId = $assignment['IdXuong'] ?? null;
            if (!$workshopId) {
                continue;
            }
            $managerId = $managerMap[$workshopId] ?? null;
            if (!$managerId) {
                continue;
            }

            $planId = $assignment['IdKeHoachSanXuatXuong'] ?? null;
            if (!$planId) {
                continue;
            }

            $entries[] = [
                'channel' => 'workshop_plan',
                'recipient' => $managerId,
                'title' => 'Có kế hoạch mới cho xưởng',
                'message' => sprintf('Kế hoạch xưởng %s vừa được tạo. Vui lòng kiểm tra và phân công.', $planId),
                'link' => '?controller=workshop_plan&action=read&id=' . urlencode($planId),
                'metadata' => [
                    'workshop_id' => $workshopId,
                    'workshop_plan_id' => $planId,
                ],
            ];
        }

        if (!empty($entries)) {
            $store = new NotificationStore();
            $store->pushMany($entries);
        }
    }

    private function extractAssignments(array $input, array $context): array
    {
        $assignments = [];
        $defaultQuantity = max(1, (int) ($context['quantity'] ?? 1));
        $defaultStart = $context['start'] ?? null;
        $allowedStatuses = ['Đang chuẩn bị', 'Đang sản xuất', 'Chờ nghiệm thu', 'Hoàn thành', 'Đang chờ xác nhận'];

        foreach ($input as $row) {
            if (!is_array($row)) {
                continue;
            }

            $workshopId = trim((string) ($row['workshop_id'] ?? ''));
            $label = trim((string) ($row['label'] ?? ''));
            $configurationId = $row['configuration_id'] ?? null;
            $componentId = $row['component_id'] ?? null;
            if (($componentId === null || $componentId === '') && $configurationId !== null) {
                $componentId = $configurationId;
            }

            if ($componentId !== null) {
                $componentId = trim((string) $componentId);
                if ($componentId === '' || !$this->componentModel->existsById($componentId)) {
                    $componentId = null;
                }
            }

            if ($workshopId === '' || $label === '') {
                continue;
            }

            $quantity = (int) ($row['quantity'] ?? 0);
            if ($quantity <= 0) {
                $quantity = $defaultQuantity;
            }

            $start = $this->normalizeDateTimeInput($row['start'] ?? null) ?? $defaultStart;
            $end = $this->normalizeDateTimeInput($row['end'] ?? null) ?? $this->normalizeDateTimeInput($row['deadline'] ?? null);

            $statusInput = $row['status'] ?? ($row['default_status'] ?? null);
            $status = in_array($statusInput, $allowedStatuses, true) ? $statusInput : 'Đang chuẩn bị';

            $assignments[] = [
                'IdKeHoachSanXuatXuong' => uniqid('KXX'),
                'IdKeHoachSanXuat' => $context['plan_id'],
                'IdXuong' => $workshopId,
                'IdCongDoan' => $componentId,
                'TenThanhThanhPhanSP' => $label,
                'SoLuong' => $quantity,
                'ThoiGianBatDau' => $start,
                'ThoiGianKetThuc' => $end,
                'TrangThai' => $status,
                'TinhTrangVatTu' => 'Chưa kiểm tra',
            ];
        }

        return $assignments;
    }

    private function countWorkshopAssignments(array $plans): int
    {
        $total = 0;
        foreach ($plans as $plan) {
            $total += (int) ($plan['TongCongDoan'] ?? 0);
        }

        return $total;
    }

    private function normalizeDateTimeInput(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        $timestamp = strtotime($value);
        if ($timestamp === false) {
            return null;
        }

        return date('Y-m-d H:i:s', $timestamp);
    }

    private function validatePlanDates(?string $startTime, ?string $endTime): bool
    {
        if (!$startTime || !$endTime) {
            return false;
        }

        $start = new DateTimeImmutable($startTime);
        $end = new DateTimeImmutable($endTime);
        $today = new DateTimeImmutable('today');

        if ($start < $today) {
            return false;
        }

        return $end >= $start;
    }

    private function validateAssignmentDates(array $assignments): bool
    {
        if (empty($assignments)) {
            return false;
        }

        $today = new DateTimeImmutable('today');
        foreach ($assignments as $assignment) {
            $startTime = $assignment['ThoiGianBatDau'] ?? null;
            $endTime = $assignment['ThoiGianKetThuc'] ?? null;
            if (!$startTime || !$endTime) {
                return false;
            }

            $start = new DateTimeImmutable($startTime);
            $end = new DateTimeImmutable($endTime);

            if ($start < $today || $end < $start) {
                return false;
            }
        }

        return true;
    }

    private function currentRole(): ?string
    {
        $user = $this->currentUser();

        return $user ? $this->resolveAccessRole($user) : null;
    }

    private function canManagePlans(?string $role): bool
    {
        return $role === 'VT_BAN_GIAM_DOC';
    }

    private function resolveDateRuleMessage(string $message): string
    {
        if (str_contains($message, 'Ngày bắt đầu không được bé hơn ngày hiện tại')) {
            return 'Ngày bắt đầu không được bé hơn ngày hiện tại.';
        }
        if (str_contains($message, 'Ngày kết thúc không được bé hơn ngày bắt đầu')) {
            return 'Ngày kết thúc không được bé hơn ngày bắt đầu.';
        }

        return 'Không thể tạo kế hoạch, vui lòng kiểm tra lại thời gian.';
    }

    private function resolveAllowedWorkshopTypes(string $label, ?string $category, ?string $detailKey): array
    {
        $normalizedLabel = function_exists('mb_strtolower') ? mb_strtolower($label) : strtolower($label);
        $normalizedCategory = $category ? (function_exists('mb_strtolower') ? mb_strtolower($category) : strtolower($category)) : '';
        $normalizedDetailKey = $detailKey ? (function_exists('mb_strtolower') ? mb_strtolower($detailKey) : strtolower($detailKey)) : '';

        if ($normalizedCategory === 'inspection' || str_contains($normalizedLabel, 'kiểm định')) {
            return ['Xưởng kiểm định'];
        }

        if (str_contains($normalizedLabel, 'lắp ráp') || str_contains($normalizedLabel, 'đóng gói')) {
            return ['Xưởng lắp ráp và đóng gói'];
        }

        $productionKeys = ['keycap', 'mainboard', 'switchtype', 'casetype', 'foam'];
        if (in_array($normalizedDetailKey, $productionKeys, true)) {
            return ['Xưởng sản xuất'];
        }

        if ($normalizedCategory === 'production' || $normalizedCategory === 'configuration-detail') {
            return ['Xưởng sản xuất'];
        }

        return ['Xưởng sản xuất'];
    }

    private function resolvePlannerUser(?array $user): ?array
    {
        if (!$user) {
            return $user;
        }

        $employeeId = $user['IdNhanVien'] ?? null;
        if (!$employeeId) {
            $username = $user['TenDangNhap'] ?? null;
            if ($username) {
                $account = $this->userModel->findByUsername($username);
                $employeeId = $account['IdNhanVien'] ?? null;
                if ($employeeId) {
                    $user['IdNhanVien'] = $employeeId;
                }
            }
        }

        if ($employeeId) {
            $employee = $this->employeeModel->find($employeeId);
            if (!empty($employee['HoTen'])) {
                $user['HoTen'] = $employee['HoTen'];
            }
        }

        return $user;
    }
}
