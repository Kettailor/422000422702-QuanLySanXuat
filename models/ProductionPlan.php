<?php

class ProductionPlan extends BaseModel
{
    protected string $table = 'ke_hoach_san_xuat';
    protected string $primaryKey = 'IdKeHoachSanXuat';

    public function getPlansWithOrders(int $limit = 20): array
    {
        $sql = 'SELECT ke_hoach_san_xuat.*, ct_don_hang.SoLuong AS SoLuongDonHang, don_hang.YeuCau
                FROM ke_hoach_san_xuat
                JOIN ct_don_hang ON ct_don_hang.IdTTCTDonHang = ke_hoach_san_xuat.IdTTCTDonHang
                JOIN don_hang ON don_hang.IdDonHang = ct_don_hang.IdDonHang
                ORDER BY ke_hoach_san_xuat.ThoiGianBD DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByOrder(string $orderId): array
    {
        $sql = 'SELECT ke_hoach_san_xuat.*, ct_don_hang.IdSanPham, ct_don_hang.SoLuong AS SoLuongChiTiet
                FROM ke_hoach_san_xuat
                JOIN ct_don_hang ON ct_don_hang.IdTTCTDonHang = ke_hoach_san_xuat.IdTTCTDonHang
                WHERE ct_don_hang.IdDonHang = :orderId
                ORDER BY ke_hoach_san_xuat.ThoiGianBD DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':orderId', $orderId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
