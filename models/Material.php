<?php

class Material extends BaseModel
{
    protected string $table = 'nguyen_lieu';
    protected string $primaryKey = 'IdNguyenLieu';

    public function findMany(array $ids): array
    {
        $ids = array_values(array_filter(array_unique($ids)));
        if (empty($ids)) {
            return [];
        }

        $placeholders = implode(', ', array_fill(0, count($ids), '?'));
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} IN ({$placeholders})";
        $stmt = $this->db->prepare($sql);

        foreach ($ids as $index => $id) {
            $stmt->bindValue($index + 1, $id, PDO::PARAM_STR);
        }

        $stmt->execute();
        $rows = $stmt->fetchAll();

        $mapped = [];
        foreach ($rows as $row) {
            $mapped[$row[$this->primaryKey]] = $row;
        }

        return $mapped;
    }
}

