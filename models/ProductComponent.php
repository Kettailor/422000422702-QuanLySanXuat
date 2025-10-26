<?php

class ProductComponent extends BaseModel
{
    protected string $table = 'xuong_cau_hinh_san_pham';
    protected string $primaryKey = 'IdPhanCong';

    public function getByProduct(string $productId): array
    {
        $sql = "SELECT pc.*, cfg.TenCauHinh, cfg.IdSanPham
                FROM {$this->table} pc
                JOIN cau_hinh_san_pham cfg ON cfg.IdCauHinh = pc.IdCauHinh
                WHERE cfg.IdSanPham = :productId
                ORDER BY pc.ThuTu, cfg.TenCauHinh, pc.TenPhanCong";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':productId', $productId);
        $stmt->execute();

        return $this->mapAssignments($stmt->fetchAll());
    }

    public function getByConfiguration(string $configurationId): array
    {
        $sql = "SELECT pc.*, cfg.TenCauHinh, cfg.IdSanPham
                FROM {$this->table} pc
                JOIN cau_hinh_san_pham cfg ON cfg.IdCauHinh = pc.IdCauHinh
                WHERE pc.IdCauHinh = :configurationId
                ORDER BY pc.ThuTu, pc.TenPhanCong";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':configurationId', $configurationId);
        $stmt->execute();

        return $this->mapAssignments($stmt->fetchAll());
    }

    public function getDefaultComponents(): array
    {
        $sql = "SELECT pc.*, NULL AS TenCauHinh, NULL AS IdSanPham
                FROM {$this->table} pc
                WHERE pc.IdSanPham IS NULL
                ORDER BY pc.ThuTu, pc.TenPhanCong";

        $stmt = $this->db->query($sql);

        return $this->mapAssignments($stmt->fetchAll());
    }

    public function getComponentsForProductConfiguration(?string $productId, ?string $configurationId): array
    {
        $components = [];
        if ($configurationId) {
            $components = $this->getByConfiguration($configurationId);
        }

        if (empty($components) && $productId) {
            $components = $this->getByProduct($productId);
        }

        if (empty($components)) {
            $components = $this->getDefaultComponents();
        }

        return $this->sortComponents($components);
    }

    private function mapAssignments(array $rows): array
    {
        if (empty($rows)) {
            return [];
        }

        return array_map(function (array $row): array {
            $label = $row['TenPhanCong'] ?? $row['TenCauHinh'] ?? 'Cấu hình sản phẩm';

            return [
                'IdCongDoan' => $row['IdPhanCong'] ?? null,
                'IdCauHinh' => $row['IdCauHinh'] ?? null,
                'TenCongDoan' => $label,
                'TenPhanCong' => $label,
                'TenCauHinh' => $row['TenCauHinh'] ?? null,
                'TyLeSoLuong' => $row['TyLeSoLuong'] ?? 1,
                'DonVi' => $row['DonVi'] ?? 'sp',
                'IdXuong' => $row['IdXuong'] ?? null,
                'TrangThaiMacDinh' => $row['TrangThaiMacDinh'] ?? null,
                'LogisticsKey' => $row['LogisticsKey'] ?? null,
                'LogisticsLabel' => $row['LogisticsLabel'] ?? $label,
                'IncludeYeuCau' => $row['IncludeYeuCau'] ?? 0,
                'ThuTu' => (int) ($row['ThuTu'] ?? 0),
            ];
        }, $rows);
    }

    private function buildOverrideKey(array $component): string
    {
        $key = $component['LogisticsKey'] ?? null;
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
