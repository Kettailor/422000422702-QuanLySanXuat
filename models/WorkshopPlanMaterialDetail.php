<?php

class WorkshopPlanMaterialDetail extends BaseModel
{
    protected string $table = 'chi_tiet_ke_hoach_san_xuat_xuong';
    protected string $primaryKey = 'IdCTKHSXX';

    public function getByWorkshopPlan(string $workshopPlanId): array
    {
        $sql = 'SELECT ct.IdCTKHSXX,
                       ct.IdKeHoachSanXuatXuong,
                       ct.IdNguyenLieu,
                       ct.SoLuong AS SoLuongKeHoach,
                       nl.TenNL,
                       nl.DonVi,
                       nl.SoLuong AS SoLuongTonKho
                FROM chi_tiet_ke_hoach_san_xuat_xuong ct
                LEFT JOIN nguyen_lieu nl ON nl.IdNguyenLieu = ct.IdNguyenLieu
                WHERE ct.IdKeHoachSanXuatXuong = :planId
                ORDER BY nl.TenNL, ct.IdCTKHSXX';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':planId', $workshopPlanId);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
