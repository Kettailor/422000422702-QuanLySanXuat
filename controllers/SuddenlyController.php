<?php

class SuddenlyController extends Controller
{
    private SuddenlyReport $SuddenlyModel;

    public function __construct()
    {
        $this->authorize(['VT_KIEM_SOAT_CL', 'VT_QUANLY_XUONG', 'VT_BAN_GIAM_DOC']);
        $this->SuddenlyModel = new SuddenlyReport();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    /** Trang chính - Dashboard biên bản đột xuất */
    public function index(): void
    {
        $filter = $_GET['filter'] ?? 'all';

        $corePath = __DIR__ . '/../core/QualityCriteria.php';
        $coreCriteria = file_exists($corePath) ? require $corePath : [];

        $listBienBan = $this->SuddenlyModel->getDanhSachBienBan();

        foreach ($listBienBan as &$bb) {
            $type = strtolower(trim($bb['LoaiTieuChi'] ?? ''));
            $bb['LoaiHopLe'] = $type && isset($coreCriteria[$type]);
        }
        unset($bb);

        if ($filter !== 'all') {
            $listBienBan = array_filter($listBienBan, function ($bb) use ($filter) {
                return strtolower($bb['LoaiTieuChi'] ?? '') === $filter;
            });
        }

        $reports   = $this->SuddenlyModel->getLatestReports(50);
        $summary   = $this->SuddenlyModel->getSuddenlySummary();
        $dashboard = $this->SuddenlyModel->getDashboardSummary();

        // ✅ Lấy flash qua query string
        $flash = null;
        if (!empty($_GET['msg'])) {
            $flash = [
                'type'    => $_GET['type'] ?? 'success',
                'message' => $_GET['msg']
            ];
        }

        $this->render('suddenly/index', [
            'title'       => 'Kiểm tra đột xuất',
            'reports'     => $reports,
            'summary'     => $summary,
            'dashboard'   => $dashboard,
            'listBienBan' => $listBienBan,
            'filter'      => $filter,
            'flash'       => $flash
        ]);
    }

    /** Xem chi tiết biên bản đột xuất */
    public function read(): void
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('?controller=suddenly&action=index&msg=' . urlencode('Thiếu mã biên bản.') . '&type=danger');
            return;
        }

        $db = $this->SuddenlyModel->getConnection();

        // Lấy thông tin biên bản
        $stmt = $db->prepare("
        SELECT 
            bb.*, 
            x.TenXuong,
            nv.HoTen AS NhanVienKiemTra,
            COALESCE(bb.TongTCD, 0)  AS TongTieuChiDat,
            COALESCE(bb.TongTCKD, 0) AS TongTieuChiKhongDat
        FROM bien_ban_danh_gia_dot_xuat bb
        LEFT JOIN xuong x ON x.IdXuong = bb.IdXuong
        LEFT JOIN nhan_vien nv ON nv.IdNhanVien = bb.IdNhanVien
        WHERE bb.IdBienBanDanhGiaDX = :id
        LIMIT 1
    ");
        $stmt->execute([':id' => $id]);
        $report = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$report) {
            $this->redirect('?controller=suddenly&action=index&msg=' . urlencode('Không tìm thấy biên bản.') . '&type=warning');
            return;
        }

        // Lấy chi tiết tiêu chí (nếu có)
        $stmt2 = $db->prepare("
        SELECT *
        FROM ttct_bien_ban_danh_gia_dot_xuat
        WHERE IdBienBanDanhGiaDX = :id
    ");
        $stmt2->execute([':id' => $id]);
        $details = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        // Lấy loại tiêu chí (nếu có)
        $report['LoaiTieuChi'] = !empty($details[0]['LoaiTieuChi'] ?? null)
            ? $details[0]['LoaiTieuChi']
            : null;

        // Render view (hiển thị đầy đủ thông tin + tổng tiêu chí)
        $this->render('suddenly/read', [
            'title'   => 'Chi tiết biên bản đột xuất',
            'report'  => $report,
            'details' => $details
        ]);
    }

