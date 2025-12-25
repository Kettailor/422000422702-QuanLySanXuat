<?php

class BillController extends Controller
{
    private Bill $billModel;
    private Order $orderModel;

    public function __construct()
    {
        $this->authorize(['VT_KETOAN', 'VT_KINH_DOANH', 'VT_ADMIN']);
        $this->billModel = new Bill();
        $this->orderModel = new Order();
    }

    public function index(): void
    {
        $bills = $this->billModel->getBillsWithOrder();
        $this->render('bill/index', [
            'title' => 'Hóa đơn',
            'bills' => $bills,
        ]);
    }

    public function read(): void
    {
        $id = $_GET['id'] ?? null;
        $bill = $id ? $this->billModel->find($id) : null;
        $this->render('bill/read', [
            'title' => 'Chi tiết hóa đơn',
            'bill' => $bill,
        ]);
    }

    public function create(): void
    {
        $this->render('bill/create', [
            'title' => 'Tạo hóa đơn',
            'eligibleOrders' => $this->orderModel->getOrdersEligibleForBilling(),
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=bill&action=index');
        }

        $data = [
            'IdHoaDon' => $_POST['IdHoaDon'] ?: uniqid('HD'),
            'NgayLap' => $_POST['NgayLap'] ?? date('Y-m-d'),
            'TrangThai' => $_POST['TrangThai'] ?? 'Chưa thanh toán',
            'LoaiHD' => $_POST['LoaiHD'] ?? 'Bán hàng',
            'IdDonHang' => $_POST['IdDonHang'] ?? null,
            'Thue' => $_POST['Thue'] ?? null,
            'MaBuuDien' => $_POST['MaBuuDien'] ?? null,
            'GhiChu' => $_POST['GhiChu'] ?? null,
        ];

        try {
            $eligibleOrders = $this->orderModel->getOrdersEligibleForBilling();
            $eligibleMap = array_fill_keys(array_column($eligibleOrders, 'IdDonHang'), true);
            if (empty($data['IdDonHang']) || !isset($eligibleMap[$data['IdDonHang']])) {
                $this->setFlash('danger', 'Chỉ được lập hóa đơn cho đơn hàng đã hoàn thành kế hoạch.');
                $this->redirect('?controller=bill&action=create');
                return;
            }
            $this->billModel->create($data);
            $this->setFlash('success', 'Đã tạo hóa đơn.');
        } catch (Throwable $e) {
            Logger::error('Lỗi khi tạo hóa đơn: ' . $e->getMessage());
            /* $this->setFlash('danger', 'Không thể tạo hóa đơn: ' . $e->getMessage()); */
            $this->setFlash('danger', 'Không thể tạo hóa đơn, vui lòng kiểm tra log để biết thêm chi tiết.');
        }

        $this->redirect('?controller=bill&action=index');
    }

    public function edit(): void
    {
        $id = $_GET['id'] ?? null;
        $bill = $id ? $this->billModel->find($id) : null;
        if ($bill && ($bill['TrangThai'] ?? '') !== 'Chưa thanh toán') {
            $this->setFlash('warning', 'Chỉ được chỉnh sửa hóa đơn khi chưa thanh toán.');
            $this->redirect('?controller=bill&action=read&id=' . urlencode($id));
        }
        $this->render('bill/edit', [
            'title' => 'Cập nhật hóa đơn',
            'bill' => $bill,
            'eligibleOrders' => $this->orderModel->getOrdersEligibleForBilling(),
        ]);
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=bill&action=index');
        }

        $id = $_POST['IdHoaDon'];
        $existing = $this->billModel->find($id);
        if ($existing && ($existing['TrangThai'] ?? '') !== 'Chưa thanh toán') {
            $this->setFlash('warning', 'Hóa đơn đã thanh toán hoặc hủy nên không thể chỉnh sửa.');
            $this->redirect('?controller=bill&action=read&id=' . urlencode($id));
            return;
        }
        $data = [
            'NgayLap' => $_POST['NgayLap'] ?? date('Y-m-d'),
            'TrangThai' => $_POST['TrangThai'] ?? 'Chưa thanh toán',
            'LoaiHD' => $_POST['LoaiHD'] ?? 'Bán hàng',
            'IdDonHang' => $_POST['IdDonHang'] ?? null,
            'Thue' => $_POST['Thue'] ?? null,
            'MaBuuDien' => $_POST['MaBuuDien'] ?? null,
            'GhiChu' => $_POST['GhiChu'] ?? null,
        ];

        try {
            $eligibleOrders = $this->orderModel->getOrdersEligibleForBilling();
            $eligibleMap = array_fill_keys(array_column($eligibleOrders, 'IdDonHang'), true);
            if (empty($data['IdDonHang']) || !isset($eligibleMap[$data['IdDonHang']])) {
                $this->setFlash('danger', 'Đơn hàng chưa hoàn thành kế hoạch nên không thể cập nhật hóa đơn.');
                $this->redirect('?controller=bill&action=edit&id=' . urlencode($id));
                return;
            }
            $this->billModel->update($id, $data);
            $this->setFlash('success', 'Cập nhật hóa đơn thành công.');
        } catch (Throwable $e) {
            Logger::error('Lỗi khi cập nhật hóa đơn ' . $id . ': ' . $e->getMessage());
            /* $this->setFlash('danger', 'Không thể cập nhật hóa đơn: ' . $e->getMessage()); */
            $this->setFlash('danger', 'Không thể cập nhật hóa đơn, vui lòng kiểm tra log để biết thêm chi tiết.');
        }

        $this->redirect('?controller=bill&action=index');
    }

    public function delete(): void
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $bill = $this->billModel->find($id);
            if (!$bill) {
                $this->setFlash('warning', 'Không tìm thấy hóa đơn.');
                $this->redirect('?controller=bill&action=index');
                return;
            }
            if (($bill['TrangThai'] ?? '') !== 'Chưa thanh toán') {
                $this->setFlash('danger', 'Chỉ được hủy hóa đơn khi chưa thanh toán.');
                $this->redirect('?controller=bill&action=read&id=' . urlencode($id));
                return;
            }
            try {
                $this->billModel->update($id, ['TrangThai' => 'Hủy']);
                $this->setFlash('success', 'Đã hủy hóa đơn.');
            } catch (Throwable $e) {
                Logger::error('Lỗi khi hủy hóa đơn ' . $id . ': ' . $e->getMessage());
                $this->setFlash('danger', 'Không thể hủy hóa đơn: ' . $e->getMessage());
            }
        }

        $this->redirect('?controller=bill&action=index');
    }
}
