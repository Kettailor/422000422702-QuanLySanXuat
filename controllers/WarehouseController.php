<?php

class WarehouseController extends Controller
{
    private Warehouse $warehouseModel;
    private InventoryLot $lotModel;
    private InventorySheet $sheetModel;
    private Workshop $workshopModel;
    private Employee $employeeModel;
    private Product $productModel;
    private ?array $visibleWarehouseIds = null;
    private const WAREHOUSE_TYPE_KEYWORDS = [
        'finished' => ['thành phẩm', 'thanh pham'],
        'quality' => ['lỗi', 'xu ly', 'xử lý'],
    ];

    public function __construct()
    {
        $this->authorize(array_merge(
            ['VT_NHANVIEN_KHO', 'VT_KHO_TRUONG', 'VT_BAN_GIAM_DOC', 'VT_ADMIN'],
            $this->getWorkshopManagerRoles(),
        ));
        $this->warehouseModel = new Warehouse();
        $this->lotModel = new InventoryLot();
        $this->sheetModel = new InventorySheet();
        $this->workshopModel = new Workshop();
        $this->employeeModel = new Employee();
        $this->productModel = new Product();
    }

    public function index(): void
    {
        $visibleIds = $this->getVisibleWarehouseIds();
        $warehouses = $this->warehouseModel->getWithSupervisor($visibleIds);
        $summary = $this->warehouseModel->getWarehouseSummary($warehouses);
        $documents = $this->sheetModel->getDocuments(null, 200, $visibleIds);
        $documentGroups = $this->buildDocumentGroups($documents);
        $warehouseGroups = $this->warehouseModel->groupWarehousesByType($warehouses, $summary['by_type'] ?? []);
        $employees = $this->employeeModel->getActiveEmployees();
        $entryForms = $this->buildWarehouseEntryForms($warehouseGroups);
        $products = $this->getProductOptionsByType();
        $lotOptionsByType = $this->getLotOptionsByType($warehouses);
        $this->render('warehouse/index', [
            'title' => 'Kho & tồn kho',
            'warehouses' => $warehouses,
            'summary' => $summary,
            'documentGroups' => $documentGroups,
            'warehouseGroups' => $warehouseGroups,
            'warehouseEntryForms' => $entryForms,
            'outboundDocumentTypes' => $this->getOutboundDocumentTypes(),
            'employees' => $employees,
            'productOptionsByType' => $products,
            'lotOptionsByType' => $lotOptionsByType,
        ]);
    }

    public function read(): void
    {
        $id = $_GET['id'] ?? null;
        if (!$this->isWarehouseAccessible($id)) {
            $this->setFlash('danger', 'Bạn không có quyền xem kho này.');
            $this->redirect('?controller=warehouse&action=index');
        }

        $warehouse = $id ? $this->warehouseModel->findWithSupervisor($id) : null;
        $lots = $id ? $this->lotModel->getLotsByWarehouse($id) : [];
        $this->render('warehouse/read', [
            'title' => 'Chi tiết kho',
            'warehouse' => $warehouse,
            'lots' => $lots,
        ]);
    }

