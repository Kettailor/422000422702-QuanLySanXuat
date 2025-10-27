<?php

class Human_resourcesController extends Controller
{
    private Employee $employeeModel;

    public function __construct()
    {
        $this->authorize(['VT_BAN_GIAM_DOC', 'VT_NHAN_SU']);
        $this->employeeModel = new Employee();
    }

    public function index(): void
    {
        $employees = $this->employeeModel->all(200);
        $this->render('human_resources/index', [
            'title' => 'Quản lý nhân sự',
            'employees' => $employees,
        ]);
    }

    public function create(): void
    {
        $this->render('human_resources/create', [
            'title' => 'Thêm nhân sự',
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=human_resources&action=index');
        }

        $data = [
            'IdNhanVien' => $_POST['IdNhanVien'] ?: uniqid('NV'),
            'HoTen' => $_POST['HoTen'] ?? null,
            'NgaySinh' => $_POST['NgaySinh'] ?? null,
            'GioiTinh' => $_POST['GioiTinh'] ?? 1,
            'ChucVu' => $_POST['ChucVu'] ?? null,
            'HeSoLuong' => $_POST['HeSoLuong'] ?? 1,
            'TrangThai' => $_POST['TrangThai'] ?? 'Đang làm việc',
            'DiaChi' => $_POST['DiaChi'] ?? null,
            'ThoiGianLamViec' => $_POST['ThoiGianLamViec'] ?? date('Y-m-d H:i:s'),
            'ChuKy' => null,
        ];

        try {
            $this->employeeModel->create($data);
            $this->setFlash('success', 'Thêm nhân sự thành công.');
        } catch (Throwable $e) {
            Logger::error('Lỗi khi thêm nhân sự: ' . $e->getMessage());
            /* $this->setFlash('danger', 'Không thể thêm nhân sự: ' . $e->getMessage()); */
            $this->setFlash('danger', 'Không thể thêm nhân sự, vui lòng kiểm tra log để biết thêm chi tiết.');
        }

        $this->redirect('?controller=human_resources&action=index');
    }

    public function edit(): void
    {
        $id = $_GET['id'] ?? null;
        $employee = $id ? $this->employeeModel->find($id) : null;

        $this->render('human_resources/edit', [
            'title' => 'Cập nhật nhân sự',
            'employee' => $employee,
        ]);
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=human_resources&action=index');
        }

        $id = $_POST['IdNhanVien'];
        $data = [
            'HoTen' => $_POST['HoTen'] ?? null,
            'NgaySinh' => $_POST['NgaySinh'] ?? null,
            'GioiTinh' => $_POST['GioiTinh'] ?? 1,
            'ChucVu' => $_POST['ChucVu'] ?? null,
            'HeSoLuong' => $_POST['HeSoLuong'] ?? 1,
            'TrangThai' => $_POST['TrangThai'] ?? 'Đang làm việc',
            'DiaChi' => $_POST['DiaChi'] ?? null,
            'ThoiGianLamViec' => $_POST['ThoiGianLamViec'] ?? date('Y-m-d H:i:s'),
        ];

        try {
            $this->employeeModel->update($id, $data);
            $this->setFlash('success', 'Cập nhật nhân sự thành công.');
        } catch (Throwable $e) {
            Logger::error('Lỗi khi cập nhật nhân sự ' . $id . ': ' . $e->getMessage());
            /* $this->setFlash('danger', 'Không thể cập nhật nhân sự: ' . $e->getMessage()); */
            $this->setFlash('danger', 'Không thể cập nhật nhân sự, vui lòng kiểm tra log để biết thêm chi tiết.');
        }

        $this->redirect('?controller=human_resources&action=index');
    }

    public function delete(): void
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                $this->employeeModel->delete($id);
                $this->setFlash('success', 'Đã xóa nhân sự.');
            } catch (Throwable $e) {
                Logger::error('Lỗi khi xóa nhân sự ' . $id . ': ' . $e->getMessage());
                /* $this->setFlash('danger', 'Không thể xóa nhân sự: ' . $e->getMessage()); */
                $this->setFlash('danger', 'Không thể xóa nhân sự, vui lòng kiểm tra log để biết thêm chi tiết.');
            }
        }

        $this->redirect('?controller=human_resources&action=index');
    }

    public function read(): void
    {
        $id = $_GET['id'] ?? null;
        $employee = $id ? $this->employeeModel->find($id) : null;
        $this->render('human_resources/read', [
            'title' => 'Chi tiết nhân sự',
            'employee' => $employee,
        ]);
    }
}
