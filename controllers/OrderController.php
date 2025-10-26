<?php

class OrderController extends Controller
{
    private Order $orderModel;
    private Customer $customerModel;
    private Product $productModel;
    private OrderDetail $orderDetailModel;
    private ProductionPlan $planModel;
    private Bill $billModel;
    private ProductConfiguration $configurationModel;
    private ProductBom $bomModel;
    private ProductComponent $componentModel;
    private ProductComponentMaterial $componentMaterialModel;

    private array $orderStatuses = ['Mới tạo', 'Chờ duyệt', 'Đang xử lý', 'Chờ giao', 'Hoàn thành', 'Đã hủy'];
    private array $planStatuses = ['Mới tạo', 'Đang lập kế hoạch', 'Chuẩn bị nguyên liệu', 'Đang triển khai', 'Tạm dừng', 'Hoàn thành'];
    private array $billStatuses = ['Chưa thanh toán', 'Đang đối soát', 'Đã xuất hóa đơn', 'Đã thanh toán', 'Đã hủy'];

    public function __construct()
    {
        $this->authorize(['VT_KINH_DOANH', 'VT_BAN_GIAM_DOC']);
        $this->orderModel = new Order();
        $this->customerModel = new Customer();
        $this->productModel = new Product();
        $this->orderDetailModel = new OrderDetail();
        $this->planModel = new ProductionPlan();
        $this->billModel = new Bill();
        $this->configurationModel = new ProductConfiguration();
        $this->bomModel = new ProductBom();
        $this->componentModel = new ProductComponent();
        $this->componentMaterialModel = new ProductComponentMaterial();
    }

    public function index(): void
    {
        $orders = $this->orderModel->getOrdersWithCustomer();
        $this->render('order/index', [
            'title' => 'Quản lý đơn hàng',
            'orders' => $orders,
        ]);
    }

    public function create(): void
    {
        $customers = $this->customerModel->all(100);
        $products = $this->productModel->all(200);
        $configurations = $this->configurationModel->all(500);
        $bomSummaries = $this->buildBomSummaries();
        $this->render('order/create', [
            'title' => 'Tạo đơn hàng mới',
            'customers' => $customers,
            'products' => $products,
            'configurations' => $configurations,
            'boms' => $bomSummaries,
            'orderStatuses' => $this->orderStatuses,
            'planStatuses' => $this->planStatuses,
            'billStatuses' => $this->billStatuses,
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=order&action=index');
        }

        $orderId = ($_POST['IdDonHang'] ?? '') ?: uniqid('DH');
        $detailsInput = $_POST['details'] ?? [];

        try {
            $customerId = $this->resolveCustomer($_POST);
        } catch (InvalidArgumentException $exception) {
            $this->setFlash('danger', $exception->getMessage());
            $this->redirect('?controller=order&action=create');
            return;
        }

        try {
            $db = Database::getInstance()->getConnection();
            $db->beginTransaction();

            $preparedDetails = $this->prepareOrderDetails($orderId, $detailsInput);
            $totalAmount = array_reduce($preparedDetails, static fn($carry, $detail) => $carry + ($detail['ThanhTien'] ?? 0), 0.0);

            $data = [
                'IdDonHang' => $orderId,
                'YeuCau' => $_POST['YeuCau'] ?? null,
                'TongTien' => $totalAmount,
                'NgayLap' => $_POST['NgayLap'] ?? date('Y-m-d'),
                'TrangThai' => $_POST['TrangThai'] ?? $this->orderStatuses[0],
                'IdKhachHang' => $customerId,
            ];

            $this->orderModel->create($data);

            foreach ($preparedDetails as $detail) {
                $this->orderDetailModel->create($detail);
            }

            $db->commit();
            $this->setFlash('success', 'Tạo đơn hàng thành công.');
        } catch (Throwable $e) {
            if (isset($db) && $db->inTransaction()) {
                $db->rollBack();
            }
            $this->setFlash('danger', 'Không thể tạo đơn hàng: ' . $e->getMessage());
        }

        $this->redirect('?controller=order&action=index');
    }

    public function edit(): void
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('?controller=order&action=index');
        }

        $order = $this->orderModel->find($id);
        $customers = $this->customerModel->all(100);
        $products = $this->productModel->all(200);
        $configurations = $this->configurationModel->all(500);
        $orderDetails = $id ? $this->orderDetailModel->getByOrder($id) : [];
        $bomSummaries = $this->buildBomSummaries();

