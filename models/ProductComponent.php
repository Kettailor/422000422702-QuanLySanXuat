<?php

class ProductComponent extends BaseModel
{
    protected string $table = 'san_pham_cong_doan';
    protected string $primaryKey = 'IdCongDoan';

    public function getByProduct(string $productId): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE IdSanPham = :productId AND (IdCauHinh IS NULL OR IdCauHinh = '') ORDER BY ThuTu, TenCongDoan";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':productId', $productId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByConfiguration(string $configurationId): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE IdCauHinh = :configurationId ORDER BY ThuTu, TenCongDoan";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':configurationId', $configurationId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getDefaultComponents(): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE IdSanPham IS NULL ORDER BY ThuTu, TenCongDoan";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getComponentsForProductConfiguration(?string $productId, ?string $configurationId): array
    {
        $components = [];
        if ($configurationId) {
            $components = $this->getByConfiguration($configurationId);
        }

        $fallbackComponents = [];
        if ($productId) {
            $fallbackComponents = $this->getByProduct($productId);
        }

        if (empty($fallbackComponents)) {
            $fallbackComponents = $this->getDefaultComponents();
        }

        if (empty($components)) {
            return $this->sortComponents($fallbackComponents);
        }

        $seenKeys = [];
        foreach ($components as $component) {
            $key = $this->buildOverrideKey($component);
            $seenKeys[$key] = true;
        }

        foreach ($fallbackComponents as $component) {
            $key = $this->buildOverrideKey($component);
            if (!isset($seenKeys[$key])) {
                $components[] = $component;
            }
        }

        return $this->sortComponents($components);
    }

    private function buildOverrideKey(array $component): string
    {
        $key = $component['LogisticsKey'] ?? $component['LoaiCongDoan'] ?? null;
        if ($key === null || $key === '') {
            $key = $component['IdCongDoan'] ?? spl_object_hash((object) $component);
        }

        return (string) $key;
    }

    private function sortComponents(array $components): array
    {
        usort($components, static function (array $a, array $b): int {
            $orderA = (int) ($a['ThuTu'] ?? 0);
            $orderB = (int) ($b['ThuTu'] ?? 0);

            if ($orderA === $orderB) {
                $nameA = (string) ($a['TenCongDoan'] ?? '');
                $nameB = (string) ($b['TenCongDoan'] ?? '');
                return strcmp($nameA, $nameB);
            }

            return $orderA <=> $orderB;
        });

        return $components;
    }
}
