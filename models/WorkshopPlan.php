<?php

class WorkshopPlan extends BaseModel
{
    protected string $table = 'ke_hoach_san_xuat_xuong';
    protected string $primaryKey = 'IdKeHoachSanXuatXuong';

    public function getDetailedPlans(int $limit = 20): array
    {
        $sql = 'SELECT ke_hoach_san_xuat_xuong.*, xuong.TenXuong, ke_hoach_san_xuat.TrangThai AS TrangThaiTong
                FROM ke_hoach_san_xuat_xuong
                JOIN xuong ON xuong.IdXuong = ke_hoach_san_xuat_xuong.IdXuong
                JOIN ke_hoach_san_xuat ON ke_hoach_san_xuat.IdKeHoachSanXuat = ke_hoach_san_xuat_xuong.IdKeHoachSanXuat
                ORDER BY ke_hoach_san_xuat_xuong.ThoiGianBatDau DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
