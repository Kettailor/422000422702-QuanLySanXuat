<?php

class OrderController extends Controller
{
    private Order $orderModel;
    private Customer $customerModel;
    private Product $productModel;
    private OrderDetail $orderDetailModel;
    private ProductConfiguration $configurationModel;
    private ProductBom $bomModel;

    private array $orderStatuses = ['Mới tạo', 'Đang xử lý', 'Chờ giao', 'Hoàn thành', 'Đã hủy'];

    public function __construct()
    {
        $this->authorize(['VT_KINH_DOANH', 'VT_BAN_GIAM_DOC']);
        $this->orderModel = new Order();
        $this->customerModel = new Customer();
        $this->productModel = new Product();
        $this->orderDetailModel = new OrderDetail();
        $this->configurationModel = new ProductConfiguration();
        $this->bomModel = new ProductBom();
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
        $customers = $this->customerModel->all(200);
        $products = $this->productModel->all(500);
        $configurations = $this->configurationModel->all(1000);

        $this->render('order/create', [
            'title' => 'Tạo đơn hàng mới',
            'customers' => $customers,
            'products' => $products,
            'configurations' => $configurations,
            'orderStatuses' => $this->orderStatuses,
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=order&action=index');
        }

        $orderId = ($_POST['IdDonHang'] ?? '') ?: uniqid('DH');
        $detailsInput = $_POST['details'] ?? [];
        $contactEmail = trim($_POST['EmailLienHe'] ?? '');

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
                'EmailLienHe' => $contactEmail !== '' ? $contactEmail : null,
                'IdKhachHang' => $customerId,
            ];

            $this->orderModel->create($data);

            foreach ($preparedDetails as $detail) {
                $this->orderDetailModel->create($detail);
            }

            $db->commit();
            $this->setFlash('success', 'Tạo đơn hàng thành công.');
        } catch (Throwable $exception) {
            if (isset($db) && $db->inTransaction()) {
                $db->rollBack();
            }
            $this->setFlash('danger', 'Không thể tạo đơn hàng: ' . $exception->getMessage());
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
        if (!$order) {
            $this->setFlash('warning', 'Không tìm thấy đơn hàng.');
            $this->redirect('?controller=order&action=index');
        }

        $customers = $this->customerModel->all(200);
        $products = $this->productModel->all(500);
        $configurations = $this->configurationModel->all(1000);
        $orderDetails = $this->orderDetailModel->getByOrder($id);
        $detailFormData = $this->prepareDetailsForForm($orderDetails);

        $this->render('order/edit', [
            'title' => 'Chỉnh sửa đơn hàng',
            'order' => $order,
            'customers' => $customers,
            'products' => $products,
            'configurations' => $configurations,
            'orderDetails' => $detailFormData,
            'orderStatuses' => $this->orderStatuses,
        ]);
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=order&action=index');
        }

        $id = $_POST['IdDonHang'] ?? null;
        if (!$id) {
            $this->redirect('?controller=order&action=index');
        }

