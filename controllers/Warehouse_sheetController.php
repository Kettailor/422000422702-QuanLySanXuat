<?php

class Warehouse_sheetController extends Controller
{
    private InventorySheet $sheetModel;

    public function __construct()
    {
        $this->authorize(['VT_NHANVIEN_KHO']);
        $this->sheetModel = new InventorySheet();
    }

    public function index(): void
    {
        $filter = $_GET['type'] ?? 'all';
        $allowedFilters = ['all', 'inbound', 'outbound'];
        if (!in_array($filter, $allowedFilters, true)) {
            $filter = 'all';
        }

        $documents = $this->sheetModel->getDocuments($filter === 'all' ? null : $filter);
        $summary = $this->sheetModel->getDocumentSummary();

        $this->render('warehouse_sheet/index', [
            'title' => 'Phiếu kho',
            'documents' => $documents,
            'summary' => $summary,
            'activeFilter' => $filter,
            'filterLabel' => $this->resolveFilterLabel($filter),
        ]);
    }

    public function create(): void
    {
        $options = $this->sheetModel->getFormOptions();
        $defaultId = $this->sheetModel->generateDocumentId();

        $this->render('warehouse_sheet/create', [
            'title' => 'Tạo phiếu kho mới',
            'warehouses' => $options['warehouses'],
            'employees' => $options['employees'],
            'types' => $options['types'],
            'document' => [
                'IdPhieu' => $defaultId,
                'NgayLP' => date('Y-m-d'),
                'NgayXN' => date('Y-m-d'),
            ],
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=warehouse_sheet&action=index');
        }

        $inputId = trim((string) ($_POST['IdPhieu'] ?? ''));

        $data = [
            'IdPhieu' => $inputId !== '' ? $inputId : $this->sheetModel->generateDocumentId($_POST['LoaiPhieu'] ?? null),
            'NgayLP' => $_POST['NgayLP'] ?? null,
            'NgayXN' => $_POST['NgayXN'] ?? null,
            'TongTien' => $_POST['TongTien'] ?? 0,
            'LoaiPhieu' => $_POST['LoaiPhieu'] ?? null,
            'IdKho' => $_POST['IdKho'] ?? null,
            'NHAN_VIENIdNhanVien' => $_POST['NguoiLap'] ?? null,
            'NHAN_VIENIdNhanVien2' => $_POST['NguoiXacNhan'] ?? null,
        ];

        if (!$this->validateRequired($data)) {
            $this->setFlash('danger', 'Vui lòng điền đầy đủ thông tin bắt buộc của phiếu.');
            $this->redirect('?controller=warehouse_sheet&action=create');
        }

        try {
            $this->sheetModel->createDocument($data);
            $this->setFlash('success', 'Đã tạo phiếu kho mới.');
        } catch (Throwable $e) {
            $this->setFlash('danger', 'Không thể tạo phiếu: ' . $e->getMessage());
        }

        $this->redirect('?controller=warehouse_sheet&action=index');
    }

    public function edit(): void
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->setFlash('danger', 'Thiếu thông tin phiếu cần sửa.');
            $this->redirect('?controller=warehouse_sheet&action=index');
        }

        $document = $this->sheetModel->findDocument($id);
        if (!$document) {
            $this->setFlash('danger', 'Không tìm thấy phiếu kho.');
            $this->redirect('?controller=warehouse_sheet&action=index');
        }

        $options = $this->sheetModel->getFormOptions();
        $document = $this->hydrateDocumentEmployees($document, $options['employees']);

