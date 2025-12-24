<?php

use Dompdf\Dompdf;

class ReportController extends Controller
{
    function index()
    {
        $billModel = new Bill();
        $orderDetailModel = new OrderDetail();
        $employeeModel = new Employee();
        $orderModel = new Order();
        $materialModel = new Material();
        $productModel = new Product();

        // var_dump($orderDetailModel->getAllWithOrderInfo());

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

    function export_pdf()
    {
        if (!class_exists('Dompdf\Dompdf')) {
            var_dump('sdd');
            // Redirect back or show an error message
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

        // ============ PHẦN SỬA ĐỂ FIX FONT TIẾNG VIỆT ============
        
        // Tạo options cho DomPDF
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans'); // Font hỗ trợ tiếng Việt
        $options->set('isPhpEnabled', true);
        
        // Khởi tạo DomPDF với options
        $dompdf = new \Dompdf\Dompdf($options);
        
        // =========================================================
        
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait'); // Đổi thành portrait cho đẹp hơn

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $filename = 'Bao_cao_thong_ke_' . date('Y-m-d_His') . '.pdf';
        $dompdf->stream($filename, ['Attachment' => 0]);
    }
}


?>
