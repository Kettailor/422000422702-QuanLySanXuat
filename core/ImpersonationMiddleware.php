<?php

class ImpersonationMiddleware
{
    public static function handle(): void
    {
        $action = $_GET['impersonation'] ?? null;
        if ($action !== 'stop') {
            return;
        }

        $user = $_SESSION['user'] ?? null;
        if (!$user || ($user['IdVaiTro'] ?? null) !== 'VT_ADMIN') {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Bạn không có quyền sử dụng chức năng giả lập.',
            ];
            header('Location: ?controller=dashboard&action=index');
            exit;
        }

        $impersonated = Impersonation::getImpersonatedRole();
        if ($impersonated) {
            $username = $user['TenDangNhap'] ?? 'unknown';
            $roleId = $impersonated['IdVaiTro'];
            error_log(sprintf('[Impersonation] Admin %s stopped impersonating role %s', $username, $roleId));
        }

        Impersonation::clear();

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Đã khôi phục quyền quản trị đầy đủ.',
        ];

        $redirect = $_SERVER['HTTP_REFERER'] ?? '?controller=dashboard&action=index';
        header('Location: ' . $redirect);
        exit;
    }
}
