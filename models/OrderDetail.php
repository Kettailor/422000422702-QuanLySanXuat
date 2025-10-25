<?php

class OrderDetail extends BaseModel
{
    protected string $table = 'ct_don_hang';
    protected string $primaryKey = 'IdTTCTDonHang';

    public function getByOrder(string $orderId): array
    {
        $sql = 'SELECT ct_don_hang.*, san_pham.TenSanPham, san_pham.DonVi, san_pham.GiaBan
                FROM ct_don_hang
                LEFT JOIN san_pham ON san_pham.IdSanPham = ct_don_hang.IdSanPham
                WHERE ct_don_hang.IdDonHang = :orderId
                ORDER BY ct_don_hang.NgayGiao IS NULL, ct_don_hang.NgayGiao';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':orderId', $orderId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function deleteByOrder(string $orderId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM ct_don_hang WHERE IdDonHang = :orderId');
        $stmt->bindValue(':orderId', $orderId);
        return $stmt->execute();
    }
}
