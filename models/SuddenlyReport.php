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

    public function getBienBanById($id) {
    $stmt = $this->db->prepare("SELECT * FROM bien_ban_danh_gia_dot_xuat WHERE IdBienBanDanhGiaDX = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


    /** Thống kê tổng hợp biên bản */
    public function getSuddenlySummary(): array
    {
        $sql = "SELECT 
                    COUNT(*) AS tong_bien_ban,
                    SUM(CASE WHEN KetQua = 'Đạt' THEN 1 ELSE 0 END) AS so_dat,
                    SUM(CASE WHEN KetQua = 'Không đạt' THEN 1 ELSE 0 END) AS so_khong_dat
                FROM bien_ban_danh_gia_dot_xuat";
        return $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    /** Dashboard tổng quan */
    public function getDashboardSummary(): array
    {
        $total  = $this->db->query("SELECT COUNT(*) FROM bien_ban_danh_gia_dot_xuat")->fetchColumn();
        $passed = $this->db->query("SELECT COUNT(*) FROM bien_ban_danh_gia_dot_xuat WHERE KetQua = 'Đạt'")->fetchColumn();
        $failed = $this->db->query("SELECT COUNT(*) FROM bien_ban_danh_gia_dot_xuat WHERE KetQua = 'Không đạt'")->fetchColumn();

        return [
            'total'  => (int)$total,
            'passed' => (int)$passed,
            'failed' => (int)$failed
        ];
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
            -- Lấy toàn bộ loại tiêu chí nếu có nhiều dòng, ví dụ: 'An toàn điện, Vệ sinh công nghiệp'
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

    /** Sinh mã biên bản */
    public function generateBienBanId(): string
    {
        $prefix = 'BBDX' . date('Ymd');
        $stmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM bien_ban_danh_gia_dot_xuat 
            WHERE IdBienBanDanhGiaDX LIKE :prefix
        ");
        $stmt->execute([':prefix' => $prefix . '%']);
        $count = (int)$stmt->fetchColumn() + 1;
        return $prefix . str_pad((string)$count, 2, '0', STR_PAD_LEFT);
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

    /** Sinh ID chi tiết */
    public function generateChiTietId(string $idBienBan): string
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM ttct_bien_ban_danh_gia_dot_xuat
            WHERE IdBienBanDanhGiaDX = :id
        ");
        $stmt->execute([':id' => $idBienBan]);
        $suffix = chr(65 + (int)$stmt->fetchColumn());
        return 'CT' . $idBienBan . $suffix;
    }

    /** Thêm chi tiết tiêu chí */
    public function insertChiTietTieuChi(
    string $idBienBan,
    string $loaiTieuChi,
    string $tieuChi,
    int $diemDat,
    ?string $ghiChu = null,
    ?string $fileName = null
): bool {
    // Sinh mã chi tiết biên bản (VD: CTBBDX20251029A)
    $stmt = $this->db->prepare("
        SELECT COUNT(*) FROM ttct_bien_ban_danh_gia_dot_xuat 
        WHERE IdBienBanDanhGiaDX = :id
    ");
    $stmt->execute([':id' => $idBienBan]);
    $suffix = chr(65 + (int)$stmt->fetchColumn()); // A, B, C...
    $idChiTiet = 'CT' . $idBienBan . $suffix;

    // Thêm bản ghi chi tiết
    $sql = "
        INSERT INTO ttct_bien_ban_danh_gia_dot_xuat
        (IdTTCTBBDGDX, LoaiTieuChi, TieuChi, DiemDG, GhiChu, HinhAnh, IdBienBanDanhGiaDX)
        VALUES (:IdCT, :Loai, :TieuChi, :Diem, :GhiChu, :HinhAnh, :IdBB)
    ";

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
            ':tckd'=> $tongTCKD,
            ':kq'  => $ketQua,
            ':id'  => $idBienBan
        ]);
    }

    /** Xóa biên bản và chi tiết liên quan */
    public function deleteBienBanCascade(string $id): bool
    {
        try {
            $this->db->beginTransaction();

            // Xóa chi tiết trước
            $stmt1 = $this->db->prepare("
                DELETE FROM ttct_bien_ban_danh_gia_dot_xuat 
                WHERE IdBienBanDanhGiaDX = :id
            ");
            $stmt1->execute([':id' => $id]);

            // Xóa biên bản cha
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
}
