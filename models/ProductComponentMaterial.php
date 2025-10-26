<?php

class ProductComponentMaterial extends BaseModel
{
    protected string $table = 'cau_hinh_nguyen_lieu';
    protected string $primaryKey = 'IdCauHinhNguyenLieu';

    public function getRawByComponent(string $configurationId): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE IdCauHinh = :configurationId ORDER BY IdCauHinhNguyenLieu";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':configurationId', $configurationId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getMaterialsForComponent(string $configurationId): array
    {
        $rows = $this->getRawByComponent($configurationId);

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

    public function getMaterialsForComponents(array $configurationIds): array
    {
        $configurationIds = array_values(array_filter(array_unique($configurationIds)));
        if (empty($configurationIds)) {
            return [];
        }

        $placeholders = implode(', ', array_fill(0, count($configurationIds), '?'));
        $sql = "SELECT * FROM {$this->table} WHERE IdCauHinh IN ({$placeholders}) ORDER BY IdCauHinh, IdCauHinhNguyenLieu";
        $stmt = $this->db->prepare($sql);

        foreach ($configurationIds as $index => $configurationId) {
            $stmt->bindValue($index + 1, $configurationId);
        }

        $stmt->execute();
        $rows = $stmt->fetchAll();

        $grouped = [];
        foreach ($rows as $row) {
            $configurationId = $row['IdCauHinh'];
            $ratio = $row['TyLeSoLuong'] ?? null;
            $standard = $row['DinhMuc'] ?? null;
            $grouped[$configurationId][] = [
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
