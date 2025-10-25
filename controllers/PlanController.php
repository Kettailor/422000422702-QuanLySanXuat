<?php

class PlanController extends Controller
{
    private ProductionPlan $planModel;
    private OrderDetail $orderDetailModel;
    private Employee $employeeModel;
    private WorkshopPlan $workshopPlanModel;

    public function __construct()
    {
        $this->authorize(['VT_BAN_GIAM_DOC', 'VT_QUANLY_XUONG']);
        $this->planModel = new ProductionPlan();
        $this->orderDetailModel = new OrderDetail();
        $this->employeeModel = new Employee();
        $this->workshopPlanModel = new WorkshopPlan();
    }

    public function index(): void
    {
        $plans = $this->planModel->getPlansWithOrders();
        $this->render('plan/index', [
            'title' => 'Kế hoạch sản xuất',
            'plans' => $plans,
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
        $orderDetail = $orderDetailId ? $this->orderDetailModel->find($orderDetailId) : null;

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
            $this->setFlash('success', 'Tạo kế hoạch sản xuất thành công.');
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
