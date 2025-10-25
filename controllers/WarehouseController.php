<?php

class WarehouseController extends Controller
{
    private Warehouse $warehouseModel;
    private InventoryLot $lotModel;
    private InventorySheet $sheetModel;

    public function __construct()
    {
        $this->authorize(['VT_NHANVIEN_KHO', 'VT_QUANLY_XUONG']);
        $this->warehouseModel = new Warehouse();
        $this->lotModel = new InventoryLot();
        $this->sheetModel = new InventorySheet();
    }

    public function index(): void
    {
        $warehouses = $this->warehouseModel->getWithSupervisor();
        $summary = $this->warehouseModel->getWarehouseSummary($warehouses);
        $documents = $this->sheetModel->getDocuments(null, 200);
        $documentGroups = $this->buildDocumentGroups($documents);
        $this->render('warehouse/index', [
            'title' => 'Kho & tồn kho',
            'warehouses' => $warehouses,
            'summary' => $summary,
            'documentGroups' => $documentGroups,
        ]);
    }

    public function read(): void
    {
        $id = $_GET['id'] ?? null;
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
        $options = $this->warehouseModel->getFormOptions();
        $this->render('warehouse/create', [
            'title' => 'Thêm kho mới',
            'workshops' => $options['workshops'],
            'managers' => $options['managers'],
            'statuses' => $options['statuses'],
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
            'NHAN_VIEN_KHO_IdNhanVien' => $_POST['IdQuanKho'] ?? null,
        ];

        try {
            $this->warehouseModel->createWarehouse($_POST);
            $this->setFlash('success', 'Đã thêm kho mới.');
        } catch (Throwable $e) {
            $this->setFlash('danger', 'Không thể thêm kho: ' . $e->getMessage());
        }

        $this->redirect('?controller=warehouse&action=index');
    }

    public function edit(): void
    {
        $id = $_GET['id'] ?? null;
        $warehouse = $id ? $this->warehouseModel->find($id) : null;
        $options = $this->warehouseModel->getFormOptions();
        $this->render('warehouse/edit', [
            'title' => 'Cập nhật kho',
            'warehouse' => $warehouse,
            'workshops' => $options['workshops'],
            'managers' => $options['managers'],
            'statuses' => $options['statuses'],
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
            'NHAN_VIEN_KHO_IdNhanVien' => $_POST['IdQuanKho'] ?? null,
        ];

        try {
            $this->warehouseModel->updateWarehouse($id, $_POST);
            $this->setFlash('success', 'Cập nhật kho thành công.');
        } catch (Throwable $e) {
            $this->setFlash('danger', 'Không thể cập nhật kho: ' . $e->getMessage());
        }

        $this->redirect('?controller=warehouse&action=index');
    }

    public function delete(): void
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                $this->warehouseModel->delete($id);
                $this->setFlash('success', 'Đã xóa kho.');
            } catch (Throwable $e) {
                $this->setFlash('danger', 'Không thể xóa kho: ' . $e->getMessage());
            }
        }

        $this->redirect('?controller=warehouse&action=index');
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
}
