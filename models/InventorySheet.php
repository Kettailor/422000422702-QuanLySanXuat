<?php

class InventorySheet extends BaseModel
{
    protected string $table = 'PHIEU';
    protected string $primaryKey = 'IdPhieu';

    /**
     * Lấy danh sách phiếu nhập/xuất kho cùng thông tin bổ sung.
     */
    public function getDocuments(int $limit = 50): array
    {
        $sql = 'SELECT
                    PHIEU.IdPhieu,
                    PHIEU.NgayLP,
                    PHIEU.NgayXN,
                    PHIEU.TongTien,
                    PHIEU.LoaiPhieu,
                    PHIEU.IdKho,
                    PHIEU.NHAN_VIENIdNhanVien,
                    PHIEU.NHAN_VIENIdNhanVien2,
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
                ORDER BY PHIEU.NgayLP DESC, PHIEU.IdPhieu DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
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
}
