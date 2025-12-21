<?php

class WorkShift extends BaseModel
{
    protected string $table = 'ca_lam';
    protected string $primaryKey = 'IdCaLamViec';

    public function getShifts(?string $workDate = null, int $limit = 200): array
    {
        $conditions = [];
        $bindings = [];

        if ($workDate) {
            $conditions[] = 'ca.NgayLamViec = :workDate';
            $bindings[':workDate'] = $workDate;
        }

        $where = $conditions ? ('WHERE ' . implode(' AND ', $conditions)) : '';

        $sql = "SELECT ca.*
                FROM ca_lam ca
                {$where}
                ORDER BY ca.NgayLamViec DESC, ca.ThoiGianBatDau ASC
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        foreach ($bindings as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getShiftsByPlan(string $planId): array
    {
        $sql = "SELECT ca.*
                FROM ca_lam ca
                WHERE ca.IdKeHoachSanXuatXuong = :planId
                ORDER BY ca.NgayLamViec DESC, ca.ThoiGianBatDau ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':planId', $planId);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function ensureDefaultShiftsForPlan(string $planId, ?string $startTime, ?string $endTime): void
    {
        $startDate = $this->normalizeDate($startTime) ?? date('Y-m-d');
        $endDate = $this->normalizeDate($endTime) ?? $startDate;

        if ($startDate > $endDate) {
            $endDate = $startDate;
        }

        $existing = $this->getExistingShiftMap($planId);

        $ranges = [
            'Ca sáng' => ['06:30:00', '14:00:00'],
            'Ca trưa' => ['14:00:00', '22:00:00'],
            'Ca tối' => ['22:00:00', '06:00:00'],
        ];

        $defaultLotId = $this->getDefaultLotId();
        if (!$defaultLotId) {
            $defaultLotId = 'LOTP202309';
        }

        $date = new DateTime($startDate);
        $end = new DateTime($endDate);
        while ($date <= $end) {
            $dateStr = $date->format('Y-m-d');
            foreach ($ranges as $label => [$start, $endHour]) {
                $key = $dateStr . '|' . $label;
                if (isset($existing[$key])) {
                    continue;
                }

                $startAt = $dateStr . ' ' . $start;
                $endAt = $dateStr . ' ' . $endHour;
                if ($label === 'Ca tối') {
                    $nextDay = (clone $date)->modify('+1 day')->format('Y-m-d');
                    $endAt = $nextDay . ' ' . $endHour;
                }

                $this->create([
                    'IdCaLamViec' => uniqid('CA'),
                    'TenCa' => $label . ' (' . $dateStr . ')',
                    'LoaiCa' => 'Sản xuất',
                    'NgayLamViec' => $dateStr,
                    'ThoiGianBatDau' => $startAt,
                    'ThoiGianKetThuc' => $endAt,
                    'TongSL' => 0,
                    'IdKeHoachSanXuatXuong' => $planId,
                    'LOIdLo' => $defaultLotId,
                ]);
            }

            $date->modify('+1 day');
        }
    }

    private function getExistingShiftMap(string $planId): array
    {
        $sql = "SELECT IdCaLamViec, TenCa, NgayLamViec
                FROM ca_lam
                WHERE IdKeHoachSanXuatXuong = :planId";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':planId', $planId);
        $stmt->execute();

        $map = [];
        foreach ($stmt->fetchAll() as $row) {
            $date = $row['NgayLamViec'] ?? null;
            $label = $row['TenCa'] ?? null;
            if ($date && $label) {
                $map[$date . '|' . $label] = true;
            }
        }

        return $map;
    }

    private function normalizeDate(?string $value): ?string
    {
        if (!$value) {
            return null;
        }
        $timestamp = strtotime($value);
        if ($timestamp === false) {
            return null;
        }
        return date('Y-m-d', $timestamp);
    }

    private function getDefaultLotId(): ?string
    {
        $stmt = $this->db->prepare('SELECT IdLo FROM lo ORDER BY NgayTao DESC, IdLo DESC LIMIT 1');
        $stmt->execute();
        $value = $stmt->fetchColumn();
        return $value !== false ? (string) $value : null;
    }
}
