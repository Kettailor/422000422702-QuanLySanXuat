<?php

class AccountController extends Controller
{
    protected Employee $employeeModel;
    protected User $userModel;
    protected Role $roleModel;

    public function __construct()
    {
        $this->authorize(['VT_ADMIN']);

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

            $config = require __DIR__ . '/../config/config.php';
            $defaultPassword = $config['auth']['default_password'];

            try {
                $this->userModel->create([
                    'IdNguoiDung' => $nextUserId,
                    'IdNhanVien' => $employeeId,
                    'TenDangNhap' => $username,
                    'IdVaiTro' => $roleId,
                    'MatKhau' => password_hash($defaultPassword, PASSWORD_BCRYPT),
                    'TrangThai' => 'Hoạt động',
                ]);
                $this->setFlash('success', 'Tạo tài khoản thành công.');
                Logger::success("Tạo tài khoản mới: $username (ID: $nextUserId)");
                $this->redirect('?controller=account&action=index');
            } catch (Exception $e) {
                Logger::error('Lỗi khi tạo tài khoản: ' . $e->getMessage());
                $this->setFlash('danger', 'Không thể tạo tài khoản. Lỗi: ' . htmlspecialchars($e->getMessage()));
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
            Logger::info("Cập nhật tài khoản: $username (ID: $id): " . ($password ? 'Đổi mật khẩu, ' : '') . "Vai trò ID: $roleId");

            $userData = [
                'TenDangNhap' => $username,
                'IdVaiTro' => $roleId,
            ];

            if (!empty($password)) {
                $userData['MatKhau'] = password_hash($password, PASSWORD_BCRYPT);
            }

            try {
                $this->userModel->update($id, $userData);
                $this->setFlash('success', 'Cập nhật tài khoản thành công.');
                Logger::success("Cập nhật tài khoản thành công: $username (ID: $id)");
                $this->redirect('?controller=account&action=index');
            } catch (Exception $e) {
                Logger::error('Lỗi khi cập nhật tài khoản ' . $id . ': ' . $e->getMessage());
                $this->setFlash('danger', 'Không thể cập nhật tài khoản. Lỗi: ' . htmlspecialchars($e->getMessage()));
                $this->redirect("?controller=account&action=edit&id=$id");
            }
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
            Logger::info("Thay đổi trạng thái tài khoản: " . $user['TenDangNhap'] . " (ID: $id)");

            if ($user) {
                $newStatus = ($user['TrangThai'] === 'Hoạt động') ? 'Tạm ngưng' : 'Hoạt động';

                try {
                    $this->userModel->update($id, ['TrangThai' => $newStatus]);
                    $this->setFlash('success', 'Cập nhật trạng thái tài khoản thành công.');
                    Logger::success("Cập nhật trạng thái tài khoản thành công: " . $user['TenDangNhap'] . " (ID: $id) sang trạng thái $newStatus");
                } catch (Exception $e) {
                    Logger::error('Lỗi khi cập nhật trạng thái tài khoản ' . $id . ': ' . $e->getMessage());
                    $this->setFlash('danger', 'Không thể cập nhật trạng thái tài khoản. Lỗi: ' . htmlspecialchars($e->getMessage()));
                }
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

        if ($_SESSION['user']['IdNguoiDung'] === $id) {
            $this->setFlash('danger', 'Không thể xóa tài khoản đang đăng nhập.');
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

        Logger::info("Xóa tài khoản: " . $user['TenDangNhap'] . " (ID: $id)");

        try {
            $this->userModel->delete($id);
            $this->setFlash('success', 'Xóa tài khoản thành công.');
            Logger::success("Xóa tài khoản thành công: " . $user['TenDangNhap'] . " (ID: $id)");
            $this->redirect('?controller=account&action=index');
        } catch (Exception $e) {
            Logger::error('Lỗi khi xóa tài khoản ' . $id . ': ' . $e->getMessage());
            $this->setFlash('danger', 'Không thể xóa tài khoản. Lỗi: ' . htmlspecialchars($e->getMessage()));
            $this->redirect('?controller=account&action=index');
        }
    }

    public function auditLog(): void
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-6 days'));
        $endDate = $_GET['end_date'] ?? date('Y-m-d');

        $logs = Logger::getLog($page, $limit);
        $loginLogs = Logger::getLoginLog($startDate, $endDate);

        $this->render('account/audit-log', [
            'title' => 'Nhật ký hoạt động',
            'logs' => $logs,
            'loginLogs' => [
                'data' => $loginLogs,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
        ]);
    }
}