    /** Form tạo mới biên bản đột xuất */
    public function create(): void
    {
        $db = $this->SuddenlyModel->getConnection();
        $stmt = $db->query("SELECT IdXuong, TenXuong FROM xuong ORDER BY TenXuong");
        $xuongList = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmtNV = $db->query("
            SELECT IdNhanVien, HoTen 
            FROM nhan_vien
            WHERE TrangThai IS NULL OR TrangThai = 'Đang làm việc'
            ORDER BY HoTen
        ");
        $nhanVienList = $stmtNV->fetchAll(PDO::FETCH_ASSOC);

        $date = date('Ymd');
        $stmt2 = $db->prepare("SELECT COUNT(*) FROM bien_ban_danh_gia_dot_xuat WHERE IdBienBanDanhGiaDX LIKE :prefix");
        $stmt2->execute([':prefix' => 'BBDX' . $date . '%']);
        $count = (int)$stmt2->fetchColumn() + 1;
        $maBienBan = 'BBDX' . $date . str_pad($count, 2, '0', STR_PAD_LEFT);

        $type = $_GET['type'] ?? 'production';
        $criteriaData = require __DIR__ . '/../core/QualityCriteria.php';

        if (!isset($criteriaData[$type])) {
            $this->redirect('?controller=suddenly&action=index&msg=' . urlencode('Loại biên bản không hợp lệ.') . '&type=danger');
        }

        $criteriaList = $criteriaData[$type];
        $criteriaGroups = array_keys($criteriaList);

        $this->render('suddenly/create', [
            'title'          => 'Tạo biên bản đột xuất',
            'xuongList'      => $xuongList,
            'nhanVienList'   => $nhanVienList,
            'criteriaList'   => $criteriaList,
            'criteriaGroups' => $criteriaGroups,
            'maBienBan'      => $maBienBan,
            'type'           => $type,
        ]);
    }

    /** Lưu biên bản đột xuất */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=suddenly&action=index');
        }

        $idBienBan   = trim($_POST['IdBienBanDanhGiaDX'] ?? '');
        if ($idBienBan === '') {
            $idBienBan = $this->SuddenlyModel->generateBienBanId();
        }

        $idXuong     = $_POST['IdXuong'] ?? null;
        $thoiGian    = $_POST['ThoiGian'] ?? date('Y-m-d H:i:s');
        $loaiTieuChi = $_POST['LoaiTieuChi'] ?? ($_GET['type'] ?? '');
        $arrTieuChi  = $_POST['TieuChi'] ?? [];
        $arrDiemDat  = $_POST['DiemDat'] ?? [];
        $arrGhiChu   = $_POST['GhiChuTC'] ?? [];
        $files       = $_FILES['FileMinhChung'] ?? null;
        $idNhanVien  = $_POST['IdNhanVien'] ?? null;

        // --- Kiểm tra dữ liệu đầu vào ---
        if (empty($idXuong)) {
            $this->redirect('?controller=suddenly&action=create&msg=' . urlencode('Yêu cầu chọn Xưởng kiểm tra.') . '&type=warning');
        }

        if (empty($idNhanVien)) {
            $this->redirect('?controller=suddenly&action=create&msg=' . urlencode('Yêu cầu chọn Nhân viên kiểm tra.') . '&type=warning');
        }

        if (empty($arrTieuChi)) {
            $this->redirect('?controller=suddenly&action=create&msg=' . urlencode('Không có tiêu chí nào được nhập.') . '&type=danger');
        }

        foreach ($arrDiemDat as $diem) {
            if ($diem === '' || !is_numeric($diem) || $diem < 0 || $diem > 10) {
                $this->redirect('?controller=suddenly&action=create&msg=' . urlencode('Điểm tiêu chí phải nằm trong khoảng 0–10 và không được bỏ trống.') . '&type=warning');
            }
        }

