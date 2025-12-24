<?php

class Timekeeping extends BaseModel
{
    protected string $table = 'cham_cong';
    protected string $primaryKey = 'IdChamCong';

    public function createForShift(
        string $employeeId,
        string $checkIn,
        ?string $checkOut,
        string $shiftId,
        ?string $note = null,
        ?string $supervisorId = null,
        ?float $checkInLat = null,
        ?float $checkInLng = null,
        ?float $checkInAccuracy = null,
    ): bool {
        $recordId = uniqid('CC');
        $payload = [
            'IdChamCong' => $recordId,
            'NHANVIEN IdNhanVien' => $employeeId,
            'ThoiGianVao' => $checkIn,
            'ThoiGIanRa' => $checkOut,
            'ViTriVaoLat' => $checkInLat,
            'ViTriVaoLng' => $checkInLng,
            'ViTriVaoAccuracy' => $checkInAccuracy,
            'IdCaLamViec' => $shiftId,
            'GhiChu' => $note ? trim($note) : null,
            'XUONGTRUONG IdNhanVien' => $supervisorId,
        ];

        return $this->create($payload);
    }

    public function getRecentByPlan(?string $planId, int $limit = 30): array
    {
        $conditions = [];
        $bindings = [];

        if ($planId) {
            $conditions[] = 'ca.`IdKeHoachSanXuatXuong` = :planId';
            $bindings[':planId'] = $planId;
        }

        $where = $conditions ? ('WHERE ' . implode(' AND ', $conditions)) : '';

        $sql = "SELECT cc.*, nv.HoTen AS TenNhanVien, ca.TenCa, ca.NgayLamViec
                FROM cham_cong cc
                LEFT JOIN ca_lam ca ON ca.IdCaLamViec = cc.`IdCaLamViec`
                LEFT JOIN nhan_vien nv ON nv.IdNhanVien = cc.`NHANVIEN IdNhanVien`
                {$where}
                ORDER BY cc.`ThoiGianVao` DESC
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        foreach ($bindings as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getRecentRecords(
        int $limit = 100,
        ?string $shiftId = null,
        ?string $workDate = null,
        ?string $workshopId = null,
        ?string $planId = null,
        ?string $employeeId = null,
    ): array {
        $conditions = [];
        $bindings = [];

        if ($shiftId) {
            $conditions[] = 'cc.`IdCaLamViec` = :shiftId';
            $bindings[':shiftId'] = $shiftId;
        }

        if ($workDate) {
            $conditions[] = 'ca.NgayLamViec = :workDate';
            $bindings[':workDate'] = $workDate;
        }

        if ($workshopId) {
            $conditions[] = 'kx.IdXuong = :workshopId';
            $bindings[':workshopId'] = $workshopId;
        }

        if ($planId) {
            $conditions[] = 'kx.IdKeHoachSanXuatXuong = :planId';
            $bindings[':planId'] = $planId;
        }

        if ($employeeId) {
            $conditions[] = 'cc.`NHANVIEN IdNhanVien` = :employeeId';
            $bindings[':employeeId'] = $employeeId;
        }

        $where = $conditions ? ('WHERE ' . implode(' AND ', $conditions)) : '';

        $sql = "SELECT cc.*, nv.HoTen AS TenNhanVien,
                       ca.TenCa,
                       ca.LoaiCa,
                       ca.NgayLamViec,
                       ca.ThoiGianBatDau,
                       ca.ThoiGianKetThuc,
                       kx.IdKeHoachSanXuatXuong,
                       kx.IdKeHoachSanXuat,
                       kx.TenThanhThanhPhanSP,
                       kx.IdXuong,
                       xuong.TenXuong
                FROM cham_cong cc
                LEFT JOIN nhan_vien nv ON nv.IdNhanVien = cc.`NHANVIEN IdNhanVien`
                LEFT JOIN ca_lam ca ON ca.IdCaLamViec = cc.`IdCaLamViec`
                LEFT JOIN ke_hoach_san_xuat_xuong kx ON kx.IdKeHoachSanXuatXuong = ca.IdKeHoachSanXuatXuong
                LEFT JOIN xuong ON xuong.IdXuong = kx.IdXuong
                {$where}
                ORDER BY cc.`ThoiGianVao` DESC
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        foreach ($bindings as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getOpenRecordForEmployee(string $employeeId, ?string $workDate = null): ?array
    {
        $conditions = ['cc.`NHANVIEN IdNhanVien` = :employeeId', 'cc.`ThoiGIanRa` IS NULL'];
        $bindings = [':employeeId' => $employeeId];

        if ($workDate) {
            $conditions[] = 'DATE(cc.`ThoiGianVao`) = :workDate';
            $bindings[':workDate'] = $workDate;
        }

        $where = 'WHERE ' . implode(' AND ', $conditions);
        $sql = "SELECT cc.*
                FROM cham_cong cc
                {$where}
                ORDER BY cc.`ThoiGianVao` DESC
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        foreach ($bindings as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function updateCheckOut(
        string $recordId,
        string $checkOut,
        ?float $checkOutLat = null,
        ?float $checkOutLng = null,
        ?float $checkOutAccuracy = null,
    ): bool {
        $sql = "UPDATE cham_cong
                SET `ThoiGIanRa` = :checkOut,
                    `ViTriRaLat` = :checkOutLat,
                    `ViTriRaLng` = :checkOutLng,
                    `ViTriRaAccuracy` = :checkOutAccuracy
                WHERE `IdChamCong` = :recordId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':checkOut', $checkOut);
        $stmt->bindValue(':checkOutLat', $checkOutLat);
        $stmt->bindValue(':checkOutLng', $checkOutLng);
        $stmt->bindValue(':checkOutAccuracy', $checkOutAccuracy);
        $stmt->bindValue(':recordId', $recordId);
        return $stmt->execute();
    }

    public function getMonthlySummary(string $employeeId, ?string $month = null): array
    {
        $month = $month ?: date('Y-m');

        $sql = "SELECT COUNT(*) AS total_records,
                       SUM(CASE
                           WHEN cc.`ThoiGIanRa` IS NOT NULL THEN TIMESTAMPDIFF(MINUTE, cc.`ThoiGianVao`, cc.`ThoiGIanRa`)
                           ELSE 0
                       END) AS total_minutes
                FROM cham_cong cc
                WHERE cc.`NHANVIEN IdNhanVien` = :employeeId
                  AND DATE_FORMAT(cc.`ThoiGianVao`, '%Y-%m') = :month";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':employeeId', $employeeId);
        $stmt->bindValue(':month', $month);
        $stmt->execute();

        $row = $stmt->fetch();
        $totalMinutes = (int) ($row['total_minutes'] ?? 0);
        $totalHours = $totalMinutes > 0 ? round($totalMinutes / 60, 1) : 0.0;

        return [
            'month' => $month,
            'total_records' => (int) ($row['total_records'] ?? 0),
            'total_hours' => $totalHours,
            'total_minutes' => $totalMinutes,
        ];
    }

}
