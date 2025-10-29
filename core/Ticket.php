<?php

class Ticket
{
    public static string $ticketFile = __DIR__ . '/../storage/ticket.json';

    public static function createTicket($message): void
    {
        $date = date('Y-m-d H:i:s');
        $user = $_SESSION['user']['TenDangNhap'] ?? 'anonymous';
        $ticket = ['date' => $date, 'user' => $user, 'request' => $message, 'status' => 'open'];

        $tickets = [];
        if (file_exists(self::$ticketFile)) {
            $content = file_get_contents(self::$ticketFile);
            $ticketId = count(json_decode($content, true)['tickets'] ?? []);
            $ticket['id'] = $ticketId;
            $tickets = json_decode($content, true)['tickets'] ?? [];
        }
        $tickets[] = $ticket;
        file_put_contents(self::$ticketFile, json_encode(['tickets' => $tickets], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public static function closeTicket($ticketId): void
    {
        $tickets = [];
        if (file_exists(self::$ticketFile)) {
            $content = file_get_contents(self::$ticketFile);
            $tickets = json_decode($content, true)['tickets'] ?? [];
        }
        foreach ($tickets as &$ticket) {
            if (isset($ticket['id']) && $ticket['id'] == $ticketId) {
                $ticket['status'] = 'close';
                break;
            }
        }
        file_put_contents(self::$ticketFile, json_encode(['tickets' => $tickets], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public static function getTickets(int $page = 1, int $limit = 20): array
    {
        if (!file_exists(self::$ticketFile)) {
            return ['tickets' => [], 'total_pages' => 0, 'page' => $page, 'limit' => $limit];
        }

        $content = file_get_contents(self::$ticketFile);
        $data = json_decode($content, true);
        $tickets = $data['tickets'] ?? [];

        $tickets = array_reverse($tickets);
        $total = count($tickets);
        $total_pages = $limit > 0 ? (int)ceil($total / $limit) : 1;
        $offset = ($page - 1) * $limit;
        $paged_tickets = array_slice($tickets, $offset, $limit);

        return [
            'tickets' => $paged_tickets,
            'total_pages' => $total_pages,
            'page' => $page,
            'limit' => $limit,
        ];
    }

}
