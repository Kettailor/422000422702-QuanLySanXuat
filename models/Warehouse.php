<?php

class Warehouse extends BaseModel
{
    private const DEFAULT_TYPE_KEY = 'material';

    private const WAREHOUSE_TYPES = [
        'material' => 'Kho nguyên liệu',
        'finished' => 'Kho thành phẩm',
        'quality' => 'Kho xử lý lỗi',
    ];

    protected string $table = 'kho';
    protected string $primaryKey = 'IdKho';

    /**
     * Lấy danh sách kho kèm theo thông tin quản kho và số liệu tổng hợp.
     */
    public function getWithSupervisor(): array
    {
        $sql = 'SELECT
                    KHO.IdKho,
                    KHO.TenKho,
                    KHO.TenLoaiKho,
                    KHO.DiaChi,
                    KHO.TongSLLo,
                    KHO.ThanhTien,
                    KHO.TrangThai,
                    KHO.TongSL,
                    KHO.IdXuong,
                    XUONG.TenXuong,
                    KHO.NHAN_VIEN_KHO_IdNhanVien,
                    NV.HoTen                                                   AS TenQuanKho,
                    COALESCE(lot_stats.total_lots, 0)                           AS SoLoDangQuanLy,
                    COALESCE(lot_stats.total_quantity, 0)                      AS TongSoLuongLo,
                    COALESCE(doc_stats.total_documents, 0)                     AS TongSoPhieu,
                    doc_stats.last_document_date                               AS LanNhapXuatGanNhat,
                    COALESCE(doc_stats.total_document_value, 0)                AS TongGiaTriPhieu,
                    COALESCE(doc_stats.month_document_value, 0)                AS GiaTriPhieuThang,
                    CASE
                        WHEN KHO.TongSL > 0 THEN ROUND((COALESCE(lot_stats.total_quantity, 0) / KHO.TongSL) * 100, 1)
                        ELSE 0
                    END                                                        AS TyLeSuDung
                FROM KHO
                LEFT JOIN NHAN_VIEN NV ON NV.IdNhanVien = KHO.NHAN_VIEN_KHO_IdNhanVien
                LEFT JOIN XUONG ON XUONG.IdXuong = KHO.IdXuong
                LEFT JOIN (
                    SELECT IdKho, COUNT(*) AS total_lots, SUM(SoLuong) AS total_quantity
                    FROM LO
                    GROUP BY IdKho
                ) AS lot_stats ON lot_stats.IdKho = KHO.IdKho
                LEFT JOIN (
                    SELECT
                        IdKho,
                        COUNT(*) AS total_documents,
                        MAX(NgayLP) AS last_document_date,
                        SUM(TongTien) AS total_document_value,
                        SUM(CASE WHEN DATE_FORMAT(NgayLP, "%Y-%m") = DATE_FORMAT(CURDATE(), "%Y-%m") THEN TongTien ELSE 0 END) AS month_document_value
                    FROM PHIEU
                    GROUP BY IdKho
                ) AS doc_stats ON doc_stats.IdKho = KHO.IdKho
                ORDER BY KHO.TenKho';

        $warehouses = $this->db->query($sql)->fetchAll();

        return array_map(function (array $warehouse): array {
            $warehouse['TenLoaiKho'] = $this->normalizeWarehouseType($warehouse['TenLoaiKho'] ?? null);

            return $warehouse;
        }, $warehouses);
    }

