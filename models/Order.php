<?php

class Order extends BaseModel
{
    protected string $table = 'don_hang';
    protected string $primaryKey = 'IdDonHang';

    public function getOrdersWithCustomer(int $limit = 50): array
    {
        $sql = 'SELECT don_hang.*, khach_hang.HoTen AS TenKhachHang, khach_hang.SoDienThoai, khach_hang.Email AS EmailKhachHang
                FROM don_hang
                JOIN khach_hang ON khach_hang.IdKhachHang = don_hang.IdKhachHang
                ORDER BY don_hang.NgayLap DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getMonthlyRevenue(): array
    {
        $sql = 'SELECT DATE_FORMAT(NgayLap, "%Y-%m") AS thang, SUM(TongTien) AS tong_doanh_thu
                FROM don_hang
                GROUP BY DATE_FORMAT(NgayLap, "%Y-%m")
                ORDER BY thang DESC
                LIMIT 6';
        return $this->db->query($sql)->fetchAll();
    }

    public function getOrderStatistics(): array
    {
        $sql = 'SELECT COUNT(*) AS total_orders,
                       SUM(TongTien) AS total_revenue,
                       SUM(CASE WHEN TrangThai IN ("Hoàn thành", "Đã hoàn thành") THEN 1 ELSE 0 END) AS completed_orders,
                       SUM(CASE WHEN TrangThai = "Đang xử lý" THEN 1 ELSE 0 END) AS processing_orders,
                       SUM(CASE WHEN TrangThai = "Chưa có kế hoạch" THEN 1 ELSE 0 END) AS pending_orders
                FROM don_hang';

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
