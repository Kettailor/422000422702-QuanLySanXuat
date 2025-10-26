<?php

class InventoryLot extends BaseModel
{
    protected string $table = 'lo';
    protected string $primaryKey = 'IdLo';

    public function getLotsByWarehouse(string $warehouseId): array
    {
        $sql = 'SELECT
                    LO.IdLo,
                    LO.TenLo,
                    LO.SoLuong,
                    LO.LoaiLo,
                    LO.NgayTao,
                    LO.IdSanPham,
                    SAN_PHAM.TenSanPham,
                    SAN_PHAM.DonVi,
                    SAN_PHAM.MoTa,
                    COALESCE(doc_stats.total_documents, 0)       AS TongSoPhieuLienQuan,
                    COALESCE(doc_stats.total_quantity, 0)        AS TongSoLuongPhieu,
                    COALESCE(doc_stats.total_received, 0)        AS TongThucNhan,
                    doc_stats.last_document_date                 AS LanPhatSinhGanNhat
                FROM LO
                LEFT JOIN SAN_PHAM ON SAN_PHAM.IdSanPham = LO.IdSanPham
                LEFT JOIN (
                    SELECT
                        CT.IdLo,
                        COUNT(DISTINCT CT.IdPhieu) AS total_documents,
                        SUM(CT.SoLuong)           AS total_quantity,
                        SUM(CT.ThucNhan)          AS total_received,
                        MAX(PH.NgayLP)            AS last_document_date
                    FROM CT_PHIEU CT
                    JOIN PHIEU PH ON PH.IdPhieu = CT.IdPhieu
                    GROUP BY CT.IdLo
                ) AS doc_stats ON doc_stats.IdLo = LO.IdLo
                WHERE LO.IdKho = :warehouse
                ORDER BY LO.NgayTao DESC, LO.IdLo DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':warehouse', $warehouseId);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createLot(array $data): bool
    {
        $payload = $this->sanitizeLotPayload($data, true);

        return $this->create($payload);
    }

    public function generateLotId(?string $prefix = null): string
    {
        $prefix = $prefix ?: 'LO';

        return $prefix . date('YmdHis');
    }

    private function sanitizeLotPayload(array $data, bool $includeId = false): array
    {
        $fields = [
            'IdLo',
            'TenLo',
            'SoLuong',
            'NgayTao',
            'LoaiLo',
            'IdSanPham',
            'IdKho',
        ];

        $payload = [];

        foreach ($fields as $field) {
            if (!$includeId && $field === 'IdLo') {
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

            if ($field === 'SoLuong' && $value !== null) {
                $value = max(0, (int) $value);
            }

            if ($field === 'NgayTao' && $value === null) {
                $value = date('Y-m-d H:i:s');
            }

            if ($value !== null) {
                $payload[$field] = $value;
            }
        }

        if (!isset($payload['NgayTao'])) {
            $payload['NgayTao'] = date('Y-m-d H:i:s');
        }

        return $payload;
    }
}
