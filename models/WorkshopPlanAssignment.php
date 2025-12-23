<?php

class WorkshopPlanAssignment extends BaseModel
{
    protected string $table = 'phan_cong_ke_hoach_xuong';
    protected string $primaryKey = 'IdPhanCong';

    public function getByPlan(string $planId): array
    {
        $sql = 'SELECT pc.IdPhanCong,
                       pc.IdKeHoachSanXuatXuong,
                       pc.IdNhanVien,
                       pc.IdCaLamViec,
                       pc.VaiTro,
                       nv.HoTen
                FROM phan_cong_ke_hoach_xuong pc
                LEFT JOIN nhan_vien nv ON nv.IdNhanVien = pc.IdNhanVien
                WHERE pc.IdKeHoachSanXuatXuong = :planId
                ORDER BY pc.IdCaLamViec, nv.HoTen, pc.IdPhanCong';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':planId', $planId);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getPlanIdsByEmployee(string $employeeId): array
    {
        $sql = 'SELECT DISTINCT IdKeHoachSanXuatXuong
                FROM phan_cong_ke_hoach_xuong
                WHERE IdNhanVien = :employeeId';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':employeeId', $employeeId);
        $stmt->execute();

        return array_values(array_filter(array_column($stmt->fetchAll(), 'IdKeHoachSanXuatXuong')));
    }

    public function isEmployeeAssignedToShift(string $employeeId, string $shiftId): bool
    {
        $sql = 'SELECT 1
                FROM phan_cong_ke_hoach_xuong
                WHERE IdNhanVien = :employeeId
                  AND IdCaLamViec = :shiftId
                LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':employeeId', $employeeId);
        $stmt->bindValue(':shiftId', $shiftId);
        $stmt->execute();

        return (bool) $stmt->fetchColumn();
    }

    public function isEmployeeAssignedForTimestamp(string $employeeId, string $timestamp): bool
    {
        $sql = 'SELECT 1
                FROM phan_cong_ke_hoach_xuong pc
                JOIN ca_lam ca ON ca.IdCaLamViec = pc.IdCaLamViec
                WHERE pc.IdNhanVien = :employeeId
                  AND :timestamp BETWEEN ca.ThoiGianBatDau AND ca.ThoiGianKetThuc
                LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':employeeId', $employeeId);
        $stmt->bindValue(':timestamp', $timestamp);
        $stmt->execute();

        return (bool) $stmt->fetchColumn();
    }

    public function replaceForPlanWithShifts(string $planId, array $assignmentsByShift, string $role = 'nhan_vien_san_xuat'): void
    {
        $this->db->beginTransaction();

        try {
            $delete = $this->db->prepare('DELETE FROM phan_cong_ke_hoach_xuong WHERE IdKeHoachSanXuatXuong = :planId');
            $delete->bindValue(':planId', $planId);
            $delete->execute();

            if (!empty($assignmentsByShift)) {
                $insert = $this->db->prepare(
                    'INSERT INTO phan_cong_ke_hoach_xuong (IdPhanCong, IdKeHoachSanXuatXuong, IdNhanVien, IdCaLamViec, VaiTro, NgayPhanCong)
                     VALUES (:id, :planId, :employeeId, :shiftId, :role, :assignedAt)'
                );
                $assignedAt = date('Y-m-d H:i:s');

                foreach ($assignmentsByShift as $shiftId => $employeeIds) {
                    $normalizedIds = array_values(array_unique(array_filter(array_map('trim', (array) $employeeIds))));
                    if (empty($normalizedIds)) {
                        continue;
                    }
                    foreach ($normalizedIds as $employeeId) {
                        $insert->bindValue(':id', uniqid('PC'));
                        $insert->bindValue(':planId', $planId);
                        $insert->bindValue(':employeeId', $employeeId);
                        $insert->bindValue(':shiftId', $shiftId);
                        $insert->bindValue(':role', $role);
                        $insert->bindValue(':assignedAt', $assignedAt);
                        $insert->execute();
                    }
                }
            }

            $this->db->commit();
        } catch (Throwable $exception) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $exception;
        }
    }

    public function syncByShiftPolicy(
        string $planId,
        array $assignmentsByShift,
        array $editableShiftIds,
        array $addOnlyShiftIds,
        string $role = 'nhan_vien_san_xuat'
    ): void {
        $this->db->beginTransaction();

        try {
            $existing = $this->getByPlan($planId);
            $existingMap = [];
            foreach ($existing as $row) {
                $shiftId = $row['IdCaLamViec'] ?? null;
                $employeeId = $row['IdNhanVien'] ?? null;
                if (!$shiftId || !$employeeId) {
                    continue;
                }
                $existingMap[$shiftId][$employeeId] = true;
            }

            if (!empty($editableShiftIds)) {
                $placeholders = implode(',', array_fill(0, count($editableShiftIds), '?'));
                $delete = $this->db->prepare(
                    'DELETE FROM phan_cong_ke_hoach_xuong
                     WHERE IdKeHoachSanXuatXuong = ?
                       AND IdCaLamViec IN (' . $placeholders . ')'
                );
                $delete->bindValue(1, $planId);
                $index = 2;
                foreach ($editableShiftIds as $shiftId) {
                    $delete->bindValue($index, $shiftId);
                    $index++;
                }
                $delete->execute();
            }

            $insert = $this->db->prepare(
                'INSERT INTO phan_cong_ke_hoach_xuong (IdPhanCong, IdKeHoachSanXuatXuong, IdNhanVien, IdCaLamViec, VaiTro, NgayPhanCong)
                 VALUES (:id, :planId, :employeeId, :shiftId, :role, :assignedAt)'
            );
            $assignedAt = date('Y-m-d H:i:s');

            foreach ($assignmentsByShift as $shiftId => $employeeIds) {
                $normalizedIds = array_values(array_unique(array_filter(array_map('trim', (array) $employeeIds))));
                if (empty($normalizedIds)) {
                    continue;
                }

                $isAddOnly = isset($addOnlyShiftIds[$shiftId]);
                foreach ($normalizedIds as $employeeId) {
                    if ($isAddOnly && isset($existingMap[$shiftId][$employeeId])) {
                        continue;
                    }

                    $insert->bindValue(':id', uniqid('PC'));
                    $insert->bindValue(':planId', $planId);
                    $insert->bindValue(':employeeId', $employeeId);
                    $insert->bindValue(':shiftId', $shiftId);
                    $insert->bindValue(':role', $role);
                    $insert->bindValue(':assignedAt', $assignedAt);
                    $insert->execute();
                }
            }

            $this->db->commit();
        } catch (Throwable $exception) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $exception;
        }
    }
}
