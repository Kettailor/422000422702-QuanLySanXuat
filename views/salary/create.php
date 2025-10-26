<?php
$periods = $periods ?? [];
$selectedPeriod = $selectedPeriod ?? date('Y-m');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Chọn kỳ tính lương</h3>
        <p class="text-muted mb-0">Bước đầu tiên trong quy trình tạo bảng lương là xác định kỳ tính lương cần xử lý.</p>
    </div>
    <a href="?controller=salary&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<div class="card p-4">
    <form method="post" action="?controller=salary&action=create" class="row g-3" id="period-form">
        <input type="hidden" name="period" id="period-value" value="<?= htmlspecialchars($selectedPeriod) ?>">
        <div class="col-md-6">
            <label for="period-select" class="form-label">Kỳ tính lương</label>
            <select id="period-select" class="form-select" required>
                <option value="">-- Chọn kỳ lương --</option>
                <?php foreach ($periods as $period): ?>
                    <option value="<?= htmlspecialchars($period) ?>" <?= $period === $selectedPeriod ? 'selected' : '' ?>>
                        <?= date('m/Y', strtotime($period . '-01')) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="form-text">Danh sách kỳ lương được tổng hợp tự động từ dữ liệu chấm công.</div>
        </div>
        <div class="col-md-6">
            <label for="custom-period" class="form-label">Hoặc nhập kỳ khác</label>
            <input type="month" id="custom-period" class="form-control" value="<?= htmlspecialchars($selectedPeriod) ?>">
            <div class="form-text">Hệ thống sẽ sử dụng kỳ nhập tay nếu khác với lựa chọn phía trên.</div>
        </div>
        <div class="col-12 d-flex justify-content-between align-items-center">
            <a class="btn btn-link" href="?controller=salary&action=create&reset=1"><i class="bi bi-arrow-counterclockwise"></i> Bắt đầu lại quy trình</a>
            <button type="submit" class="btn btn-primary px-4">Tiếp tục</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('period-select');
    const custom = document.getElementById('custom-period');
    const hidden = document.getElementById('period-value');

    const syncFromSelect = () => {
        if (!select) return;
        hidden.value = select.value || hidden.value;
        if (select.value) {
            custom.value = select.value;
        }
    };

    const syncFromCustom = () => {
        if (!custom.value) {
            return;
        }
        hidden.value = custom.value;
        if (select && Array.from(select.options).some(option => option.value === custom.value)) {
            select.value = custom.value;
        } else if (select) {
            select.value = '';
        }
    };

    if (select) {
        select.addEventListener('change', syncFromSelect);
    }
    if (custom) {
        custom.addEventListener('input', syncFromCustom);
    }

    syncFromSelect();
});
</script>
