<?php

class DotXuatController extends Controller
{
    private DotXuatReport $dotXuatModel;

    public function __construct()
    {
        $this->authorize(['VT_KIEM_SOAT_CL', 'VT_QUANLY_XUONG', 'VT_BAN_GIAM_DOC']);
        $this->dotXuatModel = new DotXuatReport();
    }

    public function index(): void
{
    $reports = $this->dotXuatModel->getLatestReports(50);

    // Thống kê tổng số, đạt, không đạt, thời gian gần nhất
    $summary = [
    'total' => count($reports),
    'dat' => 0,
    'khongdat' => 0,
    'ganNhat' => null
];

foreach ($reports as $r) {
    $ketqua = mb_strtolower(trim($r['KetQua']), 'UTF-8');

    // kiểm tra có chứa chữ "đạt" nhưng KHÔNG chứa "không"
    if (mb_strpos($ketqua, 'đạt') !== false && mb_strpos($ketqua, 'không') === false) {
        $summary['dat']++;
    } else {
        $summary['khongdat']++;
    }

    if (!$summary['ganNhat'] || $r['ThoiGian'] > $summary['ganNhat']) {
        $summary['ganNhat'] = $r['ThoiGian'];
    }
}


    $this->render('dotxuat/index', [
        'title' => 'Kiểm tra đột xuất',
        'reports' => $reports,
        'summary' => $summary
    ]);
}


    public function create(): void
{
    $xuongs = $this->dotXuatModel->getAllXuong();
    $nhanviens = $this->dotXuatModel->getAllNhanVien(); 

    $this->render('dotxuat/create', [
        'title' => 'Tạo biên bản kiểm tra đột xuất',
        'xuongs' => $xuongs,
        'nhanviens' => $nhanviens
    ]);
}


    public function store(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->redirect('?controller=dotxuat&action=index');
    }

    $idBienBan = $_POST['IdBienBanDanhGiaDX'] ?? uniqid('BBDX');
    $idXuong = $_POST['IdXuong'] ?? null;
$idNhanVien = $_POST['IdNhanVien'] ?? null;

if (empty($idXuong) || empty($idNhanVien)) {
    $this->setFlash('danger', 'Thiếu mã xưởng hoặc người kiểm tra.');
    $this->redirect('?controller=dotxuat&action=create');
    return;
}
    $ketQua = $_POST['KetQua'] ?? 'Không đạt';
    $thoiGian = $_POST['ThoiGian'] ?? date('Y-m-d H:i:s');

    if (empty($idXuong) || empty($idNhanVien)) {
        $this->setFlash('danger', 'Thiếu mã xưởng hoặc nhân viên kiểm tra.');
        $this->redirect('?controller=dotxuat&action=create');
        return;
    }

    $data = [
        'IdBienBanDanhGiaDX' => $idBienBan,
        'ThoiGian' => $thoiGian,
        'KetQua' => $ketQua,
        'IdXuong' => $idXuong,
        'IdNhanVien' => $idNhanVien
    ];

    try {
        $this->dotXuatModel->create($data);

        if (!empty($_POST['TieuChi'])) {
            $count = count($_POST['TieuChi']);
            for ($i = 0; $i < $count; $i++) {
                $tieuChi = $_POST['TieuChi'][$i] ?? '';
                $ghiChu = $_POST['GhiChu'][$i] ?? '';
                $fileName = null;

                if (!empty($_FILES['HinhAnh']['name'][$i])) {
                    $uploadDir = __DIR__ . '/../uploads/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                    $fileName = uniqid('IMG_') . '_' . basename($_FILES['HinhAnh']['name'][$i]);
                    move_uploaded_file($_FILES['HinhAnh']['tmp_name'][$i], $uploadDir . $fileName);
                }

                $this->dotXuatModel->insertChiTiet($idBienBan, $tieuChi, $ghiChu, $fileName);
            }
        }

        $this->setFlash('success', 'Đã tạo biên bản kiểm tra đột xuất thành công.');
    } catch (Throwable $e) {
        $this->setFlash('danger', 'Không thể tạo biên bản: ' . $e->getMessage());
    }

    $this->redirect('?controller=dotxuat&action=index');
}


    public function read(): void
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('?controller=dotxuat&action=index');
            return;
        }

        $report = $this->dotXuatModel->find($id);
        $details = $this->dotXuatModel->getChiTietByBienBan($id);

        $this->render('dotxuat/read', [
            'title' => 'Chi tiết biên bản kiểm tra đột xuất',
            'report' => $report,
            'details' => $details
        ]);
    }

    public function delete(): void
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                // Xóa chi tiết trước để tránh vi phạm FK
                $this->dotXuatModel->deleteChiTietByBienBan($id);
                $this->dotXuatModel->delete($id);
                $this->setFlash('success', 'Đã xóa biên bản kiểm tra đột xuất.');
            } catch (Throwable $e) {
                $this->setFlash('danger', 'Không thể xóa: ' . $e->getMessage());
            }
        }
        $this->redirect('?controller=dotxuat&action=index');
    }
}
