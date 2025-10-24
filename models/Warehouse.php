<?php

class Warehouse extends BaseModel
{
    protected string $table = 'KHO';
    protected string $primaryKey = 'IdKho';

    public function getWithSupervisor(): array
    {
        $sql = 'SELECT KHO.*, NV.HoTen AS TenQuanKho
                FROM KHO
                JOIN NHAN_VIEN NV ON NV.IdNhanVien = KHO.`NHAN_VIEN_KHO_IdNhanVien`
                ORDER BY KHO.TenKho';
        return $this->db->query($sql)->fetchAll();
    }
}
