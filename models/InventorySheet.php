<?php

class InventorySheet extends BaseModel
{
    protected string $table = 'PHIEU';
    protected string $primaryKey = 'IdPhieu';

    public function getDocuments(int $limit = 50): array
    {
        $sql = 'SELECT PHIEU.*, KHO.TenKho
                FROM PHIEU
                JOIN KHO ON KHO.IdKho = PHIEU.IdKho
                ORDER BY NgayLP DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
