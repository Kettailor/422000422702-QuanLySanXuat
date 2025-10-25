<?php
$document = $document ?? [];
$warehouses = $warehouses ?? [];
$employees = $employees ?? [];
$types = $types ?? [];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Cập nhật phiếu kho</h3>
        <p class="text-muted mb-0">Điều chỉnh thông tin phiếu đã lập để khớp với số liệu thực tế.</p>
    </div>
</div>

<?php
$actionUrl = '?controller=warehouse_sheet&action=update';
$isEdit = true;
include __DIR__ . '/form.php';
?>
