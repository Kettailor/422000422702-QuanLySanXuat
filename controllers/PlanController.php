<?php

class PlanController extends Controller
{
    private ProductionPlan $planModel;
    private OrderDetail $orderDetailModel;
    private Employee $employeeModel;
    private WorkshopPlan $workshopPlanModel;
    private ProductionAutomation $automation;
    private ProductComponent $componentModel;
    private Workshop $workshopModel;

    public function __construct()
    {
        $this->authorize(['VT_BAN_GIAM_DOC', 'VT_QUANLY_XUONG']);
        $this->planModel = new ProductionPlan();
        $this->orderDetailModel = new OrderDetail();
        $this->employeeModel = new Employee();
        $this->workshopPlanModel = new WorkshopPlan();
        $this->automation = new ProductionAutomation();
        $this->componentModel = new ProductComponent();
        $this->workshopModel = new Workshop();
    }

    public function index(): void
    {
        $plans = $this->planModel->getPlansWithOrders();
        $planIds = array_values(array_filter(array_map(static fn ($plan) => $plan['IdKeHoachSanXuat'] ?? null, $plans)));
        $workshopPlans = $this->workshopPlanModel->getByPlanIds($planIds);

        $structuredPlans = array_map(function (array $plan) use ($workshopPlans): array {
            $planId = $plan['IdKeHoachSanXuat'] ?? null;
            $plan['workshopPlans'] = $planId && isset($workshopPlans[$planId]) ? $workshopPlans[$planId] : [];
            return $plan;
        }, $plans);

        $pendingDetails = $this->orderDetailModel->getPendingForPlanning();
        $pendingOrders = [];
        foreach ($pendingDetails as $detail) {
            $orderId = $detail['IdDonHang'] ?? 'UNKNOWN';
            if (!isset($pendingOrders[$orderId])) {
                $pendingOrders[$orderId] = [
                    'IdDonHang' => $orderId,
                    'NgayLap' => $detail['NgayLap'] ?? null,
                    'TenSanPham' => $detail['TenSanPham'] ?? null,
                    'details' => [],
                ];
            }
            $pendingOrders[$orderId]['details'][] = $detail;
        }

        $totalWorkshopTasks = array_sum(array_map(static function (array $plan): int {
            return count($plan['workshopPlans'] ?? []);
        }, $structuredPlans));

        $activePlans = array_filter($structuredPlans, function (array $plan): bool {
            $status = $plan['TrangThai'] ?? '';
            if ($status === '') {
                return true;
            }
            $normalized = $this->normalizeStatus($status);
            return !in_array($normalized, ['hoàn thành', 'da hoan thanh', 'đã hoàn thành', 'da hoan thanh'], true);
        });

        $completedPlans = array_filter($structuredPlans, function (array $plan): bool {
            $status = $plan['TrangThai'] ?? '';
            if ($status === '') {
                return false;
            }
            $normalized = $this->normalizeStatus($status);
            return in_array($normalized, ['hoàn thành', 'da hoan thanh', 'đã hoàn thành', 'da hoan thanh'], true);
        });

        $stats = [
            'total_plans' => count($structuredPlans),
            'active_plans' => count($activePlans),
            'completed_plans' => count($completedPlans),
            'pending_orders' => count($pendingOrders),
            'pending_details' => count($pendingDetails),
            'workshop_tasks' => $totalWorkshopTasks,
        ];

        $this->render('plan/index', [
            'title' => 'Kế hoạch sản xuất',
            'plans' => $structuredPlans,
            'pendingOrders' => array_values($pendingOrders),
            'stats' => $stats,
        ]);
    }

