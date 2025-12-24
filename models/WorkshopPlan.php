<?php

class WorkshopPlan extends BaseModel
{
    protected string $table = 'ke_hoach_san_xuat_xuong';
    protected string $primaryKey = 'IdKeHoachSanXuatXuong';

    public function getDetailedPlans(int $limit = 20): array
    {
        $sql = 'SELECT kx.IdKeHoachSanXuatXuong,
                       kx.TenThanhThanhPhanSP,
                       kx.SoLuong,
                       kx.ThoiGianBatDau,
                       kx.ThoiGianKetThuc,
                       kx.TrangThai,
                       kx.TinhTrangVatTu,
                       kx.IdCongDoan,
                       kx.IdKeHoachSanXuat,
                       kx.IdXuong,
                       xuong.TenXuong,
                       xuong.LoaiXuong,
                       ksx.TrangThai AS TrangThaiTong,
                       san.TenSanPham,
                       san.IdSanPham,
                       san.IdSanPham,
                       COALESCE(cau.TenCauHinh, xcfg.TenPhanCong) AS TenCauHinh,
                       xcfg.IdCauHinh AS AssignmentConfigurationId,
                       xcfg.TenPhanCong,
                       don.IdDonHang
                FROM ke_hoach_san_xuat_xuong kx
                JOIN xuong ON xuong.IdXuong = kx.IdXuong
                JOIN ke_hoach_san_xuat ksx ON ksx.IdKeHoachSanXuat = kx.IdKeHoachSanXuat
                JOIN ct_don_hang ct ON ct.IdTTCTDonHang = ksx.IdTTCTDonHang
                JOIN don_hang don ON don.IdDonHang = ct.IdDonHang
                JOIN san_pham san ON san.IdSanPham = ct.IdSanPham
                LEFT JOIN xuong_cau_hinh_san_pham xcfg ON xcfg.IdPhanCong = kx.IdCongDoan
                LEFT JOIN cau_hinh_san_pham cau ON cau.IdCauHinh = ct.IdCauHinh
                ORDER BY kx.ThoiGianBatDau DESC, kx.IdKeHoachSanXuatXuong DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByPlan(string $planId): array
    {
        $sql = 'SELECT kx.IdKeHoachSanXuatXuong,
                       kx.TenThanhThanhPhanSP,
                       kx.SoLuong,
                       kx.ThoiGianBatDau,
                       kx.ThoiGianKetThuc,
                       kx.TrangThai,
                       kx.TinhTrangVatTu,
                       kx.IdCongDoan,
                       kx.IdXuong,
                       xuong.TenXuong,
                       xuong.LoaiXuong,
                       xcfg.IdCauHinh AS AssignmentConfigurationId,
                       COALESCE(cfg.TenCauHinh, xcfg.TenPhanCong) AS TenCauHinh
                FROM ke_hoach_san_xuat_xuong kx
                LEFT JOIN xuong ON xuong.IdXuong = kx.IdXuong
                LEFT JOIN xuong_cau_hinh_san_pham xcfg ON xcfg.IdPhanCong = kx.IdCongDoan
                LEFT JOIN cau_hinh_san_pham cfg ON cfg.IdCauHinh = xcfg.IdCauHinh
                WHERE kx.IdKeHoachSanXuat = :planId
                ORDER BY kx.ThoiGianBatDau ASC, kx.IdKeHoachSanXuatXuong';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':planId', $planId);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function updateStatusByPlan(string $planId, string $status, ?string $note = null): bool
    {
        $sql = 'UPDATE ke_hoach_san_xuat_xuong
                SET TrangThai = :status,
                    GhiChu = :note
                WHERE IdKeHoachSanXuat = :planId';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':note', $note);
        $stmt->bindValue(':planId', $planId);

        return $stmt->execute();
    }

    public function updateEndTimeByPlan(string $planId, string $endTime): bool
    {
        $sql = 'UPDATE ke_hoach_san_xuat_xuong
                SET ThoiGianKetThuc = :endTime
                WHERE IdKeHoachSanXuat = :planId';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':endTime', $endTime);
        $stmt->bindValue(':planId', $planId);

        return $stmt->execute();
    }

    public function getByPlanIds(array $planIds): array
    {
        $planIds = array_values(array_filter($planIds));
        if (empty($planIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($planIds), '?'));
        $sql = 'SELECT kx.IdKeHoachSanXuat,
                       kx.IdKeHoachSanXuatXuong,
                       kx.TenThanhThanhPhanSP,
                       kx.SoLuong,
                       kx.ThoiGianBatDau,
                       kx.ThoiGianKetThuc,
                       kx.TrangThai,
                       kx.TinhTrangVatTu,
                       kx.IdXuong,
                       xuong.TenXuong,
                       xuong.LoaiXuong,
                       xcfg.IdCauHinh AS AssignmentConfigurationId,
                       COALESCE(cfg.TenCauHinh, xcfg.TenPhanCong) AS TenCauHinh
                FROM ke_hoach_san_xuat_xuong kx
                LEFT JOIN xuong ON xuong.IdXuong = kx.IdXuong
                LEFT JOIN xuong_cau_hinh_san_pham xcfg ON xcfg.IdPhanCong = kx.IdCongDoan
                LEFT JOIN cau_hinh_san_pham cfg ON cfg.IdCauHinh = xcfg.IdCauHinh
                WHERE kx.IdKeHoachSanXuat IN (' . $placeholders . ')
                ORDER BY kx.ThoiGianBatDau ASC, kx.IdKeHoachSanXuatXuong';

        $stmt = $this->db->prepare($sql);
        foreach ($planIds as $index => $planId) {
            $stmt->bindValue($index + 1, $planId);
        }

        $stmt->execute();

        $grouped = [];
        foreach ($stmt->fetchAll() as $row) {
            $grouped[$row['IdKeHoachSanXuat']][] = $row;
        }

        return $grouped;
    }

    public function getMaterialStock (string $id): ?array 
    {
        $sql = "
        SELECT
	nguyen_lieu.IdNguyenLieu, 
	nguyen_lieu.TenNL, 
	chi_tiet_ke_hoach_san_xuat_xuong.SoLuong as 'SoLuongCan', 
	lo.TenLo, 
	nguyen_lieu.SoLuong as 'SoLuongTon'
FROM
	ke_hoach_san_xuat_xuong
	INNER JOIN
	chi_tiet_ke_hoach_san_xuat_xuong
	ON 
		ke_hoach_san_xuat_xuong.IdKeHoachSanXuatXuong = chi_tiet_ke_hoach_san_xuat_xuong.IdKeHoachSanXuatXuong
	INNER JOIN
	nguyen_lieu
	ON 
		chi_tiet_ke_hoach_san_xuat_xuong.IdNguyenLieu = nguyen_lieu.IdNguyenLieu
	INNER JOIN
	lo
	ON 
		nguyen_lieu.IdLo = lo.IdLo
WHERE
	ke_hoach_san_xuat_xuong.IdKeHoachSanXuatXuong = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $materials = $stmt->fetchAll();
        return $materials ?: [];
    }

    public function findWithRelations(string $id): ?array
    {
        $sql = 'SELECT kx.IdKeHoachSanXuatXuong,
                       kx.TenThanhThanhPhanSP,
                       kx.SoLuong,
                       kx.ThoiGianBatDau,
                       kx.ThoiGianKetThuc,
                       kx.TrangThai,
                       kx.TinhTrangVatTu,
                       kx.IdCongDoan,
                       kx.IdKeHoachSanXuat,
                       kx.IdXuong,
                       xuong.TenXuong,
                       xuong.LoaiXuong,
                       ksx.TrangThai AS TrangThaiTong,
                       san.TenSanPham,
                       san.IdSanPham,
                       COALESCE(cau.TenCauHinh, xcfg.TenPhanCong) AS TenCauHinh,
                       xcfg.IdCauHinh AS AssignmentConfigurationId,
                       don.IdDonHang,
                       don.YeuCau
                FROM ke_hoach_san_xuat_xuong kx
                LEFT JOIN xuong ON xuong.IdXuong = kx.IdXuong
                LEFT JOIN ke_hoach_san_xuat ksx ON ksx.IdKeHoachSanXuat = kx.IdKeHoachSanXuat
                LEFT JOIN ct_don_hang ct ON ct.IdTTCTDonHang = ksx.IdTTCTDonHang
                LEFT JOIN don_hang don ON don.IdDonHang = ct.IdDonHang
                LEFT JOIN san_pham san ON san.IdSanPham = ct.IdSanPham
                LEFT JOIN xuong_cau_hinh_san_pham xcfg ON xcfg.IdPhanCong = kx.IdCongDoan
                LEFT JOIN cau_hinh_san_pham cau ON cau.IdCauHinh = ct.IdCauHinh
                WHERE kx.IdKeHoachSanXuatXuong = :id
                LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $plan = $stmt->fetch();

        return $plan ?: null;
    }

    public function getDashboardPlans(?string $workshopId = null): array
    {
        $conditions = [];
        $params = [];

        if ($workshopId) {
            $conditions[] = 'kx.IdXuong = :workshopId';
            $params[':workshopId'] = $workshopId;
        }

        $conditions[] = '(kx.TrangThai IS NULL OR kx.TrangThai NOT IN ("Hoàn thành", "Đã hủy"))';

        $whereClause = '';
        if (!empty($conditions)) {
            $whereClause = 'WHERE ' . implode(' AND ', $conditions);
        }

        $sql = 'SELECT kx.IdKeHoachSanXuatXuong,
                       kx.TenThanhThanhPhanSP,
                       kx.SoLuong,
                       kx.ThoiGianBatDau,
                       kx.ThoiGianKetThuc,
                       kx.TrangThai,
                       kx.TinhTrangVatTu,
                       kx.IdCongDoan,
                       kx.IdKeHoachSanXuat,
                       kx.IdXuong,
                       xuong.TenXuong,
                       xuong.LoaiXuong,
                       ksx.SoLuong AS TongSoLuongKeHoach,
                       san.TenSanPham,
                       san.DonVi,
                       COALESCE(cau.IdCauHinh, xcfg.IdCauHinh) AS IdCauHinh,
                       COALESCE(cau.TenCauHinh, xcfg.TenPhanCong) AS TenCauHinh,
                       don.IdDonHang,
                       don.YeuCau
                FROM ke_hoach_san_xuat_xuong kx
                JOIN xuong ON xuong.IdXuong = kx.IdXuong
                JOIN ke_hoach_san_xuat ksx ON ksx.IdKeHoachSanXuat = kx.IdKeHoachSanXuat
                JOIN ct_don_hang ct ON ct.IdTTCTDonHang = ksx.IdTTCTDonHang
                JOIN don_hang don ON don.IdDonHang = ct.IdDonHang
                JOIN san_pham san ON san.IdSanPham = ct.IdSanPham
                LEFT JOIN xuong_cau_hinh_san_pham xcfg ON xcfg.IdPhanCong = kx.IdCongDoan
                LEFT JOIN cau_hinh_san_pham cau ON cau.IdCauHinh = ct.IdCauHinh
                ' . $whereClause . '
                ORDER BY kx.ThoiGianBatDau ASC, kx.IdKeHoachSanXuatXuong';

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function deleteWithRelations(string $id): void
    {
        $this->db->beginTransaction();

        try {
            $tables = [
                'chi_tiet_ke_hoach_san_xuat_xuong' => 'IdKeHoachSanXuatXuong',
                'lich_su_ke_hoach_xuong' => 'IdKeHoachSanXuatXuong',
                'yeu_cau_xuat_kho' => 'IdKeHoachSanXuatXuong',
            ];

            foreach ($tables as $table => $column) {
                $stmt = $this->db->prepare("DELETE FROM {$table} WHERE {$column} = :id");
                $stmt->bindValue(':id', $id);
                $stmt->execute();
            }

            $this->delete($id);
            $this->db->commit();
        } catch (Throwable $exception) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $exception;
        }
    }
}
