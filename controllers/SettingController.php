<?php

class SettingController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index(): void
    {
        $this->render('setting/index', [
            'title' => 'Cài đặt',
        ]);
    }

    public function changePassword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current-password'] ?? '';
            $newPassword = $_POST['new-password'] ?? '';

            if ($currentPassword !== $_SESSION['user']['MatKhau']
                && !password_verify($currentPassword, $_SESSION['user']['MatKhau'])
            ) {
                $this->setFlash('danger', 'Mật khẩu hiện tại không đúng.');
                $this->redirect('?controller=setting&action=changePassword');
            }

            if ($currentPassword === $newPassword) {
                $this->setFlash('danger', 'Mật khẩu mới không được trùng với mật khẩu hiện tại.');
                $this->redirect('?controller=setting&action=changePassword');
            }

            try {
                $userId = $_SESSION['user']['IdNguoiDung'];
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                $this->userModel->update($userId, ['MatKhau' => $hashedPassword]);
                $this->setFlash('success', 'Đổi mật khẩu thành công. Vui lòng đăng nhập lại.');
                $this->redirect('?controller=auth&action=logout');
            } catch (Throwable $e) {
                Logger::error('Lỗi khi đổi mật khẩu cho tài khoản ' . $userId . ': ' . $e->getMessage());
                $this->setFlash('danger', 'Không thể đổi mật khẩu, vui lòng kiểm tra log để biết thêm chi tiết.');
                $this->redirect('?controller=setting&action=changePassword');
            }

        }

        $this->render('setting/change-password', [
            'title' => 'Đổi mật khẩu',
        ]);
    }
}
