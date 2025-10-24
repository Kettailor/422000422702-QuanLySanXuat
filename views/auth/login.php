<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập hệ thống</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/styles.css">
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height: 100vh; background: var(--light-bg);">
<div class="card shadow-lg" style="max-width: 420px; width: 100%; border-radius: 24px;">
    <div class="card-body p-5">
        <div class="text-center mb-4">
            <div class="display-6 mb-3 text-primary"><i class="bi bi-gear-wide-connected"></i></div>
            <h3 class="fw-bold">Sinh viên 5 Tốt ERP</h3>
            <p class="text-muted">Đăng nhập để tiếp tục quản trị hệ thống sản xuất.</p>
        </div>
        <?php if (!empty($flash)): ?>
            <div class="alert alert-<?= $flash['type'] ?>"><?= htmlspecialchars($flash['message']) ?></div>
        <?php endif; ?>
        <form action="?controller=auth&action=login" method="post" class="row g-3">
            <div class="col-12">
                <label class="form-label">Tên đăng nhập</label>
                <input type="text" name="username" class="form-control" required autofocus>
            </div>
            <div class="col-12">
                <label class="form-label">Mật khẩu</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember">
                    <label class="form-check-label" for="remember">Ghi nhớ</label>
                </div>
                <a href="#" class="small text-decoration-none">Quên mật khẩu?</a>
            </div>
            <div class="col-12">
                <button class="btn btn-primary w-100" type="submit">Đăng nhập</button>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
