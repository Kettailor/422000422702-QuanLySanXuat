<?php

class Bill extends BaseModel
{
    protected string $table = 'hoa_don';
    protected string $primaryKey = 'IdHoaDon';

    public function getBillsWithOrder(int $limit = 50): array
    {
        $sql = 'SELECT hoa_don.*, don_hang.YeuCau AS DonHangYeuCau
                FROM hoa_don
                LEFT JOIN don_hang ON don_hang.IdDonHang = hoa_don.IdDonHang
                ORDER BY NgayLap DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByOrder(string $orderId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM hoa_don WHERE IdDonHang = :orderId ORDER BY NgayLap DESC');
        $stmt->bindValue(':orderId', $orderId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
