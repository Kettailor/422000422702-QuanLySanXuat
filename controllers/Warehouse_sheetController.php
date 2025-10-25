<?php

class Warehouse_sheetController extends Controller
{
    private InventorySheet $sheetModel;

    public function __construct()
    {
        $this->authorize(['VT_NHANVIEN_KHO']);
        $this->sheetModel = new InventorySheet();
    }

    public function index(): void
    {
        $documents = $this->sheetModel->getDocuments();
        $summary = $this->sheetModel->getDocumentSummary();
        $this->render('warehouse_sheet/index', [
            'title' => 'Phiếu kho',
            'documents' => $documents,
            'summary' => $summary,
        ]);
    }
}
