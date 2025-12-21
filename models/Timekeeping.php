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
        ?string $note = null
    ): bool {
        $recordId = uniqid('CC');
        $payload = [
            'IdChamCong' => $recordId,
            'NHANVIEN IdNhanVien' => $employeeId,
            'ThoiGianVao' => $checkIn,
            'ThoiGIanRa' => $checkOut,
            'GhiChu' => $this->buildNote($planId, $note),
        ];

        return $this->create($payload);
    }

    public function getRecentByPlan(?string $planId, int $limit = 30): array
    {
        $conditions = [];
        $bindings = [];

        if ($planId) {
            $conditions[] = 'GhiChu LIKE :planPattern';
            $bindings[':planPattern'] = '%PLAN:' . $planId . '%';
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

    private function buildNote(?string $planId, ?string $note): ?string
    {
        $segments = [];
        if ($planId) {
            $segments[] = 'PLAN:' . $planId;
        }
        if ($note) {
            $segments[] = trim($note);
        }

        if (empty($segments)) {
            return null;
        }

        return implode(' | ', $segments);
    }
}
