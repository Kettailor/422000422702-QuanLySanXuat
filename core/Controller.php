<?php

abstract class Controller
{
    protected function currentUser(): ?array
    {
        $user = $_SESSION['user'] ?? null;

        if (!$user) {
            return null;
        }

        return Impersonation::applyToUser($user);
    }

    protected function authorize(array $allowedRoles): void
    {
        $user = $this->currentUser();

        if (!$user) {
            $this->setFlash('danger', 'Vui lòng đăng nhập để tiếp tục.');
            $this->redirect('?controller=auth&action=login');
        }

        $role = $user['IdVaiTro'] ?? null;
        if (!$role) {
            $this->setFlash('danger', 'Không xác định được vai trò người dùng.');
            $this->redirect('?controller=dashboard&action=index');
        }

        $actualRole = $user['ActualIdVaiTro'] ?? $role;
        $isAdmin = $actualRole === 'VT_ADMIN';
        $isImpersonating = !empty($user['IsImpersonating']);
        $adminFlow = $_SESSION['admin_flow'] ?? 'main';
        $adminBypassEnabled = $adminFlow === 'test';
        $adminMainRestricted = $isAdmin && !$isImpersonating && $adminFlow === 'main';

        if ($adminMainRestricted) {
                $allowedControllers = [
                    'dashboard',
                    'auth',
                    'setting',
                    'notifications',
                    'admin',
                    'adminImpersonation',
                    'account',
            ];
            $currentController = $_GET['controller'] ?? 'dashboard';
            if (!in_array($currentController, $allowedControllers, true)) {
                $this->setFlash('danger', 'Luồng chính chỉ cho phép chức năng cá nhân và quản trị.');
                $this->redirect('?controller=dashboard&action=index');
            }
        }

        if ($isAdmin && $adminBypassEnabled) {
            if ($isImpersonating) {
                $username = $user['TenDangNhap'] ?? 'unknown';
                error_log(sprintf(
                    '[Impersonation] Admin %s is impersonating role %s while accessing %s',
                    $username,
                    $role,
                    $_SERVER['REQUEST_URI'] ?? 'cli'
                ));

                if (in_array('VT_ADMIN', $allowedRoles, true)) {
                    return;
                }
            } else {
                return;
            }
        }

        if (!in_array($role, $allowedRoles, true)) {
            $this->setFlash('danger', 'Bạn không có quyền truy cập chức năng này.');
            $this->redirect('?controller=dashboard&action=index');
        }
    }

    protected function resolveAccessRole(array $user): ?string
    {
        $role = $user['ActualIdVaiTro'] ?? ($user['IdVaiTro'] ?? null);
        if (!$role) {
            return null;
        }

        $isAdmin = $role === 'VT_ADMIN';
        $isImpersonating = !empty($user['IsImpersonating']);
        $adminFlow = $_SESSION['admin_flow'] ?? 'main';

        if ($isAdmin && !$isImpersonating && $adminFlow === 'test') {
            return 'VT_BAN_GIAM_DOC';
        }

        return $role;
    }

    protected function render(string $view, array $data = []): void
    {
        $viewFile = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(404);
            echo '<h1>404 - View not found</h1>';
            return;
        }

        extract($data);

        $pageTitle = $data['title'] ?? 'Quản lý sản xuất';
        $flash = $this->getFlash();
        $currentUser = $this->currentUser();
        $notifications = [];
        if ($currentUser) {
            $employeeId = $currentUser['IdNhanVien'] ?? null;
            $roleId = $currentUser['ActualIdVaiTro'] ?? ($currentUser['IdVaiTro'] ?? null);
            try {
                $notificationStore = new NotificationStore();
                $notifications = $notificationStore->filterForUser(
                    $notificationStore->readAll(),
                    $employeeId,
                    $roleId
                );
            } catch (Throwable $exception) {
                $notifications = [];
            }
        }
        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/navbar/index.php';
        include $viewFile;
        include __DIR__ . '/../views/footer.php';
    }

    protected function render_pdf(string $view , array $data = []) : void {
        $viewFile = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(404);
            echo '<h1>404 - View not found</h1>';
            return;
        }

        extract($data);

        $pageTitle = $data['title'] ?? 'Quản lý sản xuất';
        $flash = $this->getFlash();
        $currentUser = $this->currentUser();
        $notifications = [];
        if ($currentUser) {
            $employeeId = $currentUser['IdNhanVien'] ?? null;
            $roleId = $currentUser['ActualIdVaiTro'] ?? ($currentUser['IdVaiTro'] ?? null);
            try {
                $notificationStore = new NotificationStore();
                $notifications = $notificationStore->filterForUser(
                    $notificationStore->readAll(),
                    $employeeId,
                    $roleId
                );
            } catch (Throwable $exception) {
                $notifications = [];
            }
        }

        include $viewFile;
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    protected function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    protected function getFlash(): ?array
    {
        if (!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }

        return null;
    }
}
