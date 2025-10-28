<?php

class QualityController extends Controller
{
    private QualityReport $qualityModel;

    public function __construct()
    {
        $this->authorize(['VT_KIEM_SOAT_CL', 'VT_QUANLY_XUONG', 'VT_BAN_GIAM_DOC']);
        $this->qualityModel = new QualityReport();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    /** Dashboard chính */
    public function index(): void
    {
        $reports   = $this->qualityModel->getLatestReports(50);
        $summary   = $this->qualityModel->getQualitySummary();
        $dashboard = $this->qualityModel->getDashboardSummary();
        $listLo    = $this->qualityModel->getDanhSachLo();

        $this->render('quality/index', [
            'title'     => 'Kiểm soát chất lượng',
            'reports'   => $reports,
            'summary'   => $summary,
            'dashboard' => $dashboard,
            'listLo'    => $listLo
        ]);
    }

    /** Danh sách lọc theo loại */
    public function list(): void
    {
        $type = $_GET['type'] ?? 'all';
        $list = $this->qualityModel->getListByType($type);
        $titleMap = [
            'passed'   => 'Danh sách lô đạt yêu cầu',
            'failed'   => 'Danh sách lô không đạt',
            'unchecked'=> 'Danh sách lô chưa kiểm tra',
            'all'      => 'Danh sách toàn bộ lô'
        ];
        $this->render('quality/list', [
            'title' => $titleMap[$type] ?? $titleMap['all'],
            'list'  => $list,
        ]);
    }

    /** Xem chi tiết biên bản */
    public function read(): void
{
    $id = $_GET['id'] ?? null;

    if (!$id) {
        $this->setFlash('danger', 'Thiếu mã lô hoặc mã biên bản.');
        $this->redirect('?controller=quality&action=index');
    }

    // Kiểm tra xem lô này có biên bản hay chưa
    $db = $this->qualityModel->getConnection();
    $stmt = $db->prepare("
        SELECT bb.*
        FROM bien_ban_danh_gia_thanh_pham bb
        WHERE bb.IdLo = :id
        ORDER BY bb.ThoiGian DESC
        LIMIT 1
    ");
    $stmt->execute([':id' => $id]);
    $report = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($report) {
        // Có biên bản -> hiển thị trang chi tiết biên bản
        $this->render('quality/read', [
            'title'  => 'Chi tiết biên bản đánh giá',
            'report' => $report,
            'isReport' => true
        ]);
    } else {
        // Chưa có biên bản -> chỉ hiển thị thông tin lô
        $loInfo = $this->qualityModel->getLoInfo($id);
        $this->render('quality/read', [
            'title'  => 'Thông tin lô sản phẩm',
            'loInfo' => $loInfo,
            'isReport' => false
        ]);
    }
}


    /** Form tạo mới */
    public function create(): void
    {
        $idLo = $_GET['IdLo'] ?? null;
        $loInfo = null;
        $criteria = [];

        if ($idLo) {
            $loInfo = $this->qualityModel->getLoInfo($idLo);
            $criteriaList = require __DIR__ . '/../core/QualityCriteria.php';
            $xuong = $loInfo['TenXuong'] ?? null;
            if ($xuong && isset($criteriaList[$xuong])) {
                $criteria = $criteriaList[$xuong];
            }
        }

        $this->render('quality/create', [
            'title'    => 'Lập biên bản đánh giá thành phẩm',
            'loInfo'   => $loInfo,
            'criteria' => $criteria
        ]);
    }

    /** Lưu biên bản */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=quality&action=index');
        }

        $idBienBan = trim($_POST['IdBienBanDanhGiaSP'] ?? '');
        if ($idBienBan === '') {
            $idBienBan = $this->qualityModel->generateBienBanId();
        }

        $idLo = $_POST['IdLo'] ?? null;
        $thoiGian = $_POST['ThoiGian'] ?? date('Y-m-d H:i:s');
        $arrTieuChi = $_POST['TieuChi'] ?? [];
        $arrDiemDat = $_POST['DiemDat'] ?? [];
        $arrGhiChu  = $_POST['GhiChuTC'] ?? [];
        $files      = $_FILES['FileMinhChung'] ?? null;

        if (empty($arrTieuChi)) {
            $this->setFlash('danger', 'Không có tiêu chí nào được nhập.');
            $this->redirect('?controller=quality&action=create');
        }

        $db = $this->qualityModel->getConnection();
        $db->beginTransaction();

        try {
            $this->qualityModel->create([
                'IdBienBanDanhGiaSP' => $idBienBan,
                'ThoiGian'           => $thoiGian,
                'TongTCD'            => 0,
                'TongTCKD'           => 0,
                'KetQua'             => 'Không đạt',
                'IdLo'               => $idLo,
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

                $this->qualityModel->insertChiTietTieuChi($idBienBan, $tieuChi, (int)$diem, $ghiChu, $fileName);

                if ($diem >= 8) $tongTCD++;
                else $tongTCKD++;
            }

            $ketQuaTong = ($tongTCKD > 0) ? 'Không đạt' : 'Đạt';
            $this->qualityModel->updateTong($idBienBan, $tongTCD, $tongTCKD, $ketQuaTong);
            $db->commit();

            $this->setFlash('success', "Đã tạo biên bản $idBienBan thành công.");
        } catch (Throwable $e) {
            $db->rollBack();
            $this->setFlash('danger', 'Không thể tạo biên bản: ' . $e->getMessage());
        }

        $this->redirect('?controller=quality&action=index');
    }

    /** Xóa biên bản */
    public function delete(): void
{
    $id = $_GET['id'] ?? null;

    if ($id) {
        if ($this->qualityModel->deleteBienBanCascade($id)) {
            $this->setFlash('success', "Đã xóa biên bản $id và các chi tiết liên quan.");
        } else {
            $this->setFlash('danger', "Không thể xóa biên bản $id. Kiểm tra lại ràng buộc dữ liệu.");
        }
    } else {
        $this->setFlash('warning', 'Thiếu mã biên bản để xóa.');
    }

    $this->redirect('?controller=quality&action=index');
}


}
