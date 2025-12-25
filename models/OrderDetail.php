<?php

class OrderDetail extends BaseModel
{
    protected string $table = 'ct_don_hang';
    protected string $primaryKey = 'IdTTCTDonHang';

    public function getAllWithOrderInfo(): array
    {
        $sql = 'SELECT ct_don_hang.*, don_hang.IdDonHang, don_hang.YeuCau, don_hang.NgayLap,
                       san_pham.TenSanPham, san_pham.DonVi,
                       cau_hinh_san_pham.TenCauHinh, cau_hinh_san_pham.IdBOM
                FROM ct_don_hang
                JOIN don_hang ON don_hang.IdDonHang = ct_don_hang.IdDonHang
                LEFT JOIN san_pham ON san_pham.IdSanPham = ct_don_hang.IdSanPham
                LEFT JOIN cau_hinh_san_pham ON cau_hinh_san_pham.IdCauHinh = ct_don_hang.IdCauHinh
                ORDER BY don_hang.NgayLap DESC, ct_don_hang.IdTTCTDonHang';

        return $this->db->query($sql)->fetchAll();
    }

    public function getPendingForPlanning(): array
    {
        $sql = 'SELECT ct.*, don.IdDonHang, don.YeuCau AS YeuCauDonHang, don.NgayLap, don.EmailLienHe,
                       san.TenSanPham, san.DonVi, san.IdSanPham,
                       cau.TenCauHinh, cau.IdBOM, cau.Keycap, cau.Mainboard, cau.Layout, cau.SwitchType, cau.CaseType, cau.Foam,
                       kh.HoTen AS TenKhachHang, kh.SoDienThoai, kh.Email, kh.TenCongTy,
                       ct.NgayGiao, ct.YeuCau AS YeuCauChiTiet, ct.GhiChu AS GhiChuChiTiet
                FROM ct_don_hang ct
                JOIN don_hang don ON don.IdDonHang = ct.IdDonHang
                LEFT JOIN khach_hang kh ON kh.IdKhachHang = don.IdKhachHang
                LEFT JOIN san_pham san ON san.IdSanPham = ct.IdSanPham
                LEFT JOIN cau_hinh_san_pham cau ON cau.IdCauHinh = ct.IdCauHinh
                LEFT JOIN ke_hoach_san_xuat khsx
                  ON khsx.IdTTCTDonHang = ct.IdTTCTDonHang
                  AND (khsx.TrangThai IS NULL OR khsx.TrangThai <> "Hủy")
                WHERE khsx.IdKeHoachSanXuat IS NULL
                ORDER BY don.NgayLap ASC, ct.IdTTCTDonHang';

        return $this->db->query($sql)->fetchAll();
    }

    public function getByOrder(string $orderId): array
    {
        $sql = 'SELECT ct_don_hang.*, san_pham.TenSanPham, san_pham.DonVi, san_pham.GiaBan,
                       cau_hinh_san_pham.TenCauHinh, cau_hinh_san_pham.MoTa AS MoTaCauHinh, cau_hinh_san_pham.GiaBan AS GiaCauHinh,
                       cau_hinh_san_pham.IdBOM, cau_hinh_san_pham.Keycap, cau_hinh_san_pham.Mainboard,
                       cau_hinh_san_pham.Layout, cau_hinh_san_pham.SwitchType,
                       cau_hinh_san_pham.CaseType, cau_hinh_san_pham.Foam
                FROM ct_don_hang
                LEFT JOIN san_pham ON san_pham.IdSanPham = ct_don_hang.IdSanPham
                LEFT JOIN cau_hinh_san_pham ON cau_hinh_san_pham.IdCauHinh = ct_don_hang.IdCauHinh
                WHERE ct_don_hang.IdDonHang = :orderId
                ORDER BY ct_don_hang.NgayGiao IS NULL, ct_don_hang.NgayGiao';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':orderId', $orderId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPlanningContext(string $orderDetailId): ?array
    {
        $sql = 'SELECT ct.*, don_hang.IdDonHang, don_hang.YeuCau AS YeuCauDonHang, don_hang.EmailLienHe,
                       san_pham.TenSanPham, san_pham.DonVi,
                       cau_hinh_san_pham.TenCauHinh, cau_hinh_san_pham.IdBOM,
                       cau_hinh_san_pham.Keycap, cau_hinh_san_pham.Mainboard,
                       cau_hinh_san_pham.Layout, cau_hinh_san_pham.SwitchType,
                       cau_hinh_san_pham.CaseType, cau_hinh_san_pham.Foam,
                       khach_hang.HoTen AS TenKhachHang, khach_hang.SoDienThoai, khach_hang.Email, khach_hang.TenCongTy
                FROM ct_don_hang ct
                JOIN don_hang ON don_hang.IdDonHang = ct.IdDonHang
                LEFT JOIN khach_hang ON khach_hang.IdKhachHang = don_hang.IdKhachHang
                LEFT JOIN san_pham ON san_pham.IdSanPham = ct.IdSanPham
                LEFT JOIN cau_hinh_san_pham ON cau_hinh_san_pham.IdCauHinh = ct.IdCauHinh
                WHERE ct.IdTTCTDonHang = :orderDetailId
                LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':orderDetailId', $orderDetailId);
        $stmt->execute();

        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function deleteByOrder(string $orderId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM ct_don_hang WHERE IdDonHang = :orderId');
        $stmt->bindValue(':orderId', $orderId);
        return $stmt->execute();
    }
}
