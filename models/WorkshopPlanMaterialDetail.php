<?php

class WorkshopPlanMaterialDetail extends BaseModel
{
    protected string $table = 'chi_tiet_ke_hoach_san_xuat_xuong';
    protected string $primaryKey = 'IdCTKHSXX';

    public function createWorkshopPlanDetail (string $workshopPlanId, string $configId, int $productionQuantity) {
        try {
            // 1. Bắt đầu transaction để đảm bảo toàn vẹn dữ liệu
            $this->db->beginTransaction();

            // kiểm tra nguyên liệu có kh
            $sqlGetBOM = "SELECT IdNguyenLieu, TyLeSoLuong 
                          FROM cau_hinh_nguyen_lieu 
                          WHERE IdCauHinh = :configId";
            
            $stmtGet = $this->db->prepare($sqlGetBOM);
            $stmtGet->bindValue(':configId', $configId);
            $stmtGet->execute();
            $materials = $stmtGet->fetchAll();

            if (empty($materials)) {
                // Nếu không tìm thấy công thức nguyên liệu, rollback và báo lỗi
                $this->db->rollBack();
                throw new Exception("Không tìm thấy cấu hình nguyên liệu cho mã: " . $configId);
            }

            // 3. Chuẩn bị câu lệnh INSERT vào bảng chi tiết
            $sqlInsert = "INSERT INTO " . $this->table . " 
                          (IdCTKHSXX, SoLuong, SoLuongTrenDonVi, IdKeHoachSanXuatXuong, IdNguyenLieu) 
                          VALUES (:idDetail, :quantity, :ratio, :planId, :materialId)";
            
            $stmtInsert = $this->db->prepare($sqlInsert);

            // 4. Duyệt qua từng nguyên liệu để tính toán và lưu vào DB
            foreach ($materials as $material) {
                // Tính tổng số lượng cần = Số lượng SX * Định mức
                $ratio = (float) ($material['TyLeSoLuong'] ?? 1);
                if ($ratio < 0) {
                    $ratio = 0;
                }
                if ($ratio > 0) {
                    $ratio = (float) ceil($ratio);
                }
                $neededQty = (int) ceil($productionQuantity * $ratio);

                // Tạo ID chi tiết (Ví dụ: CT + Timestamp + Random để tránh trùng)
                // Bạn có thể tùy chỉnh lại logic sinh ID này theo quy tắc của dự án
                $idDetail = 'CT' . date('ymdHis') . rand(100, 999); 

                $stmtInsert->bindValue(':idDetail', $idDetail);
                $stmtInsert->bindValue(':quantity', $neededQty);
                $stmtInsert->bindValue(':ratio', $ratio);
                $stmtInsert->bindValue(':planId', $workshopPlanId);
                $stmtInsert->bindValue(':materialId', $material['IdNguyenLieu']);
                
                $stmtInsert->execute();
            }

            // 5. Nếu mọi thứ êm đẹp, xác nhận lưu vào DB
            $this->db->commit();
            return true;

        } catch (Exception $e) {
            // Nếu có lỗi bất kỳ đâu, hoàn tác lại (không lưu gì cả)
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            // Ném lỗi ra ngoài để Controller xử lý (hiển thị thông báo cho user)
            throw $e;
        }
    }

    public function getByWorkshopPlan(string $workshopPlanId): array
    {
        $sql = 'SELECT ct.IdCTKHSXX,
                       ct.IdKeHoachSanXuatXuong,
                       ct.IdNguyenLieu,
                       ct.SoLuong AS SoLuongKeHoach,
                       ct.SoLuongTrenDonVi,
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

    public function replaceForPlan(string $planId, array $materials): void
    {
        $normalized = [];
        foreach ($materials as $material) {
            $id = $material['id'] ?? $material['IdNguyenLieu'] ?? null;
            if (!$id) {
                continue;
            }

            $quantity = (int) ($material['required'] ?? $material['SoLuong'] ?? $material['SoLuongThucTe'] ?? 0);
            if ($quantity < 0) {
                $quantity = 0;
            }

            if (!isset($normalized[$id])) {
                $normalized[$id] = [
                    'quantity' => 0,
                    'ratio' => null,
                ];
            }

            $normalized[$id]['quantity'] += $quantity;

            $ratio = $material['per_unit'] ?? $material['SoLuongTrenDonVi'] ?? null;
            if ($ratio !== null && $ratio !== '') {
                $ratio = (float) ceil((float) $ratio);
                if ($ratio < 0) {
                    $ratio = 0;
                }
                $normalized[$id]['ratio'] = $ratio;
            }
        }

        $this->db->beginTransaction();

        try {
            $delete = $this->db->prepare(
                'DELETE FROM chi_tiet_ke_hoach_san_xuat_xuong WHERE IdKeHoachSanXuatXuong = :planId'
            );
            $delete->bindValue(':planId', $planId);
            $delete->execute();

            if (!empty($normalized)) {
                $insert = $this->db->prepare(
                    'INSERT INTO chi_tiet_ke_hoach_san_xuat_xuong (IdCTKHSXX, SoLuong, SoLuongTrenDonVi, IdKeHoachSanXuatXuong, IdNguyenLieu)
                     VALUES (:id, :quantity, :ratio, :planId, :materialId)'
                );

                foreach ($normalized as $materialId => $entry) {
                    $insert->bindValue(':id', uniqid('CT'));
                    $insert->bindValue(':quantity', $entry['quantity']);
                    $insert->bindValue(':ratio', $entry['ratio']);
                    $insert->bindValue(':planId', $planId);
                    $insert->bindValue(':materialId', $materialId);
                    $insert->execute();
                }
            }

            $this->db->commit();
        } catch (Throwable $exception) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $exception;
        }
    }

    public function adjustPlannedQuantity(string $planId, string $materialId, int $quantityDelta): void
    {
        $stmt = $this->db->prepare(
            'UPDATE chi_tiet_ke_hoach_san_xuat_xuong
             SET SoLuong = GREATEST(0, SoLuong + :delta)
             WHERE IdKeHoachSanXuatXuong = :planId AND IdNguyenLieu = :materialId'
        );
        $stmt->bindValue(':delta', $quantityDelta, PDO::PARAM_INT);
        $stmt->bindValue(':planId', $planId);
        $stmt->bindValue(':materialId', $materialId);
        $stmt->execute();
    }
}
