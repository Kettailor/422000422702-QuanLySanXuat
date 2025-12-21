<?php

class Workshop extends BaseModel
{
    protected string $table = 'xuong';
    protected string $primaryKey = 'IdXuong';

    public function getAllWithManagers(int $limit = 100, ?array $workshopIds = null): array
    {
        $sql = 'SELECT xuong.*, nv.HoTen AS TruongXuong
                FROM xuong
                LEFT JOIN nhan_vien nv ON nv.IdXuong = xuong.IdXuong';

        $bindings = [':limit' => $limit];
        $conditions = [];

        if ($workshopIds !== null) {
            $workshopIds = array_values(array_filter($workshopIds, 'strlen'));

            if (empty($workshopIds)) {
                return [];
            }

            $placeholders = [];
            foreach ($workshopIds as $index => $workshopId) {
                $placeholder = ':id' . $index;
                $placeholders[] = $placeholder;
                $bindings[$placeholder] = $workshopId;
            }

            $conditions[] = 'xuong.IdXuong IN (' . implode(', ', $placeholders) . ')';
            $bindings[':limit'] = min($limit, count($workshopIds));
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY xuong.TenXuong LIMIT :limit';
        $stmt = $this->db->prepare($sql);

        foreach ($bindings as $placeholder => $value) {
            $stmt->bindValue($placeholder, $placeholder === ':limit' ? (int) $value : $value, $placeholder === ':limit' ? PDO::PARAM_INT : PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getManagedWorkshopIds(string $employeeId): array
    {
        if ($employeeId === '') {
            return [];
        }

        $sql = 'SELECT IdXuong FROM xuong WHERE IdTruongXuong = :employeeId OR XUONGTRUONG_IdNhanVien = :employeeId
                UNION
                SELECT IdXuong FROM nhan_vien WHERE IdNhanVien = :employeeEmployeeId AND IdXuong IS NOT NULL';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':employeeId', $employeeId);
        $stmt->bindValue(':employeeEmployeeId', $employeeId);
        $stmt->execute();

        $ids = [];
        foreach ($stmt->fetchAll() as $row) {
            $id = $row['IdXuong'] ?? null;
            if ($id) {
                $ids[$id] = true;
            }
        }

        return array_keys($ids);
    }

    public function getCapacitySummary(?array $workshopIds = null): array
    {
        $bindings = [];
        $conditions = [];

        if ($workshopIds !== null) {
            $workshopIds = array_values(array_filter($workshopIds, 'strlen'));
            if (empty($workshopIds)) {
                return [
                    'total_workshops' => 0,
                    'max_capacity' => 0.0,
                    'current_capacity' => 0.0,
                    'workforce' => 0,
                    'utilization' => 0.0,
                ];
            }

            $placeholders = [];
            foreach ($workshopIds as $index => $workshopId) {
                $placeholder = ':id' . $index;
                $placeholders[] = $placeholder;
                $bindings[$placeholder] = $workshopId;
            }

            $conditions[] = 'IdXuong IN (' . implode(', ', $placeholders) . ')';
        }

        $sql = 'SELECT * FROM xuong';
        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $stmt = $this->db->prepare($sql);
        foreach ($bindings as $placeholder => $value) {
            $stmt->bindValue($placeholder, $value);
        }
        $stmt->execute();
        $rows = $stmt->fetchAll();

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

    public function getStatusDistribution(?array $workshopIds = null): array
    {
        $bindings = [];
        $conditions = [];

        if ($workshopIds !== null) {
            $workshopIds = array_values(array_filter($workshopIds, 'strlen'));
            if (empty($workshopIds)) {
                return [];
            }

            $placeholders = [];
            foreach ($workshopIds as $index => $workshopId) {
                $placeholder = ':id' . $index;
                $placeholders[] = $placeholder;
                $bindings[$placeholder] = $workshopId;
            }

            $conditions[] = 'IdXuong IN (' . implode(', ', $placeholders) . ')';
        }

        $sql = "SELECT TrangThai, COUNT(*) AS total FROM xuong";

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' GROUP BY TrangThai';

        $stmt = $this->db->prepare($sql);
        foreach ($bindings as $placeholder => $value) {
            $stmt->bindValue($placeholder, $value);
        }
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $result = [];
        foreach ($rows as $row) {
            $status = $row['TrangThai'] ?: 'Không xác định';
            $result[$status] = (int) $row['total'];
        }

        return $result;
    }
}
