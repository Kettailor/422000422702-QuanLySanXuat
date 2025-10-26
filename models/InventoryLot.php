<?php

class InventoryLot extends BaseModel
{
    protected string $table = 'lo';
    protected string $primaryKey = 'IdLo';

    public function getLotsByWarehouse(string $warehouseId): array
    {
<<<<<<< HEAD
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

=======
        $sql = 'SELECT * FROM lo WHERE IdKho = :warehouse ORDER BY NgayTao DESC';
>>>>>>> 65075f83681f452199a37e66bf195847eea6c888
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':warehouse', $warehouseId);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
