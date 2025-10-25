<?php

class AccountController extends Controller
{
    private Employee $employeeModel;
    private User $userModel;
    private Role $roleModel;

    public function __construct()
    {
        $this->employeeModel = new Employee();
        $this->userModel = new User();
        $this->roleModel = new Role();
    }

    public function index(): void
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;

        $users = $this->userModel->findAllWithEmployeeAndRole($page, $limit);
        $numberOfActiveUsers = $this->userModel->countActiveUsers();
        $numberOfActiveEmployees = $this->employeeModel->countActiveEmployees();

        $this->render('account/index', [
            'title' => 'Quản lý tài khoản',
            'header' => ["ID Nguoi dung", "ID nhân viên", 'Tên nhân viên', 'Vai trò', 'Chức vụ', 'Trạng thái', 'Hành động'],
            'users' => $users,
            'numberOfActiveUsers' => $numberOfActiveUsers,
            'numberOfActiveEmployees' => $numberOfActiveEmployees,
        ]);
    }

    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $employeeId = $_POST['employee'] ?? null;
            $username = $_POST['username'] ?? null;
            $roleId = $_POST['role'] ?? null;

            $userExists = $this->userModel->findByUsername($username);
            if ($userExists) {
                $this->setFlash('danger', 'Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.');
                $this->redirect('?controller=account&action=create');
            }

            $lastUser = $this->userModel->getLastUserId();
            if ($lastUser && preg_match('/ND(\d+)/', $lastUser, $matches)) {
                $nextIdNumber = (int)$matches[1] + 1;
                $nextUserId = 'ND' . str_pad($nextIdNumber, 3, '0', STR_PAD_LEFT);
            } else {
                $nextUserId = 'ND001';
            }

            $this->userModel->create([
              'IdNguoiDung' => $nextUserId,
              'IdNhanVien' => $employeeId,
              'TenDangNhap' => $username,
              'IdVaiTro' => $roleId,
              'MatKhau' => password_hash($_POST['password'], PASSWORD_BCRYPT),
              'TrangThai' => 'Hoạt động',
            ]);
            $this->redirect('?controller=account&action=index');
            $this->setFlash('success', 'Tạo tài khoản thành công.');
        }

        $employees = $this->employeeModel->getUnassignedEmployees();
        $roles = $this->roleModel->all();

        $this->render('account/create', [
            'title' => 'Tạo tài khoản mới',
            'employees' => $employees,
            'roles' => $roles,
        ]);
    }

    public function edit(): void
    {
        $id = $_GET['id'] ?? null;

        $this->render('account/edit', [
            'title' => 'Chỉnh sửa tài khoản',
            'id' => $id,
        ]);
    }

    public function delete(): void
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->userModel->delete($id);
        }
        header('Location: ?controller=account&action=index');
    }
}
