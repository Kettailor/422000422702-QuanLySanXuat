<?php

class QualityReport extends BaseModel
{
    protected string $table = 'BIEN_BAN_DANH_GIA_THANH_PHAM';
    protected string $primaryKey = 'IdBienBanDanhGiaSP';

    public function getLatestReports(int $limit = 10): array
    {
        $sql = 'SELECT BIEN_BAN_DANH_GIA_THANH_PHAM.*, LO.TenLo, LO.LoaiLo
                FROM BIEN_BAN_DANH_GIA_THANH_PHAM
                JOIN LO ON LO.IdLo = BIEN_BAN_DANH_GIA_THANH_PHAM.IdLo
                ORDER BY ThoiGian DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getQualitySummary(): array
    {
        $sql = 'SELECT COUNT(*) AS tong_bien_ban,
                       SUM(CASE WHEN KetQua = "Đạt" THEN 1 ELSE 0 END) AS so_dat,
                       SUM(CASE WHEN KetQua = "Không đạt" THEN 1 ELSE 0 END) AS so_khong_dat
                FROM BIEN_BAN_DANH_GIA_THANH_PHAM';
        return $this->db->query($sql)->fetch();
    }
}
