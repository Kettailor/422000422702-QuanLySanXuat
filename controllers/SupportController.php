<?php

class SupportController extends Controller
{
    public function __construct()
    {
        if (!$this->currentUser()) {
            $this->setFlash('danger', 'Vui lòng đăng nhập để tiếp tục.');
            $this->redirect('?controller=auth&action=login');
        }
    }

    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subject = trim($_POST['subject'] ?? '');
            $message = trim($_POST['message'] ?? '');
            $priority = trim($_POST['priority'] ?? 'normal');

            if ($subject === '' && $message === '') {
                $this->setFlash('danger', 'Vui lòng nhập tiêu đề hoặc nội dung yêu cầu.');
                $this->redirect('?controller=support&action=index');
                return;
            }

            $payload = sprintf(
                "[%s] %s\n%s",
                strtoupper($priority),
                $subject !== '' ? $subject : 'Yêu cầu hỗ trợ',
                $message,
            );

            Ticket::createTicket($payload);
            $this->setFlash('success', 'Đã gửi yêu cầu hỗ trợ. Bộ phận admin sẽ phản hồi sớm.');
            $this->redirect('?controller=support&action=index');
            return;
        }

        $this->render('support/index', [
            'title' => 'Yêu cầu hỗ trợ',
        ]);
    }
}
