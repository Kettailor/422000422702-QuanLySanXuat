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
            'header' => ["ID Người dùng", "ID nhân viên", 'Tên nhân viên', 'Vai trò', 'Chức vụ', 'Trạng thái', 'Hành động'],
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

            try {
                $this->userModel->create([
                  'IdNguoiDung' => $nextUserId,
                  'IdNhanVien' => $employeeId,
                  'TenDangNhap' => $username,
                  'IdVaiTro' => $roleId,
                  'MatKhau' => password_hash($_POST['password'], PASSWORD_BCRYPT),
                  'TrangThai' => 'Hoạt động',
                ]);
                $this->setFlash('success', 'Tạo tài khoản thành công.');
                $this->redirect('?controller=account&action=index');
            } catch (Exception $e) {
                $this->setFlash('danger', 'Đã xảy ra lỗi khi tạo tài khoản: ' . $e->getMessage());
                $this->redirect('?controller=account&action=create');
            }
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $username = $_POST['username'] ?? null;
            $roleId = $_POST['role'] ?? null;
            $password = $_POST['password'] ?? null;

            $existingUser = $this->userModel->findByUsername($username);
            if ($existingUser && $existingUser['IdNguoiDung'] !== $id) {
                $this->setFlash('danger', 'Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.');
                $this->redirect("?controller=account&action=edit&id=$id");
            }

            $userData = [
                'TenDangNhap' => $username,
                'IdVaiTro' => $roleId,
            ];

            if (!empty($password)) {
                $userData['MatKhau'] = password_hash($password, PASSWORD_BCRYPT);
            }


        $user = $this->userModel->find($id);
        $roles = $this->roleModel->all();

        $this->render('account/edit', [
            'title' => 'Chỉnh sửa tài khoản',
            'user_data' => $user,
            'roles' => $roles,
        ]);
    }

    public function suspense(): void
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $user = $this->userModel->find($id);
            if ($user) {
                $newStatus = ($user['TrangThai'] === 'Hoạt động') ? 'Tạm ngưng' : 'Hoạt động';
                $this->userModel->update($id, ['TrangThai' => $newStatus]);
                $this->setFlash('success', 'Cập nhật trạng thái tài khoản thành công.');
            }
        }
        $this->redirect('?controller=account&action=index');
    }

    public function delete(): void
    {
        $id = $_GET['id'] ?? null;
        if ($id == null) {
            $this->setFlash('danger', 'ID tài khoản không hợp lệ.');
            $this->redirect('?controller=account&action=index');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            $this->setFlash('danger', 'Tài khoản không tồn tại.');
            $this->redirect('?controller=account&action=index');
        }

        if ($user['TrangThai'] === 'Hoạt động') {
            $this->setFlash('danger', 'Chỉ có thể xóa tài khoản đang ở trạng thái Tạm ngưng.');
            $this->redirect('?controller=account&action=index');
        }
        $this->userModel->delete($id);
        $this->setFlash('success', 'Xóa tài khoản thành công.');
        $this->redirect('?controller=account&action=index');
    }

    public function auditLog(): void
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-7 days'));
        $endDate = $_GET['end_date'] ?? date('Y-m-d');

        $logs = Logger::getLog($page, $limit);
        $loginLogs = Logger::getLoginLog($startDate, $endDate);

        $this->render('account/audit_log', [
            'title' => 'Nhật ký hoạt động',
            'logs' => $logs,
            'loginLogs' => $loginLogs,
        ]);
    }
}
