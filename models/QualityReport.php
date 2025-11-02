<?php

class QualityReport extends BaseModel
{
    protected string $table = 'bien_ban_danh_gia_thanh_pham';
    protected string $primaryKey = 'IdBienBanDanhGiaSP';

    /** Lấy danh sách biên bản mới nhất */
    public function getLatestReports(int $limit = 10): array
    {
        $sql = 'SELECT 
                    bb.IdBienBanDanhGiaSP,
                    bb.KetQua,
                    bb.ThoiGian,
                    lo.IdLo,
                    lo.SoLuong,
                    sp.TenSanPham,
                    x.TenXuong
                FROM bien_ban_danh_gia_thanh_pham bb
                JOIN lo ON lo.IdLo = bb.IdLo
                LEFT JOIN san_pham sp ON sp.IdSanPham = lo.IdSanPham
                LEFT JOIN kho k ON k.IdKho = lo.IdKho
                LEFT JOIN xuong x ON x.IdXuong = k.IdXuong
                ORDER BY bb.ThoiGian DESC
                LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Lấy thống kê tổng quan */
    public function getQualitySummary(): array
    {
        $sql = 'SELECT 
                    COUNT(*) AS tong_bien_ban,
                    SUM(CASE WHEN KetQua = "Đạt" THEN 1 ELSE 0 END) AS so_dat,
                    SUM(CASE WHEN KetQua = "Không đạt" THEN 1 ELSE 0 END) AS so_khong_dat
                FROM bien_ban_danh_gia_thanh_pham';
        return $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    /** Danh sách lô kèm biên bản mới nhất (nếu có) */
    public function getDanhSachLo(): array
    {
        $sql = 'SELECT 
                    lo.IdLo,
                    lo.TenLo,
                    lo.SoLuong,
                    lo.NgayTao,
                    sp.TenSanPham,
                    x.TenXuong,
                    bb.IdBienBanDanhGiaSP,
                    bb.KetQua
                FROM lo
                LEFT JOIN san_pham sp ON sp.IdSanPham = lo.IdSanPham
                LEFT JOIN kho k ON k.IdKho = lo.IdKho
                LEFT JOIN xuong x ON x.IdXuong = k.IdXuong
                LEFT JOIN (
                    SELECT IdLo, MAX(IdBienBanDanhGiaSP) AS IdBienBanDanhGiaSP, KetQua
                    FROM bien_ban_danh_gia_thanh_pham
                    GROUP BY IdLo
                ) bb ON bb.IdLo = lo.IdLo
                ORDER BY lo.NgayTao DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Lấy thông tin chi tiết 1 lô */
    public function getLoInfo(string $idLo): ?array
    {
        $sql = 'SELECT 
                    lo.IdLo,
                    lo.TenLo,
                    sp.TenSanPham,
                    x.TenXuong
                FROM lo
                LEFT JOIN san_pham sp ON sp.IdSanPham = lo.IdSanPham
                LEFT JOIN kho k ON k.IdKho = lo.IdKho
                LEFT JOIN xuong x ON x.IdXuong = k.IdXuong
                WHERE lo.IdLo = :idLo
                LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':idLo' => $idLo]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /** Tạo biên bản cha */
    public function create(array $data): bool
    {
        if (empty($data['IdBienBanDanhGiaSP'])) {
            $data['IdBienBanDanhGiaSP'] = $this->generateBienBanId();
        }

        $sql = 'INSERT INTO bien_ban_danh_gia_thanh_pham
                    (IdBienBanDanhGiaSP, ThoiGian, TongTCD, TongTCKD, KetQua, IdLo)
                VALUES
                    (:IdBienBanDanhGiaSP, :ThoiGian, :TongTCD, :TongTCKD, :KetQua, :IdLo)';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':IdBienBanDanhGiaSP' => $data['IdBienBanDanhGiaSP'],
            ':ThoiGian'           => $data['ThoiGian'],
            ':TongTCD'            => $data['TongTCD'],
            ':TongTCKD'           => $data['TongTCKD'],
            ':KetQua'             => $data['KetQua'],
            ':IdLo'               => $data['IdLo'],
        ]);
    }

    /** Cập nhật tổng tiêu chí */
    public function updateTong(string $idBienBan, int $tongTCD, int $tongTCKD, string $ketQua): bool
    {
        $sql = 'UPDATE bien_ban_danh_gia_thanh_pham
                SET TongTCD = :tcd, TongTCKD = :tckd, KetQua = :kq
                WHERE IdBienBanDanhGiaSP = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':tcd' => $tongTCD,
            ':tckd' => $tongTCKD,
            ':kq'  => $ketQua,
            ':id'  => $idBienBan,
        ]);
    }

    /** Sinh ID biên bản (BBTPyyyymmddxx) */
    public function generateBienBanId(): string
    {
        $prefix = 'BBTP' . date('Ymd');
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM bien_ban_danh_gia_thanh_pham WHERE IdBienBanDanhGiaSP LIKE :prefix");
        $stmt->execute([':prefix' => $prefix . '%']);
        $count = (int)$stmt->fetchColumn() + 1;

        do {
            $suffix = str_pad((string)$count, 2, '0', STR_PAD_LEFT);
            $newId = $prefix . $suffix;
            $check = $this->db->prepare("SELECT 1 FROM bien_ban_danh_gia_thanh_pham WHERE IdBienBanDanhGiaSP = :id LIMIT 1");
            $check->execute([':id' => $newId]);
            if (!$check->fetchColumn()) break;
            $count++;
        } while (true);

        return $newId;
    }

    /** Sinh ID chi tiết tiêu chí */
    public function generateChiTietId(string $idBienBan): string
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM ttct_bien_ban_danh_gia_thanh_pham WHERE IdBienBanDanhGiaSP = :id");
        $stmt->execute([':id' => $idBienBan]);
        $count = (int)$stmt->fetchColumn();
        $suffix = ($count < 26) ? chr(65 + $count) : $count + 1;
        return 'CT' . $idBienBan . $suffix;
    }

    /** Thêm chi tiết tiêu chí */
    public function insertChiTietTieuChi(
        string $idBienBan,
        string $tieuChi,
        int $diemDat,
        ?string $ghiChu = null,
        ?string $fileName = null
    ): bool {
        $idChiTiet = $this->generateChiTietId($idBienBan);
        $sql = 'INSERT INTO ttct_bien_ban_danh_gia_thanh_pham
                (IdTTCTBBDGTP, Tieuchi, DiemD, GhiChu, HinhAnh, IdBienBanDanhGiaSP)
                VALUES (:IdTT, :TC, :Diem, :GC, :HA, :IdBB)';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':IdTT' => $idChiTiet,
            ':TC'   => $tieuChi,
            ':Diem' => $diemDat,
            ':GC'   => $ghiChu,
            ':HA'   => $fileName,
            ':IdBB' => $idBienBan
        ]);
    }

    /** Dashboard tổng hợp */
    public function getDashboardSummary(): array
    {
        $total = $this->db->query("SELECT COUNT(*) FROM lo")->fetchColumn();
        $checked = $this->db->query("SELECT COUNT(DISTINCT IdLo) FROM bien_ban_danh_gia_thanh_pham")->fetchColumn();
        $passed = $this->db->query("SELECT COUNT(DISTINCT IdLo) FROM bien_ban_danh_gia_thanh_pham WHERE KetQua = 'Đạt'")->fetchColumn();
        $failed = $this->db->query("SELECT COUNT(DISTINCT IdLo) FROM bien_ban_danh_gia_thanh_pham WHERE KetQua = 'Không đạt'")->fetchColumn();

        $sqlUnchecked = "SELECT COUNT(lo.IdLo)
                         FROM lo
                         LEFT JOIN bien_ban_danh_gia_thanh_pham bb ON bb.IdLo = lo.IdLo
                         WHERE bb.IdLo IS NULL";
        $unchecked = $this->db->query($sqlUnchecked)->fetchColumn();

        return [
            'total'     => (int)$total,
            'checked'   => (int)$checked,
            'passed'    => (int)$passed,
            'failed'    => (int)$failed,
            'unchecked' => (int)$unchecked,
        ];
    }

    /** Lấy danh sách theo loại (passed, failed, unchecked, all) */
    public function getListByType(string $type): array
    {
        switch ($type) {
            case 'passed':
                $sql = "SELECT lo.*, sp.TenSanPham, x.TenXuong, bb.KetQua
                        FROM lo
                        LEFT JOIN san_pham sp ON sp.IdSanPham = lo.IdSanPham
                        LEFT JOIN kho k ON k.IdKho = lo.IdKho
                        LEFT JOIN xuong x ON x.IdXuong = k.IdXuong
                        INNER JOIN bien_ban_danh_gia_thanh_pham bb 
                            ON lo.IdLo = bb.IdLo AND bb.KetQua = 'Đạt'
                        ORDER BY lo.NgayTao DESC";
                break;
            case 'failed':
                $sql = "SELECT lo.*, sp.TenSanPham, x.TenXuong, bb.KetQua
                        FROM lo
                        LEFT JOIN san_pham sp ON sp.IdSanPham = lo.IdSanPham
                        LEFT JOIN kho k ON k.IdKho = lo.IdKho
                        LEFT JOIN xuong x ON x.IdXuong = k.IdXuong
                        INNER JOIN bien_ban_danh_gia_thanh_pham bb 
                            ON lo.IdLo = bb.IdLo AND bb.KetQua = 'Không đạt'
                        ORDER BY lo.NgayTao DESC";
                break;
            case 'unchecked':
                $sql = "SELECT lo.*, sp.TenSanPham, x.TenXuong
                        FROM lo
                        LEFT JOIN san_pham sp ON sp.IdSanPham = lo.IdSanPham
                        LEFT JOIN kho k ON k.IdKho = lo.IdKho
                        LEFT JOIN xuong x ON x.IdXuong = k.IdXuong
                        WHERE lo.IdLo NOT IN (
                            SELECT IdLo FROM bien_ban_danh_gia_thanh_pham
                        )
                        ORDER BY lo.NgayTao DESC";
                break;
            default:
                $sql = "SELECT lo.*, sp.TenSanPham, x.TenXuong, bb.KetQua
                        FROM lo
                        LEFT JOIN san_pham sp ON sp.IdSanPham = lo.IdSanPham
                        LEFT JOIN kho k ON k.IdKho = lo.IdKho
                        LEFT JOIN xuong x ON x.IdXuong = k.IdXuong
                        LEFT JOIN bien_ban_danh_gia_thanh_pham bb ON bb.IdLo = lo.IdLo
                        ORDER BY lo.NgayTao DESC";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Cho phép controller truy cập PDO */
    public function getConnection(): PDO
    {
        return $this->db;
    }

    /** Xóa biên bản + chi tiết */
    public function deleteBienBanCascade(string $idBienBan, ?string $idLo = null): bool
    {
        try {
            $this->db->beginTransaction();

            // Xóa chi tiết trước
            $this->db->prepare("
                DELETE FROM ttct_bien_ban_danh_gia_thanh_pham 
                WHERE IdBienBanDanhGiaSP = :id
            ")->execute([':id' => $idBienBan]);

            // Xóa biên bản cha (nếu có IdLo, thêm điều kiện)
            $stmt = $this->db->prepare(
                "
                DELETE FROM bien_ban_danh_gia_thanh_pham 
                WHERE IdBienBanDanhGiaSP = :id" . ($idLo ? " AND IdLo = :lo" : "")
            );

            $params = [':id' => $idBienBan];
            if ($idLo) $params[':lo'] = $idLo;

            $stmt->execute($params);

            if ($stmt->rowCount() === 0) {
                throw new Exception("Không tìm thấy biên bản để xóa ($idBienBan)");
            }

            $this->db->commit();
            return true;
        } catch (Throwable $e) {
            $this->db->rollBack();
            error_log("❌ Lỗi khi xóa biên bản $idBienBan: " . $e->getMessage());
            return false;
        }
    }
}
