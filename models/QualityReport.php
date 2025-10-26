<?php

class QualityReport extends BaseModel
{
    protected string $table = 'bien_ban_danh_gia_thanh_pham';
    protected string $primaryKey = 'IdBienBanDanhGiaSP';

    // Lấy danh sách biên bản mới nhất
    public function getLatestReports(int $limit = 10): array
    {
        $sql = 'SELECT 
                    bb.IdBienBanDanhGiaSP,
                    bb.KetQua,
                    bb.ThoiGian,
                    lo.IdLo,
                    lo.SoLuong,
                    sp.TenSanPham,
                    x.TenXuong
                FROM bien_ban_danh_gia_thanh_pham bb
                JOIN lo ON lo.IdLo = bb.IdLo
                LEFT JOIN san_pham sp ON sp.IdSanPham = lo.IdSanPham
                LEFT JOIN kho k ON k.IdKho = lo.IdKho
                LEFT JOIN xuong x ON x.IdXuong = k.IdXuong
                ORDER BY bb.ThoiGian DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Tổng hợp thống kê
    public function getQualitySummary(): array
    {
        $sql = 'SELECT COUNT(*) AS tong_bien_ban,
                       SUM(CASE WHEN KetQua = "Đạt" THEN 1 ELSE 0 END) AS so_dat,
                       SUM(CASE WHEN KetQua = "Không đạt" THEN 1 ELSE 0 END) AS so_khong_dat
                FROM bien_ban_danh_gia_thanh_pham';
        return $this->db->query($sql)->fetch();
    }

    // Tạo biên bản mới
    public function create(array $data): bool
    {
        $sql = 'INSERT INTO bien_ban_danh_gia_thanh_pham
                    (IdBienBanDanhGiaSP, ThoiGian, TongTCD, TongTCKD, KetQua, IdLo)
                VALUES
                    (:IdBienBanDanhGiaSP, :ThoiGian, :TongTCD, :TongTCKD, :KetQua, :IdLo)';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    // Thêm chi tiết tiêu chí
    public function insertChiTietTieuChi(string $idBienBan, string $tieuChi, int $diemDat, string $ghiChu = null, string $fileName = null): bool
    {
        $sql = 'INSERT INTO ttct_bien_ban_danh_gia_thanh_pham
                    (IdBienBanDanhGiaSP, TenTieuChi, DiemDat, GhiChu, FileMinhChung)
                VALUES
                    (:IdBienBanDanhGiaSP, :TenTieuChi, :DiemDat, :GhiChu, :FileMinhChung)';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':IdBienBanDanhGiaSP' => $idBienBan,
            ':TenTieuChi' => $tieuChi,
            ':DiemDat' => $diemDat,
            ':GhiChu' => $ghiChu,
            ':FileMinhChung' => $fileName
        ]);
    }

    // Xóa chi tiết trước (tránh lỗi khóa ngoại)
    public function deleteChiTietByBienBan(string $id): bool
    {
        try {
            $stmt = $this->db->prepare(
                'DELETE FROM ttct_bien_ban_danh_gia_thanh_pham WHERE IdBienBanDanhGiaSP = :id'
            );
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            throw new Exception('Không thể xóa chi tiết biên bản: ' . $e->getMessage());
        }
    }

    // Xóa biên bản chính
    public function deleteBienBan(string $id): bool
    {
        try {
            $stmt = $this->db->prepare(
                'DELETE FROM bien_ban_danh_gia_thanh_pham WHERE IdBienBanDanhGiaSP = :id'
            );
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            throw new Exception('Không thể xóa biên bản: ' . $e->getMessage());
        }
    }
}
