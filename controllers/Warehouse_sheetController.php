<?php

class Warehouse_sheetController extends Controller
{
    private InventorySheet $sheetModel;
    private InventoryLot $lotModel;
    private InventorySheetDetail $sheetDetailModel;
    private Product $productModel;
    private Warehouse $warehouseModel;

    private const DOCUMENT_TYPES = [
        'inbound' => [
            'Phiếu nhập nguyên liệu',
            'Phiếu nhập thành phẩm',
            'Phiếu nhập xử lý lỗi',
        ],
        'outbound' => [
            'Phiếu xuất nguyên liệu',
            'Phiếu xuất thành phẩm',
            'Phiếu xuất xử lý lỗi',
        ],
    ];

    private const WAREHOUSE_TYPE_COMPATIBILITY = [
        'material' => ['Phiếu nhập nguyên liệu', 'Phiếu xuất nguyên liệu'],
        'finished' => ['Phiếu nhập thành phẩm', 'Phiếu xuất thành phẩm'],
        'quality' => ['Phiếu nhập xử lý lỗi', 'Phiếu xuất xử lý lỗi'],
    ];

    public function __construct()
    {
        $this->authorize(['VT_NHANVIEN_KHO']);
        $this->sheetModel = new InventorySheet();
        $this->lotModel = new InventoryLot();
        $this->sheetDetailModel = new InventorySheetDetail();
        $this->productModel = new Product();
        $this->warehouseModel = new Warehouse();
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

        $documentType = $this->normalizeDocumentType($documentType);
        if ($documentType === null) {
            $this->setFlash('danger', 'Loại phiếu không hợp lệ. Vui lòng chọn loại phiếu nhập/xuất nằm trong danh mục.');
            $this->redirect('?controller=warehouse_sheet&action=create');
            return;
        }

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
            return;
        }

        $warehouse = $this->warehouseModel->find($data['IdKho']);
        if (!$warehouse || !$this->validateWarehouseCompatibility($data['IdKho'], $documentType)) {
            $this->setFlash('danger', 'Loại phiếu không phù hợp với loại kho. Vui lòng chọn đúng loại kho để nhập/xuất.');
            $this->redirect($redirectTo);
            return;
        }

        if ($this->sheetModel->find($data['IdPhieu'])) {
            $this->setFlash('danger', 'Mã phiếu đã tồn tại. Vui lòng kiểm tra lại hoặc để hệ thống tự sinh mã.');
            $this->redirect($redirectTo);
            return;
        }

        $isQuickEntry = isset($_POST['quick_entry']) && $_POST['quick_entry'] === '1';
        $quickEntryPayload = null;

