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
        <h5 class="fw-semibold mb-3">Nhu cầu nguyên liệu thực tế</h5>
        <?php if (empty($materials)): ?>
            <div class="alert alert-light border">Chưa cấu hình chi tiết nguyên liệu cho kế hoạch này.</div>
        <?php else: ?>
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
                        <tbody>
                        <?php foreach ($materials as $index => $material): ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold mb-1"><?= htmlspecialchars($material['TenNL'] ?? $material['IdNguyenLieu']) ?></div>
                                    <div class="text-muted small">Mã: <?= htmlspecialchars($material['IdNguyenLieu']) ?></div>
                                </td>
                                <td class="text-end">
                                    <?= number_format((int) ($material['SoLuongKeHoach'] ?? 0)) ?><?= $material['DonVi'] ? ' ' . htmlspecialchars($material['DonVi']) : '' ?>
                                </td>
                                <td class="text-end">
                                    <?= number_format((int) ($material['SoLuongTonKho'] ?? 0)) ?><?= $material['DonVi'] ? ' ' . htmlspecialchars($material['DonVi']) : '' ?>
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <input type="hidden" name="materials[<?= $index ?>][IdNguyenLieu]" value="<?= htmlspecialchars($material['IdNguyenLieu']) ?>">
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
                <div class="mb-3">
                    <label for="note" class="form-label">Ghi chú điều chỉnh / lý do</label>
                    <textarea name="note" id="note" rows="3" class="form-control" placeholder="Ghi chú cho Ban giám đốc theo dõi..."></textarea>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2-circle me-2"></i>Kiểm tra tồn kho
                    </button>
                </div>
            </form>
        <?php endif; ?>
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
<?php endif; ?>
