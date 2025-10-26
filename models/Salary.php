<?php

class Salary extends BaseModel
{
    private static bool $dailyRateColumnChecked = false;
    /**
     * Cached list of columns for the bang_luong table.
     * When set to false the previous attempt to fetch columns failed and we
     * should skip any filtering to avoid breaking existing behaviour.
     *
     * @var array<string>|false|null
     */
    private static $availableColumns = null;

    public function __construct()
    {
        parent::__construct();
        $this->ensureDailyRateColumn();
    }

    public const ACCOUNTANT_COLUMN = 'KETOAN IdNhanVien2';
    public const EMPLOYEE_COLUMN = 'NHAN_VIENIdNhanVien';

    protected string $table = 'bang_luong';
    protected string $primaryKey = 'IdBangLuong';

    private function ensureDailyRateColumn(): void
    {
        if (self::$dailyRateColumnChecked) {
            return;
        }

        $columns = $this->getAvailableColumns();
        $hasDailyRateColumn = $columns === null || in_array('DonGiaNgayCong', $columns, true);

        if (!$hasDailyRateColumn) {
            try {
                $this->db->exec("ALTER TABLE `bang_luong` ADD COLUMN `DonGiaNgayCong` FLOAT NULL DEFAULT NULL AFTER `PhuCap`");
                self::$availableColumns = null; // Refresh cache after altering schema.
            } catch (Throwable $exception) {
                // Ignore schema adjustments if the database user does not have permission.
            }
        }

        self::$dailyRateColumnChecked = true;
    }

    private function getAvailableColumns(): ?array
    {
        if (self::$availableColumns === false) {
            return null;
        }

        if (self::$availableColumns === null) {
            try {
                $stmt = $this->db->query('SHOW COLUMNS FROM `bang_luong`');
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

        $allowed = array_fill_keys($columns, true);

        return array_intersect_key($data, $allowed);
    }

    public function supportsWorkingDays(): bool
    {
        $columns = $this->getAvailableColumns();
        if ($columns === null) {
            return true;
        }

        return in_array('SoNgayCong', $columns, true);
    }

    public function deriveWorkingDays(array $payroll): float
    {
        $dailyRate = (float) ($payroll['DonGiaNgayCong'] ?? 0);
        if ($dailyRate <= 0) {
            return 0.0;
        }

        $dayIncome = (float) ($payroll['TongLuongNgayCong'] ?? 0);

        return round($dayIncome / $dailyRate, 2);
    }

    public function create(array $data): bool
    {
        return parent::create($this->filterDataByColumns($data));
    }

    public function update(string $id, array $data): bool
    {
        return parent::update($id, $this->filterDataByColumns($data));
    }

    public static function calculateFigures(array $payroll): array
    {
        $base = (float) ($payroll['LuongCoBan'] ?? 0);
        $allowance = (float) ($payroll['PhuCap'] ?? 0);
        $dailyIncome = (float) ($payroll['TongLuongNgayCong'] ?? 0);
        $bonus = (float) ($payroll['Thuong'] ?? 0);
        $deduction = (float) ($payroll['KhauTru'] ?? 0);
        $tax = (float) ($payroll['ThueTNCN'] ?? 0);

        $gross = $base + $allowance + $dailyIncome + $bonus;
        $net = $gross - $deduction - $tax;

        return [
            'gross' => round($gross, 2),
            'net' => max(round($net, 2), 0),
        ];
    }

    public function getPayrolls(int $limit = 50): array
    {
        $sql = 'SELECT bang_luong.*, nv.HoTen
                FROM bang_luong
                JOIN nhan_vien nv ON nv.IdNhanVien = bang_luong.' . self::EMPLOYEE_COLUMN . '
                ORDER BY NgayLap DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPendingPayrolls(int $limit = 5): array
    {
        $sql = 'SELECT BANG_LUONG.*, NV.HoTen
                FROM BANG_LUONG
                JOIN NHAN_VIEN NV ON NV.IdNhanVien = BANG_LUONG.' . self::EMPLOYEE_COLUMN . '
                WHERE BANG_LUONG.TrangThai = :status
                ORDER BY NgayLap DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':status', 'Chờ duyệt');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPayrollSummary(): array
    {
        $sql = 'SELECT COUNT(*) AS total,
                       SUM(TongThuNhap) AS total_amount,
                       SUM(CASE WHEN TrangThai = "Chờ duyệt" THEN 1 ELSE 0 END) AS pending,
                       SUM(CASE WHEN TrangThai = "Đã duyệt" THEN 1 ELSE 0 END) AS approved,
                       SUM(CASE WHEN TrangThai = "Đã chi" THEN 1 ELSE 0 END) AS paid
                FROM bang_luong';
        $summary = $this->db->query($sql)->fetch();

        return [
            'total' => (int) ($summary['total'] ?? 0),
            'pending' => (int) ($summary['pending'] ?? 0),
            'approved' => (int) ($summary['approved'] ?? 0),
            'paid' => (int) ($summary['paid'] ?? 0),
            'total_amount' => (float) ($summary['total_amount'] ?? 0),
        ];
    }

    public function getMonthlyPayoutTrend(int $months = 6): array
    {
        $sql = 'SELECT DATE_FORMAT(NgayLap, "%Y-%m") AS thang, SUM(TongThuNhap) AS tong_chi
                FROM bang_luong
                GROUP BY DATE_FORMAT(NgayLap, "%Y-%m")
                ORDER BY thang DESC
                LIMIT :months';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':months', $months, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateStatus(string $id, string $status, ?string $signature = null): bool
    {
        $data = ['TrangThai' => $status];

        if ($signature !== null) {
            $data['ChuKy'] = $signature;
        }

        return $this->update($id, $data);
    }

    public function recalculateAll(): int
    {
        $sql = 'UPDATE bang_luong
                SET TongThuNhap = GREATEST(COALESCE(LuongCoBan, 0)
                                            + COALESCE(PhuCap, 0)
                                            + COALESCE(TongLuongNgayCong, 0)
                                            + COALESCE(Thuong, 0)
                                            - COALESCE(KhauTru, 0)
                                            - COALESCE(ThueTNCN, 0), 0),
                    TongBaoHiem = COALESCE(KhauTru, 0)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->rowCount();
    }
}
