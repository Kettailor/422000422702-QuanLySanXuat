<?php

class Warehouse extends BaseModel
{
    protected string $table = 'KHO';
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

        return $this->db->query($sql)->fetchAll();
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

        return $result ?: null;
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
        ];

        foreach ($warehouses as $warehouse) {
            $summary['total_capacity'] += (int) ($warehouse['TongSL'] ?? 0);
            $summary['total_inventory_value'] += (float) ($warehouse['ThanhTien'] ?? 0);
            $summary['total_lots'] += (int) ($warehouse['SoLoDangQuanLy'] ?? ($warehouse['TongSLLo'] ?? 0));
            $summary['total_quantity'] += (int) ($warehouse['TongSoLuongLo'] ?? 0);

            if ($this->isActiveWarehouse($warehouse['TrangThai'] ?? null)) {
                $summary['active_warehouses']++;
            }
        }

        $summary['inactive_warehouses'] = max(
            0,
            $summary['total_warehouses'] - $summary['active_warehouses']
        );

        $summary['total_inventory_value'] = round($summary['total_inventory_value'], 2);
        $summary['total_capacity'] = (int) $summary['total_capacity'];
        $summary['total_lots'] = (int) $summary['total_lots'];
        $summary['total_quantity'] = (int) $summary['total_quantity'];

        return $summary;
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