    public function create(): void
    {
        if ($this->isWarehouseStaffRestricted()) {
            $this->setFlash('danger', 'Bạn chỉ được xem kho được phân công. Vui lòng liên hệ quản lý để tạo kho mới.');
            $this->redirect('?controller=warehouse&action=index');
        }

        $options = $this->warehouseModel->getFormOptions();
        $workshops = $this->getAccessibleWorkshops();
        $this->render('warehouse/create', [
            'title' => 'Thêm kho mới',
            'managers' => $options['managers'],
            'statuses' => $options['statuses'],
            'types' => $options['types'],
            'workshops' => $workshops,
            'employees' => $this->getManagersByWorkshopRequest($workshops),
            'workshopEmployees' => $this->buildWorkshopEmployeeMap($workshops),
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=warehouse&action=index');
        }

        if (!$this->validateWarehouseInput($_POST)) {
            $this->setFlash('danger', 'Vui lòng nhập đầy đủ tên kho, xưởng phụ trách và nhân viên quản kho.');
            $this->redirect('?controller=warehouse&action=create');
        }
        $data = [
            'IdKho' => ($_POST['IdKho'] ?? '') ?: uniqid('KHO'),
            'TenKho' => $_POST['TenKho'] ?? null,
            'TenLoaiKho' => $_POST['TenLoaiKho'] ?? null,
            'DiaChi' => $_POST['DiaChi'] ?? null,
            'TongSLLo' => $_POST['TongSLLo'] ?? 0,
            'ThanhTien' => $_POST['ThanhTien'] ?? 0,
            'TrangThai' => $_POST['TrangThai'] ?? 'Đang sử dụng',
            'TongSL' => $_POST['TongSL'] ?? 0,
            'IdXuong' => $_POST['IdXuong'] ?? null,
            'NHAN_VIEN_KHO_IdNhanVien' => $_POST['NHAN_VIEN_KHO_IdNhanVien'] ?? null,
        ];

        try {
            if (!$this->canAccessWorkshop($data['IdXuong'])) {
                $this->setFlash('danger', 'Bạn chỉ có thể tạo kho trong xưởng được phân công.');
            } elseif (!$this->employeeModel->isEmployeeInWorkshop($data['NHAN_VIEN_KHO_IdNhanVien'], $data['IdXuong'])) {
                $this->setFlash('danger', 'Nhân viên quản kho phải thuộc xưởng đã chọn.');
            } elseif (empty($this->employeeModel->getActiveEmployeesByWorkshop($data['IdXuong'] ?? ''))) {
                $this->setFlash('danger', 'Xưởng chưa có nhân sự để phân công quản kho. Vui lòng cập nhật nhân sự trước.');
            } else {
                $this->warehouseModel->createWarehouse($data);
                $this->setFlash('success', 'Đã thêm kho mới.');
            }
        } catch (Throwable $e) {
            Logger::error('Lỗi khi thêm kho: ' . $e->getMessage());
            /* $this->setFlash('danger', 'Không thể thêm kho: ' . $e->getMessage()); */
            $this->setFlash('danger', 'Không thể thêm kho: Đã xảy ra lỗi, vui lòng kiểm tra log để biết thêm chi tiết.');
        }

        $this->redirect('?controller=warehouse&action=index');
    }

    public function edit(): void
    {
        $id = $_GET['id'] ?? null;
        if (!$this->isWarehouseAccessible($id)) {
            $this->setFlash('danger', 'Bạn không có quyền chỉnh sửa kho này.');
            $this->redirect('?controller=warehouse&action=index');
        }

        $warehouse = $id ? $this->warehouseModel->find($id) : null;
        $options = $this->warehouseModel->getFormOptions();
        $workshops = $this->getAccessibleWorkshops();
        $this->render('warehouse/edit', [
            'title' => 'Cập nhật kho',
            'warehouse' => $warehouse,
            'managers' => $options['managers'],
            'statuses' => $options['statuses'],
            'types' => $options['types'],
            'workshops' => $workshops,
            'employees' => $this->getManagersByWorkshopRequest($workshops, $warehouse['IdXuong'] ?? null),
            'workshopEmployees' => $this->buildWorkshopEmployeeMap($workshops),
        ]);
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=warehouse&action=index');
        }

        $id = $_POST['IdKho'];

        if (!$this->validateWarehouseInput($_POST)) {
            $this->setFlash('danger', 'Vui lòng nhập đầy đủ tên kho, xưởng phụ trách và nhân viên quản kho.');
            $this->redirect('?controller=warehouse&action=edit&id=' . urlencode($id));
        }
        $data = [
            'TenKho' => $_POST['TenKho'] ?? null,
            'TenLoaiKho' => $_POST['TenLoaiKho'] ?? null,
            'DiaChi' => $_POST['DiaChi'] ?? null,
            'TongSLLo' => $_POST['TongSLLo'] ?? 0,
            'ThanhTien' => $_POST['ThanhTien'] ?? 0,
            'TrangThai' => $_POST['TrangThai'] ?? 'Đang sử dụng',
            'TongSL' => $_POST['TongSL'] ?? 0,
            'IdXuong' => $_POST['IdXuong'] ?? null,
            'NHAN_VIEN_KHO_IdNhanVien' => $_POST['NHAN_VIEN_KHO_IdNhanVien'] ?? null,
        ];

        try {
            if (!$this->canAccessWorkshop($data['IdXuong'])) {
                $this->setFlash('danger', 'Bạn chỉ có thể cập nhật kho trong xưởng được phân công.');
            } elseif (!$this->employeeModel->isEmployeeInWorkshop($data['NHAN_VIEN_KHO_IdNhanVien'], $data['IdXuong'])) {
                $this->setFlash('danger', 'Nhân viên quản kho phải thuộc xưởng đã chọn.');
            } else {
                $this->warehouseModel->updateWarehouse($id, $data);
                $this->setFlash('success', 'Cập nhật kho thành công.');
            }
        } catch (Throwable $e) {
            Logger::error('Lỗi khi cập nhật kho ' . $id . ': ' . $e->getMessage());
            /* $this->setFlash('danger', 'Không thể cập nhật kho: ' . $e->getMessage()); */
            $this->setFlash('danger', 'Không thể cập nhật kho, vui lòng kiểm tra log để biết thêm chi tiết.');
        }

        $this->redirect('?controller=warehouse&action=index');
    }

    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=warehouse&action=index');
        }

        $id = $_POST['IdKho'] ?? null;

        if (!$id) {
            $this->setFlash('danger', 'Không xác định được kho cần xóa.');
            $this->redirect('?controller=warehouse&action=index');
        }

        if (!$this->isWarehouseAccessible($id)) {
            $this->setFlash('danger', 'Bạn không có quyền xóa kho này.');
            $this->redirect('?controller=warehouse&action=index');
        }

        try {
            $this->warehouseModel->delete($id);
            $this->setFlash('success', 'Đã xóa kho.');
            $this->redirect('?controller=warehouse&action=index');
        } catch (Throwable $e) {
            Logger::error('Lỗi khi xóa kho ' . $id . ': ' . $e->getMessage());
            /* $this->setFlash('danger', 'Không thể xóa kho: ' . $e->getMessage()); */
            $this->setFlash('danger', 'Không thể xóa kho, vui lòng kiểm tra log để biết thêm chi tiết.');
        }

    }

    private function buildWarehouseEntryForms(array $warehouseGroups): array
    {
        $definitions = [
            'material' => [
                'documentType' => 'Phiếu nhập nguyên liệu',
                'submitLabel' => 'Thêm nguyên liệu',
                'description' => 'Ghi nhận nguyên vật liệu về kho để phục vụ sản xuất.',
                'prefix' => 'PNNL',
                'ui' => [
                    'lot_code_label' => 'Mã lô nguyên liệu',
                    'lot_code_hint' => 'Mã lô được sinh theo định dạng riêng cho nguyên liệu.',
                    'lot_name_label' => 'Tên lô nguyên liệu',
                    'product_label' => 'Nguyên liệu/linh kiện',
                    'product_placeholder' => 'Chọn nguyên liệu cần nhập',
                    'unit_hint' => 'Đơn vị tính nguyên liệu sẽ tự động hiển thị.',
                    'quantity_label' => 'Số lượng nguyên liệu dự kiến',
                    'received_label' => 'Số lượng nguyên liệu thực nhận',
                ],
            ],
            'finished' => [
                'documentType' => 'Phiếu nhập thành phẩm',
                'submitLabel' => 'Thêm thành phẩm',
                'description' => 'Bổ sung thành phẩm hoàn thiện vào kho chuẩn bị xuất bán.',
                'prefix' => 'PNTP',
                'ui' => [
                    'lot_code_label' => 'Mã lô thành phẩm',
                    'lot_code_hint' => 'Mã lô áp dụng cho thành phẩm xuất kho.',
                    'lot_name_label' => 'Tên lô thành phẩm',
                    'product_label' => 'Thành phẩm',
                    'product_placeholder' => 'Chọn thành phẩm cần nhập kho',
                    'unit_hint' => 'Đơn vị thành phẩm sẽ tự động cập nhật.',
                    'quantity_label' => 'Số lượng thành phẩm dự kiến',
                    'received_label' => 'Số lượng thành phẩm thực nhận',
                ],
            ],
            'quality' => [
                'documentType' => 'Phiếu nhập xử lý lỗi',
                'submitLabel' => 'Nhận hàng lỗi',
                'description' => 'Tiếp nhận sản phẩm lỗi cần xử lý, phân loại.',
                'prefix' => 'PNXL',
                'ui' => [
                    'lot_code_label' => 'Mã lô xử lý lỗi',
                    'lot_code_hint' => 'Mã lô giúp theo dõi riêng các sản phẩm lỗi.',
                    'lot_name_label' => 'Tên lô xử lý lỗi',
                    'product_label' => 'Sản phẩm cần xử lý',
                    'product_placeholder' => 'Chọn sản phẩm cần xử lý lỗi',
                    'unit_hint' => 'Đơn vị sẽ tự động lấy theo sản phẩm đã chọn.',
                    'quantity_label' => 'Số lượng cần xử lý',
                    'received_label' => 'Số lượng tiếp nhận thực tế',
                ],
            ],
        ];

        $forms = [];

        foreach ($warehouseGroups as $key => $group) {
            if (!isset($definitions[$key])) {
                continue;
            }

            $definition = $definitions[$key];
            $forms[$key] = [
                'document_type' => $definition['documentType'],
                'document_id' => $this->sheetModel->generateDocumentId($definition['documentType']),
                'submit_label' => $definition['submitLabel'],
                'description' => $definition['description'],
                'modal_title' => $definition['submitLabel'] . ' vào ' . ($group['label'] ?? 'kho'),
                'prefix' => $definition['prefix'],
                'ui' => $definition['ui'] ?? [],
            ];
        }

        return $forms;
    }

    private function getProductOptionsByType(): array
    {
        $products = $this->productModel->all(300);

        $grouped = [
            'material' => [],
            'finished' => [],
            'quality' => [],
        ];

        foreach ($products as $product) {
            $option = [
                'id' => $product['IdSanPham'] ?? '',
                'name' => $product['TenSanPham'] ?? '',
                'unit' => $product['DonVi'] ?? '',
                'description' => $product['MoTa'] ?? '',
            ];

            $assignedTypes = $this->classifyProductForWarehouseTypes($product);

            foreach ($assignedTypes as $type) {
                $grouped[$type][] = $option;
            }
        }

        return $grouped;
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

    private function buildDocumentGroups(array $documents): array
    {
        $allDocuments = array_values($documents);
        $inboundDocuments = array_values(array_filter($documents, function (array $document): bool {
            return $this->documentTypeStartsWith($document['LoaiPhieu'] ?? null, 'Phiếu nhập');
        }));
        $outboundDocuments = array_values(array_filter($documents, function (array $document): bool {
            return $this->documentTypeStartsWith($document['LoaiPhieu'] ?? null, 'Phiếu xuất');
        }));

        $valuableDocuments = $allDocuments;
        usort($valuableDocuments, function (array $a, array $b): int {
            return ($b['TongTien'] ?? 0) <=> ($a['TongTien'] ?? 0);
        });
        $valuableCount = count($valuableDocuments);
        $valuableDocuments = array_slice($valuableDocuments, 0, 25);

        return [
            'all' => [
                'title' => 'Tất cả phiếu kho',
                'description' => 'Danh sách đầy đủ các phiếu nhập và xuất kho trong hệ thống.',
                'count' => count($allDocuments),
                'documents' => array_slice($allDocuments, 0, 50),
            ],
            'inbound' => [
                'title' => 'Phiếu nhập kho',
                'description' => 'Các phiếu nhập kho mới nhất giúp bổ sung hàng hóa vào kho.',
                'count' => count($inboundDocuments),
                'documents' => array_slice($inboundDocuments, 0, 50),
            ],
            'outbound' => [
                'title' => 'Phiếu xuất kho',
                'description' => 'Các phiếu xuất kho theo yêu cầu giao hàng hoặc điều chuyển.',
                'count' => count($outboundDocuments),
                'documents' => array_slice($outboundDocuments, 0, 50),
            ],
            'valuable' => [
                'title' => 'Phiếu giá trị cao',
                'description' => 'Top phiếu kho có giá trị lớn, ưu tiên kiểm tra và theo dõi.',
                'count' => $valuableCount,
                'documents' => $valuableDocuments,
            ],
        ];
    }

    private function validateWarehouseInput(array $data): bool
    {
        $requiredFields = ['TenKho', 'IdXuong', 'NHAN_VIEN_KHO_IdNhanVien'];

        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $data)) {
                return false;
            }

            $value = $data[$field];
            if (is_string($value)) {
                $value = trim($value);
            }

            if ($value === '' || $value === null) {
                return false;
            }
        }

        return true;
    }

    private function resolveLotPrefix(string $type): string
    {
        return match ($type) {
            'finished' => 'LOTP',
            'quality' => 'LOXL',
            default => 'LONL',
        };
    }

    private function getLotOptionsByType(array $warehouses): array
    {
        $visibleIds = $this->getVisibleWarehouseIds();
        $lots = $this->lotModel->getSelectableLots(400, $visibleIds);
        $warehouseTypeMap = [];
        foreach ($warehouses as $warehouse) {
            $typeKey = $this->resolveWarehouseTypeKey($warehouse['TenLoaiKho'] ?? '');
            $warehouseTypeMap[$warehouse['IdKho'] ?? ''] = $typeKey;
        }

        $grouped = [
            'material' => [],
            'finished' => [],
            'quality' => [],
        ];

        foreach ($lots as $lot) {
            $warehouseId = $lot['IdKho'] ?? '';
            $typeKey = $warehouseTypeMap[$warehouseId] ?? 'material';
            $grouped[$typeKey][] = [
                'id' => $lot['IdLo'] ?? '',
                'name' => $lot['TenLo'] ?? ($lot['IdLo'] ?? ''),
                'quantity' => (int) ($lot['SoLuong'] ?? 0),
                'warehouse_id' => $warehouseId,
                'warehouse_name' => $lot['TenKho'] ?? '',
                'product_id' => $lot['IdSanPham'] ?? '',
                'product_name' => $lot['TenSanPham'] ?? '',
                'unit' => $lot['DonVi'] ?? '',
            ];
        }

        return $grouped;
    }

    private function resolveWarehouseTypeKey(string $type): string
    {
        $normalized = $this->normalizeText($type);

        foreach (self::WAREHOUSE_TYPE_KEYWORDS['finished'] as $keyword) {
            if (str_contains($normalized, $keyword)) {
                return 'finished';
            }
        }

        foreach (self::WAREHOUSE_TYPE_KEYWORDS['quality'] as $keyword) {
            if (str_contains($normalized, $keyword)) {
                return 'quality';
            }
        }

        return 'material';
    }

    private function documentTypeStartsWith(?string $type, string $needle): bool
    {
        if ($type === null || $type === '') {
            return false;
        }

        if (function_exists('mb_stripos')) {
            return mb_stripos($type, $needle, 0, 'UTF-8') === 0;
        }

        return stripos($type, $needle) === 0;
    }

    private function getVisibleWarehouseIds(): ?array
    {
        if ($this->visibleWarehouseIds !== null) {
            return $this->visibleWarehouseIds;
        }

        $user = $this->currentUser();
        if (!$user) {
            return [];
        }

        $role = $this->resolveAccessRole($user);
        if (in_array($role, ['VT_ADMIN', 'VT_BAN_GIAM_DOC'], true)) {
            $this->visibleWarehouseIds = null;
            return $this->visibleWarehouseIds;
        }

        if ($role === 'VT_NHANVIEN_KHO') {
            $employeeId = $user['IdNhanVien'] ?? null;
            $this->visibleWarehouseIds = $employeeId ? $this->warehouseModel->getWarehouseIdsBySupervisor($employeeId) : [];
            return $this->visibleWarehouseIds;
        }

        $this->visibleWarehouseIds = $this->resolveWarehouseIdsByWorkshop($user);

        return $this->visibleWarehouseIds;
    }

    private function getAccessibleWorkshops(): array
    {
        $user = $this->currentUser();
        if (!$user) {
            return [];
        }

        $role = $this->resolveAccessRole($user);
        if (in_array($role, ['VT_ADMIN', 'VT_BAN_GIAM_DOC', 'VT_KHO_TRUONG'], true)) {
            return $this->workshopModel->all(200);
        }

        if (in_array($role, $this->getWorkshopManagerRoles(), true)) {
            $employeeId = $user['IdNhanVien'] ?? null;
            if (!$employeeId) {
                return [];
            }
            $workshopIds = $this->employeeModel->getWorkshopIdsForEmployee($employeeId);
            return $this->workshopModel->findByIds($workshopIds);
        }

        return [];
    }

    private function canAccessWorkshop(?string $workshopId): bool
    {
        if ($workshopId === null || $workshopId === '') {
            return false;
        }

        $workshops = $this->getAccessibleWorkshops();
        if (empty($workshops)) {
            return false;
        }

        foreach ($workshops as $workshop) {
            if (($workshop['IdXuong'] ?? null) === $workshopId) {
                return true;
            }
        }

        return false;
    }

    private function isWarehouseAccessible(?string $warehouseId): bool
    {
        if ($warehouseId === null || $warehouseId === '') {
            return false;
        }

        $visibleIds = $this->getVisibleWarehouseIds();

        return $visibleIds === null || in_array($warehouseId, $visibleIds, true);
    }

    private function isWarehouseStaffRestricted(): bool
    {
        $user = $this->currentUser();
        $role = $user ? $this->resolveAccessRole($user) : null;

        return $role === 'VT_NHANVIEN_KHO';
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

        if ($this->hasStorageWorkshop($workshopIds)) {
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

    private function getManagersByWorkshopRequest(array $workshops, ?string $currentWorkshopId = null): array
    {
        $selectedWorkshop = $_POST['IdXuong'] ?? ($_GET['IdXuong'] ?? $currentWorkshopId);

        if ($selectedWorkshop) {
            return $this->employeeModel->getActiveEmployeesByWorkshop($selectedWorkshop);
        }

        $workshopIds = array_column($workshops, 'IdXuong');

        return $this->employeeModel->getActiveEmployeesByWorkshops($workshopIds);
    }

    private function buildWorkshopEmployeeMap(array $workshops): array
    {
        $map = [];
        foreach ($workshops as $workshop) {
            $workshopId = $workshop['IdXuong'] ?? null;
            if (!$workshopId) {
                continue;
            }

            $map[$workshopId] = $this->employeeModel->getActiveEmployeesByWorkshop($workshopId);
        }

        return $map;
    }

    private function getOutboundDocumentTypes(): array
    {
        return [
            'material' => 'Phiếu xuất nguyên liệu',
            'finished' => 'Phiếu xuất thành phẩm',
            'quality' => 'Phiếu xuất xử lý lỗi',
        ];
    }
}
