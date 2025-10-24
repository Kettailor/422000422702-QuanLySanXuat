<?php

class WorkshopPlan extends BaseModel
{
    protected string $table = 'KE_HOACH_SAN_XUAT_XUONG';
    protected string $primaryKey = 'IdKeHoachSanXuatXuong';

    public function getDetailedPlans(int $limit = 20): array
    {
        $sql = 'SELECT KE_HOACH_SAN_XUAT_XUONG.*, XUONG.TenXuong, KE_HOACH_SAN_XUAT.TrangThai AS TrangThaiTong
                FROM KE_HOACH_SAN_XUAT_XUONG
                JOIN XUONG ON XUONG.IdXuong = KE_HOACH_SAN_XUAT_XUONG.IdXuong
                JOIN KE_HOACH_SAN_XUAT ON KE_HOACH_SAN_XUAT.IdKeHoachSanXuat = KE_HOACH_SAN_XUAT_XUONG.IdKeHoachSanXuat
                ORDER BY KE_HOACH_SAN_XUAT_XUONG.ThoiGianBatDau DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
