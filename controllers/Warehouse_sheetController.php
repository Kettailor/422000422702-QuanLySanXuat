<?php

class Warehouse_sheetController extends Controller
{
    private InventorySheet $sheetModel;
    private InventoryLot $lotModel;
    private InventorySheetDetail $sheetDetailModel;
    private Product $productModel;

    public function __construct()
    {
        $this->authorize(['VT_NHANVIEN_KHO']);
        $this->sheetModel = new InventorySheet();
        $this->lotModel = new InventoryLot();
        $this->sheetDetailModel = new InventorySheetDetail();
        $this->productModel = new Product();
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
        $documentType = $_POST['LoaiPhieu'] ?? null;

        $data = [
            'IdPhieu' => $inputId !== '' ? $inputId : $this->sheetModel->generateDocumentId($documentType),
            'NgayLP' => $_POST['NgayLP'] ?? null,
            'NgayXN' => $_POST['NgayXN'] ?? null,
            'TongTien' => $_POST['TongTien'] ?? 0,
            'LoaiPhieu' => $documentType,
            'IdKho' => $_POST['IdKho'] ?? null,
            'NHAN_VIENIdNhanVien' => $_POST['NguoiLap'] ?? null,
            'NHAN_VIENIdNhanVien2' => $_POST['NguoiXacNhan'] ?? null,
        ];

        $redirectTo = trim((string) ($_POST['redirect'] ?? ''));
        if ($redirectTo === '') {
            $redirectTo = '?controller=warehouse_sheet&action=index';
        }

        if (!$this->validateRequired($data)) {
            $this->setFlash('danger', 'Vui lòng điền đầy đủ thông tin bắt buộc của phiếu.');
            $this->redirect($redirectTo);
        }

        $isQuickEntry = isset($_POST['quick_entry']) && $_POST['quick_entry'] === '1';
        $quickEntryPayload = null;

        if ($isQuickEntry) {
            $quickEntryPayload = $this->prepareQuickEntryPayload($_POST, $data);

            if ($quickEntryPayload === null) {
                $this->setFlash('danger', 'Vui lòng nhập đầy đủ thông tin lô/nguyên liệu trước khi xác nhận.');
                $this->redirect($redirectTo);
                return;
            }
        }

        $connection = Database::getInstance()->getConnection();

        try {
            $connection->beginTransaction();

            if (!$this->sheetModel->createDocument($data)) {
                throw new RuntimeException('Không thể lưu phiếu kho.');
            }

            if ($quickEntryPayload) {
                if (!$this->lotModel->createLot($quickEntryPayload['lot'])) {
                    throw new RuntimeException('Không thể tạo lô hàng mới.');
                }

                if (!$this->sheetDetailModel->createDetail($quickEntryPayload['detail'])) {
                    throw new RuntimeException('Không thể lưu chi tiết phiếu.');
                }
            }

            $connection->commit();

            $this->setFlash('success', $isQuickEntry ? 'Đã lập phiếu và thêm lô vào kho thành công.' : 'Đã tạo phiếu kho mới.');
        } catch (Throwable $e) {
            if ($connection->inTransaction()) {
                $connection->rollBack();
            }

            $this->setFlash('danger', 'Không thể tạo phiếu: ' . $e->getMessage());
        }

        $this->redirect($redirectTo);
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
        $required = ['IdPhieu', 'LoaiPhieu', 'IdKho', 'NHAN_VIENIdNhanVien'];

        foreach ($required as $field) {
            if (!array_key_exists($field, $data)) {
                return false;
            }

            $value = $data[$field];
            if (is_string($value)) {
                $value = trim($value);
            }

            if ($value === null || $value === '') {
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

    private function prepareQuickEntryPayload(array $input, array $document): ?array
    {
        $warehouseId = $document['IdKho'] ?? null;
        if (!$warehouseId) {
            return null;
        }

        $warehouseType = $input['WarehouseType'] ?? 'material';
        $lotPrefix = $this->resolveLotPrefix($warehouseType);
        $lotId = trim((string) ($input['Quick_IdLo'] ?? '')) ?: $this->lotModel->generateLotId($lotPrefix);
        $lotName = trim((string) ($input['Quick_TenLo'] ?? ''));
        $productId = trim((string) ($input['Quick_IdSanPham'] ?? ''));
        $unit = trim((string) ($input['Quick_DonViTinh'] ?? ''));
        $quantity = (int) ($input['Quick_SoLuong'] ?? 0);
        $received = (int) ($input['Quick_ThucNhan'] ?? 0);

        if ($lotName === '' || $productId === '' || $quantity <= 0) {
            return null;
        }

        if ($received <= 0) {
            $received = $quantity;
        }

        if ($unit === '') {
            $unit = $this->resolveProductUnit($productId);
        }

        $detailId = $this->generateDetailId($lotId);

        return [
            'lot' => [
                'IdLo' => $lotId,
                'TenLo' => $lotName,
                'SoLuong' => $received,
                'NgayTao' => date('Y-m-d H:i:s'),
                'LoaiLo' => $this->resolveLotTypeLabel($warehouseType),
                'IdSanPham' => $productId,
                'IdKho' => $warehouseId,
            ],
            'detail' => [
                'IdTTCTPhieu' => $detailId,
                'DonViTinh' => $unit ?: null,
                'SoLuong' => $quantity,
                'ThucNhan' => $received,
                'IdPhieu' => $document['IdPhieu'],
                'IdLo' => $lotId,
            ],
        ];
    }

    private function resolveLotPrefix(string $warehouseType): string
    {
        return match ($warehouseType) {
            'finished' => 'LOTP',
            'quality' => 'LOXL',
            default => 'LONL',
        };
    }

    private function resolveLotTypeLabel(string $warehouseType): string
    {
        return match ($warehouseType) {
            'finished' => 'Thành phẩm',
            'quality' => 'Xử lý lỗi',
            default => 'Nguyên liệu',
        };
    }

    private function generateDetailId(string $lotId): string
    {
        return 'CTP' . date('YmdHis') . substr(md5($lotId . microtime()), 0, 4);
    }

    private function resolveProductUnit(string $productId): string
    {
        $product = $this->productModel->find($productId);

        return $product['DonVi'] ?? '';
    }
}
