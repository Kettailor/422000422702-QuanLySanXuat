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
        $this->authorize(['VT_BAN_GIAM_DOC']);

        $roles = $this->roleModel->all(200);
        $roles = array_filter($roles, fn (array $role) => $role['IdVaiTro'] !== 'VT_BAN_GIAM_DOC');
        $impersonatedRole = Impersonation::getImpersonatedRole();

        $this->render('admin/impersonate', [
            'title' => 'Giả lập vai trò',
            'roles' => $roles,
            'impersonatedRole' => $impersonatedRole,
        ]);
    }

    public function store(): void
    {
        $this->authorize(['VT_BAN_GIAM_DOC']);

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

        if ($role['IdVaiTro'] === 'VT_BAN_GIAM_DOC') {
            $username = $_SESSION['user']['TenDangNhap'] ?? 'unknown';
            error_log(sprintf('[Impersonation] Executive %s reverted to original privileges', $username));
            Impersonation::clear();
            $this->setFlash('info', 'Đang sử dụng quyền ban giám đốc gốc.');
            $this->redirect('?controller=adminImpersonation&action=index');
        }

        Impersonation::setImpersonatedRole($role);
        $username = $_SESSION['user']['TenDangNhap'] ?? 'unknown';
        error_log(sprintf('[Impersonation] Executive %s started impersonating role %s', $username, $role['IdVaiTro']));

        $roleName = $role['TenVaiTro'] ?? $role['IdVaiTro'];
        $this->setFlash('success', 'Đang giả lập vai trò: ' . $roleName . '.');
        $this->redirect('?controller=adminImpersonation&action=index');
    }
}
