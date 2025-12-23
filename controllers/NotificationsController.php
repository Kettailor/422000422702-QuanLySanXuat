<?php

class NotificationsController extends Controller
{
    private NotificationStore $notificationStore;

    public function __construct()
    {
        $this->notificationStore = new NotificationStore();
    }

    public function index(): void
    {
        $user = $this->currentUser();
        $employeeId = $user['IdNhanVien'] ?? null;
        $roleId = $user['ActualIdVaiTro'] ?? ($user['IdVaiTro'] ?? null);
        $entries = $this->loadNotifications($employeeId, $roleId);

        $this->render('notifications/index', [
            'title' => 'Thông báo',
            'notifications' => $entries,
        ]);
    }

    public function read(): void
    {
        $id = $_GET['id'] ?? null;
        $redirect = $_GET['redirect'] ?? '?controller=notifications&action=index';
        $user = $this->currentUser();
        $employeeId = $user['IdNhanVien'] ?? null;

        if ($id) {
            $this->notificationStore->markRead($id, $employeeId);
        }

        $this->redirect($redirect);
    }

    public function markAll(): void
    {
        $user = $this->currentUser();
        $employeeId = $user['IdNhanVien'] ?? null;
        $roleId = $user['ActualIdVaiTro'] ?? ($user['IdVaiTro'] ?? null);
        $this->notificationStore->markAllRead($employeeId, $roleId);
        $this->redirect('?controller=notifications&action=index');
    }

    private function loadNotifications(?string $employeeId, ?string $roleId): array
    {
        $entries = $this->notificationStore->readAll();
        $filtered = $this->notificationStore->filterForUser($entries, $employeeId, $roleId);

        usort($filtered, static function ($a, $b): int {
            $aTime = strtotime($a['created_at'] ?? '') ?: 0;
            $bTime = strtotime($b['created_at'] ?? '') ?: 0;
            return $bTime <=> $aTime;
        });

        return $filtered;
    }
}
