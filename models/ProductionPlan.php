<?php

class ProductionPlan extends BaseModel
{
    protected string $table = 'KE_HOACH_SAN_XUAT';
    protected string $primaryKey = 'IdKeHoachSanXuat';

    public function getPlansWithOrders(int $limit = 20): array
    {
        $sql = 'SELECT KE_HOACH_SAN_XUAT.*, CT_DON_HANG.SoLuong AS SoLuongDonHang, DON_HANG.YeuCau
                FROM KE_HOACH_SAN_XUAT
                JOIN CT_DON_HANG ON CT_DON_HANG.IdTTCTDonHang = KE_HOACH_SAN_XUAT.IdTTCTDonHang
                JOIN DON_HANG ON DON_HANG.IdDonHang = CT_DON_HANG.IdDonHang
                ORDER BY KE_HOACH_SAN_XUAT.ThoiGianBD DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByOrder(string $orderId): array
    {
        $sql = 'SELECT KE_HOACH_SAN_XUAT.*, CT_DON_HANG.IdSanPham, CT_DON_HANG.SoLuong AS SoLuongChiTiet
                FROM KE_HOACH_SAN_XUAT
                JOIN CT_DON_HANG ON CT_DON_HANG.IdTTCTDonHang = KE_HOACH_SAN_XUAT.IdTTCTDonHang
                WHERE CT_DON_HANG.IdDonHang = :orderId
                ORDER BY KE_HOACH_SAN_XUAT.ThoiGianBD DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':orderId', $orderId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
