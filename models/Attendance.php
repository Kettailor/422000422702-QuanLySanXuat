<?php

class Attendance extends BaseModel
{
    protected string $table = 'cham_cong';

    /**
     * @return string[] list of YYYY-MM strings sorted desc
     */
    public function getAvailablePeriods(): array
    {
        $sql = "SELECT DISTINCT DATE_FORMAT(`ThoiGianVao`, '%Y-%m') AS period
                FROM `cham_cong`
                WHERE `ThoiGianVao` IS NOT NULL
                ORDER BY period DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $periods = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (!empty($row['period'])) {
                $periods[] = $row['period'];
            }
        }

        return $periods;
    }

    /**
     * @param string $period YYYY-MM
     * @return array<int, array<string, mixed>>
     */
    public function getMonthlySummary(string $period): array
    {
        $sql = "SELECT nv.IdNhanVien AS employee_id,
                       nv.HoTen AS employee_name,
                       COALESCE(SUM(GREATEST(TIMESTAMPDIFF(MINUTE, cc.`ThoiGianVao`, cc.`ThoiGIanRa`), 0)), 0) AS total_minutes
                FROM nhan_vien nv
                LEFT JOIN cham_cong cc ON cc.`NHANVIEN IdNhanVien` = nv.IdNhanVien
                    AND cc.`ThoiGianVao` IS NOT NULL
                    AND cc.`ThoiGIanRa` IS NOT NULL
                    AND DATE_FORMAT(cc.`ThoiGianVao`, '%Y-%m') = :period
                WHERE nv.TrangThai = :status
                GROUP BY nv.IdNhanVien, nv.HoTen
                ORDER BY nv.HoTen";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':period', $period);
        $stmt->bindValue(':status', 'Đang làm việc');
        $stmt->execute();

        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $minutes = max((float) ($row['total_minutes'] ?? 0), 0.0);
            $hours = round($minutes / 60, 2);
            $workingDays = round($hours / 8, 2);
            $results[] = [
                'employee_id' => $row['employee_id'],
                'employee_name' => $row['employee_name'],
                'total_minutes' => $minutes,
                'total_hours' => $hours,
                'working_days' => $workingDays,
            ];
        }

        return $results;
    }

    /**
     * @param string[] $employeeIds
     * @return array<int, array<string, mixed>>
     */
    public function getSummaryForEmployees(array $employeeIds, ?string $start = null, ?string $end = null): array
    {
        $employeeIds = array_values(array_filter(array_unique($employeeIds)));
        if (empty($employeeIds)) {
            return [];
        }

        $conditions = [];
        $bindings = [];

        $placeholders = implode(', ', array_fill(0, count($employeeIds), '?'));
        $conditions[] = "cc.`NHANVIEN IdNhanVien` IN ({$placeholders})";

        foreach ($employeeIds as $index => $employeeId) {
            $bindings[$index + 1] = $employeeId;
        }

        if ($start) {
            $conditions[] = 'cc.`ThoiGianVao` >= :startTime';
            $bindings[':startTime'] = $start;
        }

        if ($end) {
            $conditions[] = 'cc.`ThoiGianVao` <= :endTime';
            $bindings[':endTime'] = $end;
        }

        $where = $conditions ? ('WHERE ' . implode(' AND ', $conditions)) : '';

        $sql = "SELECT cc.`NHANVIEN IdNhanVien` AS employee_id,
                       nv.HoTen AS employee_name,
                       COUNT(*) AS sessions,
                       COALESCE(SUM(GREATEST(TIMESTAMPDIFF(MINUTE, cc.`ThoiGianVao`, COALESCE(cc.`ThoiGIanRa`, cc.`ThoiGianVao`)), 0)), 0) AS total_minutes,
                       MIN(cc.`ThoiGianVao`) AS first_checkin,
                       MAX(cc.`ThoiGIanRa`) AS last_checkout
                FROM cham_cong cc
                JOIN nhan_vien nv ON nv.IdNhanVien = cc.`NHANVIEN IdNhanVien`
                {$where}
                GROUP BY cc.`NHANVIEN IdNhanVien`, nv.HoTen
                ORDER BY nv.HoTen";

        $stmt = $this->db->prepare($sql);
        foreach ($bindings as $key => $value) {
            if (is_int($key)) {
                $stmt->bindValue($key, $value);
            } else {
                $stmt->bindValue($key, $value);
            }
        }
        $stmt->execute();

        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $minutes = max((float) ($row['total_minutes'] ?? 0), 0.0);
            $hours = round($minutes / 60, 2);
            $workingDays = round($hours / 8, 2);
            $results[] = [
                'employee_id' => $row['employee_id'],
                'employee_name' => $row['employee_name'],
                'sessions' => (int) ($row['sessions'] ?? 0),
                'total_minutes' => $minutes,
                'total_hours' => $hours,
                'working_days' => $workingDays,
                'first_checkin' => $row['first_checkin'] ?? null,
                'last_checkout' => $row['last_checkout'] ?? null,
            ];
        }

        return $results;
    }
}
