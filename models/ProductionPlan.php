<?php

class ProductionPlan extends BaseModel
{
    protected string $table = 'ke_hoach_san_xuat';
    protected string $primaryKey = 'IdKeHoachSanXuat';

    public function getPlansWithOrders(int $limit = 20): array
    {
        $sql = 'SELECT ke_hoach_san_xuat.*, ct_don_hang.SoLuong AS SoLuongDonHang, don_hang.YeuCau,
                       san_pham.TenSanPham, cau_hinh_san_pham.TenCauHinh
                FROM ke_hoach_san_xuat
                JOIN ct_don_hang ON ct_don_hang.IdTTCTDonHang = ke_hoach_san_xuat.IdTTCTDonHang
                JOIN san_pham ON san_pham.IdSanPham = ct_don_hang.IdSanPham
                LEFT JOIN cau_hinh_san_pham ON cau_hinh_san_pham.IdCauHinh = ct_don_hang.IdCauHinh
                JOIN don_hang ON don_hang.IdDonHang = ct_don_hang.IdDonHang
                ORDER BY ke_hoach_san_xuat.ThoiGianBD DESC
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
}
