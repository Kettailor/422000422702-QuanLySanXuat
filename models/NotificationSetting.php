<?php

class NotificationSetting extends BaseModel
{
    protected string $table = 'cau_hinh_thong_bao';
    protected string $primaryKey = 'MaCauHinh';

    public function getValue(string $key): ?string
    {
        $setting = $this->find($key);
        if (!$setting) {
            return null;
        }

        $value = $setting['GiaTri'] ?? null;
        return $value !== null ? (string) $value : null;
    }

    public function getRecipients(string $key): array
    {
        $raw = $this->getValue($key);
        if ($raw === null || $raw === '') {
            return [];
        }

        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            return array_values(array_filter(array_map('strval', $decoded), fn($item) => $item !== ''));
        }

        $parts = array_map('trim', explode(',', $raw));
        return array_values(array_filter($parts, fn($item) => $item !== ''));
    }
}
