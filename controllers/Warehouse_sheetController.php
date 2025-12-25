<?php

use Dompdf\Dompdf;
use Dompdf\Options;

class Warehouse_sheetController extends Controller
{
    private InventorySheet $sheetModel;
    private InventoryLot $lotModel;
    private InventorySheetDetail $sheetDetailModel;
    private Product $productModel;
    private Warehouse $warehouseModel;
    private Workshop $workshopModel;
    private Employee $employeeModel;
    private WorkshopAssignment $assignmentModel;
    private array $warehouseCache = [];
    private ?array $accessibleWarehouseIds = null;

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
        $this->authorize(array_merge(['VT_NHANVIEN_KHO', 'VT_KHO_TRUONG', 'VT_BAN_GIAM_DOC', 'VT_ADMIN'], $this->getWorkshopManagerRoles()));
        $this->sheetModel = new InventorySheet();
        $this->lotModel = new InventoryLot();
        $this->sheetDetailModel = new InventorySheetDetail();
        $this->productModel = new Product();
        $this->warehouseModel = new Warehouse();
        $this->workshopModel = new Workshop();
        $this->employeeModel = new Employee();
        $this->assignmentModel = new WorkshopAssignment();
    }

    public function index(): void
    {
        $filter = $_GET['type'] ?? 'all';
        $allowedFilters = ['all', 'inbound', 'outbound'];
        if (!in_array($filter, $allowedFilters, true)) {
            $filter = 'all';
        }

        $visibleWarehouses = $this->getAccessibleWarehouseIds();
        $documents = $this->sheetModel->getDocuments($filter === 'all' ? null : $filter, 200, $visibleWarehouses);
        $documents = array_map(function (array $document): array {
            $document['classification'] = $this->classifyDocumentType($document['LoaiPhieu'] ?? '');
            return $document;
        }, $documents);
        $summary = $this->sheetModel->getDocumentSummary($visibleWarehouses);

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
        $products = $this->productModel->all(300);
        $warehouses = $this->filterWarehousesByAccess($options['warehouses']);
        $presetWarehouseId = $_GET['warehouse'] ?? null;
        $presetDirection = $_GET['direction'] ?? null;
        $presetCategory = $_GET['category'] ?? null;
        $presetDocumentType = $this->resolvePresetDocumentType($presetDirection, $presetCategory);

        if ($presetWarehouseId !== null && !$this->isWarehouseAccessible($presetWarehouseId)) {
            $this->setFlash('danger', 'Bạn không có quyền lập phiếu cho kho đã chọn.');
            $this->redirect('?controller=warehouse_sheet&action=index');
        }

        if ($warehouses === []) {
            $this->setFlash('danger', 'Bạn không có kho nào được phân quyền để lập phiếu.');
            $this->redirect('?controller=warehouse_sheet&action=index');
        }

        $lotFilter = $presetWarehouseId ? [$presetWarehouseId] : $this->getAccessibleWarehouseIds();
        $lots = $this->lotModel->getSelectableLots(300, $lotFilter);

        $creatorId = $this->resolveCreator(null);
        $approvers = $this->buildApproverMap($warehouses, $options['employees']);
        $workshops = $this->workshopModel->getAllWithManagers(300);
        $workshopApprovers = $this->buildWorkshopApproverMap($workshops, $options['employees']);
        $warehouseWorkshopMap = $this->buildWarehouseWorkshopMap($warehouses, $workshops);

        $this->render('warehouse_sheet/create', [
            'title' => 'Tạo phiếu kho mới',
            'warehouses' => $warehouses,
            'employees' => $options['employees'],
            'types' => $options['types'],
            'products' => $products,
            'lots' => $lots,
            'approvers' => $approvers,
            'workshopApprovers' => $workshopApprovers,
            'workshops' => $workshops,
            'warehouseWorkshopMap' => $warehouseWorkshopMap,
            'currentUser' => $this->currentUser(),
            'document' => [
                'IdPhieu' => $defaultId,
                'NgayLP' => date('Y-m-d'),
                'NgayXN' => '',
                'IdKho' => $presetWarehouseId,
                'LoaiPhieu' => $presetDocumentType,
                'NHAN_VIENIdNhanVien' => $creatorId,
                'IdKhoNhan' => '',
                'IdXuongNhan' => '',
            ],
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=warehouse_sheet&action=index');
            return;
        }

        $inputId = trim((string) ($_POST['IdPhieu'] ?? ''));
        $documentType = $_POST['LoaiPhieu'] ?? null;

        $documentType = $this->normalizeDocumentType($documentType);
        if ($documentType === null) {
            $this->setFlash('danger', 'Loại phiếu không hợp lệ. Vui lòng chọn loại phiếu nhập/xuất nằm trong danh mục.');
            $this->redirect('?controller=warehouse_sheet&action=create');
            return;
        }

        $today = date('Y-m-d');
        $data = [
            'IdPhieu' => $inputId !== '' ? $inputId : $this->sheetModel->generateDocumentId($documentType),
            'NgayLP' => $_POST['NgayLP'] ?: $today,
            'NgayXN' => null,
            'TongTien' => $_POST['TongTien'] ?? 0,
            'LoaiPhieu' => $documentType,
            'IdKho' => $_POST['IdKho'] ?? null,
            'NHAN_VIENIdNhanVien' => $this->resolveCreator(null),
            'NHAN_VIENIdNhanVien2' => null,
            'LoaiDoiTac' => null,
            'DoiTac' => null,
            'SoThamChieu' => $_POST['SoThamChieu'] ?? null,
            'LyDo' => $_POST['LyDo'] ?? null,
            'GhiChu' => $_POST['GhiChu'] ?? null,
        ];

        $warehouse = $this->findWarehouse($data['IdKho']);
        $partnerScope = $_POST['PartnerScope'] ?? 'internal';
        $partnerWorkshopId = $_POST['PartnerWorkshop'] ?? null;
        $partnerWarehouseId = $_POST['PartnerWarehouse'] ?? null;
        $isOutbound = $this->isOutboundDocument($documentType);
        $partnerData = $this->buildPartnerData($partnerScope, $_POST, $warehouse);
        $data['LoaiDoiTac'] = $partnerData['type'] ?? null;
        $data['DoiTac'] = $partnerData['name'] ?? null;

        if ($this->sheetModel->supportsColumn('IdKhoNhan')) {
            $data['IdKhoNhan'] = ($partnerScope === 'internal' && $isOutbound) ? $partnerWarehouseId : null;
        }
        if ($this->sheetModel->supportsColumn('IdXuongNhan')) {
            $data['IdXuongNhan'] = ($partnerScope === 'internal' && $isOutbound) ? $partnerWorkshopId : null;
        }

        if ($partnerScope === 'internal' && $isOutbound) {
            if (!$partnerWorkshopId || !$partnerWarehouseId) {
                $this->setFlash('danger', 'Vui lòng chọn xưởng và kho nhận nội bộ cho phiếu xuất.');
                $this->redirect('?controller=warehouse_sheet&action=create');
                return;
            }
            if (!$this->validatePartnerWarehouse($partnerWarehouseId, $partnerWorkshopId)) {
                $this->setFlash('danger', 'Kho nhận không thuộc xưởng đã chọn. Vui lòng kiểm tra lại.');
                $this->redirect('?controller=warehouse_sheet&action=create');
                return;
            }
        }

        $approverId = $this->resolveDocumentApprover($documentType, $partnerScope, $partnerWorkshopId, $data['IdKho']);
        if (!$approverId) {
            $this->setFlash('danger', 'Kho chưa có xưởng trưởng để xác nhận phiếu.');
            $this->redirect('?controller=warehouse_sheet&action=index');
            return;
        }
        if (!$this->validateApproverWorkshopForDocument($approverId, $warehouse, $partnerWorkshopId, $partnerScope, $documentType)) {
            $this->setFlash('danger', 'Người xác nhận phải thuộc xưởng đã chọn.');
            $this->redirect('?controller=warehouse_sheet&action=index');
            return;
        }
        $data['NHAN_VIENIdNhanVien2'] = $approverId;

        $redirectTo = trim((string) ($_POST['redirect'] ?? ''));
        if ($redirectTo === '') {
            $redirectTo = '?controller=warehouse_sheet&action=index';
        }

        if (!$this->validateRequired($data)) {
            $this->setFlash('danger', 'Vui lòng điền đầy đủ thông tin bắt buộc của phiếu.');
            $this->redirect($redirectTo);
            return;
        }

        if (!$this->isWarehouseAccessible($data['IdKho'])) {
            $this->setFlash('danger', 'Bạn không có quyền lập phiếu cho kho đã chọn.');
            $this->redirect($redirectTo);
            return;
        }

        $warehouse = $this->findWarehouse($data['IdKho']);
        if (!$warehouse || !$this->validateWarehouseCompatibility($data['IdKho'], $documentType)) {
            $this->setFlash('danger', 'Loại phiếu không phù hợp với loại kho. Vui lòng chọn đúng loại kho để nhập/xuất.');
            $this->redirect($redirectTo);
            return;
        }

        if (!$this->validateApprovalSeparation($data['NHAN_VIENIdNhanVien'], $data['NHAN_VIENIdNhanVien2'])) {
            $this->setFlash('danger', 'Người lập và người xác nhận phải là hai tài khoản khác nhau.');
            $this->redirect($redirectTo);
            return;
        }

        if ($this->sheetModel->find($data['IdPhieu'])) {
            $this->setFlash('danger', 'Mã phiếu đã tồn tại. Vui lòng kiểm tra lại hoặc để hệ thống tự sinh mã.');
            $this->redirect($redirectTo);
            return;
        }

        $isQuickEntry = isset($_POST['quick_entry']) && $_POST['quick_entry'] === '1';
        $isConfirmed = $this->isConfirmedData($data);
        $detailPayloads = [];
        $newLots = [];

        if ($isQuickEntry) {
            $this->handleQuickEntry($documentType, $redirectTo, $data, $warehouse, $detailPayloads, $newLots, $isConfirmed);
        } else {
            try {
                $parsed = $this->parseDocumentDetails($_POST, $documentType, $warehouse, $data['IdPhieu'], $isConfirmed);
                $detailPayloads = $parsed['details'];
                $newLots = $parsed['new_lots'];
            } catch (Throwable $e) {
                $this->setFlash('danger', $e->getMessage());
                $this->redirect($redirectTo);
                return;
            }

            if (empty($detailPayloads)) {
                $this->setFlash('danger', 'Phiếu kho cần ít nhất một dòng chi tiết để cập nhật tồn kho.');
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

            foreach ($newLots as $lotPayload) {
                if (!$this->lotModel->createLot($lotPayload)) {
                    throw new RuntimeException('Không thể tạo lô hàng mới.');
                }
            }

            foreach ($detailPayloads as $detail) {
                if (!$this->sheetDetailModel->createDetail($detail)) {
                    throw new RuntimeException('Không thể lưu chi tiết phiếu.');
                }
            }

            if ($isConfirmed) {
                $this->applyStockImpact($documentType, $detailPayloads, true, $data['IdPhieu'], $data['IdKho']);
            }

            $connection->commit();

            if ($isConfirmed) {
                $this->setFlash('success', $isQuickEntry ? 'Đã lập phiếu, xác nhận và cập nhật tồn kho.' : 'Đã tạo phiếu kho, xác nhận và cập nhật tồn kho.');
            } else {
                $this->setFlash('info', 'Đã lưu phiếu ở trạng thái chờ xác nhận. Tồn kho sẽ cập nhật sau khi xác nhận.');
            }
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

        if (!$this->isWarehouseAccessible($document['IdKho'] ?? null)) {
            $this->setFlash('danger', 'Bạn không có quyền sửa phiếu thuộc kho này.');
            $this->redirect('?controller=warehouse_sheet&action=index');
        }

        $options = $this->sheetModel->getFormOptions();
        $filteredWarehouses = $this->filterWarehousesByAccess($options['warehouses']);
        $approvers = $this->buildApproverMap($filteredWarehouses, $options['employees']);
        $workshops = $this->workshopModel->getAllWithManagers(300);
        $workshopApprovers = $this->buildWorkshopApproverMap($workshops, $options['employees']);
        $warehouseWorkshopMap = $this->buildWarehouseWorkshopMap($filteredWarehouses, $workshops);

        $this->render('warehouse_sheet/edit', [
            'title' => 'Cập nhật phiếu kho',
            'document' => $document,
            'warehouses' => $filteredWarehouses,
            'employees' => $options['employees'],
            'types' => $options['types'],
            'approvers' => $approvers,
            'workshopApprovers' => $workshopApprovers,
            'workshops' => $workshops,
            'warehouseWorkshopMap' => $warehouseWorkshopMap,
            'currentUser' => $this->currentUser(),
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

        $existing = $this->sheetModel->findDocument($id);

        $documentType = $_POST['LoaiPhieu'] ?? null;
        $documentType = $this->normalizeDocumentType($documentType);
        if ($documentType === null) {
            $this->setFlash('danger', 'Loại phiếu không hợp lệ. Vui lòng chọn đúng loại phiếu nhập/xuất.');
            $this->redirect('?controller=warehouse_sheet&action=edit&id=' . urlencode($id));
            return;
        }

        $today = date('Y-m-d');
        $data = [
            'NgayLP' => $_POST['NgayLP'] ?: ($existing['NgayLP'] ?? $today),
            'NgayXN' => $existing['NgayXN'] ?? null,
            'TongTien' => $_POST['TongTien'] ?? 0,
            'LoaiPhieu' => $documentType,
            'IdKho' => $_POST['IdKho'] ?? null,
            'NHAN_VIENIdNhanVien' => $this->resolveCreator(null),
            'NHAN_VIENIdNhanVien2' => null,
            'LoaiDoiTac' => null,
            'DoiTac' => null,
            'SoThamChieu' => $_POST['SoThamChieu'] ?? null,
            'LyDo' => $_POST['LyDo'] ?? null,
            'GhiChu' => $_POST['GhiChu'] ?? null,
        ];

        $warehouse = $this->findWarehouse($data['IdKho'] ?? ($existing['IdKho'] ?? null));
        $partnerScope = $_POST['PartnerScope'] ?? 'internal';
        $partnerWorkshopId = $_POST['PartnerWorkshop'] ?? null;
        $partnerWarehouseId = $_POST['PartnerWarehouse'] ?? null;
        $isOutbound = $this->isOutboundDocument($documentType);
        $partnerData = $this->buildPartnerData($partnerScope, $_POST, $warehouse);
        $data['LoaiDoiTac'] = $partnerData['type'] ?? null;
        $data['DoiTac'] = $partnerData['name'] ?? null;

        if ($this->sheetModel->supportsColumn('IdKhoNhan')) {
            $data['IdKhoNhan'] = ($partnerScope === 'internal' && $isOutbound) ? $partnerWarehouseId : ($existing['IdKhoNhan'] ?? null);
        }
        if ($this->sheetModel->supportsColumn('IdXuongNhan')) {
            $data['IdXuongNhan'] = ($partnerScope === 'internal' && $isOutbound) ? $partnerWorkshopId : ($existing['IdXuongNhan'] ?? null);
        }

        if ($partnerScope === 'internal' && $isOutbound) {
            if (!$partnerWorkshopId || !$partnerWarehouseId) {
                $this->setFlash('danger', 'Vui lòng chọn xưởng và kho nhận nội bộ cho phiếu xuất.');
                $this->redirect('?controller=warehouse_sheet&action=edit&id=' . urlencode($id));
                return;
            }
            if (!$this->validatePartnerWarehouse($partnerWarehouseId, $partnerWorkshopId)) {
                $this->setFlash('danger', 'Kho nhận không thuộc xưởng đã chọn. Vui lòng kiểm tra lại.');
                $this->redirect('?controller=warehouse_sheet&action=edit&id=' . urlencode($id));
                return;
            }
        }

        $approverId = $this->resolveDocumentApprover($documentType, $partnerScope, $partnerWorkshopId, $data['IdKho'] ?? ($existing['IdKho'] ?? null));
        if (!$approverId) {
            $this->setFlash('danger', 'Kho chưa có xưởng trưởng để xác nhận phiếu.');
            $this->redirect('?controller=warehouse_sheet&action=index');
            return;
        }
        if (!$this->validateApproverWorkshopForDocument($approverId, $warehouse, $partnerWorkshopId, $partnerScope, $documentType)) {
            $this->setFlash('danger', 'Người xác nhận phải thuộc xưởng đã chọn.');
            $this->redirect('?controller=warehouse_sheet&action=index');
            return;
        }
        $data['NHAN_VIENIdNhanVien2'] = $approverId;

        if (!$this->validateRequired(array_merge($data, ['IdPhieu' => $id]))) {
            $this->setFlash('danger', 'Vui lòng điền đầy đủ thông tin bắt buộc của phiếu.');
            $this->redirect('?controller=warehouse_sheet&action=edit&id=' . urlencode($id));
        }

        if (!$this->isWarehouseAccessible($data['IdKho'])) {
            $this->setFlash('danger', 'Bạn không có quyền cập nhật phiếu thuộc kho này.');
            $this->redirect('?controller=warehouse_sheet&action=index');
        }

        if (!$this->validateApprovalSeparation($data['NHAN_VIENIdNhanVien'], $data['NHAN_VIENIdNhanVien2'])) {
            $this->setFlash('danger', 'Người lập và người xác nhận phải khác nhau.');
            $this->redirect('?controller=warehouse_sheet&action=edit&id=' . urlencode($id));
        }

        $wasConfirmed = $this->isConfirmedData($existing ?? []);
        $nowConfirmed = $this->isConfirmedData(array_merge($existing ?? [], $data));
        $details = $this->sheetDetailModel->getDetailsByDocument($id);

        try {
            $this->sheetModel->updateDocument($id, $data);
            if ($nowConfirmed && !$wasConfirmed) {
                $documentType = $data['LoaiPhieu'] ?? ($existing['LoaiPhieu'] ?? '');
                $warehouseId = $data['IdKho'] ?? ($existing['IdKho'] ?? null);
                $this->applyStockImpact($documentType, $details, true, $id, $warehouseId);
                $this->setFlash('success', 'Đã xác nhận phiếu và cập nhật tồn kho.');
            } else {
                $this->setFlash('success', 'Đã cập nhật phiếu kho.');
            }
        } catch (Throwable $e) {
            $this->setFlash('danger', 'Không thể cập nhật phiếu: ' . $e->getMessage());
        }

        $this->redirect('?controller=warehouse_sheet&action=index');
    }

    public function confirm(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=warehouse_sheet&action=index');
        }

        $id = $_POST['IdPhieu'] ?? null;
        if (!$id) {
            $this->setFlash('danger', 'Không xác định được phiếu cần xác nhận.');
            $this->redirect('?controller=warehouse_sheet&action=index');
            return;
        }

        $document = $this->sheetModel->findDocument($id);
        if (!$document) {
            $this->setFlash('warning', 'Không tìm thấy phiếu kho.');
            $this->redirect('?controller=warehouse_sheet&action=index');
            return;
        }

        if (!$this->isWarehouseAccessible($document['IdKho'] ?? null)) {
            $this->setFlash('danger', 'Bạn không có quyền xác nhận phiếu thuộc kho này.');
            $this->redirect('?controller=warehouse_sheet&action=index');
            return;
        }

        if ($this->isConfirmedData($document)) {
            $this->setFlash('info', 'Phiếu đã được xác nhận trước đó.');
            $this->redirect('?controller=warehouse_sheet&action=read&id=' . urlencode($id));
            return;
        }

        if (!$this->canConfirmDocument($document)) {
            $this->setFlash('danger', 'Bạn không có quyền xác nhận phiếu này.');
            $this->redirect('?controller=warehouse_sheet&action=read&id=' . urlencode($id));
            return;
        }

        $details = $this->sheetDetailModel->getDetailsByDocument($id);
        if (empty($details)) {
            $this->setFlash('warning', 'Phiếu chưa có chi tiết để xác nhận.');
            $this->redirect('?controller=warehouse_sheet&action=read&id=' . urlencode($id));
            return;
        }

        $receivedInputs = $_POST['Detail_ThucNhan'] ?? [];
        $updatedDetails = [];

        foreach ($details as $detail) {
            $detailId = $detail['IdTTCTPhieu'] ?? null;
            if (!$detailId) {
                continue;
            }

            $planned = (int) ($detail['SoLuong'] ?? 0);
            $received = array_key_exists($detailId, $receivedInputs)
                ? (int) $receivedInputs[$detailId]
                : (int) ($detail['ThucNhan'] ?? $planned);

            if ($received < 0) {
                $this->setFlash('danger', 'Số lượng thực nhận không hợp lệ.');
                $this->redirect('?controller=warehouse_sheet&action=read&id=' . urlencode($id));
                return;
            }

            if ($received > $planned) {
                $this->setFlash('danger', 'Số lượng thực nhận không được vượt quá số lượng xuất/nhập.');
                $this->redirect('?controller=warehouse_sheet&action=read&id=' . urlencode($id));
                return;
            }

            $updatedDetails[] = array_merge($detail, ['ThucNhan' => $received]);
        }

        $connection = Database::getInstance()->getConnection();

        try {
            $connection->beginTransaction();

            foreach ($updatedDetails as $detail) {
                $this->sheetDetailModel->updateDetail($detail['IdTTCTPhieu'], [
                    'ThucNhan' => $detail['ThucNhan'],
                ]);
            }

            $confirmPayload = [
                'NgayXN' => date('Y-m-d'),
            ];
            $currentUser = $this->currentUser();
            if ($currentUser && empty($document['NHAN_VIENIdNhanVien2'])) {
                $confirmPayload['NHAN_VIENIdNhanVien2'] = $currentUser['IdNhanVien'] ?? null;
            }

            $this->sheetModel->updateDocument($id, $confirmPayload);

            $documentType = $document['LoaiPhieu'] ?? '';
            $warehouseId = $document['IdKho'] ?? null;
            $this->applyStockImpact($documentType, $updatedDetails, true, $id, $warehouseId);

            if ($this->isOutboundDocument($documentType) && !empty($document['IdKhoNhan'])) {
                $destination = $this->warehouseModel->find($document['IdKhoNhan']);
                if ($destination) {
                    $this->applyDestinationImpact($destination, $updatedDetails);
                }
            }

            $connection->commit();
            $this->setFlash('success', 'Đã xác nhận phiếu và cập nhật tồn kho.');
        } catch (Throwable $e) {
            if ($connection->inTransaction()) {
                $connection->rollBack();
            }
            $this->setFlash('danger', 'Không thể xác nhận phiếu: ' . $e->getMessage());
        }

        $this->redirect('?controller=warehouse_sheet&action=read&id=' . urlencode($id));
    }

    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=warehouse_sheet&action=index');
        }

        $id = $_POST['IdPhieu'] ?? null;
        if (!$id) {
            $this->setFlash('danger', 'Không xác định được phiếu cần hủy.');
            $this->redirect('?controller=warehouse_sheet&action=index');
        }

        $this->setFlash('warning', 'Chức năng xóa phiếu kho đã bị vô hiệu. Vui lòng cập nhật trạng thái hoặc ghi chú.');
        $this->redirect('?controller=warehouse_sheet&action=index');
    }

    public function read(): void
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->setFlash('danger', 'Thiếu thông tin phiếu cần xem.');
            $this->redirect('?controller=warehouse_sheet&action=index');
        }

        $document = $this->sheetModel->findDocument($id);
        if (!$document) {
            $this->setFlash('warning', 'Không tìm thấy phiếu kho.');
            $this->redirect('?controller=warehouse_sheet&action=index');
        }

        if (!$this->isWarehouseAccessible($document['IdKho'] ?? null)) {
            $this->setFlash('danger', 'Bạn không có quyền xem phiếu thuộc kho này.');
            $this->redirect('?controller=warehouse_sheet&action=index');
        }

        $details = $this->sheetDetailModel->getDetailsWithMeta($id);
        $warehouse = $document ? $this->warehouseModel->findWithSupervisor($document['IdKho']) : null;
        $destination = null;
        if (!empty($document['IdKhoNhan'])) {
            $destination = $this->warehouseModel->findWithSupervisor($document['IdKhoNhan']);
        }
        $totalQuantity = 0;
        foreach ($details as $detail) {
            $totalQuantity += (int) ($detail['SoLuong'] ?? 0);
        }
        $document['TongSoLuong'] = $totalQuantity;
        $document['TongMatHang'] = count($details);
        $document['classification'] = $this->classifyDocumentType($document['LoaiPhieu'] ?? '');

        $this->render('warehouse_sheet/read', [
            'title' => 'Chi tiết phiếu kho',
            'document' => $document,
            'details' => $details,
            'warehouse' => $warehouse,
            'destination' => $destination,
            'canConfirm' => $this->canConfirmDocument($document),
        ]);
    }

    public function export_pdf(): void
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->setFlash('danger', 'Thiếu thông tin phiếu cần xuất PDF.');
            $this->redirect('?controller=warehouse_sheet&action=index');
        }

        $document = $this->sheetModel->findDocument($id);
        if (!$document) {
            $this->setFlash('warning', 'Không tìm thấy phiếu kho.');
            $this->redirect('?controller=warehouse_sheet&action=index');
        }

        if (!$this->isWarehouseAccessible($document['IdKho'] ?? null)) {
            $this->setFlash('danger', 'Bạn không có quyền xuất PDF cho phiếu này.');
            $this->redirect('?controller=warehouse_sheet&action=index');
        }

        $details = $this->sheetDetailModel->getDetailsWithMeta($id);
        $warehouse = $document ? $this->warehouseModel->findWithSupervisor($document['IdKho']) : null;
        $destination = null;
        if (!empty($document['IdKhoNhan'])) {
            $destination = $this->warehouseModel->findWithSupervisor($document['IdKhoNhan']);
        }
        $totalQuantity = 0;
        foreach ($details as $detail) {
            $totalQuantity += (int) ($detail['SoLuong'] ?? 0);
        }
        $document['TongSoLuong'] = $totalQuantity;
        $document['TongMatHang'] = count($details);
        $classification = $this->classifyDocumentType($document['LoaiPhieu'] ?? '');

        ob_start();
        $this->render_pdf('warehouse_sheet/pdf', [
            'title' => 'Phiếu kho',
            'document' => $document,
            'details' => $details,
            'warehouse' => $warehouse,
            'destination' => $destination,
            'classification' => $classification,
        ]);
        $html = ob_get_clean();

        try {
            $dompdf = $this->buildPdfInstance();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream(($document['IdPhieu'] ?? 'phieu-kho') . '.pdf', ['Attachment' => 0]);
            exit;
        } catch (Throwable $e) {
            $this->setFlash('danger', 'Xuất PDF thất bại: ' . $e->getMessage());
            $this->redirect('?controller=warehouse_sheet&action=read&id=' . urlencode($id));
        }
    }

    private function buildPdfInstance(): Dompdf
    {
        if (!class_exists(Dompdf::class)) {
            throw new RuntimeException('Thư viện Dompdf chưa được cài đặt. Vui lòng chạy composer install.');
        }

        if (class_exists(Options::class)) {
            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);

            return new Dompdf($options);
        }

        return new Dompdf();
    }

    private function validateRequired(array $data): bool
    {
        $required = ['IdPhieu', 'LoaiPhieu', 'IdKho', 'NHAN_VIENIdNhanVien', 'LyDo', 'LoaiDoiTac'];

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

        if (!empty($data['LoaiDoiTac']) && empty($data['DoiTac'])) {
            return false;
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

    private function buildWarehouseWorkshopMap(array $warehouses, array $workshops): array
    {
        $workshopMap = [];
        foreach ($workshops as $workshop) {
            $workshopMap[$workshop['IdXuong'] ?? ''] = $workshop;
        }

        $map = [];
        foreach ($warehouses as $warehouse) {
            $id = $warehouse['IdKho'] ?? null;
            if (!$id) {
                continue;
            }
            $workshopId = $warehouse['IdXuong'] ?? null;
            $workshop = $workshopId ? ($workshopMap[$workshopId] ?? []) : [];
            $map[$id] = [
                'IdKho' => $id,
                'TenKho' => $warehouse['TenKho'] ?? '',
                'IdXuong' => $workshopId,
                'TenXuong' => $workshop['TenXuong'] ?? '',
            ];
        }

        return $map;
    }

    private function buildApproverMap(array $warehouses, array $employees): array
    {
        $employeeMap = [];
        foreach ($employees as $employee) {
            $employeeMap[$employee['IdNhanVien'] ?? ''] = $employee;
        }

        $workshopIds = array_values(array_filter(array_column($warehouses, 'IdXuong')));
        $managerMap = $this->workshopModel->getManagersByWorkshopIds($workshopIds);

        $approvers = [];
        foreach ($warehouses as $warehouse) {
            $warehouseId = $warehouse['IdKho'] ?? null;
            $workshopId = $warehouse['IdXuong'] ?? null;
            if (!$warehouseId || !$workshopId) {
                continue;
            }

            $managerId = $managerMap[$workshopId] ?? null;
            if (!$managerId) {
                continue;
            }

            $employee = $employeeMap[$managerId] ?? null;
            $approvers[$warehouseId] = [
                'IdNhanVien' => $managerId,
                'HoTen' => $employee['HoTen'] ?? $managerId,
                'ChucVu' => $employee['ChucVu'] ?? '',
            ];
        }

        return $approvers;
    }

    private function buildWorkshopApproverMap(array $workshops, array $employees): array
    {
        $employeeMap = [];
        foreach ($employees as $employee) {
            $employeeMap[$employee['IdNhanVien'] ?? ''] = $employee;
        }

        $approvers = [];
        foreach ($workshops as $workshop) {
            $workshopId = $workshop['IdXuong'] ?? null;
            $managerId = $workshop['XUONGTRUONG_IdNhanVien'] ?? null;
            if (!$workshopId || !$managerId) {
                continue;
            }

            $employee = $employeeMap[$managerId] ?? null;
            $approvers[$workshopId] = [
                'IdNhanVien' => $managerId,
                'HoTen' => $employee['HoTen'] ?? $managerId,
                'ChucVu' => $employee['ChucVu'] ?? '',
            ];
        }

        return $approvers;
    }

    private function resolveWarehouseApprover(?string $warehouseId): ?string
    {
        if (!$warehouseId) {
            return null;
        }

        $warehouse = $this->findWarehouse($warehouseId);
        $workshopId = $warehouse['IdXuong'] ?? null;
        if (!$workshopId) {
            return null;
        }

        $workshop = $this->workshopModel->find($workshopId);

        return $workshop['XUONGTRUONG_IdNhanVien'] ?? null;
    }

    private function resolveDocumentApprover(string $documentType, string $partnerScope, ?string $partnerWorkshopId, ?string $warehouseId): ?string
    {
        if ($partnerScope === 'internal' && $this->isOutboundDocument($documentType)) {
            if (!$partnerWorkshopId) {
                return null;
            }
            $workshop = $this->workshopModel->find($partnerWorkshopId);
            return $workshop['XUONGTRUONG_IdNhanVien'] ?? null;
        }

        return $this->resolveWarehouseApprover($warehouseId);
    }

    private function validateApproverWorkshop(?string $approverId, ?array $warehouse): bool
    {
        if (!$approverId || !$warehouse) {
            return false;
        }

        $workshopId = $warehouse['IdXuong'] ?? null;
        if (!$workshopId) {
            return false;
        }

        return $this->employeeModel->isEmployeeInWorkshop($approverId, $workshopId);
    }

    private function validateApproverWorkshopForDocument(?string $approverId, ?array $warehouse, ?string $partnerWorkshopId, string $partnerScope, string $documentType): bool
    {
        if ($partnerScope === 'internal' && $this->isOutboundDocument($documentType)) {
            if (!$partnerWorkshopId || !$approverId) {
                return false;
            }

            return $this->employeeModel->isEmployeeInWorkshop($approverId, $partnerWorkshopId);
        }

        return $this->validateApproverWorkshop($approverId, $warehouse);
    }

    private function validatePartnerWarehouse(?string $warehouseId, ?string $workshopId): bool
    {
        if (!$warehouseId || !$workshopId) {
            return false;
        }

        $warehouse = $this->warehouseModel->find($warehouseId);

        return $warehouse && ($warehouse['IdXuong'] ?? null) === $workshopId;
    }

    private function buildPartnerData(string $scope, array $input, ?array $warehouse = null): array
    {
        if ($scope === 'external') {
            $type = $input['PartnerExternalType'] ?? 'Nhà cung cấp';
            $name = trim((string) ($input['PartnerExternalName'] ?? ''));

            return [
                'type' => $type,
                'name' => $name,
            ];
        }

        $internalWarehouseId = $input['PartnerWarehouse'] ?? ($warehouse['IdKho'] ?? null);
        $internalWarehouse = $internalWarehouseId ? ($this->findWarehouse($internalWarehouseId) ?: $warehouse) : $warehouse;
        $warehouseName = $internalWarehouse['TenKho'] ?? ($internalWarehouse['IdKho'] ?? '');
        $workshopName = $internalWarehouse['TenXuong'] ?? '';

        return [
            'type' => 'Nội bộ',
            'name' => trim($warehouseName . ($workshopName ? ' · ' . $workshopName : '')),
        ];
    }

    private function prepareQuickEntryPayload(array $input, array $document, ?array $warehouse = null, bool $isConfirmed = false): ?array
    {
        $documentType = $document['LoaiPhieu'] ?? '';
        $warehouseType = $this->resolveWarehouseTypeKey($warehouse['TenLoaiKho'] ?? ($input['WarehouseType'] ?? 'material'));

        if ($this->isOutboundDocument($documentType)) {
            return $this->prepareOutboundQuickEntryPayload($input, $document, $warehouseType, $warehouse, $isConfirmed);
        }

        $payload = $this->prepareQuickEntryPayloadLegacy($input, $document, $isConfirmed);

        if ($payload === null) {
            return null;
        }

        if (!$this->validateProductCompatibility($payload['lot']['IdSanPham'] ?? null, $warehouseType)) {
            return null;
        }

        $payload['detail']['IdPhieu'] = $document['IdPhieu'];
        $payload['lot']['LoaiLo'] = $this->resolveLotTypeLabel($warehouseType);
        $payload['lot']['IdKho'] = $document['IdKho'];

        return $payload;
    }

    private function prepareQuickEntryPayloadLegacy(array $input, array $document, bool $isConfirmed = false): ?array
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
        if ($lotName === '' || $productId === '' || $quantity <= 0) {
            return null;
        }

        if ($unit === '') {
            $unit = $this->resolveProductUnit($productId);
        }

        $detailId = $this->generateDetailId($lotId);

        return [
            'lot' => [
                'IdLo' => $lotId,
                'TenLo' => $lotName,
                'SoLuong' => $isConfirmed ? $quantity : 0,
                'NgayTao' => date('Y-m-d H:i:s'),
                'LoaiLo' => $this->resolveLotTypeLabel($warehouseType),
                'IdSanPham' => $productId,
                'IdKho' => $warehouseId,
            ],
            'detail' => [
                'IdTTCTPhieu' => $detailId,
                'DonViTinh' => $unit ?: null,
                'SoLuong' => $quantity,
                'ThucNhan' => $isConfirmed ? $quantity : 0,
                'IdPhieu' => $document['IdPhieu'],
                'IdLo' => $lotId,
                'is_new_lot' => true,
            ],
        ];
    }

    private function prepareOutboundQuickEntryPayload(array $input, array $document, string $warehouseType, ?array $warehouse = null, bool $isConfirmed = false): ?array
    {
        $warehouseId = $document['IdKho'] ?? null;
        if (!$warehouseId) {
            return null;
        }

        $lotId = trim((string) ($input['Quick_IdLo_Existing'] ?? ''));
        $quantity = (int) ($input['Quick_SoLuong'] ?? 0);

        if ($lotId === '' || $quantity <= 0) {
            return null;
        }

        $lotData = $this->lotModel->findWithWarehouse($lotId);
        if (!$lotData) {
            throw new RuntimeException('Không tìm thấy lô để xuất kho.');
        }

        if (($lotData['IdKho'] ?? null) !== $warehouseId) {
            throw new RuntimeException('Lô đã chọn không thuộc kho lập phiếu.');
        }

        if ($quantity > (int) ($lotData['SoLuong'] ?? 0)) {
            throw new RuntimeException('Số lượng xuất vượt quá tồn của lô ' . $lotId . '.');
        }

        if (!$this->validateProductCompatibility($lotData['IdSanPham'] ?? null, $warehouseType)) {
            throw new RuntimeException('Lô không phù hợp với loại kho đã chọn.');
        }

        $detailId = $this->generateDetailId($lotId);
        $unit = $input['Quick_DonViTinh'] ?? ($lotData['DonVi'] ?? '');

        return [
            'lot' => $lotData,
            'detail' => [
                'IdTTCTPhieu' => $detailId,
                'DonViTinh' => $unit !== '' ? $unit : null,
                'SoLuong' => $quantity,
                'ThucNhan' => $isConfirmed ? $quantity : 0,
                'IdPhieu' => $document['IdPhieu'],
                'IdLo' => $lotId,
                'is_new_lot' => false,
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

    private function parseDocumentDetails(array $input, string $documentType, array $warehouse, string $documentId, bool $isConfirmed = false): array
    {
        $lotIds = $input['Detail_IdLo'] ?? [];
        $lotNames = $input['Detail_TenLo'] ?? [];
        $productIds = $input['Detail_IdSanPham'] ?? [];
        $quantities = $input['Detail_SoLuong'] ?? [];
        $units = $input['Detail_DonVi'] ?? [];
        $modes = $input['Detail_Mode'] ?? [];

        $isInbound = $this->isInboundDocument($documentType);
        $warehouseType = $this->resolveWarehouseTypeKey($warehouse['TenLoaiKho'] ?? '');

        $details = [];
        $newLots = [];
        $count = max(count($lotIds), count($lotNames), count($productIds), count($quantities), count($units), count($modes));

        for ($i = 0; $i < $count; $i++) {
            $mode = $modes[$i] ?? 'existing';
            $isNewLot = $mode === 'new';
            $lotId = trim((string) ($lotIds[$i] ?? ''));
            $lotName = trim((string) ($lotNames[$i] ?? ''));
            $productId = trim((string) ($productIds[$i] ?? ''));
            $quantity = (int) ($quantities[$i] ?? 0);
            $unit = trim((string) ($units[$i] ?? ''));

            if ($quantity <= 0) {
                continue;
            }

            if (!$isInbound && $isNewLot) {
                throw new RuntimeException('Phiếu xuất không được phép tạo lô mới. Vui lòng chọn lô cần xuất.');
            }

            if ($isNewLot) {
                if ($lotId === '') {
                    $lotId = $this->lotModel->generateLotId($this->resolveLotPrefix($warehouseType));
                }

                if ($lotName === '' || $productId === '') {
                    throw new RuntimeException('Vui lòng nhập tên lô và chọn sản phẩm cho lô mới.');
                }

                if ($this->lotModel->find($lotId)) {
                    throw new RuntimeException('Mã lô ' . $lotId . ' đã tồn tại. Vui lòng chọn mã khác.');
                }

                if (!$this->validateProductCompatibility($productId, $warehouseType)) {
                    throw new RuntimeException('Sản phẩm không phù hợp với loại kho đã chọn.');
                }

                $unit = $unit !== '' ? $unit : $this->resolveProductUnit($productId);

                $newLots[] = [
                    'IdLo' => $lotId,
                    'TenLo' => $lotName,
                    'SoLuong' => $isConfirmed ? $quantity : 0,
                    'NgayTao' => date('Y-m-d H:i:s'),
                    'LoaiLo' => $this->resolveLotTypeLabel($warehouseType),
                    'IdSanPham' => $productId,
                    'IdKho' => $warehouse['IdKho'],
                ];
            } else {
                $lotData = $this->lotModel->findWithWarehouse($lotId);
                if (!$lotData) {
                    throw new RuntimeException('Không tìm thấy lô ' . $lotId . ' để lập phiếu.');
                }

                if (($lotData['IdKho'] ?? null) !== $warehouse['IdKho']) {
                    throw new RuntimeException('Lô ' . $lotId . ' không thuộc kho được chọn.');
                }

                $productId = $productId ?: ($lotData['IdSanPham'] ?? '');
                $unit = $unit !== '' ? $unit : ($lotData['DonVi'] ?? '');

                if (!$isInbound && $quantity > (int) ($lotData['SoLuong'] ?? 0)) {
                    throw new RuntimeException('Số lượng xuất vượt quá tồn lô ' . $lotId . '.');
                }

                if (!$this->validateProductCompatibility($productId, $warehouseType)) {
                    throw new RuntimeException('Mặt hàng của lô ' . $lotId . ' không phù hợp với loại kho.');
                }
            }

            $details[] = [
                'IdTTCTPhieu' => $this->generateDetailId($lotId),
                'DonViTinh' => $unit !== '' ? $unit : null,
                'SoLuong' => $quantity,
                'ThucNhan' => $isConfirmed ? $quantity : 0,
                'IdPhieu' => $documentId,
                'IdLo' => $lotId,
                'is_new_lot' => $isNewLot,
            ];
        }

        return [
            'details' => $details,
            'new_lots' => $newLots,
        ];
    }

    private function handleQuickEntry(string $documentType, string $redirectTo, array $document, array $warehouse, array &$detailPayloads, array &$newLots, bool $isConfirmed = false): void
    {
        $quickEntryPayload = $this->prepareQuickEntryPayload($_POST, $document, $warehouse, $isConfirmed);

        $isOutbound = $this->isOutboundDocument($documentType);

        if ($quickEntryPayload === null) {
            $message = $isOutbound
                ? 'Vui lòng chọn lô cần xuất và số lượng hợp lệ trước khi lưu phiếu.'
                : 'Vui lòng nhập đầy đủ thông tin lô/nguyên liệu trước khi xác nhận.';
            $this->setFlash('danger', $message);
            $this->redirect($redirectTo);
            return;
        }

        if ($isOutbound) {
            $detailPayloads[] = $quickEntryPayload['detail'];
        } else {
            $detailPayloads[] = $quickEntryPayload['detail'];
            $newLots[] = $quickEntryPayload['lot'];
        }
    }

    private function findWarehouse(string $warehouseId): ?array
    {
        if (isset($this->warehouseCache[$warehouseId])) {
            return $this->warehouseCache[$warehouseId];
        }

        $warehouse = $this->warehouseModel->findWithSupervisor($warehouseId) ?? $this->warehouseModel->find($warehouseId);
        if ($warehouse && !$this->isWarehouseAccessible($warehouseId)) {
            return null;
        }

        $this->warehouseCache[$warehouseId] = $warehouse;

        return $warehouse;
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

    private function isConfirmedData(array $data): bool
    {
        $value = $data['NgayXN'] ?? null;
        if (is_string($value)) {
            $value = trim($value);
        }

        return !empty($value);
    }

    private function applyStockImpact(string $documentType, array $details, bool $isCreation, ?string $documentId = null, ?string $warehouseId = null): void
    {
        $isInbound = $this->isInboundDocument($documentType);
        $direction = $isInbound ? 1 : -1;
        $direction = $isCreation ? $direction : -$direction;

        foreach ($details as $detail) {
            $lotId = $detail['IdLo'] ?? null;
            $quantity = array_key_exists('ThucNhan', $detail)
                ? (int) ($detail['ThucNhan'] ?? 0)
                : (int) ($detail['SoLuong'] ?? 0);
            $isNewLot = (bool) ($detail['is_new_lot'] ?? false);

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

            if ($isCreation && !$isNewLot && !$this->lotModel->hasOtherDocuments($lotId, $documentId)) {
                $isNewLot = true;
            }

            $quantityDelta = $direction * $quantity;
            $skipLotQuantityUpdate = $isInbound && $isCreation && $isNewLot && ($lot['SoLuong'] ?? 0) > 0;

            if (!$skipLotQuantityUpdate) {
                $currentQuantity = (int) ($lot['SoLuong'] ?? 0);
                $newQuantity = $currentQuantity + $quantityDelta;

                if ($newQuantity < 0) {
                    throw new RuntimeException('Số lượng lô không đủ để thực hiện phiếu hoặc hoàn tác.');
                }

                $this->lotModel->adjustLotQuantity($lotId, $quantityDelta, false);
            }

            $lotDelta = 0;

            if ($isInbound && $isCreation && $isNewLot) {
                $lotDelta = 1;
            }

            $this->warehouseModel->adjustWarehouseStock($lot['IdKho'], $quantityDelta, $lotDelta);

            if ($isInbound && !$isCreation && !$this->lotModel->hasOtherDocuments($lotId, $documentId)) {
                $updatedLot = $this->lotModel->findWithWarehouse($lotId);
                if (($updatedLot['SoLuong'] ?? 0) <= 0) {
                    $this->lotModel->deleteLot($lotId);
                    $this->warehouseModel->adjustWarehouseStock($lot['IdKho'], 0, -1);
                }
            }
        }
    }

    private function applyDestinationImpact(array $destination, array $details): void
    {
        $destinationId = $destination['IdKho'] ?? null;
        if (!$destinationId) {
            return;
        }

        $warehouseType = $this->resolveWarehouseTypeKey($destination['TenLoaiKho'] ?? '');

        foreach ($details as $detail) {
            $received = array_key_exists('ThucNhan', $detail)
                ? (int) ($detail['ThucNhan'] ?? 0)
                : (int) ($detail['SoLuong'] ?? 0);

            if ($received <= 0) {
                continue;
            }

            $sourceLot = $this->lotModel->findWithWarehouse($detail['IdLo']);
            if (!$sourceLot) {
                throw new RuntimeException('Không tìm thấy lô để cập nhật kho nhận.');
            }

            if (!$this->validateProductCompatibility($sourceLot['IdSanPham'] ?? null, $warehouseType)) {
                throw new RuntimeException('Mặt hàng không phù hợp với kho nhận.');
            }

            $newLotId = $this->lotModel->generateLotId($this->resolveLotPrefix($warehouseType));
            $lotName = $sourceLot['TenLo'] ?? $newLotId;

            $this->lotModel->createLot([
                'IdLo' => $newLotId,
                'TenLo' => $lotName,
                'SoLuong' => $received,
                'NgayTao' => date('Y-m-d H:i:s'),
                'LoaiLo' => $this->resolveLotTypeLabel($warehouseType),
                'IdSanPham' => $sourceLot['IdSanPham'] ?? null,
                'IdKho' => $destinationId,
            ]);

            $this->warehouseModel->adjustWarehouseStock($destinationId, $received, 1);
        }
    }

    private function canConfirmDocument(array $document): bool
    {
        $currentUser = $this->currentUser();
        if (!$currentUser) {
            return false;
        }

        $role = $this->resolveAccessRole($currentUser);
        if (in_array($role, ['VT_ADMIN', 'VT_BAN_GIAM_DOC'], true)) {
            return true;
        }

        $approverId = $document['NHAN_VIENIdNhanVien2'] ?? null;
        $employeeId = $currentUser['IdNhanVien'] ?? null;

        return $approverId && $employeeId && $approverId === $employeeId;
    }

    private function getAccessibleWarehouseIds(): ?array
    {
        if ($this->accessibleWarehouseIds !== null) {
            return $this->accessibleWarehouseIds;
        }

        $user = $this->currentUser();
        if (!$user) {
            return [];
        }

        $role = $this->resolveAccessRole($user);
        if (in_array($role, ['VT_ADMIN', 'VT_BAN_GIAM_DOC'], true)) {
            $this->accessibleWarehouseIds = null;
            return $this->accessibleWarehouseIds;
        }

        if ($role === 'VT_NHANVIEN_KHO') {
            $this->accessibleWarehouseIds = $this->resolveWarehouseIdsByWorkshop($user);
            return $this->accessibleWarehouseIds;
        }

        $this->accessibleWarehouseIds = $this->resolveWarehouseIdsByWorkshop($user);
        return $this->accessibleWarehouseIds;
    }

    private function isWarehouseAccessible(?string $warehouseId): bool
    {
        if ($warehouseId === null || $warehouseId === '') {
            return false;
        }

        $accessible = $this->getAccessibleWarehouseIds();

        return $accessible === null || in_array($warehouseId, $accessible, true);
    }

    private function filterWarehousesByAccess(array $warehouses): array
    {
        $accessible = $this->getAccessibleWarehouseIds();
        if ($accessible === null) {
            return $warehouses;
        }

        return array_values(array_filter($warehouses, function (array $warehouse) use ($accessible): bool {
            return in_array($warehouse['IdKho'] ?? null, $accessible, true);
        }));
    }

    private function resolveWarehouseIdsByWorkshop(array $user): ?array
    {
        $employeeId = $user['IdNhanVien'] ?? null;
        if (!$employeeId) {
            return null;
        }

        $workshopIds = $this->employeeModel->getWorkshopIdsForEmployee($employeeId);
        if (empty($workshopIds)) {
            return [];
        }

        $managedIds = $this->assignmentModel->getWorkshopsManagedBy($employeeId);
        if ($this->hasStorageWorkshop($managedIds)) {
            return null;
        }

        return $this->warehouseModel->getWarehouseIdsByWorkshops($workshopIds);
    }

    private function hasStorageWorkshop(array $workshopIds): bool
    {
        foreach ($workshopIds as $workshopId) {
            $workshop = $this->workshopModel->find($workshopId);
            $type = mb_strtolower((string) ($workshop['LoaiXuong'] ?? ''));
            if (str_contains($type, 'lưu trữ')) {
                return true;
            }
        }

        return false;
    }

    private function getWorkshopManagerRoles(): array
    {
        return [
            'VT_TRUONG_XUONG_KIEM_DINH',
            'VT_TRUONG_XUONG_LAP_RAP_DONG_GOI',
            'VT_TRUONG_XUONG_SAN_XUAT',
            'VT_TRUONG_XUONG_LUU_TRU',
        ];
    }

    private function classifyDocumentType(string $documentType): array
    {
        $normalized = $this->normalizeText($documentType);
        $isInbound = str_contains($normalized, 'nhập');

        $category = 'Khác';
        if (str_contains($normalized, 'nguyên liệu') || str_contains($normalized, 'nguyen lieu')) {
            $category = 'Nguyên liệu';
        } elseif (str_contains($normalized, 'thành phẩm') || str_contains($normalized, 'thanh pham')) {
            $category = 'Thành phẩm';
        } elseif (str_contains($normalized, 'xử lý') || str_contains($normalized, 'lỗi') || str_contains($normalized, 'loi')) {
            $category = 'Hàng lỗi';
        }

        return [
            'direction' => $isInbound ? 'inbound' : 'outbound',
            'direction_label' => $isInbound ? 'Phiếu nhập' : 'Phiếu xuất',
            'category' => $category,
            'title' => strtoupper($isInbound ? 'PHIẾU NHẬP' : 'PHIẾU XUẤT') . ' ' . strtoupper($category),
            'badge_class' => $isInbound ? 'badge-soft-success' : 'badge-soft-danger',
        ];
    }

    private function resolvePresetDocumentType(?string $direction, ?string $category): ?string
    {
        if ($direction === null || $category === null) {
            return null;
        }

        $normalizedDirection = $this->normalizeText($direction);
        $normalizedCategory = $this->normalizeText($category);

        $map = [
            'material' => [
                'inbound' => 'Phiếu nhập nguyên liệu',
                'outbound' => 'Phiếu xuất nguyên liệu',
            ],
            'finished' => [
                'inbound' => 'Phiếu nhập thành phẩm',
                'outbound' => 'Phiếu xuất thành phẩm',
            ],
            'quality' => [
                'inbound' => 'Phiếu nhập xử lý lỗi',
                'outbound' => 'Phiếu xuất xử lý lỗi',
            ],
        ];

        $directionKey = str_contains($normalizedDirection, 'out') || str_contains($normalizedDirection, 'xuất') ? 'outbound' : (str_contains($normalizedDirection, 'in') || str_contains($normalizedDirection, 'nhập') ? 'inbound' : null);

        if ($directionKey === null) {
            return null;
        }

        foreach ($map as $key => $definitions) {
            if ($normalizedCategory === $key || str_contains($normalizedCategory, $key)) {
                return $definitions[$directionKey] ?? null;
            }
        }

        return null;
    }

    private function resolveCreator(?string $requestedCreator): ?string
    {
        $user = $this->currentUser();
        $role = $user ? $this->resolveAccessRole($user) : null;
        $employeeId = $user['IdNhanVien'] ?? null;

        return $employeeId ?: $requestedCreator;
    }

    private function validateApprovalSeparation(?string $creatorId, ?string $approverId): bool
    {
        if (!$creatorId || !$approverId) {
            return true;
        }

        return $creatorId !== $approverId;
    }
}
