<?php

class AdminImpersonationController extends Controller
{
    private Role $roleModel;

    public function __construct()
    {
        $this->roleModel = new Role();
    }

    public function index(): void
    {
        $this->authorize(['VT_ADMIN']);

        $roles = $this->roleModel->all(200);
        $roles = array_filter($roles, fn(array $role) => $role['IdVaiTro'] !== 'VT_ADMIN');
        $impersonatedRole = Impersonation::getImpersonatedRole();
        $adminBypassEnabled = $_SESSION['admin_bypass_enabled'] ?? true;
        $canToggleAdminBypass = true;

        $this->render('admin/impersonate', [
            'title' => 'Giả lập vai trò',
            'roles' => $roles,
            'impersonatedRole' => $impersonatedRole,
            'adminBypassEnabled' => $adminBypassEnabled,
            'canToggleAdminBypass' => $canToggleAdminBypass,
        ]);
    }

    public function store(): void
    {
        $this->authorize(['VT_ADMIN']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=adminImpersonation&action=index');
        }

        $roleId = trim($_POST['role_id'] ?? '');
        if ($roleId === '') {
            $username = $_SESSION['user']['TenDangNhap'] ?? 'unknown';
            error_log(sprintf('[Impersonation] Executive %s cleared impersonation via form submission', $username));
            Impersonation::clear();
            $this->setFlash('success', 'Đã tắt chế độ giả lập.');
            $this->redirect('?controller=adminImpersonation&action=index');
        }

        $role = $this->roleModel->find($roleId);
        if (!$role) {
            $this->setFlash('danger', 'Không tìm thấy vai trò để giả lập.');
            $this->redirect('?controller=adminImpersonation&action=index');
        }

        if ($role['IdVaiTro'] === 'VT_ADMIN') {
            $username = $_SESSION['user']['TenDangNhap'] ?? 'unknown';
            error_log(sprintf('[Impersonation] Admin %s reverted to original privileges', $username));
            Impersonation::clear();
            $this->setFlash('info', 'Đang sử dụng quyền quản trị hệ thống gốc.');
            $this->redirect('?controller=adminImpersonation&action=index');
        }

        Impersonation::setImpersonatedRole($role);
        $username = $_SESSION['user']['TenDangNhap'] ?? 'unknown';
        error_log(sprintf('[Impersonation] Admin %s started impersonating role %s', $username, $role['IdVaiTro']));

        $roleName = $role['TenVaiTro'] ?? $role['IdVaiTro'];
        $this->setFlash('success', 'Đang giả lập vai trò: ' . $roleName . '.');
        $this->redirect('?controller=adminImpersonation&action=index');
    }

    public function updateBypass(): void
    {
        $this->authorize(['VT_ADMIN']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=adminImpersonation&action=index');
        }

        $enabled = isset($_POST['admin_bypass']) && $_POST['admin_bypass'] === '1';
        $_SESSION['admin_bypass_enabled'] = $enabled;
        $this->setFlash('success', $enabled
            ? 'Đã bật toàn quyền quản trị hệ thống.'
            : 'Đã tắt toàn quyền quản trị, áp dụng giới hạn theo vai trò.');

        $this->redirect('?controller=adminImpersonation&action=index');
    }
}