    public function findWithSupervisor(string $id): ?array
    {
        $sql = 'SELECT
                    KHO.IdKho,
                    KHO.TenKho,
                    KHO.TenLoaiKho,
                    KHO.DiaChi,
                    KHO.TongSLLo,
                    KHO.ThanhTien,
                    KHO.TrangThai,
                    KHO.TongSL,
                    KHO.IdXuong,
                    XUONG.TenXuong,
                    KHO.NHAN_VIEN_KHO_IdNhanVien,
                    NV.HoTen                                                   AS TenQuanKho,
                    COALESCE(lot_stats.total_lots, 0)                           AS SoLoDangQuanLy,
                    COALESCE(lot_stats.total_quantity, 0)                      AS TongSoLuongLo,
                    COALESCE(doc_stats.total_documents, 0)                     AS TongSoPhieu,
                    doc_stats.last_document_date                               AS LanNhapXuatGanNhat,
                    COALESCE(doc_stats.total_document_value, 0)                AS TongGiaTriPhieu,
                    COALESCE(doc_stats.month_document_value, 0)                AS GiaTriPhieuThang,
                    CASE
                        WHEN KHO.TongSL > 0 THEN ROUND((COALESCE(lot_stats.total_quantity, 0) / KHO.TongSL) * 100, 1)
                        ELSE 0
                    END                                                        AS TyLeSuDung
                FROM KHO
                LEFT JOIN NHAN_VIEN NV ON NV.IdNhanVien = KHO.NHAN_VIEN_KHO_IdNhanVien
                LEFT JOIN XUONG ON XUONG.IdXuong = KHO.IdXuong
                LEFT JOIN (
                    SELECT IdKho, COUNT(*) AS total_lots, SUM(SoLuong) AS total_quantity
                    FROM LO
                    GROUP BY IdKho
                ) AS lot_stats ON lot_stats.IdKho = KHO.IdKho
                LEFT JOIN (
                    SELECT
                        IdKho,
                        COUNT(*) AS total_documents,
                        MAX(NgayLP) AS last_document_date,
                        SUM(TongTien) AS total_document_value,
                        SUM(CASE WHEN DATE_FORMAT(NgayLP, "%Y-%m") = DATE_FORMAT(CURDATE(), "%Y-%m") THEN TongTien ELSE 0 END) AS month_document_value
                    FROM PHIEU
                    GROUP BY IdKho
                ) AS doc_stats ON doc_stats.IdKho = KHO.IdKho
                WHERE KHO.IdKho = :id
                LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $result = $stmt->fetch();

        if (!$result) {
            return null;
        }

        $result['TenLoaiKho'] = $this->normalizeWarehouseType($result['TenLoaiKho'] ?? null);

        return $result;
    }

    /**
     * Tính toán số liệu tổng quan cho danh sách kho đã truy vấn.
     */
    public function getWarehouseSummary(?array $warehouses = null): array
    {
        $warehouses ??= $this->getWithSupervisor();

        $summary = [
            'total_warehouses' => count($warehouses),
            'active_warehouses' => 0,
            'inactive_warehouses' => 0,
            'total_capacity' => 0,
            'total_inventory_value' => 0.0,
            'total_lots' => 0,
            'total_quantity' => 0,
            'by_type' => $this->getEmptyTypeSummary(),
        ];

        foreach ($warehouses as $warehouse) {
            $typeKey = $this->resolveWarehouseTypeKey($warehouse['TenLoaiKho'] ?? null);
            $typeSummary = &$summary['by_type'][$typeKey];

            $summary['total_capacity'] += (int) ($warehouse['TongSL'] ?? 0);
            $summary['total_inventory_value'] += (float) ($warehouse['ThanhTien'] ?? 0);
            $summary['total_lots'] += (int) ($warehouse['SoLoDangQuanLy'] ?? ($warehouse['TongSLLo'] ?? 0));
            $summary['total_quantity'] += (int) ($warehouse['TongSoLuongLo'] ?? 0);

            $typeSummary['count']++;
            $typeSummary['total_capacity'] += (int) ($warehouse['TongSL'] ?? 0);
            $typeSummary['total_inventory_value'] += (float) ($warehouse['ThanhTien'] ?? 0);
            $typeSummary['total_lots'] += (int) ($warehouse['SoLoDangQuanLy'] ?? ($warehouse['TongSLLo'] ?? 0));
            $typeSummary['total_quantity'] += (int) ($warehouse['TongSoLuongLo'] ?? 0);

            if ($this->isActiveWarehouse($warehouse['TrangThai'] ?? null)) {
                $summary['active_warehouses']++;
                $typeSummary['active_warehouses']++;
            }
            unset($typeSummary);
        }

        $summary['inactive_warehouses'] = max(
            0,
            $summary['total_warehouses'] - $summary['active_warehouses']
        );

        $summary['total_inventory_value'] = round($summary['total_inventory_value'], 2);
        $summary['total_capacity'] = (int) $summary['total_capacity'];
        $summary['total_lots'] = (int) $summary['total_lots'];
        $summary['total_quantity'] = (int) $summary['total_quantity'];

        foreach ($summary['by_type'] as $key => $typeSummary) {
            $summary['by_type'][$key]['total_inventory_value'] = round($typeSummary['total_inventory_value'], 2);
            $summary['by_type'][$key]['total_capacity'] = (int) $typeSummary['total_capacity'];
            $summary['by_type'][$key]['total_lots'] = (int) $typeSummary['total_lots'];
            $summary['by_type'][$key]['total_quantity'] = (int) $typeSummary['total_quantity'];
        }

        return $summary;
    }

