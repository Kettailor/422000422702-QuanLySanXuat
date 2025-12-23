<?php

class SystemActivity extends BaseModel
{
    protected string $table = 'hoat_dong_he_thong';
    protected string $primaryKey = 'IdHoatDong';

    public function latest(int $limit = 8): array
    {
        $sql = 'SELECT * FROM hoat_dong_he_thong ORDER BY ThoiGian DESC LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findByOrderId(string $orderId, int $limit = 20): array
    {
        $sql = 'SELECT hd.*, COALESCE(nv.HoTen, nd.TenDangNhap) AS TenNguoiDung
                FROM hoat_dong_he_thong hd
                LEFT JOIN nguoi_dung nd ON nd.IdNguoiDung = hd.IdNguoiDung
                LEFT JOIN nhan_vien nv ON nv.IdNhanVien = nd.IdNhanVien
                WHERE hd.HanhDong LIKE :pattern
                ORDER BY hd.ThoiGian DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':pattern', '%' . $orderId . '%');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
