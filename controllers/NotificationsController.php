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
        $entries = $this->loadNotifications($employeeId);

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
        $this->notificationStore->markAllRead($employeeId);
        $this->redirect('?controller=notifications&action=index');
    }

    private function loadNotifications(?string $employeeId): array
    {
        $entries = $this->notificationStore->readAll();
        $filtered = array_values(array_filter($entries, static function ($entry) use ($employeeId): bool {
            if (!is_array($entry)) {
                return false;
            }
            $recipient = $entry['recipient'] ?? null;
            if (!$recipient) {
                return true;
            }
            return $employeeId !== null && $recipient === $employeeId;
        }));

        usort($filtered, static function ($a, $b): int {
            $aTime = strtotime($a['created_at'] ?? '') ?: 0;
            $bTime = strtotime($b['created_at'] ?? '') ?: 0;
            return $bTime <=> $aTime;
        });

        return $filtered;
    }
}
