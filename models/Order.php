<?php

class Order extends BaseModel
{
    protected string $table = 'DON_HANG';
    protected string $primaryKey = 'IdDonHang';

    public function getOrdersWithCustomer(int $limit = 50): array
    {
        $sql = 'SELECT DON_HANG.*, KHACH_HANG.HoTen AS TenKhachHang, KHACH_HANG.SoDienThoai
                FROM DON_HANG
                JOIN KHACH_HANG ON KHACH_HANG.IdKhachHang = DON_HANG.IdKhachHang
                ORDER BY DON_HANG.NgayLap DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getMonthlyRevenue(): array
    {
        $sql = 'SELECT DATE_FORMAT(NgayLap, "%Y-%m") AS thang, SUM(TongTien) AS tong_doanh_thu
                FROM DON_HANG
                GROUP BY DATE_FORMAT(NgayLap, "%Y-%m")
                ORDER BY thang DESC
                LIMIT 6';
        return $this->db->query($sql)->fetchAll();
    }
}
