<?php

class OrderDetail extends BaseModel
{
    protected string $table = 'CT_DON_HANG';
    protected string $primaryKey = 'IdTTCTDonHang';

    public function getByOrder(string $orderId): array
    {
        $sql = 'SELECT CT_DON_HANG.*, SAN_PHAM.TenSanPham, SAN_PHAM.DonVi, SAN_PHAM.GiaBan
                FROM CT_DON_HANG
                LEFT JOIN SAN_PHAM ON SAN_PHAM.IdSanPham = CT_DON_HANG.IdSanPham
                WHERE CT_DON_HANG.IdDonHang = :orderId
                ORDER BY CT_DON_HANG.NgayGiao IS NULL, CT_DON_HANG.NgayGiao';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':orderId', $orderId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function deleteByOrder(string $orderId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM CT_DON_HANG WHERE IdDonHang = :orderId');
        $stmt->bindValue(':orderId', $orderId);
        return $stmt->execute();
    }
}
