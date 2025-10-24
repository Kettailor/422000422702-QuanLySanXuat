<?php

class User extends BaseModel
{
    protected string $table = 'NGUOI_DUNG';
    protected string $primaryKey = 'IdNguoiDung';

    public function findByUsername(string $username): ?array
    {
        $sql = 'SELECT * FROM NGUOI_DUNG WHERE TenDangNhap = :username LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result ?: null;
    }
}
