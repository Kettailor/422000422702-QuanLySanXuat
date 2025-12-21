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
}
