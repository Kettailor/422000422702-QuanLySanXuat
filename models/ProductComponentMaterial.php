<?php

class ProductComponentMaterial extends BaseModel
{
    protected string $table = 'cong_doan_nguyen_lieu';
    protected string $primaryKey = 'IdCongDoanNguyenLieu';

    public function getMaterialsForComponent(string $componentId): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE IdCongDoan = :componentId ORDER BY IdCongDoanNguyenLieu";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':componentId', $componentId);
        $stmt->execute();

        $rows = $stmt->fetchAll();
        if (empty($rows)) {
            return [];
        }

        return array_map(function (array $row): array {
            $ratio = $row['TyLeSoLuong'] ?? null;
            $standard = $row['DinhMuc'] ?? null;
            return [
                'id' => $row['IdNguyenLieu'],
                'quantity_per_unit' => $ratio !== null ? $ratio : ($standard ?? 1),
                'standard_quantity' => $standard,
                'label' => $row['Nhan'] ?? null,
                'unit' => $row['DonVi'] ?? null,
                'configuration_id' => $row['IdCauHinh'] ?? null,
            ];
        }, $rows);
    }

    public function getMaterialsForComponents(array $componentIds): array
    {
        $componentIds = array_values(array_filter(array_unique($componentIds)));
        if (empty($componentIds)) {
            return [];
        }

        $placeholders = implode(', ', array_fill(0, count($componentIds), '?'));
        $sql = "SELECT * FROM {$this->table} WHERE IdCongDoan IN ({$placeholders}) ORDER BY IdCongDoan, IdCongDoanNguyenLieu";
        $stmt = $this->db->prepare($sql);

        foreach ($componentIds as $index => $componentId) {
            $stmt->bindValue($index + 1, $componentId);
        }

        $stmt->execute();
        $rows = $stmt->fetchAll();

        $grouped = [];
        foreach ($rows as $row) {
            $componentId = $row['IdCongDoan'];
            $ratio = $row['TyLeSoLuong'] ?? null;
            $standard = $row['DinhMuc'] ?? null;
            $grouped[$componentId][] = [
                'id' => $row['IdNguyenLieu'],
                'quantity_per_unit' => $ratio !== null ? $ratio : ($standard ?? 1),
                'standard_quantity' => $standard,
                'label' => $row['Nhan'] ?? null,
                'unit' => $row['DonVi'] ?? null,
                'configuration_id' => $row['IdCauHinh'] ?? null,
            ];
        }

        return $grouped;
    }
}
