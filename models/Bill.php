<?php

class Bill extends BaseModel
{
    private static bool $columnsChecked = false;
    private static $availableColumns = null;

    protected string $table = 'hoa_don';
    protected string $primaryKey = 'IdHoaDon';

    public function __construct()
    {
        parent::__construct();
        $this->ensureExtraColumns();
    }

    private function ensureExtraColumns(): void
    {
        if (self::$columnsChecked) {
            return;
        }

        $columns = $this->getAvailableColumns();
        if ($columns === null) {
            self::$columnsChecked = true;
            return;
        }

        $alterations = [
            'Thue' => 'ALTER TABLE `hoa_don` ADD COLUMN `Thue` FLOAT NULL DEFAULT NULL AFTER `LoaiHD`',
            'MaBuuDien' => 'ALTER TABLE `hoa_don` ADD COLUMN `MaBuuDien` varchar(50) NULL DEFAULT NULL AFTER `Thue`',
            'GhiChu' => 'ALTER TABLE `hoa_don` ADD COLUMN `GhiChu` text NULL DEFAULT NULL AFTER `MaBuuDien`',
        ];

        foreach ($alterations as $column => $statement) {
            if (!in_array($column, $columns, true)) {
                try {
                    $this->db->exec($statement);
                } catch (Throwable $exception) {
                    // Ignore schema updates if not permitted.
                }
            }
        }

        self::$availableColumns = null;
        self::$columnsChecked = true;
    }

    private function getAvailableColumns(): ?array
    {
        if (self::$availableColumns === false) {
            return null;
        }

        if (self::$availableColumns === null) {
            try {
                $stmt = $this->db->query('SHOW COLUMNS FROM `hoa_don`');
                $columns = [];
                foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $column) {
                    if (isset($column['Field'])) {
                        $columns[] = $column['Field'];
                    }
                }
                self::$availableColumns = $columns;
            } catch (Throwable $exception) {
                self::$availableColumns = false;
                return null;
            }
        }

        return self::$availableColumns;
    }

    private function filterDataByColumns(array $data): array
    {
        $columns = $this->getAvailableColumns();
        if ($columns === null) {
            return $data;
        }

        return array_intersect_key($data, array_fill_keys($columns, true));
    }

    public function create(array $data): bool
    {
        return parent::create($this->filterDataByColumns($data));
    }

    public function update(string $id, array $data): bool
    {
        return parent::update($id, $this->filterDataByColumns($data));
    }

    public function getBillsWithOrder(int $limit = 50): array
    {
        $sql = 'SELECT hoa_don.*, don_hang.YeuCau AS DonHangYeuCau
                FROM hoa_don
                LEFT JOIN don_hang ON don_hang.IdDonHang = hoa_don.IdDonHang
                ORDER BY NgayLap DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByOrder(string $orderId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM hoa_don WHERE IdDonHang = :orderId ORDER BY NgayLap DESC');
        $stmt->bindValue(':orderId', $orderId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
