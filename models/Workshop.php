<?php

class Workshop extends BaseModel
{
    protected string $table = 'XUONG';
    protected string $primaryKey = 'IdXuong';

    public function getAllWithManagers(int $limit = 100): array
    {
        $sql = 'SELECT XUONG.*, NV.HoTen AS TruongXuong
                FROM XUONG
                LEFT JOIN NHAN_VIEN NV ON NV.IdNhanVien = XUONG.IdTruongXuong
                ORDER BY XUONG.TenXuong
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getCapacitySummary(): array
    {
        $rows = $this->db->query('SELECT * FROM XUONG')->fetchAll();

        $summary = [
            'total_workshops' => 0,
            'max_capacity' => 0.0,
            'current_capacity' => 0.0,
            'workforce' => 0,
            'utilization' => 0.0,
        ];

        if (!$rows) {
            return $summary;
        }

        foreach ($rows as $row) {
            $summary['total_workshops']++;
            $summary['max_capacity'] += (float) ($row['CongSuatToiDa'] ?? 0);
            $summary['current_capacity'] += (float) ($row['CongSuatDangSuDung'] ?? $row['CongSuatHienTai'] ?? 0);
            $summary['workforce'] += (int) ($row['SoLuongCongNhan'] ?? 0);
        }

        if ($summary['max_capacity'] > 0) {
            $summary['utilization'] = round(($summary['current_capacity'] / $summary['max_capacity']) * 100, 2);
        }

        return $summary;
    }

    public function getStatusDistribution(): array
    {
        $sql = "SELECT TrangThai, COUNT(*) AS total FROM XUONG GROUP BY TrangThai";
        $rows = $this->db->query($sql)->fetchAll();

        $result = [];
        foreach ($rows as $row) {
            $status = $row['TrangThai'] ?: 'Không xác định';
            $result[$status] = (int) $row['total'];
        }

        return $result;
    }
}
