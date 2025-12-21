<?php
$materialSource = $materialSource ?? 'plan';
$materialOptions = $materialOptions ?? [];
$materials = $materials ?? [];
$prefillPersist = $materialSource !== 'plan';
$materialsForRender = !empty($materials) ? $materials : [['IdNguyenLieu' => '', 'SoLuongKeHoach' => 0, 'DonVi' => '', 'SoLuongTonKho' => null]];
$planAssignments = $planAssignments ?? [];
$availableEmployees = $availableEmployees ?? [];
$canUpdateProgress = $canUpdateProgress ?? false;
$workShifts = $workShifts ?? [];

$materialOptionHtml = '<option value="">Chọn nguyên liệu</option>';
foreach ($materialOptions as $option) {
    $value = htmlspecialchars($option['IdNguyenLieu'] ?? '');
    $label = htmlspecialchars(($option['TenNL'] ?? $value) . (!empty($option['DonVi']) ? ' (' . $option['DonVi'] . ')' : ''));
    $materialOptionHtml .= "<option value=\"{$value}\">{$label}</option>";
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Kiểm tra nguyên liệu kế hoạch xưởng</h3>
        <p class="text-muted mb-0">Đối chiếu nhu cầu thực tế với tồn kho và ghi nhận lịch sử phê duyệt.</p>
    </div>
    <div class="d-flex gap-2">
        <?php if (!empty($plan['IdKeHoachSanXuatXuong'])): ?>
            <a href="?controller=factory_plan&action=read&id=<?= urlencode($plan['IdKeHoachSanXuatXuong']) ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Chi tiết kế hoạch
            </a>
        <?php endif; ?>
        <a href="?controller=factory_plan&action=index" class="btn btn-outline-secondary">
            <i class="bi bi-list"></i> Danh sách kế hoạch xưởng
        </a>
    </div>
</div>

<?php if (!$plan): ?>
    <div class="alert alert-warning">Không tìm thấy kế hoạch xưởng.</div>
<?php else: ?>
        <?php if (!empty($materialCheckResult)): ?>
            <div class="alert <?= $materialCheckResult['is_sufficient'] ? 'alert-success' : 'alert-warning' ?>">
                <div class="fw-semibold mb-2">
                    <?= $materialCheckResult['is_sufficient']
                        ? 'Tồn kho đáp ứng đủ nhu cầu thực tế.'
                        : 'Thiếu nguyên liệu để đáp ứng nhu cầu. Đã tạo yêu cầu bổ sung nếu cần.' ?>
            </div>
            <?php if (!empty($materialCheckResult['items'])): ?>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Nguyên liệu</th>
                                <th class="text-end">Nhu cầu</th>
                                <th class="text-end">Tồn kho</th>
                                <th class="text-end">Thiếu hụt</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($materialCheckResult['items'] as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['name'] ?? $item['id']) ?></td>
                                <td class="text-end"><?= number_format((int) $item['required']) ?><?= $item['unit'] ? ' ' . htmlspecialchars($item['unit']) : '' ?></td>
                                <td class="text-end"><?= number_format((int) $item['available']) ?><?= $item['unit'] ? ' ' . htmlspecialchars($item['unit']) : '' ?></td>
                                <td class="text-end fw-semibold <?= ($item['shortage'] ?? 0) > 0 ? 'text-danger' : 'text-success' ?>">
                                    <?= number_format((int) ($item['shortage'] ?? 0)) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="card p-4 mb-4">
        <div class="row g-4">
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Mã kế hoạch xưởng</dt>
                    <dd class="col-sm-7 fw-semibold"><?= htmlspecialchars($plan['IdKeHoachSanXuatXuong']) ?></dd>
                    <dt class="col-sm-5">Kế hoạch tổng</dt>
                    <dd class="col-sm-7">KH <?= htmlspecialchars($plan['IdKeHoachSanXuat']) ?> (<?= htmlspecialchars($plan['TrangThaiTong'] ?? 'Chưa cập nhật') ?>)</dd>
                    <dt class="col-sm-5">Đơn hàng</dt>
                    <dd class="col-sm-7">
                        <div class="fw-semibold">ĐH <?= htmlspecialchars($plan['IdDonHang'] ?? '-') ?></div>
                        <?php if (!empty($plan['YeuCau'])): ?>
                            <div class="text-muted small">Yêu cầu: <?= htmlspecialchars($plan['YeuCau']) ?></div>
                        <?php endif; ?>
                    </dd>
                    <dt class="col-sm-5">Xưởng thực hiện</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($plan['TenXuong'] ?? $plan['IdXuong']) ?></dd>
                </dl>
            </div>
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Sản phẩm</dt>
                    <dd class="col-sm-7">
                        <div class="fw-semibold"><?= htmlspecialchars($plan['TenSanPham'] ?? '-') ?></div>
                        <?php if (!empty($plan['TenCauHinh'])): ?>
                            <div class="text-muted small">Cấu hình: <?= htmlspecialchars($plan['TenCauHinh']) ?></div>
                        <?php endif; ?>
                    </dd>
                    <dt class="col-sm-5">Hạng mục</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($plan['TenThanhThanhPhanSP']) ?></dd>
                    <dt class="col-sm-5">Số lượng kế hoạch</dt>
                    <dd class="col-sm-7"><?= number_format((int) ($plan['SoLuong'] ?? 0)) ?></dd>
                    <dt class="col-sm-5">Trạng thái</dt>
                    <dd class="col-sm-7"><span class="badge bg-info bg-opacity-25 text-primary"><?= htmlspecialchars($plan['TrangThai']) ?></span></dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-semibold mb-1">Nhu cầu nguyên liệu thực tế</h5>
                <?php if ($materialSource === 'custom'): ?>
                    <div class="text-muted small">Sản phẩm mới chưa có định mức. Vui lòng chọn nguyên liệu phù hợp trước khi kiểm tra tồn kho.</div>
                <?php endif; ?>
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="add-material-row">
                <i class="bi bi-plus-lg me-1"></i>Thêm nguyên liệu
            </button>
        </div>
        <form method="post" action="?controller=workshop_plan&action=checkMaterials">
            <input type="hidden" name="IdKeHoachSanXuatXuong" value="<?= htmlspecialchars($plan['IdKeHoachSanXuatXuong']) ?>">
            <div class="table-responsive mb-3">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Nguyên liệu</th>
                            <th class="text-end">Định mức kế hoạch</th>
                            <th class="text-end">Tồn kho</th>
                            <th style="width: 220px">Nhu cầu thực tế</th>
                        </tr>
                    </thead>
                    <tbody id="material-rows">
                    <?php foreach ($materialsForRender as $index => $material): ?>
                        <tr>
                            <td>
                                <select name="materials[<?= $index ?>][IdNguyenLieu]" class="form-select form-select-sm" required>
                                    <option value="">Chọn nguyên liệu</option>
                                    <?php foreach ($materialOptions as $option): ?>
                                        <?php $value = $option['IdNguyenLieu'] ?? ''; ?>
                                        <option value="<?= htmlspecialchars($value) ?>" <?= ($value === ($material['IdNguyenLieu'] ?? null)) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars(($option['TenNL'] ?? $value) . (!empty($option['DonVi']) ? ' (' . $option['DonVi'] . ')' : '')) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="text-end">
                                <?= number_format((int) ($material['SoLuongKeHoach'] ?? 0)) ?><?= !empty($material['DonVi']) ? ' ' . htmlspecialchars($material['DonVi']) : '' ?>
                            </td>
                            <td class="text-end">
                                <?= isset($material['SoLuongTonKho']) ? number_format((int) ($material['SoLuongTonKho'] ?? 0)) : 'Đang tra cứu' ?><?= !empty($material['DonVi']) ? ' ' . htmlspecialchars($material['DonVi']) : '' ?>
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="number" min="0" class="form-control" name="materials[<?= $index ?>][required]" value="<?= htmlspecialchars($material['SoLuongKeHoach'] ?? 0) ?>" required>
                                    <?php if (!empty($material['DonVi'])): ?>
                                        <span class="input-group-text"><?= htmlspecialchars($material['DonVi']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="row mb-3">
                <div class="col-md-8">
                    <label for="note" class="form-label">Ghi chú điều chỉnh / lý do</label>
                    <textarea name="note" id="note" rows="3" class="form-control" placeholder="Ghi chú cho Ban giám đốc theo dõi..."></textarea>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check2-circle me-2"></i>Kiểm tra tồn kho
                </button>
            </div>
        </form>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card p-4 h-100">
                <h5 class="fw-semibold mb-3">Lịch sử phê duyệt/điều chỉnh</h5>
                <?php if (empty($history)): ?>
                    <div class="alert alert-light border mb-0">Chưa có lịch sử.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Thời gian</th>
                                    <th>Hành động</th>
                                    <th>Trạng thái</th>
                                    <th>Người thực hiện</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($history as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($item['NgayThucHien'] ?? 'now'))) ?></td>
                                    <td>
                                        <div class="fw-semibold"><?= htmlspecialchars($item['HanhDong'] ?? '-') ?></div>
                                        <?php if (!empty($item['GhiChu'])): ?>
                                            <div class="text-muted small"><?= htmlspecialchars($item['GhiChu']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td><span class="badge bg-light text-dark"><?= htmlspecialchars($item['TrangThai'] ?? '-') ?></span></td>
                                    <td><?= htmlspecialchars($item['TenNguoiThucHien'] ?? $item['NguoiThucHien'] ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card p-4 h-100">
                <h5 class="fw-semibold mb-3">Yêu cầu kho đã gửi</h5>
                <?php if (empty($warehouseRequests)): ?>
                    <div class="alert alert-light border mb-0">Chưa có yêu cầu xuất kho.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Mã yêu cầu</th>
                                    <th>Ngày tạo</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($warehouseRequests as $request): ?>
                                <tr>
                                    <td class="fw-semibold"><?= htmlspecialchars($request['IdYeuCau']) ?></td>
                                    <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($request['NgayTao'] ?? 'now'))) ?></td>
                                    <td><span class="badge bg-warning bg-opacity-25 text-warning"><?= htmlspecialchars($request['TrangThai'] ?? 'Chờ xử lý') ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-lg-6">
            <div class="card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-semibold mb-1">Phân công nhân sự</h5>
                        <div class="text-muted small">Xưởng trưởng phân công nhân sự cho kế hoạch xưởng.</div>
                    </div>
                </div>
                <?php if (empty($availableEmployees)): ?>
                    <div class="alert alert-light border mb-0">Chưa có nhân sự sản xuất được gán cho xưởng.</div>
                <?php else: ?>
                    <form method="post" action="?controller=workshop_plan&action=assignEmployees">
                        <input type="hidden" name="IdKeHoachSanXuatXuong" value="<?= htmlspecialchars($plan['IdKeHoachSanXuatXuong']) ?>">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Chọn nhân sự sản xuất</label>
                            <select name="employee_ids[]" class="form-select" multiple size="6">
                                <?php
                                    $assignedIds = array_column($planAssignments, 'IdNhanVien');
                                ?>
                                <?php foreach ($availableEmployees as $employee): ?>
                                    <?php $employeeId = $employee['IdNhanVien'] ?? ''; ?>
                                    <option value="<?= htmlspecialchars($employeeId) ?>" <?= in_array($employeeId, $assignedIds, true) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($employee['HoTen'] ?? $employeeId) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="text-muted small mt-2">Giữ Ctrl/Cmd để chọn nhiều nhân sự.</div>
                        </div>
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi bi-people me-2"></i>Lưu phân công
                        </button>
                    </form>
                <?php endif; ?>
                <?php if (!empty($planAssignments)): ?>
                    <div class="mt-4">
                        <div class="fw-semibold mb-2">Nhân sự đã phân công</div>
                        <ul class="list-unstyled mb-0">
                            <?php foreach ($planAssignments as $assignment): ?>
                                <li class="d-flex align-items-center gap-2 mb-1">
                                    <i class="bi bi-person-check text-primary"></i>
                                    <span><?= htmlspecialchars($assignment['HoTen'] ?? $assignment['IdNhanVien']) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-semibold mb-1">Cập nhật tiến độ cuối ca</h5>
                        <div class="text-muted small">Chỉ mở khi đã đủ nguyên liệu và có phân công nhân sự.</div>
                    </div>
                </div>
                <?php if (!$canUpdateProgress): ?>
                    <div class="alert alert-light border mb-0">Cần đủ nguyên liệu và có phân công để cập nhật tiến độ.</div>
                <?php else: ?>
                    <form method="post" action="?controller=workshop_plan&action=updateProgress">
                        <input type="hidden" name="IdKeHoachSanXuatXuong" value="<?= htmlspecialchars($plan['IdKeHoachSanXuatXuong']) ?>">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ca làm việc</label>
                            <select name="shift_id" class="form-select" required>
                                <option value="">Chọn ca làm việc</option>
                                <?php foreach ($workShifts as $shift): ?>
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
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-clipboard-check me-2"></i>Cập nhật tiến độ
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addRowBtn = document.getElementById('add-material-row');
    const rowsContainer = document.getElementById('material-rows');
    const optionTemplate = <?= json_encode($materialOptionHtml, JSON_UNESCAPED_UNICODE) ?>;

    if (addRowBtn && rowsContainer) {
        addRowBtn.addEventListener('click', function() {
            const index = rowsContainer.querySelectorAll('tr').length;
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <select name="materials[${index}][IdNguyenLieu]" class="form-select form-select-sm" required>
                        ${optionTemplate}
                    </select>
                </td>
                <td class="text-end">0</td>
                <td class="text-end">Đang tra cứu</td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="number" min="0" class="form-control" name="materials[${index}][required]" value="0" required>
                    </div>
                </td>
            `;
            rowsContainer.appendChild(row);
        });
    }
});
</script>
