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
                       pc.VaiTro,
                       nv.HoTen
                FROM phan_cong_ke_hoach_xuong pc
                LEFT JOIN nhan_vien nv ON nv.IdNhanVien = pc.IdNhanVien
                WHERE pc.IdKeHoachSanXuatXuong = :planId
                ORDER BY nv.HoTen, pc.IdPhanCong';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':planId', $planId);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function replaceForPlan(string $planId, array $employeeIds, string $role = 'nhan_vien_san_xuat'): void
    {
        $employeeIds = array_values(array_unique(array_filter(array_map('trim', $employeeIds))));

        $this->db->beginTransaction();

        try {
            $delete = $this->db->prepare('DELETE FROM phan_cong_ke_hoach_xuong WHERE IdKeHoachSanXuatXuong = :planId');
            $delete->bindValue(':planId', $planId);
            $delete->execute();

            if (!empty($employeeIds)) {
                $insert = $this->db->prepare(
                    'INSERT INTO phan_cong_ke_hoach_xuong (IdPhanCong, IdKeHoachSanXuatXuong, IdNhanVien, VaiTro, NgayPhanCong)
                     VALUES (:id, :planId, :employeeId, :role, :assignedAt)'
                );
                $assignedAt = date('Y-m-d H:i:s');

                foreach ($employeeIds as $employeeId) {
                    $insert->bindValue(':id', uniqid('PC'));
                    $insert->bindValue(':planId', $planId);
                    $insert->bindValue(':employeeId', $employeeId);
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
