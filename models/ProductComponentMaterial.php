<?php

class ProductComponentMaterial extends BaseModel
{
    protected string $table = 'cong_doan_nguyen_lieu';
    protected string $primaryKey = 'IdCongDoanNguyenLieu';

    public function getRawByComponent(string $componentId): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE IdCongDoan = :componentId ORDER BY IdCongDoanNguyenLieu";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':componentId', $componentId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getMaterialsForComponent(string $componentId): array
    {
        $rows = $this->getRawByComponent($componentId);

        if (empty($rows)) {
            return [];
        }

        return array_map(function (array $row): array {
            return [
                'id' => $row['IdNguyenLieu'],
                'quantity_per_unit' => $row['TyLeSoLuong'] ?? 1,
                'label' => $row['Nhan'] ?? null,
                'unit' => $row['DonVi'] ?? null,
            ];
        }, $rows);
    }
}
