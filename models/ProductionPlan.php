<?php

class ProductionPlan extends BaseModel
{
    protected string $table = 'ke_hoach_san_xuat';
    protected string $primaryKey = 'IdKeHoachSanXuat';

    public function getPlansWithOrders(int $limit = 20): array
    {
        $sql = 'SELECT k.IdKeHoachSanXuat,
                       k.SoLuong,
                       k.ThoiGianBD,
                       k.ThoiGianKetThuc,
                       k.TrangThai,
                       k.`BANGIAMDOC IdNhanVien` AS BanGiamDocId,
                       ct.IdTTCTDonHang,
                       ct.IdDonHang,
                       ct.IdSanPham,
                       ct.IdCauHinh,
                       ct.SoLuong AS SoLuongDonHang,
                       don.YeuCau,
                       don.NgayLap AS NgayLapDonHang,
                       san.TenSanPham,
                       san.DonVi,
                       cau.TenCauHinh,
                       ql.HoTen AS TenQuanLy,
                       COALESCE(ctx.TongCongDoan, 0) AS TongCongDoan,
                       COALESCE(ctx.CongDoanHoanThanh, 0) AS CongDoanHoanThanh
                FROM ke_hoach_san_xuat k
                JOIN ct_don_hang ct ON ct.IdTTCTDonHang = k.IdTTCTDonHang
                JOIN san_pham san ON san.IdSanPham = ct.IdSanPham
                LEFT JOIN cau_hinh_san_pham cau ON cau.IdCauHinh = ct.IdCauHinh
                JOIN don_hang don ON don.IdDonHang = ct.IdDonHang
                LEFT JOIN nhan_vien ql ON ql.IdNhanVien = k.`BANGIAMDOC IdNhanVien`
                LEFT JOIN (
                    SELECT IdKeHoachSanXuat,
                           COUNT(*) AS TongCongDoan,
                           SUM(CASE WHEN TrangThai = "Hoàn thành" THEN 1 ELSE 0 END) AS CongDoanHoanThanh
                    FROM ke_hoach_san_xuat_xuong
                    GROUP BY IdKeHoachSanXuat
                ) AS ctx ON ctx.IdKeHoachSanXuat = k.IdKeHoachSanXuat
                ORDER BY k.ThoiGianBD DESC, k.IdKeHoachSanXuat DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByOrder(string $orderId): array
    {
        $sql = 'SELECT ke_hoach_san_xuat.*, ct_don_hang.IdSanPham, ct_don_hang.IdCauHinh,
                       ct_don_hang.SoLuong AS SoLuongChiTiet,
                       san_pham.TenSanPham, cau_hinh_san_pham.TenCauHinh
                FROM ke_hoach_san_xuat
                JOIN ct_don_hang ON ct_don_hang.IdTTCTDonHang = ke_hoach_san_xuat.IdTTCTDonHang
                JOIN san_pham ON san_pham.IdSanPham = ct_don_hang.IdSanPham
                LEFT JOIN cau_hinh_san_pham ON cau_hinh_san_pham.IdCauHinh = ct_don_hang.IdCauHinh
                WHERE ct_don_hang.IdDonHang = :orderId
                ORDER BY ke_hoach_san_xuat.ThoiGianBD DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':orderId', $orderId);
                $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPlanWithRelations(string $planId): ?array
    {
        $sql = 'SELECT k.IdKeHoachSanXuat,
                       k.SoLuong,
                       k.ThoiGianBD,
                       k.ThoiGianKetThuc,
                       k.TrangThai,
                       k.`BANGIAMDOC IdNhanVien` AS BanGiamDocId,
                       ct.IdTTCTDonHang,
                       ct.IdDonHang,
                       ct.IdSanPham,
                       ct.IdCauHinh,
                       ct.SoLuong AS SoLuongChiTiet,
                       don.YeuCau,
                       don.NgayLap,
                       san.TenSanPham,
                       san.DonVi,
                       cau.TenCauHinh,
                       ql.HoTen AS TenQuanLy,
                       COALESCE(ctx.TongCongDoan, 0) AS TongCongDoan,
                       COALESCE(ctx.CongDoanHoanThanh, 0) AS CongDoanHoanThanh
                FROM ke_hoach_san_xuat k
                JOIN ct_don_hang ct ON ct.IdTTCTDonHang = k.IdTTCTDonHang
                JOIN don_hang don ON don.IdDonHang = ct.IdDonHang
                JOIN san_pham san ON san.IdSanPham = ct.IdSanPham
                LEFT JOIN cau_hinh_san_pham cau ON cau.IdCauHinh = ct.IdCauHinh
                LEFT JOIN nhan_vien ql ON ql.IdNhanVien = k.`BANGIAMDOC IdNhanVien`
                LEFT JOIN (
                    SELECT IdKeHoachSanXuat,
                           COUNT(*) AS TongCongDoan,
                           SUM(CASE WHEN TrangThai = "Hoàn thành" THEN 1 ELSE 0 END) AS CongDoanHoanThanh
                    FROM ke_hoach_san_xuat_xuong
                    GROUP BY IdKeHoachSanXuat
                ) AS ctx ON ctx.IdKeHoachSanXuat = k.IdKeHoachSanXuat
                WHERE k.IdKeHoachSanXuat = :planId
                LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':planId', $planId);
        $stmt->execute();

        $plan = $stmt->fetch();

        return $plan ?: null;
    }

    public function getPlansForWorkshopAssignment(): array
    {
        $sql = 'SELECT k.IdKeHoachSanXuat,
                       k.TrangThai,
                       k.ThoiGianBD,
                       ct.IdTTCTDonHang,
                       ct.SoLuong AS SoLuongChiTiet,
                       don.IdDonHang,
                       don.YeuCau,
                       san.TenSanPham,
                       san.DonVi,
                       cau.TenCauHinh
                FROM ke_hoach_san_xuat k
                JOIN ct_don_hang ct ON ct.IdTTCTDonHang = k.IdTTCTDonHang
                JOIN don_hang don ON don.IdDonHang = ct.IdDonHang
                JOIN san_pham san ON san.IdSanPham = ct.IdSanPham
                LEFT JOIN cau_hinh_san_pham cau ON cau.IdCauHinh = ct.IdCauHinh
                ORDER BY k.ThoiGianBD DESC, k.IdKeHoachSanXuat DESC';

        return $this->db->query($sql)->fetchAll();
    }
}
