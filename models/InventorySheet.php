<?php

class InventorySheet extends BaseModel
{
    protected string $table = 'phieu';
    protected string $primaryKey = 'IdPhieu';
    private ?array $columnCache = null;
    private bool $allowOptionalColumns = true;

    /**
     * Lấy danh sách phiếu nhập/xuất kho cùng thông tin bổ sung.
     */
    public function getDocuments(?string $filterType = null, int $limit = 50): array
    {
        $conditions = [];
        $params = [];

        if ($filterType === 'inbound') {
            $conditions[] = 'PHIEU.LoaiPhieu LIKE :inboundType';
            $params[':inboundType'] = 'Phiếu nhập%';
        } elseif ($filterType === 'outbound') {
            $conditions[] = 'PHIEU.LoaiPhieu LIKE :outboundType';
            $params[':outboundType'] = 'Phiếu xuất%';
        }

        $whereClause = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $selectFields = $this->buildSelectFields();

        $sql = 'SELECT
                    ' . implode(",\n                    ", $selectFields) . ',
                    KHO.TenKho,
                    NV_LAP.HoTen AS NguoiLap,
                    NV_XN.HoTen AS NguoiXacNhan,
                    COALESCE(item_stats.total_items, 0) AS TongMatHang,
                    COALESCE(item_stats.total_quantity, 0) AS TongSoLuong,
                    COALESCE(item_stats.total_received, 0) AS TongThucNhan
                FROM PHIEU
                JOIN KHO ON KHO.IdKho = PHIEU.IdKho
                LEFT JOIN NHAN_VIEN NV_LAP ON NV_LAP.IdNhanVien = PHIEU.NHAN_VIENIdNhanVien
                LEFT JOIN NHAN_VIEN NV_XN ON NV_XN.IdNhanVien = PHIEU.NHAN_VIENIdNhanVien2
                LEFT JOIN (
                    SELECT
                        IdPhieu,
                        COUNT(DISTINCT IdLo) AS total_items,
                        SUM(SoLuong) AS total_quantity,
                        SUM(ThucNhan) AS total_received
                    FROM CT_PHIEU
                    GROUP BY IdPhieu
                ) AS item_stats ON item_stats.IdPhieu = PHIEU.IdPhieu
                ' . $whereClause . '
                ORDER BY PHIEU.NgayLP DESC, PHIEU.IdPhieu DESC
                LIMIT :limit';

        try {
            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            $rows = $stmt->fetchAll() ?: [];

            return array_map(fn(array $row) => $this->hydrateOptionalColumns($row), $rows);
        } catch (PDOException $e) {
            if ($this->shouldRetryWithoutOptional($e)) {
                $this->disableOptionalColumns();
                return $this->getDocuments($filterType, $limit);
            }
            throw $e;
        }
    }

    /**
     * Thống kê tổng quan về các phiếu kho.
     */
    public function getDocumentSummary(): array
    {
        $summarySql = 'SELECT
                            COUNT(*) AS total_documents,
                            SUM(CASE WHEN LoaiPhieu LIKE "Phiếu nhập%" THEN 1 ELSE 0 END) AS inbound_documents,
                            SUM(CASE WHEN LoaiPhieu LIKE "Phiếu xuất%" THEN 1 ELSE 0 END) AS outbound_documents,
                            SUM(TongTien) AS total_value
                        FROM PHIEU';

        $summary = $this->db->query($summarySql)->fetch() ?: [];

        $trendSql = 'SELECT
                            DATE_FORMAT(NgayLP, "%Y-%m") AS thang,
                            COUNT(*) AS so_phieu,
                            SUM(TongTien) AS tong_tien
                        FROM PHIEU
                        GROUP BY DATE_FORMAT(NgayLP, "%Y-%m")
                        ORDER BY thang DESC
                        LIMIT 6';

        $trend = $this->db->query($trendSql)->fetchAll();

        return [
            'total_documents' => (int) ($summary['total_documents'] ?? 0),
            'inbound_documents' => (int) ($summary['inbound_documents'] ?? 0),
            'outbound_documents' => (int) ($summary['outbound_documents'] ?? 0),
            'total_value' => (float) ($summary['total_value'] ?? 0),
            'monthly_trend' => $trend,
        ];
    }

