<?php

class QualityController extends Controller
{
    private QualityReport $qualityModel;
    private Workshop $workshopModel;

    public function __construct()
    {
        $this->authorize(['VT_KIEM_SOAT_CL', 'VT_QUANLY_XUONG', 'VT_BAN_GIAM_DOC']);
        $this->qualityModel = new QualityReport();
        $this->workshopModel = new Workshop();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    /** Trang chính */
    public function index(): void
    {
        $reports   = $this->qualityModel->getLatestReports(50);
        $summary   = $this->qualityModel->getQualitySummary();
        $dashboard = $this->qualityModel->getDashboardSummary();
        $listLo    = $this->qualityModel->getDanhSachLo();

        // ✅ Lấy flash qua query string
        $flash = null;
        if (!empty($_GET['msg'])) {
            $flash = [
                'type' => $_GET['type'] ?? 'success',
                'message' => $_GET['msg'],
            ];
        }

        $this->render('quality/index', [
            'title'     => 'Kiểm soát chất lượng',
            'reports'   => $reports,
            'summary'   => $summary,
            'dashboard' => $dashboard,
            'listLo'    => $listLo,
            'flash'     => $flash,
        ]);
    }

    /** Xem chi tiết biên bản hoặc lô */
    public function read(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('?controller=quality&action=index&msg='
                . urlencode('Thiếu mã lô hoặc mã biên bản.')
                . '&type=danger');
        }

        $db = $this->qualityModel->getConnection();

        // ===== LẤY BIÊN BẢN =====
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

            // ===== LẤY ẢNH MINH CHỨNG =====
            $stmtImg = $db->prepare("
            SELECT HinhAnh
            FROM ttct_bien_ban_danh_gia_thanh_pham
            WHERE IdBienBanDanhGiaSP = :id
              AND HinhAnh IS NOT NULL
              AND HinhAnh <> ''
        ");
            $stmtImg->execute([
                ':id' => $report['IdBienBanDanhGiaSP'],
            ]);
            $images = $stmtImg->fetchAll(PDO::FETCH_COLUMN);

            // ===== LẤY NGƯỜI LẬP (HỌ TÊN) =====
            $nguoiLap = $_SESSION['user']['TenDangNhap'] ?? 'Không xác định';

            $idNV = $_SESSION['user']['IdNhanVien'] ?? null;
            if ($idNV) {
                $stmtNV = $db->prepare("
                SELECT HoTen
                FROM nhan_vien
                WHERE IdNhanVien = :id
            ");
                $stmtNV->execute([':id' => $idNV]);
                $hoTen = $stmtNV->fetchColumn();

                if ($hoTen) {
                    $nguoiLap = $hoTen;
                }
            }

            // ===== RENDER VIEW =====
            $this->render('quality/read', [
                'title'     => 'Chi tiết biên bản đánh giá',
                'report'    => $report,
                'images'    => $images,
                'isReport'  => true,
                'nguoiLap'  => $nguoiLap,   // 👈 TRUYỀN SANG VIEW
            ]);
        }
    }

    /** Form tạo mới biên bản */
    public function create(): void
    {
        $idLo = $_GET['IdLo'] ?? null;
        $loInfo = null;
        $criteria = [];

        if ($idLo) {
            $db = $this->qualityModel->getConnection();
            $stmt = $db->prepare("SELECT COUNT(*) FROM bien_ban_danh_gia_thanh_pham WHERE IdLo = :idLo");
            $stmt->execute([':idLo' => $idLo]);
            $exists = (int) $stmt->fetchColumn() > 0;

            if ($exists) {
                $this->redirect('?controller=quality&action=index&msg=' . urlencode("Lô $idLo đã có biên bản, không thể tạo mới.") . '&type=warning');
            }

            $loInfo = $this->qualityModel->getLoInfo($idLo);
            $criteriaDir = __DIR__ . '/../storage/quality_criteria.json';
            if (file_exists($criteriaDir)) {
                $jsonContent = file_get_contents($criteriaDir);
                $allCriteria = json_decode($jsonContent, true) ?? [];
                $idXuong = $loInfo['idXuong'] ?? null;
                if ($idXuong && isset($allCriteria[$idXuong])) {
                    $criteria = $allCriteria[$idXuong];
                }
            }
        }


        $this->render('quality/create', [
            'title'    => 'Lập biên bản đánh giá thành phẩm',
            'loInfo'   => $loInfo,
            'criteria' => $criteria,
        ]);
    }

    /** Lưu biên bản */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=quality&action=index');
        }

