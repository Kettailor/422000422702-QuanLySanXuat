<?php

class ProductComponent extends BaseModel
{
    protected string $table = 'san_pham_cong_doan';
    protected string $primaryKey = 'IdCongDoan';

    public function getByProduct(string $productId): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE IdSanPham = :productId ORDER BY ThuTu, TenCongDoan";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':productId', $productId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getDefaultComponents(): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE IdSanPham IS NULL ORDER BY ThuTu, TenCongDoan";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