    public function getStatusOptions(): array
    {
        return ['Đang sử dụng', 'Tạm dừng', 'Bảo trì'];
    }

    public function findFinishedWarehouseByWorkshop(?string $workshopId): ?array
    {
        if (!$workshopId) {
            return null;
        }

        $sql = 'SELECT *
                FROM kho
                WHERE IdXuong = :workshopId
                  AND TenLoaiKho LIKE :type
                ORDER BY IdKho
                LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':workshopId', $workshopId);
        $stmt->bindValue(':type', '%Thành phẩm%');
        $stmt->execute();

        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function getFormOptions(): array
    {
        $workshops = $this->db->query('SELECT IdXuong, TenXuong FROM XUONG ORDER BY TenXuong')->fetchAll();
        $managers = $this->db->query('SELECT IdNhanVien, HoTen, ChucVu FROM NHAN_VIEN ORDER BY HoTen')->fetchAll();

        return [
            'workshops' => $workshops,
            'managers' => $managers,
            'statuses' => $this->getStatusOptions(),
            'types' => $this->getWarehouseTypeOptions(),
        ];
    }

    public function getWarehouseTypeOptions(): array
    {
        return self::WAREHOUSE_TYPES;
    }

    public function groupWarehousesByType(array $warehouses, ?array $typeSummary = null): array
    {
        $emptyTypeSummary = $this->getEmptyTypeSummary();
        $groups = [];
        foreach (self::WAREHOUSE_TYPES as $key => $label) {
            $groups[$key] = [
                'key' => $key,
                'label' => $label,
                'description' => $this->getWarehouseTypeDescription($key),
                'warehouses' => [],
                'statistics' => $typeSummary[$key] ?? $emptyTypeSummary[$key],
            ];
        }

        foreach ($warehouses as $warehouse) {
            $typeKey = $this->resolveWarehouseTypeKey($warehouse['TenLoaiKho'] ?? null);
            $groups[$typeKey]['warehouses'][] = $warehouse;
        }

        return $groups;
    }

    public function createWarehouse(array $data): bool
    {
        $payload = $this->sanitizeWarehousePayload($data, true);

        return $this->create($payload);
    }

    public function updateWarehouse(string $id, array $data): bool
    {
        $payload = $this->sanitizeWarehousePayload(array_merge($data, ['IdKho' => $id]));

        return $this->update($id, $payload);
    }

    public function generateWarehouseId(): string
    {
        return 'KHO' . date('YmdHis');
    }

    private function sanitizeWarehousePayload(array $data, bool $includeId = false): array
    {
        $fields = [
            'IdKho',
            'TenKho',
            'TenLoaiKho',
            'DiaChi',
            'TongSLLo',
            'ThanhTien',
            'TrangThai',
            'TongSL',
            'IdXuong',
            'NHAN_VIEN_KHO_IdNhanVien',
        ];

        $payload = [];

        foreach ($fields as $field) {
            if (!$includeId && $field === 'IdKho') {
                continue;
            }

            $value = $data[$field] ?? null;

            if ($field === 'IdKho') {
                $value = $value ?: $this->generateWarehouseId();
            }

            if (is_string($value)) {
                $value = trim($value);
                if ($value === '') {
                    $value = null;
                }
            }

            switch ($field) {
                case 'TongSLLo':
                case 'TongSL':
                    $payload[$field] = $value !== null ? max(0, (int) $value) : 0;
                    break;
                case 'ThanhTien':
                    $payload[$field] = $value !== null ? max(0, (int) $value) : 0;
                    break;
                case 'TenLoaiKho':
                    $payload[$field] = $this->normalizeWarehouseType($value);
                    break;
                case 'TrangThai':
                    $payload[$field] = $this->normalizeStatus($value);
                    break;
                default:
                    if ($value !== null) {
                        $payload[$field] = $value;
                    }
                    break;
            }
        }

        return $payload;
    }

