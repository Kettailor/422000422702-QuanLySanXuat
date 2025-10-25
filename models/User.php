<?php

class User extends BaseModel
{
    protected string $table = 'nguoi_dung';
    protected string $primaryKey = 'IdNguoiDung';

    public function findByUsername(string $username): ?array
    {
        $sql = 'SELECT * FROM nguoi_dung WHERE TenDangNhap = :username LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function getLastUserId(): ?string
    {
        $sql = 'SELECT IdNguoiDung FROM NGUOI_DUNG ORDER BY IdNguoiDung DESC LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result ? $result['IdNguoiDung'] : null;
    }

    public function countActiveUsers(): int
    {
        $sql = 'SELECT COUNT(*) as count FROM NGUOI_DUNG WHERE TrangThai = :status';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':status', 'Hoạt động');
        $stmt->execute();
        $result = $stmt->fetch();
        return (int)$result['count'];
    }

    public function findAllWithEmployeeAndRole(
        int $page = 1,
        int $limit = 20
    ): array {
        $stmt = $this->db->prepare('
          SELECT ND.IdNguoiDung, ND.TenDangNhap, ND.TrangThai,
            VT.IdVaiTro, VT.TenVaiTro,
            NV.IdNhanVien, NV.HoTen, NV.ChucVu
          FROM NGUOI_DUNG ND
          JOIN NHAN_VIEN NV ON ND.IdNhanVien = NV.IdNhanVien
          JOIN VAI_TRO VT ON ND.IdVaiTro = VT.IdVaiTro
          ORDER BY ND.IdNguoiDung
          LIMIT :limit OFFSET :offset
        ');
        $stmt->execute([
            ':limit' => $limit,
            ':offset' => ($page - 1) * $limit
        ]);
        $users = $stmt->fetchAll();

        $stmt = $this->db->prepare('
          SELECT COUNT(*) as count
          FROM NGUOI_DUNG ND
        ');
        $stmt->execute();
        $total = $stmt->fetch()['count'];
        $totalPages = ceil($total / $limit);

        return [
          'data' => $users,
          'total' => (int)$total,
          'page' => $page,
          'limit' => $limit,
          'totalPages' => $totalPages
        ];
    }
}
