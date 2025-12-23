<?php

class AdminController extends Controller
{
    public function __construct()
    {
        $this->authorize(['VT_ADMIN', 'VT_BAN_GIAM_DOC']);
    }

    public function ticket(): void
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

        $tickets = Ticket::getTickets($page, $limit);

        $this->render('admin/ticket', [
            'title' => 'Yêu cầu hỗ trợ',
            'tickets' => $tickets,
        ]);
    }

    public function closeTicket(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ticketId = $_POST['ticket_id'] ?? null;
            $response = trim($_POST['response'] ?? '');
            $status = trim($_POST['status'] ?? 'close');

            if ($ticketId) {
                Ticket::closeTicket($ticketId, $response !== '' ? $response : null, $status);
                $this->setFlash('success', 'Cập nhật yêu cầu thành công.');
            }
        }
        $this->redirect('?controller=admin&action=ticket');
    }
}
