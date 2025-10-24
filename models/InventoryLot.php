<?php

class InventoryLot extends BaseModel
{
    protected string $table = 'LO';
    protected string $primaryKey = 'IdLo';

    public function getLotsByWarehouse(string $warehouseId): array
    {
        $sql = 'SELECT * FROM LO WHERE IdKho = :warehouse ORDER BY NgayTao DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':warehouse', $warehouseId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