        foreach ($arrGhiChu as $note) {
            if (preg_match('/[#@\$%<>\{\}\[\]\;]/', $note)) {
                $this->redirect(
                    '?controller=suddenly&action=create&msg=' . urlencode('Ghi chú chứa kí tự không hợp lệ, nhập lại Ghi chú') .
                        '&type=danger'
                );
            }
        }

        if (!$files || empty($files['name'][0])) {
            $this->redirect('?controller=suddenly&action=create&msg=' . urlencode('Yêu cầu tải ảnh minh chứng.') . '&type=danger');
        }

        foreach ($files['name'] as $i => $name) {
            if (empty($name)) {
                $this->redirect('?controller=suddenly&action=create&msg=' . urlencode('Yêu cầu tải ảnh minh chứng cho tất cả tiêu chí.') . '&type=danger');
            }
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                $this->redirect('?controller=suddenly&action=create&msg=' . urlencode('Định dạng ảnh không hợp lệ. Chỉ chấp nhận JPG hoặc PNG.') . '&type=danger');
            }
        }
        // --- Hết phần kiểm tra ---

        $db = $this->SuddenlyModel->getConnection();
        $db->beginTransaction();

        try {
            $this->SuddenlyModel->create([
                'IdBienBanDanhGiaDX' => $idBienBan,
                'IdXuong'            => $idXuong,
                'IdNhanVien'         => $idNhanVien,
                'ThoiGian'           => $thoiGian,
                'TongTCD'            => 0,
                'TongTCKD'           => 0,
                'KetQua'             => 'Không đạt',
            ]);

            $tongTCD = 0;
            $tongTCKD = 0;

            foreach ($arrTieuChi as $i => $tieuChi) {
                if (trim($tieuChi) === '') continue;

                $diem = max(0, min(10, (float)($arrDiemDat[$i] ?? 0)));
                $ghiChu = trim($arrGhiChu[$i] ?? '');
                $fileName = null;

                if ($files && !empty($files['name'][$i])) {
                    $uploadDir = __DIR__ . '/../uploads/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                    $safeName = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $files['name'][$i]);
                    $fileName = uniqid('mc_') . '_' . $safeName;
                    move_uploaded_file($files['tmp_name'][$i], $uploadDir . $fileName);
                }

                $this->SuddenlyModel->insertChiTietTieuChi(
                    $idBienBan,
                    $loaiTieuChi,
                    $tieuChi,
                    (int)$diem,
                    $ghiChu,
                    $fileName
                );

                if ($diem >= 9) $tongTCD++;
                else $tongTCKD++;
            }

            $ketQuaTong = ($tongTCKD > 0) ? 'Không đạt' : 'Đạt';
            $this->SuddenlyModel->updateTong($idBienBan, $tongTCD, $tongTCKD, $ketQuaTong);
            $db->commit();

            $msg = "Biên bản {$idBienBan} lưu thành công.";
            $this->redirect('?controller=suddenly&action=index&msg=' . urlencode($msg) . '&type=success');
        } catch (Throwable $e) {
            $db->rollBack();
            $this->redirect('?controller=suddenly&action=create&msg=' . urlencode('Không thể lưu biên bản: ' . $e->getMessage()) . '&type=danger');
        }
    }

    /** Xóa biên bản đột xuất */
    public function delete(): void
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('?controller=suddenly&action=index&msg=' . urlencode('Thiếu mã biên bản để xóa.') . '&type=warning');
        }

        $deleted = $this->SuddenlyModel->deleteBienBanCascade($id);

        if ($deleted) {
            $this->redirect('?controller=suddenly&action=index&msg=' . urlencode("Xóa biên bản $id thành công.") . '&type=success');
        } else {
            $this->redirect('?controller=suddenly&action=index&msg=' . urlencode("Không thể xóa biên bản $id. Kiểm tra lại ràng buộc dữ liệu.") . '&type=danger');
        }
    }
}
