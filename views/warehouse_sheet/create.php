<?php
$document = $document ?? [];
$warehouses = $warehouses ?? [];
$employees = $employees ?? [];
$types = $types ?? [];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Tạo phiếu kho mới</h3>
        <p class="text-muted mb-0">Nhập thông tin phiếu xuất/nhập kho theo biểu mẫu chuẩn của hệ thống.</p>
    </div>
</div>

<?php
$actionUrl = '?controller=warehouse_sheet&action=store';
$isEdit = false;
include __DIR__ . '/form.php';
?>
