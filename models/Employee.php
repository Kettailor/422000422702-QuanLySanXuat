<?php

class Employee extends BaseModel
{
    protected string $table = 'nhan_vien';
    protected string $primaryKey = 'IdNhanVien';

    public function getActiveEmployees(): array
    {
        $sql = 'SELECT * FROM nhan_vien WHERE TrangThai = :status ORDER BY HoTen';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':status', 'Đang làm việc');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getActiveEmployeesByWorkshop(string $workshopId): array
    {
        $sql = 'SELECT DISTINCT nv.*
                FROM nhan_vien nv
                LEFT JOIN xuong_nhan_vien xnv ON xnv.IdNhanVien = nv.IdNhanVien
                WHERE nv.TrangThai = ?
                  AND (nv.idXuong = ? OR xnv.IdXuong = ?)
                ORDER BY nv.HoTen';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, 'Đang làm việc');
        $stmt->bindValue(2, $workshopId);
        $stmt->bindValue(3, $workshopId);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getActiveEmployeesByWorkshops(array $workshopIds): array
    {
        $workshopIds = array_values(array_filter($workshopIds));
        if (empty($workshopIds)) {
            return [];
        }

        $placeholderString = implode(', ', array_fill(0, count($workshopIds), '?'));

        $sql = 'SELECT DISTINCT nv.*
                FROM nhan_vien nv
                LEFT JOIN xuong_nhan_vien xnv ON xnv.IdNhanVien = nv.IdNhanVien
                WHERE nv.TrangThai = ?
                  AND (nv.idXuong IN (' . $placeholderString . ')
                       OR xnv.IdXuong IN (' . $placeholderString . '))
                ORDER BY nv.HoTen';

        $stmt = $this->db->prepare($sql);
        $paramIndex = 1;
        $stmt->bindValue($paramIndex++, 'Đang làm việc');
        foreach ($workshopIds as $workshopId) {
            $stmt->bindValue($paramIndex++, $workshopId);
        }
        foreach ($workshopIds as $workshopId) {
            $stmt->bindValue($paramIndex++, $workshopId);
        }
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getWorkshopIdsForEmployee(?string $employeeId): array
    {
        if ($employeeId === null || $employeeId === '') {
            return [];
        }

        $sql = 'SELECT DISTINCT IdXuong FROM (
                    SELECT idXuong AS IdXuong FROM nhan_vien WHERE IdNhanVien = ?
                    UNION ALL
                    SELECT IdXuong FROM xuong_nhan_vien WHERE IdNhanVien = ?
                    UNION ALL
                    SELECT IdXuong FROM xuong WHERE XUONGTRUONG_IdNhanVien = ?
                ) AS workshop_ids
                WHERE IdXuong IS NOT NULL AND IdXuong <> ""';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $employeeId);
        $stmt->bindValue(2, $employeeId);
        $stmt->bindValue(3, $employeeId);
        $stmt->execute();

        return array_values(array_unique($stmt->fetchAll(PDO::FETCH_COLUMN) ?: []));
    }

    public function isEmployeeInWorkshop(?string $employeeId, ?string $workshopId): bool
    {
        if ($employeeId === null || $employeeId === '' || $workshopId === null || $workshopId === '') {
            return false;
        }

        $sql = 'SELECT 1
                FROM (
                    SELECT idXuong AS IdXuong FROM nhan_vien WHERE IdNhanVien = ?
                    UNION ALL
                    SELECT IdXuong FROM xuong_nhan_vien WHERE IdNhanVien = ?
                    UNION ALL
                    SELECT IdXuong FROM xuong WHERE XUONGTRUONG_IdNhanVien = ?
                ) AS workshop_ids
                WHERE IdXuong = ?
                LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $employeeId);
        $stmt->bindValue(2, $employeeId);
        $stmt->bindValue(3, $employeeId);
        $stmt->bindValue(4, $workshopId);
        $stmt->execute();

        return (bool) $stmt->fetchColumn();
    }

    public function getBoardManagers(): array
    {
        $sql = 'SELECT *
                FROM nhan_vien
                WHERE TrangThai = :status
                  AND (ChucVu LIKE :keyword OR ChucVu LIKE :keywordShort)
                ORDER BY HoTen';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':status', 'Đang làm việc');
        $stmt->bindValue(':keyword', '%giám đốc%');
        $stmt->bindValue(':keywordShort', '%Ban giám đốc%');
        $stmt->execute();
        $rows = $stmt->fetchAll();

        if ($rows) {
            return $rows;
        }

        return $this->getActiveEmployees();
    }

    public function countActiveEmployees(): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(nhan_vien.IdNhanVien) AS count FROM nhan_vien');
        $stmt->execute();
        $result = $stmt->fetch();
        return (int) $result['count'];
    }

    public function getUnassignedEmployees(): array
    {
        $stmt = $this->db->prepare('SELECT nv.IdNhanVien, nv.HoTen
          FROM NHAN_VIEN nv
          LEFT JOIN NGUOI_DUNG nd ON nv.IdNhanVien = nd.IdNhanVien
          WHERE nd.IdNhanVien IS NULL');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getEmployeesByRoleIds(array $roleIds, ?string $status = null): array
    {
        $roleIds = array_values(array_filter(array_map('trim', $roleIds), static fn($id) => $id !== ''));
        if (empty($roleIds)) {
            return [];
        }

        $placeholders = implode(', ', array_fill(0, count($roleIds), '?'));
        $sql = 'SELECT * FROM nhan_vien WHERE IdVaiTro IN (' . $placeholders . ')';
        if ($status !== null && $status !== '') {
            $sql .= ' AND TrangThai = ?';
        }
        $sql .= ' ORDER BY HoTen';
        $stmt = $this->db->prepare($sql);
        foreach ($roleIds as $index => $roleId) {
            $stmt->bindValue($index + 1, $roleId);
        }
        if ($status !== null && $status !== '') {
            $stmt->bindValue(count($roleIds) + 1, $status);
        }
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
