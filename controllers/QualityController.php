<?php

class QualityController extends Controller
{
    private QualityReport $qualityModel;

    public function __construct()
    {
        $this->authorize(['VT_KIEM_SOAT_CL', 'VT_QUANLY_XUONG', 'VT_BAN_GIAM_DOC']);
        $this->qualityModel = new QualityReport();
    }

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

    public function read(): void
    {
        $id = $_GET['id'] ?? null;
        $report = $id ? $this->qualityModel->find($id) : null;
        $this->render('quality/read', [
            'title' => 'Chi tiết biên bản',
            'report' => $report,
        ]);
    }

    public function create(): void
    {
        $this->render('quality/create', [
            'title' => 'Lập biên bản đánh giá',
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=quality&action=index');
        }

        $data = [
            'IdBienBanDanhGiaSP' => $_POST['IdBienBanDanhGiaSP'] ?: uniqid('BBTP'),
            'ThoiGian' => $_POST['ThoiGian'] ?? date('Y-m-d H:i:s'),
            'TongTCD' => $_POST['TongTCD'] ?? 0,
            'TongTCKD' => $_POST['TongTCKD'] ?? 0,
            'KetQua' => $_POST['KetQua'] ?? 'Đạt',
            'IdLo' => $_POST['IdLo'] ?? null,
        ];

        try {
            $this->qualityModel->create($data);
            $this->setFlash('success', 'Đã tạo biên bản đánh giá.');
        } catch (Throwable $e) {
            Logger::error('Lỗi khi tạo biên bản đánh giá: ' . $e->getMessage());
            /* $this->setFlash('danger', 'Không thể tạo biên bản: ' . $e->getMessage()); */
            $this->setFlash('danger', 'Không thể tạo biên bản, vui lòng kiểm tra log để biết thêm chi tiết.');
        }

        $this->redirect('?controller=quality&action=index');
    }

    public function edit(): void
    {
        $id = $_GET['id'] ?? null;
        $report = $id ? $this->qualityModel->find($id) : null;
        $this->render('quality/edit', [
            'title' => 'Cập nhật biên bản',
            'report' => $report,
        ]);
    }

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
            Logger::error('Lỗi khi cập nhật biên bản ' . $id . ': ' . $e->getMessage());
            /* $this->setFlash('danger', 'Không thể cập nhật biên bản: ' . $e->getMessage()); */
            $this->setFlash('danger', 'Không thể cập nhật biên bản, vui lòng kiểm tra log để biết thêm chi tiết.');
        }

        $this->redirect('?controller=quality&action=index');
    }

    public function delete(): void
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                $this->qualityModel->delete($id);
                $this->setFlash('success', 'Đã xóa biên bản.');
            } catch (Throwable $e) {
                Logger::error('Lỗi khi xóa biên bản ' . $id . ': ' . $e->getMessage());
                /* $this->setFlash('danger', 'Không thể xóa biên bản: ' . $e->getMessage()); */
                $this->setFlash('danger', 'Không thể xóa biên bản, vui lòng kiểm tra log để biết thêm chi tiết.');
            }
        }

        $this->redirect('?controller=quality&action=index');
    }
}
