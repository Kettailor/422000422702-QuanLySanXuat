<?php

class InventoryLot extends BaseModel
{
    protected string $table = 'lo';
    protected string $primaryKey = 'IdLo';

    public function getLotsByWarehouse(string $warehouseId): array
    {
        $sql = 'SELECT * FROM lo WHERE IdKho = :warehouse ORDER BY NgayTao DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':warehouse', $warehouseId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
