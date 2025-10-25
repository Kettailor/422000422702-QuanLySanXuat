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

    public function findWithRelations(string $id): ?array
    {
        $sql = 'SELECT kx.IdKeHoachSanXuatXuong,
                       kx.TenThanhThanhPhanSP,
                       kx.SoLuong,
                       kx.ThoiGianBatDau,
                       kx.ThoiGianKetThuc,
                       kx.TrangThai,
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
}
