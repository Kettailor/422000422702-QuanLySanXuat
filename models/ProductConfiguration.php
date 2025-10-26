<?php

class ProductConfiguration extends BaseModel
{
    protected string $table = 'cau_hinh_san_pham';
    protected string $primaryKey = 'IdCauHinh';

    public function getByProduct(string $productId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE IdSanPham = :productId ORDER BY TenCauHinh");
        $stmt->bindValue(':productId', $productId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
