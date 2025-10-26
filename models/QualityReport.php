<?php

class QualityReport extends BaseModel
{
    protected string $table = 'bien_ban_danh_gia_thanh_pham';
    protected string $primaryKey = 'IdBienBanDanhGiaSP';

    public function getLatestReports(int $limit = 10): array
    {
        $sql = 'SELECT bien_ban_danh_gia_thanh_pham.*, lo.TenLo, lo.LoaiLo
                FROM bien_ban_danh_gia_thanh_pham
                JOIN lo ON lo.IdLo = bien_ban_danh_gia_thanh_pham.IdLo
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
                FROM bien_ban_danh_gia_thanh_pham';
        return $this->db->query($sql)->fetch();
    }
}
