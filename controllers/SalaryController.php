<?php

class SalaryController extends Controller
{
    private Salary $salaryModel;

    public function __construct()
    {
        $this->salaryModel = new Salary();
    }

    public function index(): void
    {
        $payrolls = $this->salaryModel->getPayrolls();
        $this->render('salary/index', [
            'title' => 'Bảng lương',
            'payrolls' => $payrolls,
        ]);
    }

    public function read(): void
    {
        $id = $_GET['id'] ?? null;
        $payroll = $id ? $this->salaryModel->find($id) : null;
        $this->render('salary/read', [
            'title' => 'Chi tiết bảng lương',
            'payroll' => $payroll,
        ]);
    }

    public function create(): void
    {
        $this->render('salary/create', [
            'title' => 'Tạo bảng lương',
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=salary&action=index');
        }

        $data = [
            'IdBangLuong' => $_POST['IdBangLuong'] ?: uniqid('BL'),
            '`KETOAN IdNhanVien2`' => $_POST['KeToan'] ?? null,
            'NHAN_VIENIdNhanVien' => $_POST['IdNhanVien'] ?? null,
            'ThangNam' => $_POST['ThangNam'] ?? null,
            'LuongCoBan' => $_POST['LuongCoBan'] ?? 0,
            'PhuCap' => $_POST['PhuCap'] ?? 0,
            'KhauTru' => $_POST['KhauTru'] ?? 0,
            'ThueTNCN' => $_POST['ThueTNCN'] ?? 0,
            'TongThuNhap' => $_POST['TongThuNhap'] ?? 0,
            'TrangThai' => $_POST['TrangThai'] ?? 'Chờ duyệt',
            'NgayLap' => $_POST['NgayLap'] ?? date('Y-m-d'),
            'ChuKy' => null,
        ];

        try {
            $this->salaryModel->create($data);
            $this->setFlash('success', 'Đã tạo bảng lương.');
        } catch (Throwable $e) {
            $this->setFlash('danger', 'Không thể tạo bảng lương: ' . $e->getMessage());
        }

        $this->redirect('?controller=salary&action=index');
    }

    public function edit(): void
    {
        $id = $_GET['id'] ?? null;
        $payroll = $id ? $this->salaryModel->find($id) : null;
        $this->render('salary/edit', [
            'title' => 'Cập nhật bảng lương',
            'payroll' => $payroll,
        ]);
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=salary&action=index');
        }

        $id = $_POST['IdBangLuong'];
        $data = [
            '`KETOAN IdNhanVien2`' => $_POST['KeToan'] ?? null,
            'NHAN_VIENIdNhanVien' => $_POST['IdNhanVien'] ?? null,
            'ThangNam' => $_POST['ThangNam'] ?? null,
            'LuongCoBan' => $_POST['LuongCoBan'] ?? 0,
            'PhuCap' => $_POST['PhuCap'] ?? 0,
            'KhauTru' => $_POST['KhauTru'] ?? 0,
            'ThueTNCN' => $_POST['ThueTNCN'] ?? 0,
            'TongThuNhap' => $_POST['TongThuNhap'] ?? 0,
            'TrangThai' => $_POST['TrangThai'] ?? 'Chờ duyệt',
            'NgayLap' => $_POST['NgayLap'] ?? date('Y-m-d'),
        ];

        try {
            $this->salaryModel->update($id, $data);
            $this->setFlash('success', 'Cập nhật bảng lương thành công.');
        } catch (Throwable $e) {
            $this->setFlash('danger', 'Không thể cập nhật bảng lương: ' . $e->getMessage());
        }

        $this->redirect('?controller=salary&action=index');
    }

    public function delete(): void
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                $this->salaryModel->delete($id);
                $this->setFlash('success', 'Đã xóa bảng lương.');
            } catch (Throwable $e) {
                $this->setFlash('danger', 'Không thể xóa bảng lương: ' . $e->getMessage());
            }
        }

        $this->redirect('?controller=salary&action=index');
    }
}
