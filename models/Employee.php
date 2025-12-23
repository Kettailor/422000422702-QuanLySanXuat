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
        $stmt = $this->db->prepare('SELECT nv.IdNhanVien, nv.HoTen, nv.ChucVu
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
