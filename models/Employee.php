<?php

class Employee extends BaseModel
{
    protected string $table = 'NHAN_VIEN';
    protected string $primaryKey = 'IdNhanVien';

    public function getActiveEmployees(): array
    {
        $sql = 'SELECT * FROM NHAN_VIEN WHERE TrangThai = :status ORDER BY HoTen';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':status', 'Đang làm việc');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countActiveEmployees(): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(nhan_vien.IdNhanVien) AS count FROM nhan_vien');
        $stmt->execute();
        $result = $stmt->fetch();
        return (int)$result['count'];
    }

    public function getUnassignedEmployees(): array
    {
        $stmt = $this->db->prepare('SELECT nv.IdNhanVien, nv.HoTen
          FROM NHAN_VIEN nv
          LEFT JOIN NGUOI_DUNG nd ON nv.IdNhanVien = nd.IdNhanVien
          WHERE nd.IdNhanVien IS NULL');
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
