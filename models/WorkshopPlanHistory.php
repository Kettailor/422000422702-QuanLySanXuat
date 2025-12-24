<?php

class WorkshopPlanHistory extends BaseModel
{
    protected string $table = 'lich_su_ke_hoach_xuong';
    protected string $primaryKey = 'IdLichSu';

    public function log(
        string $workshopPlanId,
        string $status,
        string $action,
        ?string $note,
        ?string $actorId,
        array $details = [],
        ?string $warehouseRequestId = null
    ): string {
        $recordId = uniqid('LSKHSXX');

        $data = [
            'IdLichSu' => $recordId,
            'IdKeHoachSanXuatXuong' => $workshopPlanId,
            'TrangThai' => $status,
            'HanhDong' => $action,
            'GhiChu' => $note,
            'NguoiThucHien' => $actorId,
            'NgayThucHien' => date('Y-m-d H:i:s'),
            'ThongTinChiTiet' => !empty($details) ? json_encode($details, JSON_UNESCAPED_UNICODE) : null,
            'IdYeuCauKho' => $warehouseRequestId,
        ];

        $this->create($data);

        return $recordId;
    }

    public function getByPlan(string $workshopPlanId, int $limit = 20): array
    {
        $sql = 'SELECT ls.*, nv.HoTen AS TenNguoiThucHien
                FROM lich_su_ke_hoach_xuong ls
                LEFT JOIN nhan_vien nv ON nv.IdNhanVien = ls.NguoiThucHien
                WHERE ls.IdKeHoachSanXuatXuong = :planId
                ORDER BY ls.NgayThucHien DESC, ls.IdLichSu DESC
                LIMIT :limit';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':planId', $workshopPlanId);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getProducedQuantity(string $workshopPlanId): int
    {
        $sql = 'SELECT ThongTinChiTiet
                FROM lich_su_ke_hoach_xuong
                WHERE IdKeHoachSanXuatXuong = :planId
                  AND HanhDong = :action';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':planId', $workshopPlanId);
        $stmt->bindValue(':action', 'Cập nhật tiến độ cuối ca');
        $stmt->execute();

        $total = 0;
        foreach ($stmt->fetchAll() as $row) {
            $details = json_decode($row['ThongTinChiTiet'] ?? '', true);
            $quantity = (int) ($details['quantity'] ?? 0);
            if ($quantity > 0) {
                $total += $quantity;
            }
        }

        return $total;
    }
}
