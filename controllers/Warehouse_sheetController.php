<?php

class Warehouse_sheetController extends Controller
{
    private InventorySheet $sheetModel;

    public function __construct()
    {
        $this->sheetModel = new InventorySheet();
    }

    public function index(): void
    {
        $documents = $this->sheetModel->getDocuments();
        $this->render('warehouse_sheet/index', [
            'title' => 'Phiếu kho',
            'documents' => $documents,
        ]);
    }
}
