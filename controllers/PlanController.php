<?php

class PlanController extends Controller
{
    private ProductionPlan $planModel;

    public function __construct()
    {
        $this->planModel = new ProductionPlan();
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
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=plan&action=index');
        }

        $data = [
            'IdKeHoachSanXuat' => $_POST['IdKeHoachSanXuat'] ?: uniqid('KH'),
            'SoLuong' => $_POST['SoLuong'] ?? 0,
            'ThoiGianKetThuc' => $_POST['ThoiGianKetThuc'] ?? null,
            'TrangThai' => $_POST['TrangThai'] ?? 'Mới tạo',
            'ThoiGianBD' => $_POST['ThoiGianBD'] ?? null,
            '`BANIAMDOC IdNhanVien`' => $_POST['BanGiamDoc'] ?? null,
            'IdTTCTDonHang' => $_POST['IdTTCTDonHang'] ?? null,
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
        $plan = $id ? $this->planModel->find($id) : null;
        $this->render('plan/edit', [
            'title' => 'Cập nhật kế hoạch',
            'plan' => $plan,
        ]);
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=plan&action=index');
        }

        $id = $_POST['IdKeHoachSanXuat'];
        $data = [
            'SoLuong' => $_POST['SoLuong'] ?? 0,
            'ThoiGianKetThuc' => $_POST['ThoiGianKetThuc'] ?? null,
            'TrangThai' => $_POST['TrangThai'] ?? 'Mới tạo',
            'ThoiGianBD' => $_POST['ThoiGianBD'] ?? null,
            '`BANIAMDOC IdNhanVien`' => $_POST['BanGiamDoc'] ?? null,
            'IdTTCTDonHang' => $_POST['IdTTCTDonHang'] ?? null,
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
        $plan = $id ? $this->planModel->find($id) : null;
        $this->render('plan/read', [
            'title' => 'Chi tiết kế hoạch',
            'plan' => $plan,
        ]);
    }
}
