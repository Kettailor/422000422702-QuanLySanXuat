<?php

class Salary extends BaseModel
{
    protected string $table = 'BANG_LUONG';
    protected string $primaryKey = 'IdBangLuong';

    public function getPayrolls(int $limit = 50): array
    {
        $sql = 'SELECT BANG_LUONG.*, NV.HoTen
                FROM BANG_LUONG
                JOIN NHAN_VIEN NV ON NV.IdNhanVien = BANG_LUONG.NHAN_VIENIdNhanVien
                ORDER BY NgayLap DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
