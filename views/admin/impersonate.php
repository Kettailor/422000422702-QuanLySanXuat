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

                    <?php if (!empty($canToggleAdminFlow)): ?>
                        <div class="alert alert-light border mb-3">
                            <div class="fw-semibold mb-1">Luồng quản trị</div>
                            <p class="text-muted small mb-3">
                                Luồng chính: sử dụng đầy đủ chức năng cá nhân và quản trị theo quyền chuẩn.
                                Luồng test: mở toàn quyền và truy cập toàn bộ dữ liệu để kiểm thử nhanh.
                            </p>
                            <div class="d-flex flex-wrap gap-2">
                                <form method="post" action="?controller=adminImpersonation&action=updateFlow">
                                    <input type="hidden" name="admin_flow" value="main">
                                    <button type="submit" class="btn btn-outline-secondary btn-sm<?= ($adminFlow ?? 'main') === 'main' ? ' active' : '' ?>">
                                        Luồng chính
                                    </button>
                                </form>
                                <form method="post" action="?controller=adminImpersonation&action=updateFlow">
                                    <input type="hidden" name="admin_flow" value="test">
                                    <button type="submit" class="btn btn-outline-success btn-sm<?= ($adminFlow ?? 'main') === 'test' ? ' active' : '' ?>">
                                        Luồng test (toàn quyền)
                                    </button>
                                </form>
                            </div>
                            <div class="mt-3">
                                <span class="badge <?= ($adminFlow ?? 'main') === 'test' ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= ($adminFlow ?? 'main') === 'test' ? 'Đang ở luồng test' : 'Đang ở luồng chính' ?>
                                </span>
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
