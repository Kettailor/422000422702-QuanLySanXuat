<?php

class AccountController extends Controller
{
    private Employee $employeeModel;
    private User $userModel;

    public function __construct()
    {
        $this->employeeModel = new Employee();
        $this->userModel = new User();
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
            'header' => ["ID", 'Tên nhân viên', 'Vai trò', 'Chức vụ', 'Trạng thái', 'Hành động'],
            'users' => $users,
            'numberOfActiveUsers' => $numberOfActiveUsers,
            'numberOfActiveEmployees' => $numberOfActiveEmployees,
        ]);
    }

    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log('POST data: ' . print_r($_POST, true));
        }

        $employees = $this->employeeModel->getUnassignedEmployees();

        $this->render('account/create', [
            'title' => 'Tạo tài khoản mới',
            'employees' => $employees,
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
            // Thực hiện xóa tài khoản
            // $this->userModel->delete($id);
        }
        header('Location: ?controller=account&action=index');
    }
}
