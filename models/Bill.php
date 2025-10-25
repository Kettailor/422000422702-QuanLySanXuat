<?php

class Bill extends BaseModel
{
    protected string $table = 'HOA_DON';
    protected string $primaryKey = 'IdHoaDon';

    public function getBillsWithOrder(int $limit = 50): array
    {
        $sql = 'SELECT HOA_DON.*, DON_HANG.YeuCau AS DonHangYeuCau
                FROM HOA_DON
                LEFT JOIN DON_HANG ON DON_HANG.IdDonHang = HOA_DON.IdDonHang
                ORDER BY NgayLap DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByOrder(string $orderId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM HOA_DON WHERE IdDonHang = :orderId ORDER BY NgayLap DESC');
        $stmt->bindValue(':orderId', $orderId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