    public function create(): void
    {
        $orderDetails = $this->orderDetailModel->getPendingForPlanning();
        $selectedOrderDetailId = $_GET['order_detail_id'] ?? null;

        foreach ($orderDetails as &$detail) {
            $productId = $detail['IdSanPham'] ?? null;
            $configurationId = $detail['IdCauHinh'] ?? null;
            $components = $this->componentModel->getComponentsForProductConfiguration($productId, $configurationId);

            $detail['components'] = array_map(function (array $component) use ($detail): array {
                $ratio = $component['TyLeSoLuong'] ?? $component['quantity_per_unit'] ?? 1;
                $ratio = is_numeric($ratio) ? (float) $ratio : 1.0;

                return [
                    'id' => $component['IdCongDoan'] ?? null,
                    'name' => $component['TenCongDoan'] ?? 'Hạng mục sản xuất',
                    'default_workshop' => $component['IdXuong'] ?? null,
                    'unit' => $component['DonVi'] ?? ($detail['DonVi'] ?? 'sản phẩm'),
                    'quantity_ratio' => $ratio,
                    'include_request' => !empty($component['IncludeYeuCau'] ?? $component['include_request'] ?? false),
                    'logistics_key' => $component['LogisticsKey'] ?? null,
                ];
            }, $components);
        }
        unset($detail);

        $this->render('plan/create', [
            'title' => 'Tạo kế hoạch sản xuất',
            'orderDetails' => $orderDetails,
            'managers' => $this->employeeModel->getBoardManagers(),
            'workshops' => $this->workshopModel->getAllWithManagers(),
            'selectedOrderDetailId' => $selectedOrderDetailId,
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
            $this->setFlash('danger', 'Vui lòng chọn chi tiết đơn hàng hợp lệ.');
            $this->redirect('?controller=plan&action=create');
            return;
        }

        $quantity = $_POST['SoLuong'] ?? null;
        if ($quantity === '' || $quantity === null) {
            $quantity = $orderDetail['SoLuong'] ?? 0;
        }

        $startTime = $this->normalizeDateTimeInput($_POST['ThoiGianBD'] ?? null);
        $endTime = $this->normalizeDateTimeInput($_POST['ThoiGianKetThuc'] ?? null);

        $data = [
            'IdKeHoachSanXuat' => $_POST['IdKeHoachSanXuat'] ?: uniqid('KH'),
            'SoLuong' => $quantity,
            'ThoiGianKetThuc' => $endTime,
            'TrangThai' => $_POST['TrangThai'] ?? 'Mới tạo',
            'ThoiGianBD' => $startTime,
            '`BANIAMDOC IdNhanVien`' => $_POST['BanGiamDoc'] ?? null,
            'IdTTCTDonHang' => $orderDetailId,
        ];

        try {
            $this->planModel->create($data);

            $assignments = $this->prepareManualAssignments(
                $_POST['workshop_assignments'] ?? [],
                [
                    'plan_id' => $data['IdKeHoachSanXuat'],
                    'quantity' => (int) $quantity,
                    'start' => $startTime,
                    'end' => $endTime,
                ]
            );

            $flashType = 'success';
            $message = 'Tạo kế hoạch sản xuất thành công.';

            if (!empty($assignments)) {
                foreach ($assignments as $assignment) {
                    $this->workshopPlanModel->create($assignment);
                }
                $message .= ' Đã ghi nhận phân xưởng phụ trách theo thiết lập thủ công.';
            } else {
                try {
                    $this->automation->handleNewPlan($data, $orderDetail);
                    $message .= ' Đã tự động phân công cho các xưởng và thông báo kho vận.';
                } catch (Throwable $automationError) {
                    $flashType = 'warning';
                    $message .= ' Tuy nhiên không thể tự động phân bổ: ' . $automationError->getMessage();
                }
            }

            $this->setFlash($flashType, $message);
        } catch (Throwable $e) {
            $this->setFlash('danger', 'Không thể tạo kế hoạch: ' . $e->getMessage());
        }

        $this->redirect('?controller=plan&action=index');
    }

    public function edit(): void
    {
        $id = $_GET['id'] ?? null;
        $plan = $id ? $this->planModel->getPlanWithRelations($id) : null;
        $this->render('plan/edit', [
            'title' => 'Cập nhật kế hoạch',
            'plan' => $plan,
            'orderDetails' => $this->orderDetailModel->getAllWithOrderInfo(),
            'managers' => $this->employeeModel->getBoardManagers(),
        ]);
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=plan&action=index');
            return;
        }

        $id = $_POST['IdKeHoachSanXuat'];
        $orderDetailId = $_POST['IdTTCTDonHang'] ?? null;
        $orderDetail = $orderDetailId ? $this->orderDetailModel->find($orderDetailId) : null;

        if (!$orderDetail) {
            $this->setFlash('danger', 'Chi tiết đơn hàng không hợp lệ.');
            $this->redirect('?controller=plan&action=edit&id=' . urlencode($id));
            return;
        }

        $quantity = $_POST['SoLuong'] ?? null;
        if ($quantity === '' || $quantity === null) {
            $quantity = $orderDetail['SoLuong'] ?? 0;
        }

        $startTime = $this->normalizeDateTimeInput($_POST['ThoiGianBD'] ?? null);
        $endTime = $this->normalizeDateTimeInput($_POST['ThoiGianKetThuc'] ?? null);

        $data = [
            'SoLuong' => $quantity,
            'ThoiGianKetThuc' => $endTime,
            'TrangThai' => $_POST['TrangThai'] ?? 'Mới tạo',
            'ThoiGianBD' => $startTime,
            '`BANIAMDOC IdNhanVien`' => $_POST['BanGiamDoc'] ?? null,
            'IdTTCTDonHang' => $orderDetailId,
        ];

        try {
            $this->planModel->update($id, $data);
            $this->setFlash('success', 'Cập nhật kế hoạch thành công.');
        } catch (Throwable $e) {
            $this->setFlash('danger', 'Không thể cập nhật kế hoạch: ' . $e->getMessage());
        }

        $this->redirect('?controller=plan&action=index');
    }

