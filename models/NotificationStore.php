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

        $normalized = array_map(fn($entry) => $this->normalizeEntry($entry), $entries);
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

            $readAt = date(DATE_ATOM);
            if ($recipient !== null && $recipient !== '') {
                $entry['read_by'] = $this->normalizeReadBy($entry['read_by'] ?? null);
                $entry['read_by'][$recipient] = $readAt;
                if (!empty($entry['recipient']) && $entry['recipient'] === $recipient) {
                    $entry['read_at'] = $readAt;
                    $entry['is_read'] = true;
                }
            } else {
                $entry['read_at'] = $readAt;
                $entry['is_read'] = true;
            }
            $updated = true;
            break;
        }
        unset($entry);

        if ($updated) {
            $this->write($entries);
        }
    }

    public function markAllRead(?string $recipient = null, ?string $roleId = null): void
    {
        $entries = $this->readAll();
        $updated = false;

        foreach ($entries as &$entry) {
            if (!is_array($entry)) {
                continue;
            }
            if (!$this->matchesRecipient($entry, $recipient, $roleId)) {
                continue;
            }
            if ($recipient !== null && $recipient !== '') {
                $readBy = $this->normalizeReadBy($entry['read_by'] ?? null);
                if (isset($readBy[$recipient])) {
                    continue;
                }
            } elseif (!empty($entry['is_read']) || !empty($entry['read_at'])) {
                continue;
            }
            $readAt = date(DATE_ATOM);
            if ($recipient !== null && $recipient !== '') {
                $entry['read_by'] = $this->normalizeReadBy($entry['read_by'] ?? null);
                $entry['read_by'][$recipient] = $readAt;
                if (!empty($entry['recipient']) && $entry['recipient'] === $recipient) {
                    $entry['read_at'] = $readAt;
                    $entry['is_read'] = true;
                }
            } else {
                $entry['read_at'] = $readAt;
                $entry['is_read'] = true;
            }
            $updated = true;
        }
        unset($entry);

        if ($updated) {
            $this->write($entries);
        }
    }

    public function deleteForUser(string $notificationId, ?string $recipientId, ?string $roleId): void
    {
        if ($recipientId === null || $recipientId === '') {
            return;
        }

        $entries = $this->readAll();
        $updated = false;

        foreach ($entries as &$entry) {
            if (!is_array($entry)) {
                continue;
            }
            if (($entry['id'] ?? null) !== $notificationId) {
                continue;
            }
            if (!$this->matchesRecipient($entry, $recipientId, $roleId)) {
                continue;
            }

            $entry['deleted_by'] = $this->normalizeDeletedBy($entry['deleted_by'] ?? null);
            $entry['deleted_by'][$recipientId] = date(DATE_ATOM);
            $updated = true;
            break;
        }
        unset($entry);

        if ($updated) {
            $this->write($entries);
        }
    }

    public function filterForUser(array $entries, ?string $recipientId, ?string $roleId): array
    {
        $filtered = array_values(array_filter($entries, function ($entry) use ($recipientId, $roleId): bool {
            if (!is_array($entry)) {
                return false;
            }

            if (!$this->matchesRecipient($entry, $recipientId, $roleId)) {
                return false;
            }

            if ($recipientId !== null && $recipientId !== '') {
                $deletedBy = $this->normalizeDeletedBy($entry['deleted_by'] ?? null);
                if (isset($deletedBy[$recipientId])) {
                    return false;
                }
            }

            return true;
        }));

        return array_values(array_map(function (array $entry) use ($recipientId): array {
            $readBy = $this->normalizeReadBy($entry['read_by'] ?? null);
            $isRead = false;
            $readAt = null;

            if ($recipientId !== null && $recipientId !== '') {
                if (isset($readBy[$recipientId])) {
                    $isRead = true;
                    $readAt = $readBy[$recipientId];
                } elseif (!empty($entry['recipient']) && $entry['recipient'] === $recipientId) {
                    if (!empty($entry['is_read']) || !empty($entry['read_at'])) {
                        $isRead = true;
                        $readAt = $entry['read_at'] ?? null;
                    }
                }
            } else {
                $isRead = !empty($entry['is_read']) || !empty($entry['read_at']);
                $readAt = $entry['read_at'] ?? null;
            }

            $entry['is_read'] = $isRead;
            if ($readAt !== null) {
                $entry['read_at'] = $readAt;
            } else {
                unset($entry['read_at']);
            }

            return $entry;
        }, $filtered));
    }

    public function resolveScope(array $entry): string
    {
        $recipient = $entry['recipient'] ?? null;
        $recipientRole = $entry['recipient_role'] ?? null;

        if ($recipient) {
            return 'personal';
        }

        if ($recipientRole) {
            return 'role';
        }

        return 'general';
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
        if (!isset($entry['read_by']) || !is_array($entry['read_by'])) {
            $entry['read_by'] = [];
        }
        if (!isset($entry['deleted_by']) || !is_array($entry['deleted_by'])) {
            $entry['deleted_by'] = [];
        }

        return $entry;
    }

    private function matchesRecipient(array $entry, ?string $recipientId, ?string $roleId): bool
    {
        $recipient = $entry['recipient'] ?? null;
        if ($recipient !== null && $recipient !== '') {
            if ($recipientId === null || $recipientId === '') {
                return false;
            }
            if ($recipient !== $recipientId) {
                return false;
            }
        }

        $recipientRole = $entry['recipient_role'] ?? null;
        if ($recipientRole !== null && $recipientRole !== '') {
            if ($roleId === null || $roleId === '') {
                return false;
            }
            if ($recipientRole !== $roleId) {
                return false;
            }
        }

        return true;
    }

    private function normalizeReadBy(mixed $readBy): array
    {
        if (!is_array($readBy)) {
            return [];
        }

        $normalized = [];
        foreach ($readBy as $key => $value) {
            if (is_int($key)) {
                $recipientId = (string) $value;
                if ($recipientId !== '') {
                    $normalized[$recipientId] = $normalized[$recipientId] ?? date(DATE_ATOM);
                }
                continue;
            }

            $recipientId = (string) $key;
            if ($recipientId !== '') {
                $normalized[$recipientId] = is_string($value) && $value !== '' ? $value : date(DATE_ATOM);
            }
        }

        return $normalized;
    }

    private function normalizeDeletedBy(mixed $deletedBy): array
    {
        if (!is_array($deletedBy)) {
            return [];
        }

        $normalized = [];
        foreach ($deletedBy as $key => $value) {
            if (is_int($key)) {
                $recipientId = (string) $value;
                if ($recipientId !== '') {
                    $normalized[$recipientId] = $normalized[$recipientId] ?? date(DATE_ATOM);
                }
                continue;
            }

            $recipientId = (string) $key;
            if ($recipientId !== '') {
                $normalized[$recipientId] = is_string($value) && $value !== '' ? $value : date(DATE_ATOM);
            }
        }

        return $normalized;
    }
}