        $this->render('order/edit', [
            'title' => 'Chỉnh sửa đơn hàng',
            'order' => $order,
            'customers' => $customers,
            'products' => $products,
            'configurations' => $configurations,
            'orderDetails' => $orderDetails,
            'boms' => $bomSummaries,
            'orderStatuses' => $this->orderStatuses,
            'planStatuses' => $this->planStatuses,
            'billStatuses' => $this->billStatuses,
        ]);
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=order&action=index');
        }

        $id = $_POST['IdDonHang'];
        $detailsInput = $_POST['details'] ?? [];

        try {
            $customerId = $this->resolveCustomer($_POST);
        } catch (InvalidArgumentException $exception) {
            $this->setFlash('danger', $exception->getMessage());
            $this->redirect('?controller=order&action=edit&id=' . urlencode($id));
            return;
        }

        try {
            $db = Database::getInstance()->getConnection();
            $db->beginTransaction();

            $preparedDetails = $this->prepareOrderDetails($id, $detailsInput);
            $totalAmount = array_reduce($preparedDetails, static fn($carry, $detail) => $carry + ($detail['ThanhTien'] ?? 0), 0.0);

            $data = [
                'YeuCau' => $_POST['YeuCau'] ?? null,
                'TongTien' => $totalAmount,
                'NgayLap' => $_POST['NgayLap'] ?? date('Y-m-d'),
                'TrangThai' => $_POST['TrangThai'] ?? $this->orderStatuses[0],
                'IdKhachHang' => $customerId,
            ];

            $this->orderModel->update($id, $data);
            $this->orderDetailModel->deleteByOrder($id);

            foreach ($preparedDetails as $detail) {
                $this->orderDetailModel->create($detail);
            }

            $db->commit();
            $this->setFlash('success', 'Cập nhật đơn hàng thành công.');
        } catch (Throwable $e) {
            if (isset($db) && $db->inTransaction()) {
                $db->rollBack();
            }
            $this->setFlash('danger', 'Không thể cập nhật đơn hàng: ' . $e->getMessage());
        }

        $this->redirect('?controller=order&action=index');
    }

    public function delete(): void
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                $this->orderModel->delete($id);
                $this->setFlash('success', 'Đã xóa đơn hàng.');
            } catch (Throwable $e) {
                $this->setFlash('danger', 'Không thể xóa đơn hàng: ' . $e->getMessage());
            }
        }

        $this->redirect('?controller=order&action=index');
    }

    public function read(): void
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('?controller=order&action=index');
        }

        $order = $this->orderModel->find($id);
        $customer = $order ? $this->customerModel->find($order['IdKhachHang']) : null;
        $orderDetails = $id ? $this->orderDetailModel->getByOrder($id) : [];
        $plans = $id ? $this->planModel->getByOrder($id) : [];
        $bills = $id ? $this->billModel->getByOrder($id) : [];

        $this->render('order/read', [
            'title' => 'Chi tiết đơn hàng',
            'order' => $order,
            'customer' => $customer,
            'orderDetails' => $orderDetails,
            'plans' => $plans,
            'bills' => $bills,
            'orderStatuses' => $this->orderStatuses,
            'planStatuses' => $this->planStatuses,
            'billStatuses' => $this->billStatuses,
        ]);
    }

    public function updateStatuses(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=order&action=index');
        }

        $orderId = $_POST['order_id'] ?? null;
        if (!$orderId) {
            $this->redirect('?controller=order&action=index');
        }

        $orderStatus = $_POST['order_status'] ?? null;
        $planStatuses = $_POST['plan_statuses'] ?? [];
        $billStatuses = $_POST['bill_statuses'] ?? [];

        try {
            $db = Database::getInstance()->getConnection();
            $db->beginTransaction();

            if ($orderStatus !== null && $orderStatus !== '') {
                $this->orderModel->update($orderId, ['TrangThai' => $orderStatus]);
            }

            foreach ($planStatuses as $planId => $status) {
                if ($status === '' || !$planId) {
                    continue;
                }
                $this->planModel->update($planId, ['TrangThai' => $status]);
            }

            foreach ($billStatuses as $billId => $status) {
                if ($status === '' || !$billId) {
                    continue;
                }
                $this->billModel->update($billId, ['TrangThai' => $status]);
            }

            $db->commit();
            $this->setFlash('success', 'Cập nhật trạng thái thành công.');
        } catch (Throwable $e) {
            if (isset($db) && $db->inTransaction()) {
                $db->rollBack();
            }
            $this->setFlash('danger', 'Không thể cập nhật trạng thái: ' . $e->getMessage());
        }

        $this->redirect('?controller=order&action=read&id=' . urlencode($orderId));
    }

    private function buildBomSummaries(): array
    {
        $summaries = [];
        $boms = $this->bomModel->all(500);

        foreach ($boms as $bom) {
            $bomId = $bom['IdBOM'] ?? null;
            if (!$bomId) {
                continue;
            }

            $steps = $this->componentModel->getByBom($bomId);
            $stepSummaries = [];

            foreach ($steps as $step) {
                $materials = $this->componentMaterialModel->getMaterialsForComponent($step['IdCongDoan']);
                $stepSummaries[] = [
                    'id' => $step['IdCongDoan'],
                    'name' => $step['TenCongDoan'],
                    'unit' => $step['DonVi'],
                    'quantity_per_unit' => $step['TyLeSoLuong'],
                    'workshop' => $step['IdXuong'],
                    'logistics_key' => $step['LogisticsKey'],
                    'logistics_label' => $step['LogisticsLabel'],
                    'include_request' => (bool) ($step['IncludeYeuCau'] ?? false),
                    'order' => (int) ($step['ThuTu'] ?? 0),
                    'materials' => $materials,
                ];
            }

            $summaries[$bomId] = [
                'id' => $bomId,
                'product_id' => $bom['IdSanPham'] ?? null,
                'name' => $bom['TenBOM'] ?? $bomId,
                'description' => $bom['MoTa'] ?? null,
                'steps' => $stepSummaries,
            ];
        }

        return $summaries;
    }

    private function prepareOrderDetails(string $orderId, array $detailsInput): array
    {
        $prepared = [];

        foreach ($detailsInput as $detail) {
            if (!is_array($detail)) {
                continue;
            }

            $productId = $detail['product_id'] ?? null;
            if (!$productId) {
                continue;
            }

            $configurationMode = $detail['configuration_mode'] ?? 'existing';
            $configurationId = $detail['configuration_id'] ?? null;

            if ($configurationMode === 'new') {
                $configurationName = trim($detail['new_configuration_name'] ?? '');
                if ($configurationName === '') {
                    throw new InvalidArgumentException('Tên cấu hình mới không được để trống.');
                }

                $layout = trim($detail['new_configuration_layout'] ?? '') ?: null;
                $switchType = trim($detail['new_configuration_switch'] ?? '') ?: null;
                $caseType = trim($detail['new_configuration_case'] ?? '') ?: null;
                $foam = trim($detail['new_configuration_foam'] ?? '') ?: null;
                $bomTemplate = trim($detail['new_configuration_bom_template'] ?? '') ?: null;

                if ($bomTemplate) {
                    $templateBom = $this->bomModel->find($bomTemplate);
                    if (!$templateBom) {
                        throw new InvalidArgumentException('Preset BOM không tồn tại.');
                    }
                    if (($templateBom['IdSanPham'] ?? null) !== $productId) {
                        throw new InvalidArgumentException('Preset BOM không thuộc sản phẩm đã chọn.');
                    }
                }

                $configurationId = uniqid('CFG');
                $bomId = uniqid('BOM');
                $bomName = sprintf('%s - BOM', $configurationName);
                $bomDescription = $detail['new_configuration_description'] ?? null;

                $this->bomModel->create([
                    'IdBOM' => $bomId,
                    'TenBOM' => $bomName,
                    'MoTa' => $bomDescription ?: sprintf('BOM tự tạo cho %s', $configurationName),
                    'IdSanPham' => $productId,
                ]);

                $this->cloneBomStructure($productId, $bomId, $bomTemplate);

                $this->configurationModel->create([
                    'IdCauHinh' => $configurationId,
                    'TenCauHinh' => $configurationName,
                    'MoTa' => $detail['new_configuration_description'] ?? null,
                    'GiaBan' => (float) ($detail['new_configuration_price'] ?? 0),
                    'IdSanPham' => $productId,
                    'IdBOM' => $bomId,
                    'Layout' => $layout,
                    'SwitchType' => $switchType,
                    'CaseType' => $caseType,
                    'Foam' => $foam,
                ]);
            } else {
                if (!$configurationId) {
                    throw new InvalidArgumentException('Vui lòng chọn cấu hình sản phẩm.');
                }

                $configuration = $this->configurationModel->find($configurationId);
                if (!$configuration) {
                    throw new InvalidArgumentException('Cấu hình sản phẩm không tồn tại.');
                }

                if (($configuration['IdSanPham'] ?? null) !== $productId) {
                    throw new InvalidArgumentException('Cấu hình không thuộc sản phẩm đã chọn.');
                }

                $bomId = $configuration['IdBOM'] ?? null;
                if (!$bomId) {
                    throw new InvalidArgumentException('Cấu hình chưa được gắn với BOM.');
                }

                if (!$this->bomModel->find($bomId)) {
                    throw new InvalidArgumentException('BOM của cấu hình đã chọn không tồn tại.');
                }
            }

            $quantity = (int) ($detail['quantity'] ?? 0);
            if ($quantity <= 0) {
                continue;
            }

            $unitPrice = (float) ($detail['unit_price'] ?? 0);
            $vatInput = (float) ($detail['vat'] ?? 0);
            $vat = $vatInput > 1 ? $vatInput / 100 : $vatInput;
            $total = $quantity * $unitPrice * (1 + $vat);

            $delivery = $detail['delivery_date'] ?? null;
            if (!empty($delivery)) {
                $timestamp = strtotime($delivery);
                $delivery = $timestamp ? date('Y-m-d H:i:s', $timestamp) : null;
            } else {
                $delivery = null;
            }

            $prepared[] = [
                'IdTTCTDonHang' => uniqid('CTDH'),
                'IdDonHang' => $orderId,
                'IdSanPham' => $productId,
                'IdCauHinh' => $configurationId,
                'SoLuong' => $quantity,
                'NgayGiao' => $delivery,
                'YeuCau' => $detail['requirement'] ?? null,
                'DonGia' => $unitPrice,
                'ThanhTien' => $total,
                'GhiChu' => $detail['note'] ?? null,
                'VAT' => $vat,
            ];
        }

        if (empty($prepared)) {
            throw new InvalidArgumentException('Đơn hàng phải có ít nhất một sản phẩm hợp lệ.');
        }

        return $prepared;
    }

    private function cloneBomStructure(string $productId, string $targetBomId, ?string $sourceBomId = null): void
    {
        $components = [];

        if ($sourceBomId) {
            $components = $this->componentModel->getByBom($sourceBomId);
        }

        if (empty($components)) {
            $components = $this->componentModel->getByProduct($productId);
        }

        if (empty($components) && $productId) {
            $existingBoms = $this->bomModel->getByProduct($productId);
            foreach ($existingBoms as $existingBom) {
                $existingId = $existingBom['IdBOM'] ?? null;
                if (!$existingId || $existingId === $targetBomId) {
                    continue;
                }
                $components = $this->componentModel->getByBom($existingId);
                if (!empty($components)) {
                    break;
                }
            }
        }

        if (empty($components)) {
            return;
        }

        foreach ($components as $component) {
            $newComponentId = uniqid('CD');

            $componentData = [
                'IdCongDoan' => $newComponentId,
                'IdBOM' => $targetBomId,
                'IdSanPham' => $productId,
                'TenCongDoan' => $component['TenCongDoan'] ?? null,
                'TyLeSoLuong' => $component['TyLeSoLuong'] ?? 1,
                'DonVi' => $component['DonVi'] ?? null,
                'IdXuong' => $component['IdXuong'] ?? null,
                'TrangThaiMacDinh' => $component['TrangThaiMacDinh'] ?? null,
                'LogisticsKey' => $component['LogisticsKey'] ?? null,
                'LogisticsLabel' => $component['LogisticsLabel'] ?? null,
                'IncludeYeuCau' => $component['IncludeYeuCau'] ?? 0,
                'ThuTu' => $component['ThuTu'] ?? 0,
            ];

            $this->componentModel->create($componentData);

            $materials = $this->componentMaterialModel->getRawByComponent($component['IdCongDoan']);
            foreach ($materials as $material) {
                $this->componentMaterialModel->create([
                    'IdCongDoanNguyenLieu' => uniqid('CDML'),
                    'IdCongDoan' => $newComponentId,
                    'IdNguyenLieu' => $material['IdNguyenLieu'],
                    'TyLeSoLuong' => $material['TyLeSoLuong'] ?? 1,
                    'Nhan' => $material['Nhan'] ?? null,
                    'DonVi' => $material['DonVi'] ?? null,
                ]);
            }
        }
    }

    private function resolveCustomer(array $input, ?string $fallbackCustomerId = null): string
    {
        $mode = $input['customer_mode'] ?? 'existing';

        if ($mode === 'new') {
            $name = trim($input['customer_name'] ?? '');
            if ($name === '') {
                throw new InvalidArgumentException('Vui lòng nhập tên khách hàng mới.');
            }

            $phone = trim($input['customer_phone'] ?? '');
            $address = trim($input['customer_address'] ?? '');
            $type = trim($input['customer_type'] ?? 'Khách hàng mới');

            $customerId = uniqid('KH');
            $this->customerModel->create([
                'IdKhachHang' => $customerId,
                'HoTen' => $name,
                'GioiTinh' => null,
                'DiaChi' => $address ?: null,
                'SoLuongDonHang' => 0,
                'SoDienThoai' => $phone ?: null,
                'TongTien' => 0,
                'LoaiKhachHang' => $type ?: 'Khách hàng mới',
            ]);

            return $customerId;
        }

        $existingId = $input['customer_existing_id'] ?? $fallbackCustomerId;
        if (!$existingId) {
            throw new InvalidArgumentException('Vui lòng chọn khách hàng từ danh sách.');
        }

        return $existingId;
    }
}
