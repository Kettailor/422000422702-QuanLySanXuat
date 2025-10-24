<?php

class WarehouseController extends Controller
{
    private Warehouse $warehouseModel;
    private InventoryLot $lotModel;

    public function __construct()
    {
        $this->warehouseModel = new Warehouse();
        $this->lotModel = new InventoryLot();
    }

    public function index(): void
    {
        $warehouses = $this->warehouseModel->getWithSupervisor();
        $this->render('warehouse/index', [
            'title' => 'Kho & tồn kho',
            'warehouses' => $warehouses,
        ]);
    }

    public function read(): void
    {
        $id = $_GET['id'] ?? null;
        $warehouse = $id ? $this->warehouseModel->find($id) : null;
        $lots = $id ? $this->lotModel->getLotsByWarehouse($id) : [];
        $this->render('warehouse/read', [
            'title' => 'Chi tiết kho',
            'warehouse' => $warehouse,
            'lots' => $lots,
        ]);
    }

    public function create(): void
    {
        $this->render('warehouse/create', [
            'title' => 'Thêm kho mới',
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=warehouse&action=index');
        }

        $data = [
            'IdKho' => $_POST['IdKho'] ?: uniqid('KHO'),
            'TenKho' => $_POST['TenKho'] ?? null,
            'TenLoaiKho' => $_POST['TenLoaiKho'] ?? null,
            'DiaChi' => $_POST['DiaChi'] ?? null,
            'TongSLLo' => $_POST['TongSLLo'] ?? 0,
            'ThanhTien' => $_POST['ThanhTien'] ?? 0,
            'TrangThai' => $_POST['TrangThai'] ?? 'Đang hoạt động',
            'TongSL' => $_POST['TongSL'] ?? 0,
            'IdXuong' => $_POST['IdXuong'] ?? null,
            '`NHAN_VIEN_KHO_IdNhanVien`' => $_POST['IdQuanKho'] ?? null,
        ];

        try {
            $this->warehouseModel->create($data);
            $this->setFlash('success', 'Đã thêm kho mới.');
        } catch (Throwable $e) {
            $this->setFlash('danger', 'Không thể thêm kho: ' . $e->getMessage());
        }

        $this->redirect('?controller=warehouse&action=index');
    }

    public function edit(): void
    {
        $id = $_GET['id'] ?? null;
        $warehouse = $id ? $this->warehouseModel->find($id) : null;
        $this->render('warehouse/edit', [
            'title' => 'Cập nhật kho',
            'warehouse' => $warehouse,
        ]);
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=warehouse&action=index');
        }

        $id = $_POST['IdKho'];
        $data = [
            'TenKho' => $_POST['TenKho'] ?? null,
            'TenLoaiKho' => $_POST['TenLoaiKho'] ?? null,
            'DiaChi' => $_POST['DiaChi'] ?? null,
            'TongSLLo' => $_POST['TongSLLo'] ?? 0,
            'ThanhTien' => $_POST['ThanhTien'] ?? 0,
            'TrangThai' => $_POST['TrangThai'] ?? 'Đang hoạt động',
            'TongSL' => $_POST['TongSL'] ?? 0,
            'IdXuong' => $_POST['IdXuong'] ?? null,
            '`NHAN_VIEN_KHO_IdNhanVien`' => $_POST['IdQuanKho'] ?? null,
        ];

        try {
            $this->warehouseModel->update($id, $data);
            $this->setFlash('success', 'Cập nhật kho thành công.');
        } catch (Throwable $e) {
            $this->setFlash('danger', 'Không thể cập nhật kho: ' . $e->getMessage());
        }

        $this->redirect('?controller=warehouse&action=index');
    }

    public function delete(): void
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                $this->warehouseModel->delete($id);
                $this->setFlash('success', 'Đã xóa kho.');
            } catch (Throwable $e) {
                $this->setFlash('danger', 'Không thể xóa kho: ' . $e->getMessage());
            }
        }

        $this->redirect('?controller=warehouse&action=index');
    }
}
