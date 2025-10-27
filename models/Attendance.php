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
}
