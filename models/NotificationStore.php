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

