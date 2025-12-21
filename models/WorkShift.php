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
}
