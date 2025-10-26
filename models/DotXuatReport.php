<?php

class DotXuatReport extends BaseModel
{
    protected string $table = 'bien_ban_danh_gia_dot_xuat';
    protected string $primaryKey = 'IdBienBanDanhGiaDX';

    public function getLatestReports(int $limit = 20): array
{
    $sql = "SELECT 
                bb.*, 
                x.TenXuong, 
                nv.HoTen AS TenNhanVien
            FROM bien_ban_danh_gia_dot_xuat bb
            LEFT JOIN xuong x ON x.IdXuong = bb.IdXuong
            LEFT JOIN nhan_vien nv ON nv.IdNhanVien = bb.IdNhanVien
            ORDER BY bb.ThoiGian DESC
            LIMIT :limit";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    public function getAllXuong(): array
    {
        $sql = "SELECT IdXuong, TenXuong FROM xuong";
        return $this->db->query($sql)->fetchAll();
        $stmt = $this->db->prepare("SELECT IdNhanVien, HoTen FROM nhanvien ORDER BY HoTen ASC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertChiTietTieuChi($idBienBan, $loaiTieuChi, $tieuChi, $diemDG, $ghiChu, $fileName)
{
    $sql = "INSERT INTO ttct_bien_ban_danh_gia_dot_xuat 
            (LoaiTieuChi, TieuChi, DiemDG, GhiChu, HinhAnh, IdBienBanDanhGiaDX)
            VALUES (:LoaiTieuChi, :TieuChi, :DiemDG, :GhiChu, :HinhAnh, :IdBienBanDanhGiaDX)";
    
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        ':LoaiTieuChi' => $loaiTieuChi,
        ':TieuChi' => $tieuChi,
        ':DiemDG' => $diemDG,
        ':GhiChu' => $ghiChu,
        ':HinhAnh' => $fileName,
        ':IdBienBanDanhGiaDX' => $idBienBan
    ]);
}
    public function getAllNhanVien(): array
{
    try {
        $sql = "SELECT IdNhanVien, HoTen 
                FROM nhan_vien 
                ORDER BY HoTen ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Không thể lấy danh sách nhân viên: " . $e->getMessage());
    }
}


    public function getChiTietByBienBan(string $idBienBan): array
{
    $stmt = $this->db->prepare("SELECT * FROM ttct_bien_ban_danh_gia_dot_xuat WHERE IdBienBanDanhGiaDX = :id");
    $stmt->execute([':id' => $idBienBan]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function deleteChiTietByBienBan(string $idBienBan): void
{
    $stmt = $this->db->prepare("DELETE FROM ttct_bien_ban_danh_gia_dot_xuat WHERE IdBienBanDanhGiaDX = :id");
    $stmt->execute([':id' => $idBienBan]);
}

public function find(string $idBienBan): ?array
{
    $stmt = $this->db->prepare("
        SELECT bb.*, x.TenXuong, nv.HoTen 
        FROM bien_ban_danh_gia_dot_xuat bb
        JOIN xuong x ON x.IdXuong = bb.IdXuong
        JOIN nhan_vien nv ON nv.IdNhanVien = bb.IdNhanVien
        WHERE bb.IdBienBanDanhGiaDX = :id
    ");
    $stmt->execute([':id' => $idBienBan]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}
    
    public function insertChiTiet(string $idBienBan, string $tieuChi, ?string $ghiChu, ?string $hinhAnh): bool
{
    try {
        $sql = "INSERT INTO ttct_bien_ban_danh_gia_dot_xuat 
                (IdTTCTBBDGDX, TieuChi, DiemDG, GhiChu, HinhAnh, IdBienBanDanhGiaDX) 
                VALUES (:id, :tieuchi, NULL, :ghichu, :hinhanh, :idbb)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => uniqid('CTBBDX'),
            ':tieuchi' => $tieuChi,
            ':ghichu' => $ghiChu,
            ':hinhanh' => $hinhAnh,
            ':idbb' => $idBienBan
        ]);
        return true;
    } catch (PDOException $e) {
        throw new Exception("Không thể thêm chi tiết biên bản: " . $e->getMessage());
    }
}

}
