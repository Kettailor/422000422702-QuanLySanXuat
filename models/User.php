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
}
