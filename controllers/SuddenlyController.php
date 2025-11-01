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
    // Lọc theo loại (all / production / worker)
    $filter = $_GET['filter'] ?? 'all';

    // Nạp dữ liệu core từ file QualityCriteria.php
    $corePath = __DIR__ . '/../core/QualityCriteria.php';
    $coreCriteria = file_exists($corePath) ? require $corePath : [];

    // Lấy danh sách biên bản từ database
    $listBienBan = $this->SuddenlyModel->getDanhSachBienBan();

    // So sánh dữ liệu core với DB để xác định loại hợp lệ
    foreach ($listBienBan as &$bb) {
        $type = strtolower(trim($bb['LoaiTieuChi'] ?? ''));
        if ($type && isset($coreCriteria[$type])) {
            $bb['LoaiHopLe'] = true;
        } else {
            $bb['LoaiHopLe'] = false;
        }
    }
    unset($bb);

    // Nếu có filter (production/worker) thì lọc danh sách
    if ($filter !== 'all') {
        $listBienBan = array_filter($listBienBan, function ($bb) use ($filter) {
            return strtolower($bb['LoaiTieuChi'] ?? '') === $filter;
        });
    }

    // Lấy các thống kê
    $reports   = $this->SuddenlyModel->getLatestReports(50);
    $summary   = $this->SuddenlyModel->getSuddenlySummary();
    $dashboard = $this->SuddenlyModel->getDashboardSummary();

    // Render ra view
    $this->render('suddenly/index', [
        'title'       => 'Kiểm tra đột xuất',
        'reports'     => $reports,
        'summary'     => $summary,
        'dashboard'   => $dashboard,
        'listBienBan' => $listBienBan,
        'filter'      => $filter
    ]);
}


    /** Xem chi tiết biên bản đột xuất */
    public function read(): void
{
    $id = $_GET['id'] ?? null;

    // 1. Kiểm tra mã biên bản
    if (!$id) {
        $this->setFlash('danger', 'Thiếu mã biên bản.');
        $this->redirect('?controller=suddenly&action=index');
        return;
    }

    // 2. Kết nối database
    $db = $this->SuddenlyModel->getConnection();

    // 3. Lấy thông tin biên bản + xưởng
    $stmt = $db->prepare("
    SELECT 
        bb.*, 
        x.TenXuong,
        nv.HoTen AS NhanVienKiemTra
    FROM bien_ban_danh_gia_dot_xuat bb
    LEFT JOIN xuong x ON x.IdXuong = bb.IdXuong
    LEFT JOIN nhan_vien nv ON nv.IdNhanVien = bb.IdNhanVien
    WHERE bb.IdBienBanDanhGiaDX = :id
    LIMIT 1
");

    $stmt->execute([':id' => $id]);
    $report = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$report) {
        $this->setFlash('warning', 'Không tìm thấy biên bản.');
        $this->redirect('?controller=suddenly&action=index');
        return;
    }

    // 4. Lấy chi tiết tiêu chí
    $stmt2 = $db->prepare(" SELECT * FROM ttct_bien_ban_danh_gia_dot_xuat WHERE IdBienBanDanhGiaDX = :id ");
    $stmt2->execute([':id' => $id]);
    $details = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    // 5. Gán loại tiêu chí (nếu có)
    $report['LoaiTieuChi'] = !empty($details[0]['LoaiTieuChi'] ?? null)
        ? $details[0]['LoaiTieuChi']
        : null;

    // 6. Render sang view
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

        // 🔹 Lấy danh sách xưởng
        $stmt = $db->query("SELECT IdXuong, TenXuong FROM xuong ORDER BY TenXuong");
        $xuongList = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 🔹 Lấy toàn bộ nhân viên còn hoạt động
        $stmtNV = $db->query("
            SELECT IdNhanVien, HoTen 
            FROM nhan_vien
            WHERE TrangThai IS NULL OR TrangThai = 'Đang làm việc'
            ORDER BY HoTen
        ");
        $nhanVienList = $stmtNV->fetchAll(PDO::FETCH_ASSOC);

        // 🔹 Sinh mã biên bản
        $date = date('Ymd');
        $stmt2 = $db->prepare("SELECT COUNT(*) FROM bien_ban_danh_gia_dot_xuat WHERE IdBienBanDanhGiaDX LIKE :prefix");
        $stmt2->execute([':prefix' => 'BBDX' . $date . '%']);
        $count = (int)$stmt2->fetchColumn() + 1;
        $maBienBan = 'BBDX' . $date . str_pad($count, 2, '0', STR_PAD_LEFT);

        // 🔹 Lấy loại biên bản (factory / production / worker)
        //    Ví dụ: ?controller=suddenly&action=create&type=production
        $type = $_GET['type'] ?? 'production';
        $criteriaData = require __DIR__ . '/../core/QualityCriteria.php';

        if (!isset($criteriaData[$type])) {
            $this->setFlash('danger', 'Loại biên bản không hợp lệ.');
            $this->redirect('?controller=suddenly&action=index');
        }

        // 🔹 Lấy danh sách nhóm tiêu chí của loại tương ứng
        $criteriaList = $criteriaData[$type];
        $criteriaGroups = array_keys($criteriaList);

        // Render
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

        if (empty($arrTieuChi)) {
            $this->setFlash('danger', 'Không có tiêu chí nào được nhập.');
            $this->redirect('?controller=suddenly&action=create');
        }

        $db = $this->SuddenlyModel->getConnection();
        $db->beginTransaction();

        try {
            // Tạo biên bản cha
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

            $this->setFlash('success', "Đã tạo biên bản $idBienBan thành công.");
        } catch (Throwable $e) {
            $db->rollBack();
            $this->setFlash('danger', 'Không thể tạo biên bản: ' . $e->getMessage());
        }

        $this->redirect('?controller=suddenly&action=index');
    }

    /** Xóa biên bản đột xuất */
    public function delete(): void
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            if ($this->SuddenlyModel->deleteBienBanCascade($id)) {
                $this->setFlash('success', "Đã xóa biên bản $id và các chi tiết liên quan.");
            } else {
                $this->setFlash('danger', "Không thể xóa biên bản $id. Kiểm tra lại ràng buộc dữ liệu.");
            }
        } else {
            $this->setFlash('warning', 'Thiếu mã biên bản để xóa.');
        }

        $this->redirect('?controller=suddenly&action=index');
    }

    
}
