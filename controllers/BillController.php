<?php

class BillController extends Controller
{
    private Bill $billModel;

    public function __construct()
    {
        $this->billModel = new Bill();
    }

    public function index(): void
    {
        $bills = $this->billModel->getBillsWithOrder();
        $this->render('bill/index', [
            'title' => 'Hóa đơn',
            'bills' => $bills,
        ]);
    }

    public function read(): void
    {
        $id = $_GET['id'] ?? null;
        $bill = $id ? $this->billModel->find($id) : null;
        $this->render('bill/read', [
            'title' => 'Chi tiết hóa đơn',
            'bill' => $bill,
        ]);
    }

    public function create(): void
    {
        $this->render('bill/create', [
            'title' => 'Tạo hóa đơn',
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=bill&action=index');
        }

        $data = [
            'IdHoaDon' => $_POST['IdHoaDon'] ?: uniqid('HD'),
            'NgayLap' => $_POST['NgayLap'] ?? date('Y-m-d'),
            'TrangThai' => $_POST['TrangThai'] ?? 'Chưa thanh toán',
            'LoaiHD' => $_POST['LoaiHD'] ?? 'Bán hàng',
            'IdDonHang' => $_POST['IdDonHang'] ?? null,
        ];

        try {
            $this->billModel->create($data);
            $this->setFlash('success', 'Đã tạo hóa đơn.');
        } catch (Throwable $e) {
            $this->setFlash('danger', 'Không thể tạo hóa đơn: ' . $e->getMessage());
        }

        $this->redirect('?controller=bill&action=index');
    }

    public function edit(): void
    {
        $id = $_GET['id'] ?? null;
        $bill = $id ? $this->billModel->find($id) : null;
        $this->render('bill/edit', [
            'title' => 'Cập nhật hóa đơn',
            'bill' => $bill,
        ]);
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=bill&action=index');
        }

        $id = $_POST['IdHoaDon'];
        $data = [
            'NgayLap' => $_POST['NgayLap'] ?? date('Y-m-d'),
            'TrangThai' => $_POST['TrangThai'] ?? 'Chưa thanh toán',
            'LoaiHD' => $_POST['LoaiHD'] ?? 'Bán hàng',
            'IdDonHang' => $_POST['IdDonHang'] ?? null,
        ];

        try {
            $this->billModel->update($id, $data);
            $this->setFlash('success', 'Cập nhật hóa đơn thành công.');
        } catch (Throwable $e) {
            $this->setFlash('danger', 'Không thể cập nhật hóa đơn: ' . $e->getMessage());
        }

        $this->redirect('?controller=bill&action=index');
    }

    public function delete(): void
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                $this->billModel->delete($id);
                $this->setFlash('success', 'Đã xóa hóa đơn.');
            } catch (Throwable $e) {
                $this->setFlash('danger', 'Không thể xóa hóa đơn: ' . $e->getMessage());
            }
        }

        $this->redirect('?controller=bill&action=index');
    }
}
