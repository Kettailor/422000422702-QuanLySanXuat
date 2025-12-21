<?php

class Timekeeping extends BaseModel
{
    protected string $table = 'cham_cong';
    protected string $primaryKey = 'IdChamCong';

    public function createForPlan(
        string $employeeId,
        string $checkIn,
        ?string $checkOut,
        ?string $planId,
        ?string $note = null,
        ?string $supervisorId = null,
        ?string $shiftId = null
    ): bool {
        $recordId = uniqid('CC');
        $payload = [
            'IdChamCong' => $recordId,
            'NHANVIEN IdNhanVien' => $employeeId,
            'ThoiGianVao' => $checkIn,
            'ThoiGIanRa' => $checkOut,
            'IdKeHoachSanXuatXuong' => $planId,
            'GhiChu' => $note ? trim($note) : null,
            'XUONGTRUONG IdNhanVien' => $supervisorId,
            'IdCaLamViec' => $shiftId,
        ];

        return $this->create($payload);
    }

    public function getRecentByPlan(?string $planId, int $limit = 30): array
    {
        $conditions = [];
        $bindings = [];

        if ($planId) {
            $conditions[] = 'cc.`IdKeHoachSanXuatXuong` = :planId';
            $bindings[':planId'] = $planId;
        }

        $where = $conditions ? ('WHERE ' . implode(' AND ', $conditions)) : '';

        $sql = "SELECT cc.*, nv.HoTen AS TenNhanVien
                FROM cham_cong cc
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

    public function getRecentRecords(int $limit = 100, ?string $planId = null): array
    {
        $conditions = [];
        $bindings = [];

        if ($planId) {
            $conditions[] = 'cc.`IdKeHoachSanXuatXuong` = :planId';
            $bindings[':planId'] = $planId;
        }

        $where = $conditions ? ('WHERE ' . implode(' AND ', $conditions)) : '';

        $sql = "SELECT cc.*, nv.HoTen AS TenNhanVien,
                       kx.TenThanhThanhPhanSP,
                       kx.IdKeHoachSanXuatXuong
                FROM cham_cong cc
                LEFT JOIN nhan_vien nv ON nv.IdNhanVien = cc.`NHANVIEN IdNhanVien`
                LEFT JOIN ke_hoach_san_xuat_xuong kx ON kx.IdKeHoachSanXuatXuong = cc.`IdKeHoachSanXuatXuong`
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

}
