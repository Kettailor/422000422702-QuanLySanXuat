<?php

class PlanController extends Controller
{
    private ProductionPlan $planModel;
    private OrderDetail $orderDetailModel;
    private Employee $employeeModel;
    private WorkshopPlan $workshopPlanModel;
    private ProductComponent $componentModel;
    private Workshop $workshopModel;

    public function __construct()
    {
        $this->authorize(['VT_BAN_GIAM_DOC', 'VT_QUANLY_XUONG']);
        $this->planModel = new ProductionPlan();
        $this->orderDetailModel = new OrderDetail();
        $this->employeeModel = new Employee();
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

        $this->render('plan/create', [
            'title' => 'Lập kế hoạch sản xuất',
            'pendingOrders' => $pendingOrders,
            'selectedOrderDetailId' => $selectedOrderDetailId,
            'selectedOrderDetail' => $selectedOrderDetail,
            'componentAssignments' => $componentAssignments,
            'managers' => $this->employeeModel->getBoardManagers(),
            'workshops' => $this->workshopModel->getAllWithManagers(),
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
            '`BANGIAMDOC IdNhanVien`' => $_POST['BanGiamDoc'] ?? null,
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
            $this->setFlash('danger', 'Không thể tạo kế hoạch: ' . $exception->getMessage());
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
            $this->setFlash('danger', 'Không thể xóa kế hoạch: ' . $exception->getMessage());
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
            ];
        }

        return $assignments;
    }

    private function extractAssignments(array $input, array $context): array
    {
        $assignments = [];
        $defaultQuantity = max(1, (int) ($context['quantity'] ?? 1));
        $defaultStart = $context['start'] ?? null;
        $allowedStatuses = ['Đang chuẩn bị', 'Đang sản xuất', 'Chờ nghiệm thu', 'Hoàn thành'];

        foreach ($input as $row) {
            if (!is_array($row)) {
                continue;
            }

            $workshopId = trim((string) ($row['workshop_id'] ?? ''));
            $label = trim((string) ($row['label'] ?? ''));
            $configurationId = $row['configuration_id'] ?? null;
            $componentId = $row['component_id'] ?? null;
            if ($componentId === null && $configurationId !== null) {
                $componentId = $configurationId;
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

            $statusInput = $row['status'] ?? null;
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
