<?php

class AuthController extends Controller
{
    private User $userModel;
    private Employee $employeeModel;
    private Role $roleModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->employeeModel = new Employee();
        $this->roleModel = new Role();
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->findByUsername($username);
            if ($user && ($user['MatKhau'] === $password || password_verify($password, $user['MatKhau']))) {
                $role = $this->roleModel->find($user['IdVaiTro']);
                $_SESSION['user'] = array_merge($user, [
                    'TenVaiTro' => $role['TenVaiTro'] ?? null,
                ]);
                $this->setFlash('success', 'Đăng nhập thành công.');
                Logger::login($username);
                $this->redirect('?controller=dashboard&action=index');
                return;
            }

            Logger::warn("Thất bại đăng nhập cho tên đăng nhập: $username");
            $this->setFlash('danger', 'Tên đăng nhập hoặc mật khẩu không chính xác.');
        }

        $flash = $this->getFlash();
        include __DIR__ . '/../views/auth/login.php';
    }

    public function logout(): void
    {
        Impersonation::clear();
        unset($_SESSION['user']);
        $this->setFlash('success', 'Đã đăng xuất.');
        $this->redirect('?controller=auth&action=login');
    }

    public function profile(): void
    {
        $user = $_SESSION['user'] ?? null;
        $employee = null;
        if ($user) {
            $user = Impersonation::applyToUser($user);
            $employee = $this->employeeModel->find($user['IdNhanVien']);
        }

        $this->render('auth/profile', [
            'title' => 'Thông tin cá nhân',
            'user' => $user,
            'employee' => $employee,
        ]);
    }
}
