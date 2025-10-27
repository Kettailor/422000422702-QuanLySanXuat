<?php

class PlanController extends Controller
{
    private ProductionPlan $planModel;
    private OrderDetail $orderDetailModel;
    private WorkshopPlan $workshopPlanModel;
    private ProductComponent $componentModel;
    private Workshop $workshopModel;

    public function __construct()
    {
        $this->authorize(['VT_BAN_GIAM_DOC', 'VT_QUANLY_XUONG']);
        $this->planModel = new ProductionPlan();
        $this->orderDetailModel = new OrderDetail();
        $this->workshopPlanModel = new WorkshopPlan();
        $this->componentModel = new ProductComponent();
        $this->workshopModel = new Workshop();
    }

    public function index(): void
    {
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
        ]);
    }

    public function create(): void
    {
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

        $currentUser = $this->currentUser();

        $this->render('plan/create', [
            'title' => 'Lập kế hoạch sản xuất',
            'pendingOrders' => $pendingOrders,
            'selectedOrderDetailId' => $selectedOrderDetailId,
            'selectedOrderDetail' => $selectedOrderDetail,
            'componentAssignments' => $componentAssignments,
            'configurationDetails' => $selectedOrderDetail ? $this->buildConfigurationDetails($selectedOrderDetail) : [],
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

        $orderDetailId = $_POST['IdTTCTDonHang'] ?? null;
        $orderDetail = $orderDetailId ? $this->orderDetailModel->getPlanningContext($orderDetailId) : null;

        if (!$orderDetail) {
            $this->setFlash('danger', 'Vui lòng chọn đơn hàng hợp lệ trước khi lập kế hoạch.');
            $this->redirect('?controller=plan&action=create');
            return;
        }

        $currentUser = $this->currentUser();
        $actorId = $currentUser['IdNhanVien'] ?? null;

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
        $status = $_POST['TrangThai'] ?? 'Đã lập kế hoạch';

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

        if (empty($assignments)) {
            $this->setFlash('warning', 'Vui lòng phân công ít nhất một xưởng phụ trách cho sản phẩm.');
            $this->redirect('?controller=plan&action=create&order_detail_id=' . urlencode($orderDetailId));
            return;
        }

        try {
            $this->planModel->create($planData);
            foreach ($assignments as $assignment) {
                $this->workshopPlanModel->create($assignment);
            }
            $this->setFlash('success', 'Đã lập kế hoạch sản xuất và giao nhiệm vụ cho các xưởng.');
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

    public function delete(): void
    {
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
            ];
        }

        return $this->appendConfigurationDetailAssignments($assignments, $configurationDetails, max(1, $quantity), $orderDetail);
    }

    private function buildConfigurationDetails(array $orderDetail): array
    {
        $mapping = [
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

        $normalizer = static function (string $value): string {
            return function_exists('mb_strtolower') ? mb_strtolower($value) : strtolower($value);
        };

        $existingLabels = array_map(function (array $assignment) use ($normalizer): string {
            return $normalizer((string) ($assignment['label'] ?? ''));
        }, $assignments);

        foreach ($configurationDetails as $detail) {
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
            ];

            $existingLabels[] = $normalizer($displayLabel);
        }

        return $assignments;
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
}
