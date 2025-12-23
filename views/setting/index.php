<?php
$user = $user ?? [];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Cài đặt tài khoản</h3>
        <p class="text-muted mb-0">Quản lý bảo mật và thông tin đăng nhập của bạn.</p>
    </div>
    <a href="?controller=auth&action=profile" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Hồ sơ cá nhân
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card p-4 h-100">
            <h5 class="mb-3">Thông tin tài khoản</h5>
            <ul class="list-unstyled mb-0">
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span>ID người dùng</span>
                    <span class="fw-semibold"><?= htmlspecialchars($user['IdNguoiDung'] ?? '-') ?></span>
                </li>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span>Tên đăng nhập</span>
                    <span class="fw-semibold"><?= htmlspecialchars($user['TenDangNhap'] ?? '-') ?></span>
                </li>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span>Vai trò</span>
                    <span class="fw-semibold"><?= htmlspecialchars($user['ActualTenVaiTro'] ?? ($user['TenVaiTro'] ?? '-')) ?></span>
                </li>
                <li class="d-flex justify-content-between py-2">
                    <span>Trạng thái</span>
                    <span class="badge bg-light text-dark"><?= htmlspecialchars($user['TrangThai'] ?? 'Hoạt động') ?></span>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card p-4 h-100">
            <h5 class="mb-3">Bảo mật & tuỳ chỉnh</h5>
            <div class="d-grid gap-3">
                <div class="border rounded-3 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">Đổi mật khẩu</div>
                            <div class="text-muted small">Cập nhật mật khẩu định kỳ để bảo vệ tài khoản.</div>
                        </div>
                        <a href="?controller=setting&action=changePassword" class="btn btn-outline-primary">Thực hiện</a>
                    </div>
                </div>
                <div class="border rounded-3 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">Thông tin hồ sơ</div>
                            <div class="text-muted small">Kiểm tra thông tin nhân viên và quyền truy cập.</div>
                        </div>
                        <a href="?controller=auth&action=profile" class="btn btn-outline-secondary">Xem hồ sơ</a>
                    </div>
                </div>
                <div class="border rounded-3 p-3 bg-light">
                    <div class="fw-semibold mb-1">Lưu ý bảo mật</div>
                    <div class="text-muted small">Không chia sẻ tài khoản, đăng xuất khi rời khỏi thiết bị.</div>
                </div>
            </div>
        </div>
    </div>
</div>