        $this->render('warehouse_sheet/edit', [
            'title' => 'Cập nhật phiếu kho',
            'document' => $document,
            'warehouses' => $options['warehouses'],
            'employees' => $options['employees'],
            'types' => $options['types'],
        ]);
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=warehouse_sheet&action=index');
        }

        $id = $_POST['IdPhieu'] ?? null;
        if (!$id) {
            $this->setFlash('danger', 'Không xác định được phiếu cần cập nhật.');
            $this->redirect('?controller=warehouse_sheet&action=index');
        }

        $data = [
            'NgayLP' => $_POST['NgayLP'] ?? null,
            'NgayXN' => $_POST['NgayXN'] ?? null,
            'TongTien' => $_POST['TongTien'] ?? 0,
            'LoaiPhieu' => $_POST['LoaiPhieu'] ?? null,
            'IdKho' => $_POST['IdKho'] ?? null,
            'NHAN_VIENIdNhanVien' => $_POST['NguoiLap'] ?? null,
            'NHAN_VIENIdNhanVien2' => $_POST['NguoiXacNhan'] ?? null,
        ];

        if (!$this->validateRequired(array_merge($data, ['IdPhieu' => $id]))) {
            $this->setFlash('danger', 'Vui lòng điền đầy đủ thông tin bắt buộc của phiếu.');
            $this->redirect('?controller=warehouse_sheet&action=edit&id=' . urlencode($id));
        }

        try {
            $this->sheetModel->updateDocument($id, $data);
            $this->setFlash('success', 'Đã cập nhật phiếu kho.');
        } catch (Throwable $e) {
            $this->setFlash('danger', 'Không thể cập nhật phiếu: ' . $e->getMessage());
        }

        $this->redirect('?controller=warehouse_sheet&action=index');
    }

    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=warehouse_sheet&action=index');
        }

        $id = $_POST['IdPhieu'] ?? null;
        if (!$id) {
            $this->setFlash('danger', 'Không xác định được phiếu cần xóa.');
            $this->redirect('?controller=warehouse_sheet&action=index');
        }

        try {
            if ($this->sheetModel->deleteDocument($id)) {
                $this->setFlash('success', 'Đã xóa phiếu kho.');
            } else {
                $this->setFlash('warning', 'Phiếu kho đã không còn tồn tại.');
            }
        } catch (Throwable $e) {
            $this->setFlash('danger', 'Không thể xóa phiếu: ' . $e->getMessage());
        }

        $this->redirect('?controller=warehouse_sheet&action=index');
    }

    private function validateRequired(array $data): bool
    {
        $required = ['IdPhieu', 'LoaiPhieu', 'IdKho', 'NHAN_VIENIdNhanVien', 'NHAN_VIENIdNhanVien2'];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                return false;
            }
        }

        return true;
    }

    private function resolveFilterLabel(string $filter): string
    {
        return match ($filter) {
            'inbound' => 'Danh sách phiếu nhập',
            'outbound' => 'Danh sách phiếu xuất',
            default => 'Tất cả phiếu kho',
        };
    }

    private function hydrateDocumentEmployees(array $document, array $employees): array
    {
        $document['NHAN_VIENIdNhanVien'] = $this->resolveEmployeeId(
            $document['NHAN_VIENIdNhanVien'] ?? null,
            $document['NguoiLapId'] ?? null,
            $document['NguoiLap'] ?? null,
            $employees
        );

        $document['NHAN_VIENIdNhanVien2'] = $this->resolveEmployeeId(
            $document['NHAN_VIENIdNhanVien2'] ?? null,
            $document['NguoiXacNhanId'] ?? null,
            $document['NguoiXacNhan'] ?? null,
            $employees
        );

        return $document;
    }

    private function resolveEmployeeId(?string $primaryId, ?string $fallbackId, ?string $name, array $employees): ?string
    {
        $normalizedId = $this->normalizeId($primaryId);
        if ($normalizedId !== null) {
            return $normalizedId;
        }

        $fallbackId = $this->normalizeId($fallbackId);
        if ($fallbackId !== null) {
            return $fallbackId;
        }

        $normalizedName = $this->normalizeName($name);
        if ($normalizedName === null) {
            return null;
        }

        foreach ($employees as $employee) {
            if (!isset($employee['IdNhanVien'])) {
                continue;
            }

            $employeeName = $this->normalizeName($employee['HoTen'] ?? null);
            if ($employeeName === null) {
                continue;
            }

            if ($employeeName === $normalizedName) {
                return $this->normalizeId($employee['IdNhanVien']);
            }
        }

        return null;
    }

    private function normalizeId(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function normalizeName(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);
        if ($value === '') {
            return null;
        }

        if (function_exists('mb_strtolower')) {
            return mb_strtolower($value, 'UTF-8');
        }

        return strtolower($value);
    }
}
