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

    public function getByWorkshopMaterialWarehouse(string $workshopId): array
    {
        if ($workshopId === '') {
            return [];
        }

        $sql = 'SELECT nl.*
                FROM nguyen_lieu nl
                JOIN lo ON lo.IdLo = nl.IdLo
                JOIN kho ON kho.IdKho = lo.IdKho
                WHERE kho.IdXuong = :workshopId
                  AND (
                    LOWER(kho.TenLoaiKho) LIKE :materialKeyword
                    OR LOWER(kho.TenLoaiKho) LIKE :materialKeywordAscii
                  )
                ORDER BY nl.TenNL';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':workshopId', $workshopId);
        $stmt->bindValue(':materialKeyword', '%nguyên%');
        $stmt->bindValue(':materialKeywordAscii', '%nguyen%');
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function checkAvailability(array $requirements): array
    {
        $requirements = array_values(array_filter($requirements, function ($item) {
            $id = $item['id'] ?? $item['IdNguyenLieu'] ?? null;
            $required = $item['required'] ?? $item['SoLuong'] ?? $item['SoLuongThucTe'] ?? null;
            return $id !== null && $required !== null;
        }));

        if (empty($requirements)) {
            return [
                'is_sufficient' => true,
                'items' => [],
            ];
        }

        $materialIds = array_map(fn($item) => $item['id'] ?? $item['IdNguyenLieu'], $requirements);
        $materialModel = new self();
        $inventory = $materialModel->findMany($materialIds);

        $resultItems = [];
        $isSufficient = true;

        foreach ($requirements as $index => $requirement) {
            $materialId = $requirement['id'] ?? $requirement['IdNguyenLieu'];
            $requiredQuantity = (int) ($requirement['required'] ?? $requirement['SoLuong'] ?? $requirement['SoLuongThucTe'] ?? 0);
            if ($requiredQuantity < 0) {
                $requiredQuantity = 0;
            }

            $material = $inventory[$materialId] ?? [];
            $availableQuantity = (int) ($material['SoLuong'] ?? 0);
            $shortage = max(0, $requiredQuantity - $availableQuantity);

            if ($shortage > 0) {
                $isSufficient = false;
            }

            $resultItems[] = [
                'id' => $materialId,
                'name' => $material['TenNL'] ?? null,
                'unit' => $material['DonVi'] ?? null,
                'required' => $requiredQuantity,
                'available' => $availableQuantity,
                'shortage' => $shortage,
                'index' => $index,
            ];
        }

        return [
            'is_sufficient' => $isSufficient,
            'items' => $resultItems,
        ];
    }

    public function adjustStock(string $materialId, int $quantityDelta, bool $allowNegative = false): void
    {
        $stmt = $this->db->prepare(
            'SELECT SoLuong FROM nguyen_lieu WHERE IdNguyenLieu = :id FOR UPDATE'
        );
        $stmt->bindValue(':id', $materialId);
        $stmt->execute();
        $current = $stmt->fetchColumn();

        if ($current === false) {
            throw new RuntimeException('Không tìm thấy nguyên liệu cần cập nhật tồn kho.');
        }

        $currentQty = (int) $current;
        $newQty = $currentQty + $quantityDelta;

        if ($newQty < 0 && !$allowNegative) {
            throw new RuntimeException('Tồn kho nguyên liệu không đủ để trừ.');
        }

        $update = $this->db->prepare(
            'UPDATE nguyen_lieu SET SoLuong = :qty WHERE IdNguyenLieu = :id'
        );
        $update->bindValue(':qty', $newQty, PDO::PARAM_INT);
        $update->bindValue(':id', $materialId);
        $update->execute();
    }
}