    private function getEmptyTypeSummary(): array
    {
        $summary = [];
        foreach (self::WAREHOUSE_TYPES as $key => $label) {
            $summary[$key] = [
                'label' => $label,
                'count' => 0,
                'active_warehouses' => 0,
                'total_capacity' => 0,
                'total_inventory_value' => 0.0,
                'total_lots' => 0,
                'total_quantity' => 0,
            ];
        }

        return $summary;
    }

    private function getWarehouseTypeDescription(string $key): string
    {
        return match ($key) {
            'material' => 'Quản lý nhập kho nguyên vật liệu đầu vào cho sản xuất.',
            'finished' => 'Lưu trữ thành phẩm đã hoàn thiện chờ xuất bán.',
            'quality' => 'Tiếp nhận sản phẩm lỗi cần xử lý và phân loại.',
            default => 'Kho tổng hợp theo loại hàng hóa.',
        };
    }

    private function resolveWarehouseTypeKey(?string $value): string
    {
        if ($value === null || $value === '') {
            return self::DEFAULT_TYPE_KEY;
        }

        $normalized = $this->normalizeString($value);

        if (isset(self::WAREHOUSE_TYPES[$normalized])) {
            return $normalized;
        }

        foreach (self::WAREHOUSE_TYPES as $key => $label) {
            if ($normalized === $this->normalizeString($label)) {
                return $key;
            }
        }

        if (
            str_contains($normalized, 'nguyên') ||
            str_contains($normalized, 'nguyen') ||
            str_contains($normalized, 'liệu') ||
            str_contains($normalized, 'lieu')
        ) {
            return 'material';
        }

        if (
            (str_contains($normalized, 'thành') || str_contains($normalized, 'thanh')) &&
            (str_contains($normalized, 'phẩm') || str_contains($normalized, 'pham'))
        ) {
            return 'finished';
        }

        if (
            str_contains($normalized, 'lỗi') ||
            str_contains($normalized, 'loi') ||
            str_contains($normalized, 'xử lý') ||
            str_contains($normalized, 'xu ly')
        ) {
            return 'quality';
        }

        return self::DEFAULT_TYPE_KEY;
    }

    private function normalizeWarehouseType(?string $type): string
    {
        $typeKey = $this->resolveWarehouseTypeKey($type);

        return self::WAREHOUSE_TYPES[$typeKey];
    }

    private function normalizeString(string $value): string
    {
        $normalized = function_exists('mb_strtolower')
            ? mb_strtolower($value, 'UTF-8')
            : strtolower($value);

        return trim(preg_replace('/\s+/', ' ', $normalized));
    }

    private function normalizeStatus(?string $status): string
    {
        $statusOptions = $this->getStatusOptions();

        if ($status === null) {
            return $statusOptions[0];
        }

        foreach ($statusOptions as $option) {
            if ($option === $status) {
                return $option;
            }
        }

        return $statusOptions[0];
    }

    /**
     * Xác định trạng thái hoạt động của kho theo chuỗi mô tả trong cơ sở dữ liệu.
     */
    private function isActiveWarehouse(?string $status): bool
    {
        if ($status === null || $status === '') {
            return false;
        }

        $normalized = function_exists('mb_strtolower')
            ? mb_strtolower($status, 'UTF-8')
            : strtolower($status);

        return in_array($normalized, ['đang sử dụng', 'dang su dung'], true);
    }
}
