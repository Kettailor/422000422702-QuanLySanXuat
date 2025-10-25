<?php

class Order extends BaseModel
{
    protected string $table = 'DON_HANG';
    protected string $primaryKey = 'IdDonHang';

    public function getOrdersWithCustomer(int $limit = 50): array
    {
        $sql = 'SELECT DON_HANG.*, KHACH_HANG.HoTen AS TenKhachHang, KHACH_HANG.SoDienThoai,
                       (
                           SELECT GROUP_CONCAT(DISTINCT HOA_DON.TrangThai ORDER BY HOA_DON.NgayLap DESC SEPARATOR ", ")
                           FROM HOA_DON
                           WHERE HOA_DON.IdDonHang = DON_HANG.IdDonHang
                       ) AS TrangThaiHoaDon,
                       (
                           SELECT GROUP_CONCAT(DISTINCT KE_HOACH_SAN_XUAT.TrangThai ORDER BY KE_HOACH_SAN_XUAT.ThoiGianBD DESC SEPARATOR ", ")
                           FROM KE_HOACH_SAN_XUAT
                           JOIN CT_DON_HANG ON CT_DON_HANG.IdTTCTDonHang = KE_HOACH_SAN_XUAT.IdTTCTDonHang
                           WHERE CT_DON_HANG.IdDonHang = DON_HANG.IdDonHang
                       ) AS TrangThaiKeHoach
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

    public function getOrderStatistics(): array
    {
        $sql = 'SELECT COUNT(*) AS total_orders,
                       SUM(TongTien) AS total_revenue,
                       SUM(CASE WHEN TrangThai = "Hoàn thành" THEN 1 ELSE 0 END) AS completed_orders,
                       SUM(CASE WHEN TrangThai = "Đang xử lý" THEN 1 ELSE 0 END) AS processing_orders,
                       SUM(CASE WHEN TrangThai = "Chờ duyệt" THEN 1 ELSE 0 END) AS pending_orders
                FROM DON_HANG';

        $stats = $this->db->query($sql)->fetch();

        return [
            'total_orders' => (int) ($stats['total_orders'] ?? 0),
            'total_revenue' => (float) ($stats['total_revenue'] ?? 0),
            'completed_orders' => (int) ($stats['completed_orders'] ?? 0),
            'processing_orders' => (int) ($stats['processing_orders'] ?? 0),
            'pending_orders' => (int) ($stats['pending_orders'] ?? 0),
        ];
    }
}
