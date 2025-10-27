<?php

class Logger
{
    public static string $logFile = __DIR__ . '/../storage/app.log';
    public static string $loginLogFile = __DIR__ . '/../storage/login.log';

    private static function log($message, string $level = 'INFO'): void
    {
        $date = date('Y-m-d H:i:s');
        $user = $_SESSION['user']['TenDangNhap'] ?? 'anonymous';
        $formattedMessage = "[$date] [$level] [$user] $message" . PHP_EOL;
        error_log(self::$logFile);
        file_put_contents(self::$logFile, $formattedMessage, FILE_APPEND);
    }

    public static function getLog(int $page = 1, int $limit = 10): array
    {
        if (!file_exists(self::$logFile)) {
            return [
                'logs' => [],
                'total_pages' => 0,
                'page' => $page,
                'limit' => $limit,
            ];
        }

        $lines = file(self::$logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $logs = [];
        foreach ($lines as $line) {
            if (preg_match('/^\[(.*?)\] \[(.*?)\] \[(.*?)\] (.*)$/', $line, $matches)) {
                $logs[] = [
                    'date' => $matches[1],
                    'level' => $matches[2],
                    'actor' => $matches[3],
                    'action' => $matches[4],
                ];
            }
        }

        $logs = array_reverse($logs);
        $total = count($logs);
        $total_pages = $limit > 0 ? (int)ceil($total / $limit) : 1;
        $offset = ($page - 1) * $limit;
        $paged_logs = array_slice($logs, $offset, $limit);

        return [
            'logs' => $paged_logs,
            'total_pages' => $total_pages,
            'page' => $page,
            'limit' => $limit,
        ];
    }

    public static function info($message): void
    {
        self::log($message, 'INFO');
    }

    public static function error($message): void
    {
        self::log($message, 'ERROR');
    }

    public static function warn($message): void
    {
        self::log($message, 'WARN');
    }

    public static function debug($message): void
    {
        self::log($message, 'DEBUG');
    }

    public static function login($username): void
    {
        $date = date('Y-m-d');
        $formattedMessage = "[$date] User '$username' logged in." . PHP_EOL;
        file_put_contents(self::$loginLogFile, $formattedMessage, FILE_APPEND);
    }

    public static function getLoginLog(string $startDate, string $endDate): array
    {
        if (!file_exists(self::$loginLogFile)) {
            return [];
        }

        $start = DateTime::createFromFormat('Y-m-d H:i:s', $startDate . ' 00:00:00');
        $end = DateTime::createFromFormat('Y-m-d H:i:s', $endDate . ' 23:59:59');
        if (!$start || !$end) {
            return [];
        }

        $lines = file(self::$loginLogFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $logs = [];
        foreach ($lines as $line) {
            if (preg_match('/^\[(.*?)\] User \'(.*?)\' logged in\.$/', $line, $matches)) {
                $logDate = DateTime::createFromFormat('Y-m-d H:i:s', trim($matches[1]));
                if ($logDate && $logDate >= $start && $logDate <= $end) {
                    $logs[] = [
                        'day' => $logDate->format('Y-m-d'),
                        'date' => $matches[1],
                        'username' => $matches[2],
                    ];
                }
            }
        }
        return $logs;
    }
}
