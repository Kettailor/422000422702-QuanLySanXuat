<?php

class SuddenlyReport extends BaseModel
{
    protected string $table = 'bien_ban_danh_gia_dot_xuat';
    protected string $primaryKey = 'IdBienBanDanhGiaDX';

    /** Lấy danh sách biên bản mới nhất */
    public function getLatestReports(int $limit = 10): array
    {
        $sql = "SELECT 
                    bb.IdBienBanDanhGiaDX,
                    bb.KetQua,
                    bb.ThoiGian,
                    x.TenXuong
                FROM bien_ban_danh_gia_dot_xuat bb
                LEFT JOIN xuong x ON x.IdXuong = bb.IdXuong
                ORDER BY bb.ThoiGian DESC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Lấy thông tin một biên bản theo ID */
    public function getBienBanById(string $id): ?array
    {
        $stmt = $this->db->prepare("
        SELECT 
            bb.*,
            x.TenXuong,
            nv.HoTen AS NhanVienKiemTra,
            GROUP_CONCAT(DISTINCT t.LoaiTieuChi SEPARATOR ', ') AS LoaiTieuChi
        FROM bien_ban_danh_gia_dot_xuat bb
        LEFT JOIN xuong x ON x.IdXuong = bb.IdXuong
        LEFT JOIN nhan_vien nv ON nv.IdNhanVien = bb.IdNhanVien
        LEFT JOIN ttct_bien_ban_danh_gia_dot_xuat t
            ON t.IdBienBanDanhGiaDX LIKE CONCAT(bb.IdBienBanDanhGiaDX, '%')
        WHERE bb.IdBienBanDanhGiaDX = ?
        GROUP BY bb.IdBienBanDanhGiaDX
        LIMIT 1
    ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }


    /** Lấy danh sách chi tiết tiêu chí của biên bản */
    public function getChiTietByBienBan(string $idBienBan): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM ttct_bien_ban_danh_gia_dot_xuat
            WHERE IdBienBanDanhGiaDX = :id
        ");
        $stmt->execute([':id' => $idBienBan]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Thống kê tiêu chí đạt / không đạt cho 1 biên bản */
    public function getThongKeTieuChi(string $idBienBan): array
    {
        $sql = "
            SELECT 
                SUM(CASE WHEN KetQua = 'Đạt' THEN 1 ELSE 0 END) AS TongTieuChiDat,
                SUM(CASE WHEN KetQua = 'Không đạt' THEN 1 ELSE 0 END) AS TongTieuChiKhongDat
            FROM ttct_bien_ban_danh_gia_dot_xuat
            WHERE IdBienBanDanhGiaDX = :id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $idBienBan]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [
            'TongTieuChiDat' => 0,
            'TongTieuChiKhongDat' => 0
        ];
    }

    /** Thống kê tổng hợp tất cả biên bản */
    public function getSuddenlySummary(): array
    {
        $sql = "SELECT 
                    COUNT(*) AS tong_bien_ban,
                    SUM(CASE WHEN KetQua = 'Đạt' THEN 1 ELSE 0 END) AS so_dat,
                    SUM(CASE WHEN KetQua = 'Không đạt' THEN 1 ELSE 0 END) AS so_khong_dat
                FROM bien_ban_danh_gia_dot_xuat";
        $stmt = $this->db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [
            'tong_bien_ban' => 0,
            'so_dat' => 0,
            'so_khong_dat' => 0
        ];
    }

    /** Dashboard tổng quan */
    public function getDashboardSummary(): array
    {
        $total  = (int)$this->db->query("SELECT COUNT(*) FROM bien_ban_danh_gia_dot_xuat")->fetchColumn();
        $passed = (int)$this->db->query("SELECT COUNT(*) FROM bien_ban_danh_gia_dot_xuat WHERE KetQua = 'Đạt'")->fetchColumn();
        $failed = (int)$this->db->query("SELECT COUNT(*) FROM bien_ban_danh_gia_dot_xuat WHERE KetQua = 'Không đạt'")->fetchColumn();

        return compact('total', 'passed', 'failed');
    }

    /** Danh sách toàn bộ biên bản */
    public function getDanhSachBienBan(): array
    {
        $sql = "
            SELECT 
                bb.IdBienBanDanhGiaDX,
                bb.ThoiGian,
                bb.KetQua,
                x.TenXuong,
                nv.HoTen AS NhanVienKiemTra,
                GROUP_CONCAT(DISTINCT t.LoaiTieuChi SEPARATOR ', ') AS LoaiTieuChi
            FROM bien_ban_danh_gia_dot_xuat bb
            LEFT JOIN xuong x ON x.IdXuong = bb.IdXuong
            LEFT JOIN nhan_vien nv ON nv.IdNhanVien = bb.IdNhanVien
            LEFT JOIN ttct_bien_ban_danh_gia_dot_xuat t 
                ON TRIM(t.IdBienBanDanhGiaDX) = TRIM(bb.IdBienBanDanhGiaDX)
            GROUP BY bb.IdBienBanDanhGiaDX
            ORDER BY bb.ThoiGian DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Sinh mã biên bản tự động */
    public function generateBienBanId(PDO $db): string
    {
        $date = date('Ymd');
        $prefix = 'BBDX' . $date;

        $stmt = $db->prepare("
        SELECT MAX(CAST(SUBSTRING(IdBienBanDanhGiaDX, 13, 2) AS UNSIGNED))
        FROM bien_ban_danh_gia_dot_xuat
        WHERE IdBienBanDanhGiaDX LIKE :prefix
    ");
        $stmt->execute([
            ':prefix' => $prefix . '%'
        ]);

        $max = (int)$stmt->fetchColumn();
        $next = $max + 1;

        return $prefix . str_pad($next, 2, '0', STR_PAD_LEFT);
    }


    /** Tạo biên bản cha */
    public function create(array $data): bool
    {
        $sql = "INSERT INTO bien_ban_danh_gia_dot_xuat
                (IdBienBanDanhGiaDX, IdXuong, IdNhanVien, ThoiGian, TongTCD, TongTCKD, KetQua)
                VALUES (:IdBienBanDanhGiaDX, :IdXuong, :IdNhanVien, :ThoiGian, :TongTCD, :TongTCKD, :KetQua)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    /** Thêm chi tiết tiêu chí cho biên bản */
    public function insertChiTietTieuChi(
        string $idBienBan,
        string $loaiTieuChi,
        string $tieuChi,
        int $diemDat,
        ?string $ghiChu = null,
        ?string $fileName = null
    ): bool {
        // Sinh ID chi tiết biên bản: CTBBDX20251102A, B, C...
        $stmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM ttct_bien_ban_danh_gia_dot_xuat
            WHERE IdBienBanDanhGiaDX = :id
        ");
        $stmt->execute([':id' => $idBienBan]);
        $suffix = chr(65 + (int)$stmt->fetchColumn());
        $idChiTiet = 'CT' . $idBienBan . $suffix;

        $sql = "INSERT INTO ttct_bien_ban_danh_gia_dot_xuat
                (IdTTCTBBDGDX, LoaiTieuChi, TieuChi, DiemDG, GhiChu, HinhAnh, IdBienBanDanhGiaDX)
                VALUES (:IdCT, :Loai, :TieuChi, :Diem, :GhiChu, :HinhAnh, :IdBB)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':IdCT'     => $idChiTiet,
            ':Loai'     => $loaiTieuChi,
            ':TieuChi'  => $tieuChi,
            ':Diem'     => $diemDat,
            ':GhiChu'   => $ghiChu,
            ':HinhAnh'  => $fileName,
            ':IdBB'     => $idBienBan
        ]);
    }

    /** Cập nhật tổng điểm và kết quả */
    public function updateTong(string $idBienBan, int $tongTCD, int $tongTCKD, string $ketQua): bool
    {
        $sql = "UPDATE bien_ban_danh_gia_dot_xuat
                SET TongTCD = :tcd, TongTCKD = :tckd, KetQua = :kq
                WHERE IdBienBanDanhGiaDX = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':tcd' => $tongTCD,
            ':tckd' => $tongTCKD,
            ':kq'  => $ketQua,
            ':id'  => $idBienBan
        ]);
    }

    /** Xóa biên bản và chi tiết liên quan */
    public function deleteBienBanCascade(string $id): bool
    {
        try {
            $this->db->beginTransaction();

            $stmt1 = $this->db->prepare("
                DELETE FROM ttct_bien_ban_danh_gia_dot_xuat 
                WHERE IdBienBanDanhGiaDX = :id
            ");
            $stmt1->execute([':id' => $id]);

            $stmt2 = $this->db->prepare("
                DELETE FROM bien_ban_danh_gia_dot_xuat 
                WHERE IdBienBanDanhGiaDX = :id
            ");
            $stmt2->execute([':id' => $id]);

            $this->db->commit();
            return true;
        } catch (Throwable $e) {
            $this->db->rollBack();
            error_log("Lỗi xóa biên bản đột xuất: " . $e->getMessage());
            return false;
        }
    }

    /** Cho phép controller truy cập PDO */
    public function getConnection(): PDO
    {
        return $this->db;
    }
    public function getImagesByReportId(string $idBienBan): array
    {
        $sql = "
        SELECT HinhAnh
        FROM ttct_bien_ban_danh_gia_dot_xuat
        WHERE IdBienBanDanhGiaDX = ?
          AND HinhAnh IS NOT NULL
          AND HinhAnh <> ''
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idBienBan]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
