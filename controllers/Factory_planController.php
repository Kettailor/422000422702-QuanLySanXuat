<?php

class Factory_planController extends Controller
{
    private WorkshopPlan $workshopPlanModel;
    private ProductionPlan $productionPlanModel;
    private Workshop $workshopModel;

    public function __construct()
    {
        $this->authorize(['VT_QUANLY_XUONG', 'VT_NHANVIEN_SANXUAT']);
        $this->workshopPlanModel = new WorkshopPlan();
        $this->productionPlanModel = new ProductionPlan();
        $this->workshopModel = new Workshop();
    }

    public function index(): void
    {
        $plans = $this->workshopPlanModel->getDetailedPlans();
        $this->render('factory_plan/index', [
            'title' => 'Kế hoạch xưởng',
            'plans' => $plans,
        ]);
    }

    public function read(): void
    {
        $id = $_GET['id'] ?? null;
        $plan = $id ? $this->workshopPlanModel->findWithRelations($id) : null;
        $this->render('factory_plan/read', [
            'title' => 'Chi tiết kế hoạch xưởng',
            'plan' => $plan,
        ]);
    }

    public function create(): void
    {
        $this->render('factory_plan/create', [
            'title' => 'Thêm kế hoạch xưởng',
            'productionPlans' => $this->productionPlanModel->getPlansForWorkshopAssignment(),
            'workshops' => $this->workshopModel->getAllWithManagers(),
            'selectedPlanId' => $_GET['IdKeHoachSanXuat'] ?? null,
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=factory_plan&action=index');
            return;
        }

        $planId = $_POST['IdKeHoachSanXuat'] ?? null;
        $workshopId = $_POST['IdXuong'] ?? null;

        if (!$planId || !$workshopId) {
            $this->setFlash('danger', 'Vui lòng chọn kế hoạch tổng và xưởng sản xuất.');
            $this->redirect('?controller=factory_plan&action=create');
            return;
        }

        $data = [
            'IdKeHoachSanXuatXuong' => $_POST['IdKeHoachSanXuatXuong'] ?: uniqid('KXX'),
            'TenThanhThanhPhanSP' => $_POST['TenThanhThanhPhanSP'] ?? null,
            'SoLuong' => $_POST['SoLuong'] ?? 0,
            'ThoiGianBatDau' => $_POST['ThoiGianBatDau'] ?? null,
            'ThoiGianKetThuc' => $_POST['ThoiGianKetThuc'] ?? null,
            'TrangThai' => $_POST['TrangThai'] ?? 'Đang chuẩn bị',
            'IdKeHoachSanXuat' => $planId,
            'IdXuong' => $workshopId,
        ];

        try {
            $this->workshopPlanModel->create($data);
            $this->setFlash('success', 'Đã thêm kế hoạch xưởng.');
        } catch (Throwable $e) {
            $this->setFlash('danger', 'Không thể thêm kế hoạch xưởng: ' . $e->getMessage());
        }

        $this->redirect('?controller=factory_plan&action=index');
    }

    public function edit(): void
    {
        $id = $_GET['id'] ?? null;
        $plan = $id ? $this->workshopPlanModel->findWithRelations($id) : null;
        $this->render('factory_plan/edit', [
            'title' => 'Cập nhật kế hoạch xưởng',
            'plan' => $plan,
            'productionPlans' => $this->productionPlanModel->getPlansForWorkshopAssignment(),
            'workshops' => $this->workshopModel->getAllWithManagers(),
        ]);
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=factory_plan&action=index');
            return;
        }

        $id = $_POST['IdKeHoachSanXuatXuong'];
        $planId = $_POST['IdKeHoachSanXuat'] ?? null;
        $workshopId = $_POST['IdXuong'] ?? null;

        if (!$planId || !$workshopId) {
            $this->setFlash('danger', 'Thiếu thông tin kế hoạch tổng hoặc xưởng.');
            $this->redirect('?controller=factory_plan&action=edit&id=' . urlencode($id));
            return;
        }

        $data = [
            'TenThanhThanhPhanSP' => $_POST['TenThanhThanhPhanSP'] ?? null,
            'SoLuong' => $_POST['SoLuong'] ?? 0,
            'ThoiGianBatDau' => $_POST['ThoiGianBatDau'] ?? null,
            'ThoiGianKetThuc' => $_POST['ThoiGianKetThuc'] ?? null,
            'TrangThai' => $_POST['TrangThai'] ?? 'Đang chuẩn bị',
            'IdKeHoachSanXuat' => $planId,
            'IdXuong' => $workshopId,
        ];

        try {
            $this->workshopPlanModel->update($id, $data);
            $this->setFlash('success', 'Cập nhật kế hoạch xưởng thành công.');
        } catch (Throwable $e) {
            $this->setFlash('danger', 'Không thể cập nhật kế hoạch xưởng: ' . $e->getMessage());
        }

        $this->redirect('?controller=factory_plan&action=index');
    }

    public function delete(): void
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                $this->workshopPlanModel->delete($id);
                $this->setFlash('success', 'Đã xóa kế hoạch xưởng.');
            } catch (Throwable $e) {
                $this->setFlash('danger', 'Không thể xóa kế hoạch xưởng: ' . $e->getMessage());
            }
        }

        $this->redirect('?controller=factory_plan&action=index');
    }
}
