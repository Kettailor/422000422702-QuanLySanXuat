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
}
