<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="h4 mb-3">Giả lập vai trò</h1>
                    <p class="text-muted small">Chọn vai trò để kiểm thử giao diện và phân quyền. Mọi thao tác sẽ được ghi log để truy vết.</p>

                    <?php if (!empty($impersonatedRole)): ?>
                        <div class="alert alert-warning d-flex align-items-center justify-content-between" role="alert">
                            <div>
                                <strong>Đang giả lập:</strong>
                                <?= htmlspecialchars($impersonatedRole['TenVaiTro'] ?? $impersonatedRole['IdVaiTro']) ?>
                                <span class="badge bg-light text-muted ms-2">
                                    #<?= htmlspecialchars($impersonatedRole['IdVaiTro']) ?>
                                </span>
                            </div>
                            <a class="btn btn-sm btn-outline-danger" href="?impersonation=stop">Hủy giả lập</a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info" role="alert">
                            Hiện tại bạn đang sử dụng quyền mặc định.
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($canToggleAdminBypass)): ?>
                        <div class="alert alert-light border mb-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <div class="fw-semibold">Toàn quyền quản trị hệ thống</div>
                                    <small class="text-muted">
                                        <?= ($adminBypassEnabled ?? true)
                                            ? 'Đang bật: bỏ qua mọi giới hạn để kiểm thử nhanh.'
                                            : 'Đang tắt: áp dụng giới hạn theo vai trò để kiểm tra phân quyền.' ?>
                                    </small>
                                </div>
                                <form method="post" action="?controller=adminImpersonation&action=updateBypass">
                                    <div class="form-check form-switch m-0">
                                        <input class="form-check-input" type="checkbox" role="switch" id="admin_bypass" name="admin_bypass" value="1" <?= ($adminBypassEnabled ?? true) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="admin_bypass">Bật toàn quyền</label>
                                    </div>
                                    <div class="text-end mt-3">
                                        <button type="submit" class="btn btn-outline-primary btn-sm">Lưu cấu hình quyền</button>
                                    </div>
                                </form>
                            </div>
                            <div class="d-flex flex-wrap gap-2 mt-3">
                                <form method="post" action="?controller=adminImpersonation&action=updateBypass">
                                    <input type="hidden" name="admin_bypass" value="0">
                                    <button type="submit" class="btn btn-outline-secondary btn-sm">Luồng chính</button>
                                </form>
                                <form method="post" action="?controller=adminImpersonation&action=updateBypass">
                                    <input type="hidden" name="admin_bypass" value="1">
                                    <button type="submit" class="btn btn-outline-success btn-sm">Luồng test (toàn quyền)</button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="?controller=adminImpersonation&action=store">
                        <div class="mb-3">
                            <label for="role_id" class="form-label">Vai trò muốn giả lập</label>
                            <select name="role_id" id="role_id" class="form-select" required>
                                <option value="">-- Chọn vai trò --</option>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= htmlspecialchars($role['IdVaiTro']) ?>">
                                        <?= htmlspecialchars($role['TenVaiTro'] ?? $role['IdVaiTro']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Để dừng giả lập, hãy chọn nút "Hủy giả lập" hoặc gửi biểu mẫu với lựa chọn trống.</small>
                            <button type="submit" class="btn btn-primary">Bắt đầu giả lập</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
