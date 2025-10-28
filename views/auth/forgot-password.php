<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu</title>
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
            <p class="text-muted">Vui lòng nhập tên đăng nhập của bạn để đặt lại mật khẩu.</p>
        </div>
        <?php if (!empty($flash)): ?>
            <div class="alert alert-<?= $flash['type'] ?>"><?= htmlspecialchars($flash['message']) ?></div>
        <?php endif; ?>
        <form method="post" class="row g-3">
            <div class="col-12">
                <label class="form-label">Tên đăng nhập</label>
                <input type="text" name="username" class="form-control" required autofocus>
            </div>
            <div class="col-12">
              <button class="btn btn-primary w-100" type="submit">Đặt lại mật khẩu</button>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
