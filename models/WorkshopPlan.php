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
                       ksx.TrangThai AS TrangThaiTong,
                       san.TenSanPham,
                       cau.TenCauHinh,
                       don.IdDonHang
                FROM ke_hoach_san_xuat_xuong kx
                JOIN xuong ON xuong.IdXuong = kx.IdXuong
                JOIN ke_hoach_san_xuat ksx ON ksx.IdKeHoachSanXuat = kx.IdKeHoachSanXuat
                JOIN ct_don_hang ct ON ct.IdTTCTDonHang = ksx.IdTTCTDonHang
                JOIN don_hang don ON don.IdDonHang = ct.IdDonHang
                JOIN san_pham san ON san.IdSanPham = ct.IdSanPham
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
                       xuong.TenXuong
                FROM ke_hoach_san_xuat_xuong kx
                LEFT JOIN xuong ON xuong.IdXuong = kx.IdXuong
                WHERE kx.IdKeHoachSanXuat = :planId
                ORDER BY kx.ThoiGianBatDau ASC, kx.IdKeHoachSanXuatXuong';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':planId', $planId);
        $stmt->execute();

        return $stmt->fetchAll();
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
                       xuong.TenXuong
                FROM ke_hoach_san_xuat_xuong kx
                LEFT JOIN xuong ON xuong.IdXuong = kx.IdXuong
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
                       ksx.TrangThai AS TrangThaiTong,
                       san.TenSanPham,
                       cau.TenCauHinh,
                       don.IdDonHang,
                       don.YeuCau
                FROM ke_hoach_san_xuat_xuong kx
                LEFT JOIN xuong ON xuong.IdXuong = kx.IdXuong
                LEFT JOIN ke_hoach_san_xuat ksx ON ksx.IdKeHoachSanXuat = kx.IdKeHoachSanXuat
                LEFT JOIN ct_don_hang ct ON ct.IdTTCTDonHang = ksx.IdTTCTDonHang
                LEFT JOIN don_hang don ON don.IdDonHang = ct.IdDonHang
                LEFT JOIN san_pham san ON san.IdSanPham = ct.IdSanPham
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
                       ksx.SoLuong AS TongSoLuongKeHoach,
                       san.TenSanPham,
                       san.DonVi,
                       cau.IdCauHinh,
                       cau.TenCauHinh,
                       don.IdDonHang,
                       don.YeuCau
                FROM ke_hoach_san_xuat_xuong kx
                JOIN xuong ON xuong.IdXuong = kx.IdXuong
                JOIN ke_hoach_san_xuat ksx ON ksx.IdKeHoachSanXuat = kx.IdKeHoachSanXuat
                JOIN ct_don_hang ct ON ct.IdTTCTDonHang = ksx.IdTTCTDonHang
                JOIN don_hang don ON don.IdDonHang = ct.IdDonHang
                JOIN san_pham san ON san.IdSanPham = ct.IdSanPham
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
}
