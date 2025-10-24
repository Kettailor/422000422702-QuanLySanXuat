<?php

class OrderController extends Controller
{
    private Order $orderModel;
    private Customer $customerModel;

    public function __construct()
    {
        $this->orderModel = new Order();
        $this->customerModel = new Customer();
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
        $this->render('order/create', [
            'title' => 'Tạo đơn hàng mới',
            'customers' => $customers,
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=order&action=index');
        }

        $data = [
            'IdDonHang' => $_POST['IdDonHang'] ?: uniqid('DH'),
            'YeuCau' => $_POST['YeuCau'] ?? null,
            'TongTien' => $_POST['TongTien'] ?? 0,
            'NgayLap' => $_POST['NgayLap'] ?? date('Y-m-d'),
            'TrangThai' => $_POST['TrangThai'] ?? 'Mới tạo',
            'IdKhachHang' => $_POST['IdKhachHang'] ?? '',
        ];

        try {
            $this->orderModel->create($data);
            $this->setFlash('success', 'Tạo đơn hàng thành công.');
        } catch (Throwable $e) {
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

        $this->render('order/edit', [
            'title' => 'Chỉnh sửa đơn hàng',
            'order' => $order,
            'customers' => $customers,
        ]);
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=order&action=index');
        }

        $id = $_POST['IdDonHang'];
        $data = [
            'YeuCau' => $_POST['YeuCau'] ?? null,
            'TongTien' => $_POST['TongTien'] ?? 0,
            'NgayLap' => $_POST['NgayLap'] ?? date('Y-m-d'),
            'TrangThai' => $_POST['TrangThai'] ?? 'Mới tạo',
            'IdKhachHang' => $_POST['IdKhachHang'] ?? '',
        ];

        try {
            $this->orderModel->update($id, $data);
            $this->setFlash('success', 'Cập nhật đơn hàng thành công.');
        } catch (Throwable $e) {
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

        $this->render('order/read', [
            'title' => 'Chi tiết đơn hàng',
            'order' => $order,
            'customer' => $customer,
        ]);
    }
}
