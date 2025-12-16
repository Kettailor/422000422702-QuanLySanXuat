<?php

class InventorySheetDetail extends BaseModel
{
    protected string $table = 'ct_phieu';
    protected string $primaryKey = 'IdTTCTPhieu';

    public function createDetail(array $data): bool
    {
        $payload = $this->sanitizeDetailPayload($data, true);

        return $this->create($payload);
    }

    public function getDetailsByDocument(string $documentId): array
    {
        $stmt = $this->db->prepare('SELECT IdTTCTPhieu, DonViTinh, SoLuong, ThucNhan, IdPhieu, IdLo FROM CT_PHIEU WHERE IdPhieu = :id');
        $stmt->bindValue(':id', $documentId);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function sanitizeDetailPayload(array $data, bool $includeId = false): array
    {
        $fields = [
            'IdTTCTPhieu',
            'DonViTinh',
            'SoLuong',
            'ThucNhan',
            'IdPhieu',
            'IdLo',
        ];

        $payload = [];

        foreach ($fields as $field) {
            if (!$includeId && $field === 'IdTTCTPhieu') {
                continue;
            }

            if (!array_key_exists($field, $data)) {
                continue;
            }

            $value = $data[$field];

            if (is_string($value)) {
                $value = trim($value);
            }

            if ($value === '') {
                $value = null;
            }

            if (in_array($field, ['SoLuong', 'ThucNhan'], true) && $value !== null) {
                $value = max(0, (int) $value);
            }

            if ($value !== null) {
                $payload[$field] = $value;
            }
        }

        return $payload;
    }
}
