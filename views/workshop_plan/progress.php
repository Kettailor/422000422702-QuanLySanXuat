<?php
$plan = $plan ?? null;
$availableShifts = $availableShifts ?? [];
$canUpdateProgress = $canUpdateProgress ?? false;
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h3 class="fw-bold mb-1">Cập nhật tiến độ cuối ca</h3>
        <p class="text-muted mb-0">Ghi nhận sản lượng hoàn thành theo ca đã phân công.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="?controller=workshop_plan&action=read&id=<?= urlencode($plan['IdKeHoachSanXuatXuong'] ?? '') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Chi tiết kế hoạch
        </a>
    </div>
</div>

<?php if (!$plan): ?>
    <div class="alert alert-warning">Không tìm thấy kế hoạch xưởng.</div>
<?php elseif (!$canUpdateProgress): ?>
    <div class="alert alert-light border">Cần đủ nguyên liệu và có phân công theo ca để cập nhật tiến độ.</div>
<?php elseif (empty($availableShifts)): ?>
    <div class="alert alert-light border">Chưa có ca làm việc đã phân công cho kế hoạch xưởng.</div>
<?php else: ?>
    <form method="post" action="?controller=workshop_plan&action=updateProgress">
        <input type="hidden" name="IdKeHoachSanXuatXuong" value="<?= htmlspecialchars($plan['IdKeHoachSanXuatXuong']) ?>">
        <div class="card p-4">
            <div class="mb-3">
                <label class="form-label fw-semibold">Ca làm việc</label>
                <select name="shift_id" class="form-select" required>
                    <option value="">Chọn ca làm việc</option>
                    <?php foreach ($availableShifts as $shift): ?>
                        <option value="<?= htmlspecialchars($shift['IdCaLamViec'] ?? '') ?>">
                            <?= htmlspecialchars(($shift['TenCa'] ?? '') . ' • ' . ($shift['NgayLamViec'] ?? '')) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Số lượng thành phẩm hoàn thành</label>
                <input type="number" min="1" name="produced_quantity" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Tên lô (tuỳ chọn)</label>
                <input type="text" name="lot_name" class="form-control" placeholder="Ví dụ: Lô TP ca sáng 15/01">
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-clipboard-check me-2"></i>Cập nhật tiến độ
                </button>
            </div>
        </div>
    </form>
<?php endif; ?>
