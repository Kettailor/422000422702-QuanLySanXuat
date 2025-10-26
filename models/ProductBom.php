<?php

class ProductBom extends BaseModel
{
    protected string $table = 'product_components';
    protected string $primaryKey = 'IdBOM';

    public function getByProduct(string $productId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE IdSanPham = :productId ORDER BY TenBOM");
        $stmt->bindValue(':productId', $productId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