        if ($isQuickEntry) {
            $quickEntryPayload = $this->prepareQuickEntryPayload($_POST, $data, $warehouse);

            if ($quickEntryPayload === null) {
                $this->setFlash('danger', 'Vui lòng nhập đầy đủ thông tin lô/nguyên liệu trước khi xác nhận.');
                $this->redirect($redirectTo);
                return;
            }

            if ($this->isOutboundDocument($documentType)) {
                $this->setFlash('danger', 'Nhập nhanh chỉ áp dụng cho phiếu nhập. Vui lòng chọn lô để xuất kho.');
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

                $this->applyStockImpact($documentType, [$quickEntryPayload['detail']], true, true, $data['IdPhieu'], $data['IdKho']);
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

        $document = $this->sheetModel->findDocument($id);
        if (!$document) {
            $this->setFlash('warning', 'Phiếu kho đã không còn tồn tại.');
            $this->redirect('?controller=warehouse_sheet&action=index');
            return;
        }

        $details = $this->sheetDetailModel->getDetailsByDocument($id);
        $connection = Database::getInstance()->getConnection();

        try {
            $connection->beginTransaction();

            if (!empty($details)) {
                $this->applyStockImpact($document['LoaiPhieu'], $details, false, false, $id, $document['IdKho'] ?? null);
            }

            if ($this->sheetModel->deleteDocument($id)) {
                $this->setFlash('success', 'Đã xóa phiếu kho.');
            } else {
                $this->setFlash('warning', 'Phiếu kho đã không còn tồn tại.');
            }

            $connection->commit();
        } catch (Throwable $e) {
            if ($connection->inTransaction()) {
                $connection->rollBack();
            }
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

    private function prepareQuickEntryPayload(array $input, array $document, ?array $warehouse = null): ?array
    {
        $payload = $this->prepareQuickEntryPayloadLegacy($input, $document);

        if ($payload === null) {
            return null;
        }

        $warehouseType = $this->resolveWarehouseTypeKey($warehouse['TenLoaiKho'] ?? ($input['WarehouseType'] ?? 'material'));

        if (!$this->validateProductCompatibility($payload['lot']['IdSanPham'] ?? null, $warehouseType)) {
            return null;
        }

        $payload['detail']['IdPhieu'] = $document['IdPhieu'];
        $payload['lot']['LoaiLo'] = $this->resolveLotTypeLabel($warehouseType);
        $payload['lot']['IdKho'] = $document['IdKho'];

        return $payload;
    }

    private function prepareQuickEntryPayloadLegacy(array $input, array $document): ?array
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

    private function normalizeDocumentType(?string $type): ?string
    {
        if ($type === null) {
            return null;
        }

        $normalized = function_exists('mb_strtolower') ? mb_strtolower($type, 'UTF-8') : strtolower($type);

        foreach (self::DOCUMENT_TYPES as $direction => $types) {
            foreach ($types as $item) {
                $candidate = function_exists('mb_strtolower') ? mb_strtolower($item, 'UTF-8') : strtolower($item);

                if ($candidate === $normalized) {
                    return $item;
                }
            }
        }

        return null;
    }

    private function isInboundDocument(string $type): bool
    {
        return in_array($type, self::DOCUMENT_TYPES['inbound'], true);
    }

    private function isOutboundDocument(string $type): bool
    {
        return in_array($type, self::DOCUMENT_TYPES['outbound'], true);
    }

    private function validateWarehouseCompatibility(string $warehouseId, string $documentType): bool
    {
        $warehouse = $this->warehouseModel->find($warehouseId);

        if (!$warehouse) {
            return false;
        }

        $typeKey = $this->resolveWarehouseTypeKey($warehouse['TenLoaiKho'] ?? '');
        $allowed = self::WAREHOUSE_TYPE_COMPATIBILITY[$typeKey] ?? [];

        return in_array($documentType, $allowed, true);
    }

    private function resolveWarehouseTypeKey(?string $type): string
    {
        $normalized = function_exists('mb_strtolower') ? mb_strtolower((string) $type, 'UTF-8') : strtolower((string) $type);

        if (str_contains($normalized, 'thành phẩm') || str_contains($normalized, 'thanh pham')) {
            return 'finished';
        }

        if (str_contains($normalized, 'lỗi') || str_contains($normalized, 'xu ly') || str_contains($normalized, 'xử lý')) {
            return 'quality';
        }

        return 'material';
    }

    private function validateProductCompatibility(?string $productId, string $warehouseType): bool
    {
        if ($productId === null || $productId === '') {
            return false;
        }

        $product = $this->productModel->find($productId);
        if (!$product) {
            return false;
        }

        $candidateTypes = $this->classifyProductForWarehouseTypes($product);

        return in_array($warehouseType, $candidateTypes, true);
    }

    private function classifyProductForWarehouseTypes(array $product): array
    {
        $id = strtoupper((string) ($product['IdSanPham'] ?? ''));
        $name = $this->normalizeText($product['TenSanPham'] ?? '');
        $description = $this->normalizeText($product['MoTa'] ?? '');

        $types = [];

        $isComponent = str_starts_with($id, 'SPCOMP')
            || str_contains($name, 'linh kiện')
            || str_contains($description, 'linh kiện')
            || str_contains($name, 'kit')
            || str_contains($description, 'kit');

        $isFinished = str_starts_with($id, 'SPKB')
            || str_contains($name, 'thành phẩm')
            || str_contains($description, 'thành phẩm')
            || (!$isComponent && !str_contains($name, 'linh kiện'));

        if ($isComponent) {
            $types[] = 'material';
        }

        if ($isFinished) {
            $types[] = 'finished';
            $types[] = 'quality';
        }

        if (empty($types)) {
            $types = ['material', 'finished', 'quality'];
        }

        return array_values(array_unique($types));
    }

    private function normalizeText(string $value): string
    {
        if (function_exists('mb_strtolower')) {
            $value = mb_strtolower($value, 'UTF-8');
        } else {
            $value = strtolower($value);
        }

        return trim(preg_replace('/\s+/', ' ', $value));
    }

    private function applyStockImpact(string $documentType, array $details, bool $isCreation, bool $isNewLotCreated, ?string $documentId = null, ?string $warehouseId = null): void
    {
        $isInbound = $this->isInboundDocument($documentType);
        $direction = $isInbound ? 1 : -1;
        $direction = $isCreation ? $direction : -$direction;

        foreach ($details as $detail) {
            $lotId = $detail['IdLo'] ?? null;
            $quantity = (int) ($detail['ThucNhan'] ?? $detail['SoLuong'] ?? 0);

            if (!$lotId || $quantity <= 0) {
                continue;
            }

            $lot = $this->lotModel->findWithWarehouse($lotId);
            if (!$lot) {
                throw new RuntimeException('Không tìm thấy lô liên quan tới phiếu.');
            }

            if ($warehouseId !== null && ($lot['IdKho'] ?? null) !== $warehouseId) {
                throw new RuntimeException('Lô không thuộc kho của phiếu. Vui lòng kiểm tra lại.');
            }

            $quantityDelta = $direction * $quantity;
            $currentQuantity = (int) ($lot['SoLuong'] ?? 0);
            $newQuantity = $currentQuantity + $quantityDelta;

            if ($newQuantity < 0) {
                throw new RuntimeException('Số lượng lô không đủ để thực hiện phiếu hoặc hoàn tác.');
            }

            if (!$isNewLotCreated || !$isInbound) {
                $this->lotModel->adjustLotQuantity($lotId, $quantityDelta, false);
            }

            $lotDelta = 0;

            if ($isInbound && $isCreation && $isNewLotCreated) {
                $lotDelta = 1;
            }

            if (!$isInbound && !$isCreation && !$this->lotModel->hasOtherDocuments($lotId, $documentId)) {
                $lotDelta = 0;
            }

            $this->warehouseModel->adjustWarehouseStock($lot['IdKho'], $quantityDelta, $lotDelta);

            if ($isInbound && !$isCreation && !$this->lotModel->hasOtherDocuments($lotId, $documentId) && ($lot['SoLuong'] ?? 0) + $quantityDelta <= 0) {
                $this->lotModel->deleteLot($lotId);
                $this->warehouseModel->adjustWarehouseStock($lot['IdKho'], 0, -1);
            }
        }
    }
}
