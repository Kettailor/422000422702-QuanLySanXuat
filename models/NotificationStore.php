<?php

class NotificationStore
{
    private string $filePath;

    public function __construct(?string $filePath = null)
    {
        $this->filePath = $filePath ?? __DIR__ . '/../storage/notifications.json';
        $this->ensureStorage();
    }

    public function push(array $entry): void
    {
        $this->pushMany([$entry]);
    }

    public function pushMany(array $entries): void
    {
        if (empty($entries)) {
            return;
        }

        $normalized = array_map(fn ($entry) => $this->normalizeEntry($entry), $entries);
        $current = $this->readAll();
        $payload = array_merge($current, $normalized);

        $this->write($payload);
    }

    public function readAll(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $content = file_get_contents($this->filePath);
        if ($content === false || $content === '') {
            return [];
        }

        $decoded = json_decode($content, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function markRead(string $notificationId, ?string $recipient = null): void
    {
        $entries = $this->readAll();
        $updated = false;

        foreach ($entries as &$entry) {
            if (!is_array($entry)) {
                continue;
            }
            if (($entry['id'] ?? null) !== $notificationId) {
                continue;
            }
            if ($recipient !== null) {
                $entryRecipient = $entry['recipient'] ?? null;
                if ($entryRecipient !== null && $entryRecipient !== $recipient) {
                    continue;
                }
            }

            $entry['read_at'] = date(DATE_ATOM);
            $entry['is_read'] = true;
            $updated = true;
            break;
        }
        unset($entry);

        if ($updated) {
            $this->write($entries);
        }
    }

    public function markAllRead(?string $recipient = null): void
    {
        $entries = $this->readAll();
        $updated = false;

        foreach ($entries as &$entry) {
            if (!is_array($entry)) {
                continue;
            }
            $entryRecipient = $entry['recipient'] ?? null;
            if ($recipient !== null && $entryRecipient !== null && $entryRecipient !== $recipient) {
                continue;
            }
            if (!empty($entry['is_read']) || !empty($entry['read_at'])) {
                continue;
            }
            $entry['read_at'] = date(DATE_ATOM);
            $entry['is_read'] = true;
            $updated = true;
        }
        unset($entry);

        if ($updated) {
            $this->write($entries);
        }
    }

    private function ensureStorage(): void
    {
        $directory = dirname($this->filePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        if (!file_exists($this->filePath)) {
            $this->write([]);
        }
    }

    private function write(array $data): void
    {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            throw new RuntimeException('Không thể mã hóa dữ liệu thông báo.');
        }

        file_put_contents($this->filePath, $json, LOCK_EX);
    }

    private function normalizeEntry(array $entry): array
    {
        $entry['id'] = $entry['id'] ?? uniqid('NTF');
        $entry['created_at'] = $entry['created_at'] ?? date(DATE_ATOM);
        if (!isset($entry['metadata']) || !is_array($entry['metadata'])) {
            $entry['metadata'] = [];
        }

        return $entry;
    }
}
