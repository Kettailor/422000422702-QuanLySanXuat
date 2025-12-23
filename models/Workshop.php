<?php

class Workshop extends BaseModel
{
    protected string $table = 'xuong';
    protected string $primaryKey = 'IdXuong';

    public function getByManager(string $managerId, int $limit = 100): array
    {
        $sql = 'SELECT xuong.*, manager.HoTen AS TruongXuong
                FROM xuong
                LEFT JOIN nhan_vien manager ON manager.IdNhanVien = xuong.XUONGTRUONG_IdNhanVien
                WHERE xuong.XUONGTRUONG_IdNhanVien = :managerId
                ORDER BY xuong.TenXuong
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':managerId', $managerId);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getAllWithManagers(int $limit = 100): array
    {
        $sql = 'SELECT xuong.*, manager.HoTen AS TruongXuong
                FROM xuong
                LEFT JOIN nhan_vien manager ON manager.IdNhanVien = xuong.XUONGTRUONG_IdNhanVien
                ORDER BY xuong.TenXuong
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
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

    public function findByIds(array $ids): array
    {
        $ids = array_values(array_filter($ids));
        if (empty($ids)) {
            return [];
        }

        $placeholders = [];
        $bindings = [];
        foreach ($ids as $index => $id) {
            $key = ':id' . $index;
            $placeholders[] = $key;
            $bindings[$key] = $id;
        }

        $sql = 'SELECT xuong.*, manager.HoTen AS TruongXuong
                FROM xuong
                LEFT JOIN nhan_vien manager ON manager.IdNhanVien = xuong.XUONGTRUONG_IdNhanVien
                WHERE xuong.IdXuong IN (' . implode(',', $placeholders) . ')
                ORDER BY xuong.TenXuong';

        $stmt = $this->db->prepare($sql);
        foreach ($bindings as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getAssignedManagerIds(): array
    {
        $stmt = $this->db->query('SELECT DISTINCT XUONGTRUONG_IdNhanVien FROM xuong WHERE XUONGTRUONG_IdNhanVien IS NOT NULL');
        return array_values(array_filter(array_column($stmt->fetchAll(), 'XUONGTRUONG_IdNhanVien')));
    }

    public function getManagersByWorkshopIds(array $ids): array
    {
        $ids = array_values(array_filter($ids));
        if (empty($ids)) {
            return [];
        }

        $placeholders = [];
        $bindings = [];
        foreach ($ids as $index => $id) {
            $key = ':id' . $index;
            $placeholders[] = $key;
            $bindings[$key] = $id;
        }

        $sql = 'SELECT IdXuong, XUONGTRUONG_IdNhanVien
                FROM xuong
                WHERE IdXuong IN (' . implode(',', $placeholders) . ')';

        $stmt = $this->db->prepare($sql);
        foreach ($bindings as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        $rows = $stmt->fetchAll();
        $map = [];
        foreach ($rows as $row) {
            $workshopId = $row['IdXuong'] ?? null;
            if (!$workshopId) {
                continue;
            }
            $map[$workshopId] = $row['XUONGTRUONG_IdNhanVien'] ?? null;
        }

        return $map;
    }
}
