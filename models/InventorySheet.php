<?php

class InventorySheet extends BaseModel
{
    protected string $table = 'phieu';
    protected string $primaryKey = 'IdPhieu';

    public function getDocuments(int $limit = 50): array
    {
        $sql = 'SELECT phieu.*, kho.TenKho
                FROM phieu
                JOIN kho ON kho.IdKho = phieu.IdKho
                ORDER BY NgayLP DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