    public function delete(): void
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                $this->planModel->delete($id);
                $this->setFlash('success', 'Đã xóa kế hoạch.');
            } catch (Throwable $e) {
                $this->setFlash('danger', 'Không thể xóa kế hoạch: ' . $e->getMessage());
            }
        }

        $this->redirect('?controller=plan&action=index');
    }

    public function read(): void
    {
        $id = $_GET['id'] ?? null;
        $plan = $id ? $this->planModel->getPlanWithRelations($id) : null;
        $workshopPlans = $plan ? $this->workshopPlanModel->getByPlan($plan['IdKeHoachSanXuat']) : [];
        $this->render('plan/read', [
            'title' => 'Chi tiết kế hoạch',
            'plan' => $plan,
            'workshopPlans' => $workshopPlans,
        ]);
    }

    private function normalizeStatus(string $status): string
    {
        $status = trim($status);
        if ($status === '') {
            return '';
        }

        if (function_exists('mb_strtolower')) {
            return mb_strtolower($status, 'UTF-8');
        }

        return strtolower($status);
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

    private function prepareManualAssignments(array $input, array $context): array
    {
        $assignments = [];
        $defaultQuantity = max(1, (int) ($context['quantity'] ?? 1));
        $defaultStart = $context['start'] ?? null;
        $defaultEnd = $context['end'] ?? null;
        $allowedStatuses = ['Đang chuẩn bị', 'Đang sản xuất', 'Hoàn thành', 'Tạm dừng'];

        foreach ($input as $row) {
            if (!is_array($row)) {
                continue;
            }

            $workshopId = trim((string) ($row['workshop_id'] ?? ''));
            $label = trim((string) ($row['label'] ?? ($row['component_label'] ?? '')));
            if ($workshopId === '' || $label === '') {
                continue;
            }

            $componentId = trim((string) ($row['component_id'] ?? '')) ?: null;
            $quantity = (int) ($row['quantity'] ?? 0);
            if ($quantity <= 0) {
                $quantity = $defaultQuantity;
            }

            $start = $this->normalizeDateTimeInput($row['start'] ?? null) ?? $defaultStart;
            $end = $this->normalizeDateTimeInput($row['end'] ?? null) ?? $defaultEnd;

            $status = $row['status'] ?? null;
            if (!is_string($status) || !in_array($status, $allowedStatuses, true)) {
                $status = 'Đang chuẩn bị';
            }

            $assignments[] = [
                'IdKeHoachSanXuatXuong' => uniqid('KXX'),
                'TenThanhThanhPhanSP' => $label,
                'SoLuong' => $quantity,
                'ThoiGianBatDau' => $start,
                'ThoiGianKetThuc' => $end,
                'TrangThai' => $status,
                'TinhTrangVatTu' => 'Chưa kiểm tra',
                'IdCongDoan' => $componentId,
                'IdKeHoachSanXuat' => $context['plan_id'],
                'IdXuong' => $workshopId,
            ];
        }

        return $assignments;
    }
}
