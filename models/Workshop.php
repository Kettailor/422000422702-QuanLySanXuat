<?php

class Workshop extends BaseModel
{
    protected string $table = 'xuong';
    protected string $primaryKey = 'IdXuong';

    public function getAllWithManagers(int $limit = 100): array
    {
        $sql = 'SELECT xuong.*, manager.HoTen AS TruongXuong
                FROM xuong
                LEFT JOIN xuong_nhan_vien xnv ON xnv.IdXuong = xuong.IdXuong AND xnv.VaiTro = :managerRole
                LEFT JOIN nhan_vien manager ON manager.IdNhanVien = xnv.IdNhanVien
                ORDER BY xuong.TenXuong
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':managerRole', 'truong_xuong');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getCapacitySummary(): array
    {
        $rows = $this->db->query('SELECT * FROM xuong')->fetchAll();

        $summary = [
            'total_workshops' => 0,
            'max_capacity' => 0.0,
            'current_capacity' => 0.0,
            'workforce' => 0,
            'max_workforce' => 0,
            'utilization' => 0.0,
            'workforce_utilization' => 0.0,
        ];

        if (!$rows) {
            return $summary;
        }

        foreach ($rows as $row) {
            $summary['total_workshops']++;
            $summary['max_capacity'] += (float) ($row['CongSuatToiDa'] ?? 0);
            $summary['current_capacity'] += (float) ($row['CongSuatDangSuDung'] ?? $row['CongSuatHienTai'] ?? 0);
            $summary['workforce'] += (int) ($row['SoLuongCongNhan'] ?? 0);
            $summary['max_workforce'] += (int) ($row['SlNhanVien'] ?? 0);
        }

        if ($summary['max_capacity'] > 0) {
            $summary['utilization'] = round(($summary['current_capacity'] / $summary['max_capacity']) * 100, 2);
        }

        if ($summary['max_workforce'] > 0) {
            $summary['workforce_utilization'] = round(($summary['workforce'] / $summary['max_workforce']) * 100, 2);
        } else {
            $summary['workforce_utilization'] = 0.0;
        }

        return $summary;
    }

    public function getStatusDistribution(): array
    {
        $sql = "SELECT TrangThai, COUNT(*) AS total FROM xuong GROUP BY TrangThai";
        $rows = $this->db->query($sql)->fetchAll();

        $result = [];
        foreach ($rows as $row) {
            $status = $row['TrangThai'] ?: 'Không xác định';
            $result[$status] = (int) $row['total'];
        }

        return $result;
    }
}
