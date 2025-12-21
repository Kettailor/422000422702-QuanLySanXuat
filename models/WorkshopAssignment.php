<?php

class WorkshopAssignment
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAssignmentsByWorkshop(string $workshopId): array
    {
        $sql = 'SELECT xnv.IdNhanVien, xnv.VaiTro, nv.HoTen
                FROM xuong_nhan_vien xnv
                JOIN nhan_vien nv ON nv.IdNhanVien = xnv.IdNhanVien
                WHERE xnv.IdXuong = :workshopId
                ORDER BY nv.HoTen';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':workshopId', $workshopId);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $assignments = [
            'truong_xuong' => [],
            'nhan_vien_kho' => [],
            'nhan_vien_san_xuat' => [],
        ];

        foreach ($rows as $row) {
            $role = $row['VaiTro'];
            if (!isset($assignments[$role])) {
                $assignments[$role] = [];
            }
            $assignments[$role][] = $row;
        }

        return $assignments;
    }

    public function getWorkshopsManagedBy(string $employeeId): array
    {
        $sql = 'SELECT IdXuong FROM xuong_nhan_vien WHERE IdNhanVien = :employeeId AND VaiTro = :role';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':employeeId', $employeeId);
        $stmt->bindValue(':role', 'truong_xuong');
        $stmt->execute();

        return array_column($stmt->fetchAll(), 'IdXuong');
    }

    public function getWorkshopsByEmployee(string $employeeId): array
    {
        $sql = 'SELECT DISTINCT IdXuong FROM xuong_nhan_vien WHERE IdNhanVien = :employeeId';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':employeeId', $employeeId);
        $stmt->execute();

        return array_column($stmt->fetchAll(), 'IdXuong');
    }

    public function syncAssignments(
        string $workshopId,
        ?string $managerId,
        array $warehouseIds,
        array $productionIds
    ): void {
        $this->db->beginTransaction();

        $delete = $this->db->prepare('DELETE FROM xuong_nhan_vien WHERE IdXuong = :workshopId');
        $delete->execute([':workshopId' => $workshopId]);

        $insert = $this->db->prepare(
            'INSERT INTO xuong_nhan_vien (IdXuong, IdNhanVien, VaiTro) VALUES (:workshopId, :employeeId, :role)'
        );

        $managerId = $managerId ?: null;
        if ($managerId) {
            $this->insertAssignment($insert, $workshopId, $managerId, 'truong_xuong');
        }

        foreach ($this->uniqueIds($warehouseIds) as $employeeId) {
            $this->insertAssignment($insert, $workshopId, $employeeId, 'nhan_vien_kho');
        }

        foreach ($this->uniqueIds($productionIds) as $employeeId) {
            $this->insertAssignment($insert, $workshopId, $employeeId, 'nhan_vien_san_xuat');
        }

        $this->db->commit();
    }

    private function insertAssignment(\PDOStatement $stmt, string $workshopId, string $employeeId, string $role): void
    {
        $stmt->execute([
            ':workshopId' => $workshopId,
            ':employeeId' => $employeeId,
            ':role' => $role,
        ]);
    }

    private function uniqueIds(array $ids): array
    {
        return array_values(array_unique(array_filter(array_map('trim', $ids))));
    }
}
