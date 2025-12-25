
<?php

class SuddenlyController extends Controller
{
    private SuddenlyReport $SuddenlyModel;

    public function __construct()
    {
        $this->authorize(['VT_KIEM_SOAT_CL', 'VT_QUANLY_XUONG', 'VT_BAN_GIAM_DOC', 'VT_ADMIN']);
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

        $flash = null;
        if (!empty($_GET['msg'])) {
            $flash = [
                'type'    => $_GET['type'] ?? 'success',
                'message' => $_GET['msg'],
            ];
        }

        $this->render('suddenly/index', [
            'title'       => 'Kiểm tra đột xuất',
            'reports'     => $reports,
            'summary'     => $summary,
            'dashboard'   => $dashboard,
            'listBienBan' => $listBienBan,
            'filter'      => $filter,
            'flash'       => $flash,
        ]);
    }

    public function read(): void
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('?controller=suddenly&action=index&msg=' . urlencode('Thiếu mã biên bản.') . '&type=danger');
            return;
        }

        $model = new SuddenlyReport();

        $report = $model->getBienBanById($id);
        if (!$report) {
            $this->redirect('?controller=suddenly&action=index&msg=' . urlencode('Không tìm thấy biên bản.') . '&type=warning');
            return;
        }

        $details = $model->getChiTietByBienBan($id);
        $images  = $model->getImagesByReportId($id);

        $this->render('suddenly/read', [
            'title'   => 'Chi tiết biên bản đột xuất',
            'report'  => $report,
            'details' => $details,
            'images'  => $images,
        ]);
    }

    public function create(): void
    {
        $sessionUser = $_SESSION['user'] ?? null;

        if (!$sessionUser) {
            $this->redirect('?controller=auth&action=login');
            return;
        }

        $db = $this->SuddenlyModel->getConnection();
        $stmtHoTen = $db->prepare("
        SELECT HoTen 
        FROM nhan_vien 
        WHERE IdNhanVien = ?
    ");
        $stmtHoTen->execute([$sessionUser['IdNhanVien']]);
        $nv = $stmtHoTen->fetch(PDO::FETCH_ASSOC);

        $sessionUser['HoTen'] = $nv['HoTen'] ?? '';

        $stmt = $db->query("SELECT IdXuong, TenXuong FROM xuong ORDER BY TenXuong");
        $xuongList = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $model = new SuddenlyReport();
        $maBienBan = $model->generateBienBanId($db);

        $type = $_GET['type'] ?? 'production';
        $criteriaData = require __DIR__ . '/../core/QualityCriteria.php';

        if (!isset($criteriaData[$type])) {
            $this->redirect('?controller=suddenly&action=index&msg='
                . urlencode('Loại biên bản không hợp lệ.') . '&type=danger');
            return;
        }

        $criteriaList   = $criteriaData[$type];
        $criteriaGroups = array_keys($criteriaList);

        $this->render('suddenly/create', [
            'title'          => 'Tạo biên bản đột xuất',
            'xuongList'      => $xuongList,
            'criteriaList'   => $criteriaList,
            'criteriaGroups' => $criteriaGroups,
            'maBienBan'      => $maBienBan,
            'type'           => $type,
            'sessionUser'    => $sessionUser,
        ]);
    }


    /** Lưu biên bản đột xuất */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=suddenly&action=index');
            return;
        }
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        $fail = function (string $msg, string $type = 'warning') use ($isAjax) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'type'    => $type,
                    'message' => $msg,
                ]);
                exit;
            }

            $this->redirect(
                '?controller=suddenly&action=create&msg='
                    . urlencode($msg) . '&type=' . $type,
            );
            exit;
        };
        $sessionUser = $_SESSION['user'] ?? null;

        if (!$sessionUser || empty($sessionUser['IdNhanVien'])) {
            $fail('Phiên đăng nhập không hợp lệ hoặc đã hết hạn.', 'danger');
        }
        $idNhanVien = $sessionUser['IdNhanVien'];
        $idBienBan = $_POST['IdBienBanDanhGiaDX'] ?? null;
        if (!$idBienBan) {
            $fail('Thiếu mã biên bản.', 'danger');
        }

        $idXuong     = $_POST['IdXuong'] ?? null;
        $thoiGian    = $_POST['ThoiGian'] ?? date('Y-m-d H:i:s');
        $loaiTieuChi = $_POST['LoaiTieuChi'] ?? '';
        $arrTieuChi  = $_POST['TieuChi'] ?? [];
        $arrDiemDat  = $_POST['DiemDat'] ?? [];
        $arrGhiChu   = $_POST['GhiChuTC'] ?? [];
        $files       = $_FILES['FileMinhChung'] ?? null;

        if (empty($idXuong)) {
            $fail('Yêu cầu chọn Xưởng kiểm tra.');
        }
        if (empty($loaiTieuChi)) {
            $fail('Chưa chọn loại tiêu chí.', 'danger');
        }

        if (empty($arrTieuChi)) {
            $fail('Không có tiêu chí nào được nhập.', 'danger');
        }

        foreach ($arrDiemDat as $diem) {
            if ($diem === '' || !is_numeric($diem) || $diem < 0 || $diem > 10) {
                $fail('Yêu cầu nhập điểm cho tiêu chí.', 'warning');
            }
        }

        foreach ($arrGhiChu as $note) {
            if (preg_match('/[#@\$%<>\{\}\[\]\;]/', $note)) {
                $fail('Ghi chú chứa kí tự không hợp lệ.', 'danger');
            }
        }

        if (!$files || empty($files['name'][0])) {
            $fail('Yêu cầu tải ít nhất một ảnh minh chứng.', 'danger');
        }

        foreach ($files['name'] as $name) {
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                $fail('Định dạng ảnh không hợp lệ. Chỉ chấp nhận JPG hoặc PNG.', 'danger');
            }
        }
        $db = $this->SuddenlyModel->getConnection();

        try {
            $db->beginTransaction();
            $this->SuddenlyModel->create([
                'IdBienBanDanhGiaDX' => $idBienBan,
                'IdXuong'            => $idXuong,
                'IdNhanVien'         => $idNhanVien,
                'ThoiGian'           => $thoiGian,
                'TongTCD'            => 0,
                'TongTCKD'           => 0,
                'KetQua'             => 'Không đạt',
            ]);
            $tongTCD  = 0;
            $tongTCKD = 0;

            $uploadDir = __DIR__ . '/../storage/img/bbdgdx/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            foreach ($arrTieuChi as $i => $tieuChi) {
                if (trim($tieuChi) === '') {
                    continue;
                }

                $diem   = (int) $arrDiemDat[$i];
                $ghiChu = trim($arrGhiChu[$i] ?? '');
                $fileName = null;

                if (!empty($files['name'][$i])) {
                    $safeName = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $files['name'][$i]);
                    $fileName = uniqid('mc_') . '_' . $safeName;
                    move_uploaded_file($files['tmp_name'][$i], $uploadDir . $fileName);
                }

                $this->SuddenlyModel->insertChiTietTieuChi(
                    $idBienBan,
                    $loaiTieuChi,
                    $tieuChi,
                    $diem,
                    $ghiChu,
                    $fileName,
                );

                if ($diem >= 9) {
                    $tongTCD++;
                } else {
                    $tongTCKD++;
                }
            }
            $ketQuaTong = ($tongTCKD > 0) ? 'Không đạt' : 'Đạt';
            $this->SuddenlyModel->updateTong($idBienBan, $tongTCD, $tongTCKD, $ketQuaTong);
            $db->commit();
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => "Biên bản {$idBienBan} lưu thành công.",
                ]);
                exit;
            }

            $this->redirect(
                '?controller=suddenly&action=index&msg='
                    . urlencode("Biên bản {$idBienBan} lưu thành công.")
                    . '&type=success',
            );
        } catch (Throwable $e) {

            if ($db->inTransaction()) {
                $db->rollBack();
            }

            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage(),
                ]);
                exit;
            }

            $this->redirect(
                '?controller=suddenly&action=create&msg='
                    . urlencode('Không thể lưu biên bản: ' . $e->getMessage())
                    . '&type=danger',
            );
        }
    }


    /** Xóa biên bản đột xuất */
    public function delete(): void
    {
        $this->redirect('?controller=suddenly&action=index&msg=' . urlencode('Chức năng xóa đã bị vô hiệu. Vui lòng cập nhật trạng thái nếu cần.') . '&type=warning');
    }
}
