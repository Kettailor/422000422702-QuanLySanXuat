<?php

class Salary extends BaseModel
{
    public const ACCOUNTANT_COLUMN = '`KETOAN IdNhanVien2`';
    public const EMPLOYEE_COLUMN = 'NHAN_VIENIdNhanVien';

    protected string $table = 'bang_luong';
    protected string $primaryKey = 'IdBangLuong';

    public static function calculateFigures(float $base, float $allowance, float $deduction, float $tax): array
    {
        $gross = $base + $allowance;
        $net = $gross - $deduction - $tax;

        return [
            'gross' => round($gross, 2),
            'net' => max(round($net, 2), 0),
        ];
    }

    public function getPayrolls(int $limit = 50): array
    {
        $sql = 'SELECT bang_luong.*, nv.HoTen
                FROM bang_luong
                JOIN nhan_vien nv ON nv.IdNhanVien = bang_luong.' . self::EMPLOYEE_COLUMN . '
                ORDER BY NgayLap DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPendingPayrolls(int $limit = 5): array
    {
        $sql = 'SELECT bang_luong.*, nv.HoTen
                FROM bang_luong
                JOIN nhan_vien nv ON nv.IdNhanVien = bang_luong.' . self::EMPLOYEE_COLUMN . '
                WHERE bang_luong.TrangThai = :status
                ORDER BY NgayLap DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':status', 'Chờ duyệt');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPayrollSummary(): array
    {
        $sql = 'SELECT COUNT(*) AS total,
                       SUM(TongThuNhap) AS total_amount,
                       SUM(CASE WHEN TrangThai = "Chờ duyệt" THEN 1 ELSE 0 END) AS pending,
                       SUM(CASE WHEN TrangThai = "Đã duyệt" THEN 1 ELSE 0 END) AS approved,
                       SUM(CASE WHEN TrangThai = "Đã chi" THEN 1 ELSE 0 END) AS paid
                FROM bang_luong';
        $summary = $this->db->query($sql)->fetch();

        return [
            'total' => (int) ($summary['total'] ?? 0),
            'pending' => (int) ($summary['pending'] ?? 0),
            'approved' => (int) ($summary['approved'] ?? 0),
            'paid' => (int) ($summary['paid'] ?? 0),
            'total_amount' => (float) ($summary['total_amount'] ?? 0),
        ];
    }

    public function getMonthlyPayoutTrend(int $months = 6): array
    {
        $sql = 'SELECT DATE_FORMAT(NgayLap, "%Y-%m") AS thang, SUM(TongThuNhap) AS tong_chi
                FROM bang_luong
                GROUP BY DATE_FORMAT(NgayLap, "%Y-%m")
                ORDER BY thang DESC
                LIMIT :months';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':months', $months, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateStatus(string $id, string $status, ?string $signature = null): bool
    {
        $data = ['TrangThai' => $status];

        if ($signature !== null) {
            $data['ChuKy'] = $signature;
        }

        return $this->update($id, $data);
    }
}
