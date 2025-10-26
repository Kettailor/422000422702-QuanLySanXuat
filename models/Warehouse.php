<?php

class Warehouse extends BaseModel
{
    protected string $table = 'kho';
    protected string $primaryKey = 'IdKho';

    public function getWithSupervisor(): array
    {
        $sql = 'SELECT kho.*, nv.HoTen AS TenQuanKho
                FROM kho
                JOIN nhan_vien nv ON nv.IdNhanVien = kho.`NHAN_VIEN_KHO_IdNhanVien`
                ORDER BY kho.TenKho';
        return $this->db->query($sql)->fetchAll();
    }
}
