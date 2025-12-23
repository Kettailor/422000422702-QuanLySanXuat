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

    public function findShiftForTimestamp(string $timestamp): ?array
    {
        $sql = "SELECT ca.*
                FROM ca_lam ca
                WHERE :now BETWEEN ca.ThoiGianBatDau AND ca.ThoiGianKetThuc
                ORDER BY ca.ThoiGianBatDau DESC
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':now', $timestamp);
        $stmt->execute();

        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function ensureDefaultShiftsForPlan(string $planId, ?string $startTime, ?string $endTime): void
    {
        $startDate = $this->normalizeDate($startTime) ?? date('Y-m-d');
        $endDate = $this->normalizeDate($endTime) ?? $startDate;

        if ($startTime && $endTime) {
            $startTs = strtotime($startTime);
            $endTs = strtotime($endTime);
            if ($startTs !== false && $endTs !== false && $endTs < $startTs) {
                $endDate = date('Y-m-d', strtotime($startDate . ' +1 day'));
            }
        }

        if ($startDate > $endDate) {
            $endDate = $startDate;
        }

        $this->removeShiftsOutsideRange($planId, $startDate, $endDate);

        $existing = $this->getExistingShiftMap($planId);

        $ranges = [
            'Ca sáng' => ['06:30:00', '14:00:00'],
            'Ca trưa' => ['14:00:00', '22:00:00'],
            'Ca tối' => ['22:00:00', '06:00:00'],
        ];

        $defaultLotId = $this->getDefaultLotId();

        $date = new DateTimeImmutable($startDate);
        $end = new DateTimeImmutable($endDate);
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
                    'TenCa' => $label,
                    'LoaiCa' => 'Sản xuất',
                    'NgayLamViec' => $dateStr,
                    'ThoiGianBatDau' => $startAt,
                    'ThoiGianKetThuc' => $endAt,
                    'TongSL' => 0,
                    'IdKeHoachSanXuatXuong' => $planId,
                    'LOIdLo' => $defaultLotId,
                ]);
            }

            $date = $date->modify('+1 day');
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
            $type = $this->extractShiftType($label);
            if ($date && $type) {
                $map[$date . '|' . $type] = true;
            }
        }

        return $map;
    }

    private function normalizeDate(?string $value): ?string
    {
        if (!$value) {
            return null;
        }
        $value = trim($value);
        if (preg_match('/^\d{1,2}\/\d{1,2}$/', $value)) {
            $value .= '/' . date('Y');
        }

        $formats = ['Y-m-d H:i:s', 'Y-m-d', 'd/m/Y H:i:s', 'd/m/Y H:i', 'd/m/Y', 'd/m/y'];
        foreach ($formats as $format) {
            $date = DateTime::createFromFormat($format, $value);
            if ($date instanceof DateTime) {
                return $date->format('Y-m-d');
            }
        }

        $timestamp = strtotime($value);
        if ($timestamp === false) {
            return null;
        }
        return date('Y-m-d', $timestamp);
    }

    private function extractShiftType(?string $label): ?string
    {
        if (!$label) {
            return null;
        }

        $normalized = mb_strtolower($label, 'UTF-8');
        if (str_contains($normalized, 'sáng')) {
            return 'Ca sáng';
        }
        if (str_contains($normalized, 'trưa') || str_contains($normalized, 'chiều')) {
            return 'Ca trưa';
        }
        if (str_contains($normalized, 'tối')) {
            return 'Ca tối';
        }

        return null;
    }

    private function getDefaultLotId(): ?string
    {
        $stmt = $this->db->prepare('SELECT IdLo FROM lo ORDER BY NgayTao DESC, IdLo DESC LIMIT 1');
        $stmt->execute();
        $value = $stmt->fetchColumn();
        return $value !== false ? (string) $value : null;
    }

    private function removeShiftsOutsideRange(string $planId, string $startDate, string $endDate): void
    {
        $this->db->beginTransaction();
        try {
            $shiftSql = 'SELECT IdCaLamViec
                         FROM ca_lam
                         WHERE IdKeHoachSanXuatXuong = :planId
                           AND (NgayLamViec < :startDate OR NgayLamViec > :endDate)';
            $shiftStmt = $this->db->prepare($shiftSql);
            $shiftStmt->bindValue(':planId', $planId);
            $shiftStmt->bindValue(':startDate', $startDate);
            $shiftStmt->bindValue(':endDate', $endDate);
            $shiftStmt->execute();
            $shiftIds = $shiftStmt->fetchAll(PDO::FETCH_COLUMN);

            if (!empty($shiftIds)) {
                $placeholders = implode(',', array_fill(0, count($shiftIds), '?'));
                $assignmentSql = "DELETE FROM phan_cong_ke_hoach_xuong WHERE IdCaLamViec IN ({$placeholders})";
                $assignmentStmt = $this->db->prepare($assignmentSql);
                foreach ($shiftIds as $index => $shiftId) {
                    $assignmentStmt->bindValue($index + 1, $shiftId);
                }
                $assignmentStmt->execute();

                $attendanceSql = "DELETE FROM cham_cong WHERE IdCaLamViec IN ({$placeholders})";
                $attendanceStmt = $this->db->prepare($attendanceSql);
                foreach ($shiftIds as $index => $shiftId) {
                    $attendanceStmt->bindValue($index + 1, $shiftId);
                }
                $attendanceStmt->execute();

                $deleteSql = "DELETE FROM ca_lam WHERE IdCaLamViec IN ({$placeholders})";
                $deleteStmt = $this->db->prepare($deleteSql);
                foreach ($shiftIds as $index => $shiftId) {
                    $deleteStmt->bindValue($index + 1, $shiftId);
                }
                $deleteStmt->execute();
            }

            $this->db->commit();
        } catch (Throwable $exception) {
            $this->db->rollBack();
            throw $exception;
        }
    }
}