        $idLo = $_POST['IdLo'] ?? null;

        if ($idLo) {
            $db = $this->qualityModel->getConnection();
            $stmt = $db->prepare("SELECT COUNT(*) FROM bien_ban_danh_gia_thanh_pham WHERE IdLo = :idLo");
            $stmt->execute([':idLo' => $idLo]);
            if ((int) $stmt->fetchColumn() > 0) {
                $this->redirect('?controller=quality&action=index&msg=' . urlencode("Lô $idLo đã có biên bản, không thể tạo mới.") . '&type=warning');
            }
        }

        $idBienBan = trim($_POST['IdBienBanDanhGiaSP'] ?? '') ?: $this->qualityModel->generateBienBanId();
        $thoiGian = $_POST['ThoiGian'] ?? date('Y-m-d H:i:s');
        $arrTieuChi = $_POST['TieuChi'] ?? [];
        $arrDiemDat = $_POST['DiemDat'] ?? [];
        $arrGhiChu  = $_POST['GhiChuTC'] ?? [];
        $files      = $_FILES['FileMinhChung'] ?? null;

        if (empty($arrTieuChi)) {
            $this->redirect('?controller=quality&action=create&msg=' . urlencode('Không có tiêu chí nào được nhập.') . '&type=danger');
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
                if (trim($tieuChi) === '') {
                    continue;
                }

                $diem = max(0, min(10, (float) ($arrDiemDat[$i] ?? 0)));
                $ghiChu = trim($arrGhiChu[$i] ?? '');
                $fileName = null;

                if ($files && !empty($files['name'][$i])) {

                    $uploadDir = realpath(__DIR__ . '/../storage/img/bbdgtp');
                    if ($uploadDir === false) {
                        if (!mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
                            throw new Exception('Không thể tạo thư mục lưu trữ file upload');
                        }
                    }
                    $uploadDir .= DIRECTORY_SEPARATOR;

                    if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                        continue;
                    }

                    $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                    if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                        continue;
                    }

                    $fileName = uniqid('tp_', true) . '.' . $ext;

