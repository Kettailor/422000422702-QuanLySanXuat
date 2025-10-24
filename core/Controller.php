<?php

abstract class Controller
{
    protected function currentUser(): ?array
    {
        return $_SESSION['user'] ?? null;
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

        if ($role === 'VT_ADMIN') {
            return;
        }

        if (!in_array($role, $allowedRoles, true)) {
            $this->setFlash('danger', 'Bạn không có quyền truy cập chức năng này.');
            $this->redirect('?controller=dashboard&action=index');
        }
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
        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/navbar/index.php';
        include $viewFile;
        include __DIR__ . '/../views/footer.php';
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