    public function findDocument(string $id): ?array
    {
        $selectFields = $this->buildSelectFields();

        $sql = 'SELECT
                    ' . implode(",\n                    ", $selectFields) . ',
                    KHO.TenKho,
                    NV_LAP.HoTen AS NguoiLap,
                    NV_XN.HoTen AS NguoiXacNhan
                FROM PHIEU
                JOIN KHO ON KHO.IdKho = PHIEU.IdKho
                LEFT JOIN NHAN_VIEN NV_LAP ON NV_LAP.IdNhanVien = PHIEU.NHAN_VIENIdNhanVien
                LEFT JOIN NHAN_VIEN NV_XN ON NV_XN.IdNhanVien = PHIEU.NHAN_VIENIdNhanVien2
                WHERE PHIEU.IdPhieu = :id';

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            $document = $stmt->fetch();

            return $document ? $this->hydrateOptionalColumns($document) : null;
        } catch (PDOException $e) {
            if ($this->shouldRetryWithoutOptional($e)) {
                $this->disableOptionalColumns();
                return $this->findDocument($id);
            }
            throw $e;
        }
    }

    public function getFormOptions(): array
    {
        $warehouses = $this->db->query('SELECT IdKho, TenKho, TenLoaiKho FROM KHO ORDER BY TenKho')->fetchAll();
        $employees = $this->db->query('SELECT IdNhanVien, HoTen FROM NHAN_VIEN ORDER BY HoTen')->fetchAll();
        $types = $this->db->query('SELECT DISTINCT LoaiPhieu FROM PHIEU ORDER BY LoaiPhieu')->fetchAll(PDO::FETCH_COLUMN) ?: [];

        return [
            'warehouses' => $warehouses,
            'employees' => $employees,
            'types' => array_values(array_filter($types)),
        ];
    }

    public function createDocument(array $data): bool
    {
        $payload = $this->sanitizeDocumentPayload($data, true);
        return $this->create($payload);
    }

    public function updateDocument(string $id, array $data): bool
    {
        $payload = $this->sanitizeDocumentPayload($data);
        return $this->update($id, $payload);
    }

    public function deleteDocument(string $id): bool
    {
        $this->db->beginTransaction();

        try {
            $detailStmt = $this->db->prepare('DELETE FROM CT_PHIEU WHERE IdPhieu = :id');
            $detailStmt->bindValue(':id', $id);
            $detailStmt->execute();

            $headerStmt = $this->db->prepare('DELETE FROM PHIEU WHERE IdPhieu = :id');
            $headerStmt->bindValue(':id', $id);
            $headerStmt->execute();

            $this->db->commit();

            return $headerStmt->rowCount() > 0;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function generateDocumentId(?string $type = null): string
    {
        $prefix = 'PH';
        if ($type) {
            $normalized = function_exists('mb_strtolower') ? mb_strtolower($type, 'UTF-8') : strtolower($type);
            if (str_contains($normalized, 'nhập')) {
                $prefix = 'PN';
            } elseif (str_contains($normalized, 'xuất')) {
                $prefix = 'PX';
            }
        }

        return $prefix . date('YmdHis');
    }

    private function sanitizeDocumentPayload(array $data, bool $includeId = false): array
    {
        $fields = [
            'IdPhieu',
            'NgayLP',
            'NgayXN',
            'TongTien',
            'LoaiPhieu',
            'IdKho',
            'NHAN_VIENIdNhanVien',
            'NHAN_VIENIdNhanVien2',
            'LoaiDoiTac',
            'DoiTac',
            'SoThamChieu',
            'LyDo',
            'GhiChu',
        ];

        $payload = [];
        $available = $this->getColumnMap();

        foreach ($fields as $field) {
            if (!$includeId && $field === 'IdPhieu') {
                continue;
            }

            if (!isset($available[$field])) {
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

            if ($field === 'TongTien' && $value !== null) {
                $value = (int) $value;
                $value = (float) $value;
            }

            $payload[$field] = $value;
        }

        return $payload;
    }

    private function hydrateOptionalColumns(array $row): array
    {
        $optional = ['LoaiDoiTac', 'DoiTac', 'SoThamChieu', 'LyDo', 'GhiChu'];

        foreach ($optional as $field) {
            if (!array_key_exists($field, $row)) {
                $row[$field] = null;
            }
        }

        return $row;
    }

    private function buildSelectFields(): array
    {
        $base = [
            'PHIEU.IdPhieu',
            'PHIEU.NgayLP',
            'PHIEU.NgayXN',
            'PHIEU.TongTien',
            'PHIEU.LoaiPhieu',
            'PHIEU.IdKho',
            'PHIEU.NHAN_VIENIdNhanVien',
            'PHIEU.NHAN_VIENIdNhanVien2',
        ];

        $optionalColumns = ['LoaiDoiTac', 'DoiTac', 'SoThamChieu', 'LyDo', 'GhiChu'];
        $available = $this->getColumnMap();

        if ($this->allowOptionalColumns) {
            foreach ($optionalColumns as $col) {
                if (isset($available[$col])) {
                    $base[] = 'PHIEU.' . $col;
                }
            }
        }

        return $base;
    }

    private function getColumnMap(): array
    {
        if ($this->columnCache !== null) {
            return $this->columnCache;
        }

        $sql = 'SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = :table';
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':table', $this->table);
            $stmt->execute();

            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
        } catch (Throwable $e) {
            $columns = [];
        }

        $map = [];
        foreach ($columns as $column) {
            $map[$column] = true;
        }

        $this->columnCache = $map;

        return $this->columnCache;
    }

    private function shouldRetryWithoutOptional(PDOException $e): bool
    {
        return $e->getCode() === '42S22'
            || str_contains(strtolower($e->getMessage()), 'unknown column');
    }

    private function disableOptionalColumns(): void
    {
        $this->allowOptionalColumns = false;
        if ($this->columnCache !== null) {
            unset(
                $this->columnCache['LoaiDoiTac'],
                $this->columnCache['DoiTac'],
                $this->columnCache['SoThamChieu'],
                $this->columnCache['LyDo'],
                $this->columnCache['GhiChu']
            );
        }
    }
}
