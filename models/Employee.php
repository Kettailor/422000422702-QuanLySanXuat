<?php

class Employee extends BaseModel
{
    protected string $table = 'nhan_vien';
    protected string $primaryKey = 'IdNhanVien';

    public function getActiveEmployees(): array
    {
        $sql = 'SELECT * FROM nhan_vien WHERE TrangThai = :status ORDER BY HoTen';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':status', 'Đang làm việc');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getBoardManagers(): array
    {
        $sql = 'SELECT *
                FROM nhan_vien
                WHERE TrangThai = :status
                  AND (ChucVu LIKE :keyword OR ChucVu LIKE :keywordShort)
                ORDER BY HoTen';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':status', 'Đang làm việc');
        $stmt->bindValue(':keyword', '%giám đốc%');
        $stmt->bindValue(':keywordShort', '%Ban giám đốc%');
        $stmt->execute();
        $rows = $stmt->fetchAll();

        if ($rows) {
            return $rows;
        }

        return $this->getActiveEmployees();
    }
}
