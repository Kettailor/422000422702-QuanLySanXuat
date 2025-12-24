<?php

class WarehouseRequest extends BaseModel
{
    protected string $table = 'yeu_cau_xuat_kho';
    protected string $primaryKey = 'IdYeuCau';

    public function createFromShortages(
        string $workshopPlanId,
        array $items,
        ?string $requesterId,
        ?string $note = null,
    ): ?string {
        if (empty($items)) {
            return null;
        }

        $recordId = uniqid('YCK');
        $payload = [
            'IdYeuCau' => $recordId,
            'IdKeHoachSanXuatXuong' => $workshopPlanId,
            'NguoiYeuCau' => $requesterId,
            'TrangThai' => 'Chờ xử lý',
            'NoiDung' => json_encode([
                'items' => $items,
                'note' => $note,
            ], JSON_UNESCAPED_UNICODE),
            'NgayTao' => date('Y-m-d H:i:s'),
        ];

        $this->create($payload);

        return $recordId;
    }

    public function getByPlan(string $workshopPlanId): array
    {
        $sql = 'SELECT *
                FROM yeu_cau_xuat_kho
                WHERE IdKeHoachSanXuatXuong = :planId
                ORDER BY NgayTao DESC, IdYeuCau DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':planId', $workshopPlanId);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getPendingByPlanIds(array $planIds): array
    {
        $planIds = array_values(array_filter($planIds));
        if (empty($planIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($planIds), '?'));
        $sql = 'SELECT *
                FROM yeu_cau_xuat_kho
                WHERE IdKeHoachSanXuatXuong IN (' . $placeholders . ')
                  AND (TrangThai IS NULL OR TrangThai NOT IN ("Hoàn thành", "Da hoan thanh"))
                ORDER BY NgayTao DESC, IdYeuCau DESC';

        $stmt = $this->db->prepare($sql);
        foreach ($planIds as $index => $planId) {
            $stmt->bindValue($index + 1, $planId);
        }
        $stmt->execute();

        $rows = $stmt->fetchAll();
        $grouped = [];
        foreach ($rows as $row) {
            $planId = $row['IdKeHoachSanXuatXuong'] ?? null;
            if (!$planId || isset($grouped[$planId])) {
                continue;
            }
            $grouped[$planId] = $row;
        }

        return $grouped;
    }

    public function markCompleted(string $requestId): bool
    {
        return $this->update($requestId, [
            'TrangThai' => 'Hoàn thành',
        ]);
    }
}