        $detailsInput = $_POST['details'] ?? [];
        $contactEmail = trim($_POST['EmailLienHe'] ?? '');

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
                'EmailLienHe' => $contactEmail !== '' ? $contactEmail : null,
                'IdKhachHang' => $customerId,
            ];

            $this->orderModel->update($id, $data);
            $this->orderDetailModel->deleteByOrder($id);

            foreach ($preparedDetails as $detail) {
                $this->orderDetailModel->create($detail);
            }

            $db->commit();
            $this->setFlash('success', 'Cập nhật đơn hàng thành công.');
        } catch (Throwable $exception) {
            if (isset($db) && $db->inTransaction()) {
                $db->rollBack();
            }
            $this->setFlash('danger', 'Không thể cập nhật đơn hàng: ' . $exception->getMessage());
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
            } catch (Throwable $exception) {
                $this->setFlash('danger', 'Không thể xóa đơn hàng: ' . $exception->getMessage());
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
        if (!$order) {
            $this->setFlash('warning', 'Không tìm thấy đơn hàng.');
            $this->redirect('?controller=order&action=index');
        }

        $customer = $order ? $this->customerModel->find($order['IdKhachHang']) : null;
        $orderDetails = $this->orderDetailModel->getByOrder($id);
        $detailedItems = $this->prepareDetailsForDisplay($orderDetails);

        $this->render('order/read', [
            'title' => 'Chi tiết đơn hàng',
            'order' => $order,
            'customer' => $customer,
            'orderDetails' => $detailedItems,
            'orderStatuses' => $this->orderStatuses,
        ]);
    }

    private function prepareOrderDetails(string $orderId, array $detailsInput): array
    {
        $prepared = [];

        foreach ($detailsInput as $detail) {
            if (!is_array($detail)) {
                continue;
            }

            $productMode = $detail['product_mode'] ?? 'existing';
            $productId = $detail['product_id'] ?? null;
            $product = null;

            if ($productMode === 'new') {
                $productName = trim($detail['new_product_name'] ?? '');
                if ($productName === '') {
                    throw new InvalidArgumentException('Vui lòng nhập tên sản phẩm mới.');
                }
                $productUnit = trim($detail['new_product_unit'] ?? '');
                $productDescription = trim($detail['new_product_description'] ?? '');
                $productPrice = (float)($detail['unit_price'] ?? 0);

                if ($productId && $existingProduct = $this->productModel->find($productId)) {
                    $this->productModel->update($productId, [
                        'TenSanPham' => $productName,
                        'DonVi' => $productUnit ?: ($existingProduct['DonVi'] ?? null),
                        'MoTa' => $productDescription ?: null,
                        'GiaBan' => $productPrice,
                    ]);
                } else {
                    $productId = uniqid('SP');
                    $this->productModel->create([
                        'IdSanPham' => $productId,
                        'TenSanPham' => $productName,
                        'DonVi' => $productUnit ?: null,
                        'MoTa' => $productDescription ?: null,
                        'GiaBan' => $productPrice,
                    ]);
                }
            } else {
                if (!$productId) {
                    throw new InvalidArgumentException('Vui lòng chọn sản phẩm.');
                }
                $product = $this->productModel->find($productId);
                if (!$product) {
                    throw new InvalidArgumentException('Sản phẩm đã chọn không tồn tại.');
                }
            }

            $configurationMode = $detail['configuration_mode'] ?? 'existing';
            $configurationId = $detail['configuration_id'] ?? null;
            $configuration = null;

            $configKeycap = trim($detail['config_keycap'] ?? '');
            $configSwitch = trim($detail['config_switch'] ?? '');
            $configCase = trim($detail['config_case'] ?? '');
            $configMain = trim($detail['config_main'] ?? '');
            $configOthers = trim($detail['config_others'] ?? '');

            if ($configurationMode === 'new') {
                $configurationName = trim($detail['new_configuration_name'] ?? '');
                if ($configurationName === '') {
                    throw new InvalidArgumentException('Vui lòng nhập tên cấu hình sản phẩm.');
                }

                $configurationDescription = trim($detail['new_configuration_description'] ?? '');
                $configurationPrice = (float)($detail['new_configuration_price'] ?? 0);

                if ($configurationId && $existingConfiguration = $this->configurationModel->find($configurationId)) {
                    $this->configurationModel->update($configurationId, [
                        'TenCauHinh' => $configurationName,
                        'MoTa' => $configurationDescription ?: ($configKeycap ?: null),
                        'GiaBan' => $configurationPrice,
                        'IdSanPham' => $productId,
                        'Layout' => $configMain ?: null,
                        'SwitchType' => $configSwitch ?: null,
                        'CaseType' => $configCase ?: null,
                        'Foam' => $configOthers ?: null,
                    ]);
                    $bomId = $existingConfiguration['IdBOM'] ?? null;
                    if ($bomId) {
                        $this->bomModel->update($bomId, [
                            'TenBOM' => sprintf('BOM - %s', $configurationName),
                            'MoTa' => $configurationDescription ?: null,
                            'IdSanPham' => $productId,
                        ]);
                    }
                    $configuration = $this->configurationModel->find($configurationId);
                } else {
                    $configurationId = uniqid('CFG');
                    $bomId = uniqid('BOM');

                    $this->bomModel->create([
                        'IdBOM' => $bomId,
                        'TenBOM' => sprintf('BOM - %s', $configurationName),
                        'MoTa' => $configurationDescription ?: null,
                        'IdSanPham' => $productId,
                    ]);

                    $this->configurationModel->create([
                        'IdCauHinh' => $configurationId,
                        'TenCauHinh' => $configurationName,
                        'MoTa' => $configurationDescription ?: ($configKeycap ?: null),
                        'GiaBan' => $configurationPrice,
                        'IdSanPham' => $productId,
                        'IdBOM' => $bomId,
                        'Layout' => $configMain ?: null,
                        'SwitchType' => $configSwitch ?: null,
                        'CaseType' => $configCase ?: null,
                        'Foam' => $configOthers ?: null,
                    ]);
                    $configuration = $this->configurationModel->find($configurationId);
                }
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
            }

            if ($configuration) {
                $configSwitch = $configSwitch ?: trim((string)($configuration['SwitchType'] ?? ''));
                $configCase = $configCase ?: trim((string)($configuration['CaseType'] ?? ''));
                $configMain = $configMain ?: trim((string)($configuration['Layout'] ?? ''));
                $configOthers = $configOthers ?: trim((string)($configuration['Foam'] ?? ''));
                if ($configKeycap === '' && !empty($configuration['MoTa'])) {
                    $configKeycap = trim((string)$configuration['MoTa']);
                }
            }

            $quantity = (int)($detail['quantity'] ?? 0);
            if ($quantity <= 0) {
                continue;
            }

            $unitPrice = (float)($detail['unit_price'] ?? 0);
            $vatInput = (float)($detail['vat'] ?? 0);
            $vat = $vatInput > 1 ? $vatInput / 100 : $vatInput;
            $total = $quantity * $unitPrice * (1 + $vat);

            $delivery = $detail['delivery_date'] ?? null;
            if ($delivery) {
                $timestamp = strtotime($delivery);
                $delivery = $timestamp ? date('Y-m-d H:i:s', $timestamp) : null;
            }

            $note = trim($detail['note'] ?? '');
            $metaPayload = [
                'note' => $note !== '' ? $note : null,
                'configuration' => array_filter([
                    'keycap' => $configKeycap !== '' ? $configKeycap : null,
                    'switch' => $configSwitch !== '' ? $configSwitch : null,
                    'case' => $configCase !== '' ? $configCase : null,
                    'main' => $configMain !== '' ? $configMain : null,
                    'others' => $configOthers !== '' ? $configOthers : null,
                ]),
                'source' => [
                    'product' => $productMode,
                    'configuration' => $configurationMode,
                ],
            ];

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
                'GhiChu' => json_encode($metaPayload, JSON_UNESCAPED_UNICODE),
                'VAT' => $vat,
            ];
        }

        if (empty($prepared)) {
            throw new InvalidArgumentException('Đơn hàng phải có ít nhất một sản phẩm hợp lệ.');
        }

        return $prepared;
    }

    private function parseDetailMeta(array $detail): array
    {
        $meta = [
            'note' => null,
            'configuration' => [
                'keycap' => null,
                'switch' => null,
                'case' => null,
                'main' => null,
                'others' => null,
            ],
            'source' => [
                'product' => 'existing',
                'configuration' => 'existing',
            ],
        ];

        $rawMeta = $detail['GhiChu'] ?? null;
        if ($rawMeta) {
            $decoded = json_decode((string)$rawMeta, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                if (isset($decoded['note'])) {
                    $note = trim((string)$decoded['note']);
                    $meta['note'] = $note !== '' ? $note : null;
                }
                if (isset($decoded['configuration']) && is_array($decoded['configuration'])) {
                    $meta['configuration'] = array_merge($meta['configuration'], array_intersect_key($decoded['configuration'], $meta['configuration']));
                }
                if (isset($decoded['source']) && is_array($decoded['source'])) {
                    $meta['source'] = array_merge($meta['source'], array_intersect_key($decoded['source'], $meta['source']));
                }
            } else {
                $note = trim((string)$rawMeta);
                $meta['note'] = $note !== '' ? $note : null;
            }
        }

        $detail['meta'] = $meta;
        return $detail;
    }

    private function prepareDetailsForForm(array $details): array
    {
        return array_map(function (array $detail): array {
            $detail = $this->parseDetailMeta($detail);
            $meta = $detail['meta'];

            $delivery = $detail['NgayGiao'] ?? null;
            if ($delivery) {
                $timestamp = strtotime($delivery);
                $delivery = $timestamp ? date('Y-m-d\TH:i', $timestamp) : null;
            }

            $vatPercent = isset($detail['VAT']) ? ((float)$detail['VAT']) * 100 : 0;

            return [
                'product_mode' => $meta['source']['product'] ?? 'existing',
                'product_id' => $detail['IdSanPham'] ?? null,
                'new_product_name' => $detail['TenSanPham'] ?? '',
                'new_product_unit' => $detail['DonVi'] ?? '',
                'new_product_description' => $detail['MoTa'] ?? '',
                'configuration_mode' => $meta['source']['configuration'] ?? 'existing',
                'configuration_id' => $detail['IdCauHinh'] ?? null,
                'new_configuration_name' => $detail['TenCauHinh'] ?? '',
                'new_configuration_price' => $detail['GiaCauHinh'] ?? '',
                'new_configuration_description' => $detail['MoTaCauHinh'] ?? '',
                'quantity' => (int)($detail['SoLuong'] ?? 1),
                'unit_price' => (float)($detail['DonGia'] ?? 0),
                'vat' => $vatPercent,
                'delivery_date' => $delivery,
                'requirement' => $detail['YeuCau'] ?? '',
                'note' => $meta['note'] ?? '',
                'config_keycap' => $meta['configuration']['keycap'] ?? '',
                'config_switch' => $meta['configuration']['switch'] ?? '',
                'config_case' => $meta['configuration']['case'] ?? '',
                'config_main' => $meta['configuration']['main'] ?? '',
                'config_others' => $meta['configuration']['others'] ?? '',
            ];
        }, $details);
    }

    private function prepareDetailsForDisplay(array $details): array
    {
        return array_map(function (array $detail): array {
            return $this->parseDetailMeta($detail);
        }, $details);
    }

    private function resolveCustomer(array $input, ?string $fallbackCustomerId = null): string
    {
        $mode = $input['customer_mode'] ?? 'existing';

        if ($mode === 'new') {
            $name = trim($input['customer_name'] ?? '');
            if ($name === '') {
                throw new InvalidArgumentException('Vui lòng nhập tên khách hàng mới.');
            }

            $company = trim($input['customer_company'] ?? '');
            $phone = trim($input['customer_phone'] ?? '');
            $email = trim($input['customer_email'] ?? '');
            $address = trim($input['customer_address'] ?? '');
            $type = trim($input['customer_type'] ?? 'Khách hàng mới');

            $customerId = uniqid('KH');
            $this->customerModel->create([
                'IdKhachHang' => $customerId,
                'HoTen' => $name,
                'TenCongTy' => $company !== '' ? $company : $name,
                'GioiTinh' => null,
                'DiaChi' => $address ?: null,
                'SoLuongDonHang' => 0,
                'SoDienThoai' => $phone ?: null,
                'Email' => $email !== '' ? $email : null,
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
