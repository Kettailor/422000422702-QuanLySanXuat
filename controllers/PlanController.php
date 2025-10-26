<?php

class PlanController extends Controller
{
    private ProductionPlan $planModel;
    private OrderDetail $orderDetailModel;
    private Employee $employeeModel;
    private WorkshopPlan $workshopPlanModel;
    private ProductionAutomation $automation;

    public function __construct()
    {
        $this->authorize(['VT_BAN_GIAM_DOC', 'VT_QUANLY_XUONG']);
        $this->planModel = new ProductionPlan();
        $this->orderDetailModel = new OrderDetail();
        $this->employeeModel = new Employee();
        $this->workshopPlanModel = new WorkshopPlan();
        $this->automation = new ProductionAutomation();
    }

    public function index(): void
    {
        $plans = $this->planModel->getPlansWithOrders(100);
        $planIds = array_column($plans, 'IdKeHoachSanXuat');
        $workshopPlans = $this->workshopPlanModel->getByPlanIds($planIds);

        $structuredPlans = [];
        $orders = [];

        foreach ($plans as $plan) {
            $planId = $plan['IdKeHoachSanXuat'];
            $orderId = $plan['IdDonHang'];

            $totalSteps = (int) ($plan['TongCongDoan'] ?? 0);
            $completedSteps = (int) ($plan['CongDoanHoanThanh'] ?? 0);
            $progress = $totalSteps > 0 ? (int) round(($completedSteps / $totalSteps) * 100) : null;

            $plan['workshops'] = $workshopPlans[$planId] ?? [];
            $plan['progressPercent'] = $progress;
            $plan['completedSteps'] = $completedSteps;
            $plan['totalSteps'] = $totalSteps;

            $structuredPlans[$planId] = $plan;

            if (!isset($orders[$orderId])) {
                $orders[$orderId] = [
                    'IdDonHang' => $orderId,
                    'YeuCau' => $plan['YeuCau'] ?? null,
                    'NgayLap' => $plan['NgayLapDonHang'] ?? null,
                    'plans' => [],
                ];
            }

            $orders[$orderId]['plans'][] = [
                'IdKeHoachSanXuat' => $planId,
                'TrangThai' => $plan['TrangThai'] ?? null,
                'TenSanPham' => $plan['TenSanPham'] ?? null,
                'progressPercent' => $progress,
            ];
        }

        $this->render('plan/index', [
            'title' => 'Kế hoạch sản xuất',
            'orders' => array_values($orders),
            'plans' => $structuredPlans,
            'initialPlanId' => $planIds[0] ?? null,
        ]);
    }

    public function create(): void
    {
        $this->render('plan/create', [
            'title' => 'Tạo kế hoạch sản xuất',
            'orderDetails' => $this->orderDetailModel->getAllWithOrderInfo(),
            'managers' => $this->employeeModel->getBoardManagers(),
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

        $data = [
            'IdKeHoachSanXuat' => $_POST['IdKeHoachSanXuat'] ?: uniqid('KH'),
            'SoLuong' => $quantity,
            'ThoiGianKetThuc' => $_POST['ThoiGianKetThuc'] ?? null,
            'TrangThai' => $_POST['TrangThai'] ?? 'Mới tạo',
            'ThoiGianBD' => $_POST['ThoiGianBD'] ?? null,
            '`BANIAMDOC IdNhanVien`' => $_POST['BanGiamDoc'] ?? null,
            'IdTTCTDonHang' => $orderDetailId,
        ];

        try {
            $this->planModel->create($data);

            $flashType = 'success';
            $message = 'Tạo kế hoạch sản xuất thành công.';

            try {
                $this->automation->handleNewPlan($data, $orderDetail);
                $message .= ' Đã tự động phân công cho các xưởng và thông báo kho vận.';
            } catch (Throwable $automationError) {
                $flashType = 'warning';
                $message .= ' Tuy nhiên không thể tự động phân bổ: ' . $automationError->getMessage();
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

        $data = [
            'SoLuong' => $quantity,
            'ThoiGianKetThuc' => $_POST['ThoiGianKetThuc'] ?? null,
            'TrangThai' => $_POST['TrangThai'] ?? 'Mới tạo',
            'ThoiGianBD' => $_POST['ThoiGianBD'] ?? null,
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
}