                    if (!move_uploaded_file($files['tmp_name'][$i], $uploadDir . $fileName)) {
                        throw new Exception('Không thể lưu file upload');
                    }
                }


                $this->qualityModel->insertChiTietTieuChi($idBienBan, $tieuChi, (int) $diem, $ghiChu, $fileName);

                if ($diem >= 9) {
                    $tongTCD++;
                } else {
                    $tongTCKD++;
                }
            }

            $ketQuaTong = ($tongTCKD > 0) ? 'Không đạt' : 'Đạt';
            $this->qualityModel->updateTong($idBienBan, $tongTCD, $tongTCKD, $ketQuaTong);

            $db->commit();

            $this->redirect('?controller=quality&action=index&msg=' . urlencode('Lưu biên bản thành công.') . '&type=success');
        } catch (Throwable $e) {
            $db->rollBack();
            $this->redirect('?controller=quality&action=index&msg=' . urlencode('Không thể tạo biên bản: ' . $e->getMessage()) . '&type=danger');
        }
    }

    /** Xóa biên bản */
    public function delete(): void
    {
        $idBienBan = $_GET['id'] ?? null;
        $idLo = $_GET['IdLo'] ?? null; // nếu bạn cần IdLo cho mục đích khác vẫn giữ

        if (!$idBienBan) {
            $this->redirect('?controller=quality&action=index&msg=' . urlencode('Thiếu mã biên bản để xóa.') . '&type=warning');
        }

        // Gọi model đúng cú pháp
        $deleted = $this->qualityModel->deleteBienBanCascade($idBienBan);

        if ($deleted) {
            $this->redirect('?controller=quality&action=index&msg=' . urlencode('Xóa biên bản thành công.') . '&type=success');
        } else {
            $this->redirect('?controller=quality&action=index&msg=' . urlencode('Không thể xóa biên bản. Vui lòng kiểm tra lại dữ liệu.') . '&type=danger');
        }
    }

    /** Quan ly tieu chi danh gia */
    public function criterias(): void
    {
        if (!$_GET['id']) {
            $this->render('quality/criterias', [
                'title'    => 'Quản lý tiêu chí đánh giá',
                'workshops' => $this->workshopModel->all(),
            ]);
        } else {
            $criteriaPath = __DIR__ . '/../storage/quality_criteria.json';
            $idXuong = $_GET['id'];
            $criteriaData = [];
            if (file_exists($criteriaPath)) {
                $jsonContent = file_get_contents($criteriaPath);
                $criteriaData = json_decode($jsonContent, true) ?? [];
            }

            $criteriaList = [];
            if (isset($criteriaData[$idXuong])) {
                $criteriaList = $criteriaData[$idXuong];
            }

            $this->render('quality/criterias', [
                'title'    => 'Quản lý tiêu chí đánh giá',
                'criterias' => $criteriaList,
            ]);
        }
    }

    public function createCriteria(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $idXuong = $_GET['id'] ?? null;

            if (!$idXuong) {
                $this->setFlash('danger', 'Thiếu mã xưởng để thêm tiêu chí.');
                $this->redirect('?controller=quality&action=criterias');
            }

            $this->render('quality/create_criteria', [
                'title'   => 'Thêm tiêu chí đánh giá',
                'idXuong' => $idXuong,
            ]);
        }

        error_log(print_r($_POST, true));
        $criteriaPath = __DIR__ . '/../storage/quality_criteria.json';
        $idXuong = $_POST['idXuong'] ?? null;
        $criterion = trim($_POST['criterion'] ?? '');
        $description = trim($_POST['description'] ?? '');
        if (!$idXuong || !$criterion) {
            $this->setFlash('danger', 'Thiếu thông tin tiêu chí.');
            $this->redirect('?controller=quality&action=createCriteria&id=' . urlencode($idXuong));
        }
        $criteriaData = [];
        if (file_exists($criteriaPath)) {
            $jsonContent = file_get_contents($criteriaPath);
            $criteriaData = json_decode($jsonContent, true) ?? [];
        }
        if (!isset($criteriaData[$idXuong])) {
            $criteriaData[$idXuong] = [];
        }
        $newCriteria = [
            'id'          => uniqid('TC_'),
            'criterion'   => $criterion,
            'description' => $description,
        ];
        $criteriaData[$idXuong][] = $newCriteria;
        file_put_contents($criteriaPath, json_encode($criteriaData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $this->setFlash('success', 'Thêm tiêu chí thành công.');
        $this->redirect('?controller=quality&action=criterias&id=' . urlencode($idXuong));
    }

    public function deleteCriteria(): void
    {
        $criteriaPath = __DIR__ . '/../storage/quality_criteria.json';
        $idXuong = $_GET['idXuong'] ?? null;
        $criteriaId = $_GET['criteriaId'] ?? null;

        if (!$idXuong || !$criteriaId) {
            $this->setFlash('danger', 'Thiếu thông tin để xóa tiêu chí.');
            $this->redirect('?controller=quality&action=criterias');
        }

        $criteriaData = [];
        if (file_exists($criteriaPath)) {
            $jsonContent = file_get_contents($criteriaPath);
            $criteriaData = json_decode($jsonContent, true) ?? [];
        }

        if (isset($criteriaData[$idXuong])) {
            $criteriaList = &$criteriaData[$idXuong];
            foreach ($criteriaList as $index => $criteria) {
                if (($criteria['id'] ?? '') === $criteriaId) {
                    array_splice($criteriaList, $index, 1);
                    break;
                }
            }
            file_put_contents($criteriaPath, json_encode($criteriaData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            $this->setFlash('success', 'Xóa tiêu chí thành công.');
        } else {
            $this->setFlash('warning', 'Không tìm thấy tiêu chí để xóa.');
        }

        $this->redirect('?controller=quality&action=criterias&id=' . urlencode($idXuong));
    }
}
