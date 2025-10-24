<?php

class Factory_planController extends Controller
{
    private WorkshopPlan $workshopPlanModel;

    public function __construct()
    {
        $this->workshopPlanModel = new WorkshopPlan();
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
        $plan = $id ? $this->workshopPlanModel->find($id) : null;
        $this->render('factory_plan/read', [
            'title' => 'Chi tiết kế hoạch xưởng',
            'plan' => $plan,
        ]);
    }

    public function create(): void
    {
        $this->render('factory_plan/create', [
            'title' => 'Thêm kế hoạch xưởng',
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=factory_plan&action=index');
        }

        $data = [
            'IdKeHoachSanXuatXuong' => $_POST['IdKeHoachSanXuatXuong'] ?: uniqid('KXX'),
            'TenThanhThanhPhanSP' => $_POST['TenThanhThanhPhanSP'] ?? null,
            'SoLuong' => $_POST['SoLuong'] ?? 0,
            'ThoiGianBatDau' => $_POST['ThoiGianBatDau'] ?? null,
            'ThoiGianKetThuc' => $_POST['ThoiGianKetThuc'] ?? null,
            'TrangThai' => $_POST['TrangThai'] ?? 'Đang chuẩn bị',
            'IdKeHoachSanXuat' => $_POST['IdKeHoachSanXuat'] ?? null,
            'IdXuong' => $_POST['IdXuong'] ?? null,
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
        $plan = $id ? $this->workshopPlanModel->find($id) : null;
        $this->render('factory_plan/edit', [
            'title' => 'Cập nhật kế hoạch xưởng',
            'plan' => $plan,
        ]);
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=factory_plan&action=index');
        }

        $id = $_POST['IdKeHoachSanXuatXuong'];
        $data = [
            'TenThanhThanhPhanSP' => $_POST['TenThanhThanhPhanSP'] ?? null,
            'SoLuong' => $_POST['SoLuong'] ?? 0,
            'ThoiGianBatDau' => $_POST['ThoiGianBatDau'] ?? null,
            'ThoiGianKetThuc' => $_POST['ThoiGianKetThuc'] ?? null,
            'TrangThai' => $_POST['TrangThai'] ?? 'Đang chuẩn bị',
            'IdKeHoachSanXuat' => $_POST['IdKeHoachSanXuat'] ?? null,
            'IdXuong' => $_POST['IdXuong'] ?? null,
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
