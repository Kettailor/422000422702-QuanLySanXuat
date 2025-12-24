<?php

use Dompdf\Dompdf;

class ReportController extends Controller
{
    public function index()
    {
        $billModel = new Bill();
        $orderDetailModel = new OrderDetail();
        $employeeModel = new Employee();
        $orderModel = new Order();
        $materialModel = new Material();
        $productModel = new Product();


        $data = [
            'bills' => $billModel->all(10000),
            'orderDetails' => $orderDetailModel->getAllWithOrderInfo(),
            'employees' => $employeeModel->all(10000),
            'orders' => $orderModel->all(10000),
            'materials' => $materialModel->all(10000),
            'products' => $productModel->all(10000),
        ];

        $this->render('report/index', $data);
    }

    public function export_pdf()
    {
        if (!class_exists('Dompdf\Dompdf')) {
            var_dump('sdd');
            header('Location: ?controller=report&action=index&error=pdf_lib_missing');
            exit;
        }

        $billModel = new Bill();
        $orderDetailModel = new OrderDetail();
        $employeeModel = new Employee();
        $orderModel = new Order();
        $materialModel = new Material();
        $productModel = new Product();

        $data = [
            'bills' => $billModel->all(10000),
            'orderDetails' => $orderDetailModel->getAllWithOrderInfo(),
            'employees' => $employeeModel->all(10000),
            'orders' => $orderModel->all(10000),
            'materials' => $materialModel->all(10000),
            'products' => $productModel->all(10000),
        ];

        ob_start();
        $this->render_pdf('report/pdf_template', $data);
        $html = ob_get_clean();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'landscape');

        $dompdf->render();

        $dompdf->stream('report.pdf', ['Attachment' => 0]);
    }
}
