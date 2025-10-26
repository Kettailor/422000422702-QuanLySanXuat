<?php

class QualityController extends Controller
{
    private QualityReport $qualityModel;

    public function __construct()
    {
        $this->authorize(['VT_KIEM_SOAT_CL', 'VT_QUANLY_XUONG', 'VT_BAN_GIAM_DOC']);
        $this->qualityModel = new QualityReport();
    }

    /** Trang danh sách biên bản */
    public function index(): void
    {
        $reports = $this->qualityModel->getLatestReports(50);
        $summary = $this->qualityModel->getQualitySummary();

        $this->render('quality/index', [
            'title' => 'Kiểm soát chất lượng',
            'reports' => $reports,
            'summary' => $summary,
        ]);
    }

    /** Xem chi tiết biên bản */
    public function read(): void
    {
        $id = $_GET['id'] ?? null;
        $report = $id ? $this->qualityModel->find($id) : null;

        $this->render('quality/read', [
            'title' => 'Chi tiết biên bản',
            'report' => $report,
        ]);
    }

    /** Form tạo biên bản mới */
    public function create(): void
    {
        $this->render('quality/create', [
            'title' => 'Lập biên bản đánh giá thành phẩm',
        ]);
    }

    /** Xử lý lưu biên bản */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=quality&action=index');
        }

        // Sinh ID biên bản và lấy ID lô
        $idBienBan = $_POST['IdBienBanDanhGiaSP'] ?: uniqid('BBTP');
        $idLo = $_POST['IdLo'] ?? null;

        // Dữ liệu lưu vào bảng chính
        $data = [
    'IdBienBanDanhGiaSP' => $idBienBan,
    'ThoiGian' => $_POST['ThoiGian'] ?? date('Y-m-d H:i:s'),
    'TongTCD' => $_POST['TongTCD'] ?? 0,
    'TongTCKD' => $_POST['TongTCKD'] ?? 0,
    'KetQua' => $_POST['KetQua'] ?? 'Không đạt',
    'IdLo' => $idLo,
];


        try {
            // Lưu biên bản chính
            $this->qualityModel->create($data);

            // Nếu có tiêu chí, lưu từng dòng vào bảng chi tiết
            if (!empty($_POST['TenTieuChi'])) {
                $count = count($_POST['TenTieuChi']);

                for ($i = 0; $i < $count; $i++) {
                    $tieuChi = $_POST['TenTieuChi'][$i] ?? '';
                    $diemD = $_POST['DiemDat'][$i] ?? 0;
                    $ghiChu = $_POST['GhiChuTC'][$i] ?? '';
                    $fileName = null;

                    // Upload file minh chứng
                    if (!empty($_FILES['FileMinhChung']['name'][$i])) {
                        $uploadDir = __DIR__ . '/../uploads/';
                        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                        $fileName = uniqid() . '_' . basename($_FILES['FileMinhChung']['name'][$i]);
                        move_uploaded_file($_FILES['FileMinhChung']['tmp_name'][$i], $uploadDir . $fileName);
                    }

                    // Ghi vào bảng chi tiết
                    $this->qualityModel->insertChiTietTieuChi($idBienBan, $tieuChi, $diemD, $ghiChu, $fileName);
                }
            }

            $this->setFlash('success', 'Đã tạo biên bản và lưu chi tiết tiêu chí thành công.');
        } catch (Throwable $e) {
            $this->setFlash('danger', 'Không thể tạo biên bản: ' . $e->getMessage());
        }

        $this->redirect('?controller=quality&action=index');
    }

    /** Form chỉnh sửa biên bản */
    public function edit(): void
    {
        $id = $_GET['id'] ?? null;
        $report = $id ? $this->qualityModel->find($id) : null;

        $this->render('quality/edit', [
            'title' => 'Cập nhật biên bản',
            'report' => $report,
        ]);
    }

    /** Cập nhật biên bản */
    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=quality&action=index');
        }

        $id = $_POST['IdBienBanDanhGiaSP'];
        $data = [
            'ThoiGian' => $_POST['ThoiGian'] ?? date('Y-m-d H:i:s'),
            'TongTCD' => $_POST['TongTCD'] ?? 0,
            'TongTCKD' => $_POST['TongTCKD'] ?? 0,
            'KetQua' => $_POST['KetQua'] ?? 'Đạt',
            'IdLo' => $_POST['IdLo'] ?? null,
        ];

        try {
            $this->qualityModel->update($id, $data);
            $this->setFlash('success', 'Cập nhật biên bản thành công.');
        } catch (Throwable $e) {
            $this->setFlash('danger', 'Không thể cập nhật biên bản: ' . $e->getMessage());
        }

        $this->redirect('?controller=quality&action=index');
    }

    public function delete(): void
{
    $id = $_GET['id'] ?? null;

    if ($id) {
        try {
            // Xóa chi tiết trước
            $this->qualityModel->deleteChiTietByBienBan($id);

            // Rồi xóa biên bản chính
            $this->qualityModel->deleteBienBan($id);

            $this->setFlash('success', 'Đã xóa biên bản đánh giá và toàn bộ chi tiết liên quan.');
        } catch (Throwable $e) {
            $this->setFlash('danger', 'Không thể xóa biên bản: ' . $e->getMessage());
        }
    }

    $this->redirect('?controller=quality&action=index');
}



}
