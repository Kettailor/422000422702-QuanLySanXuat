<?php

abstract class Controller
{
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
