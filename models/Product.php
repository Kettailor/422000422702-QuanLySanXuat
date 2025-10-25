<?php

class Product extends BaseModel
{
    protected string $table = 'SAN_PHAM';
    protected string $primaryKey = 'IdSanPham';

    public function findByName(string $name): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE TenSanPham = :name LIMIT 1");
        $stmt->bindValue(':name', $name);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result ?: null;
    }
}
