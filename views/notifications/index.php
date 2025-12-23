<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Thông báo</h3>
        <p class="text-muted mb-0">Theo dõi các thông báo quan trọng trong hệ thống.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="?controller=notifications&action=markAll" class="btn btn-outline-primary">Đánh dấu đã đọc</a>
        <a href="?controller=dashboard&action=index" class="btn btn-outline-secondary">Quay lại</a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="list-group list-group-flush">
        <?php if (!empty($notifications)): ?>
            <?php foreach ($notifications as $notification): ?>
                <?php if (!is_array($notification)) {
                    continue;
                } ?>
                <?php
                    $title = $notification['title'] ?? 'Thông báo hệ thống';
                    $message = $notification['message'] ?? null;
                    $time = $notification['time'] ?? null;
                    $link = $notification['link'] ?? null;
                    $isRead = !empty($notification['is_read']) || !empty($notification['read_at']);
                    $id = $notification['id'] ?? null;
                    $redirect = $link ?: '?controller=notifications&action=index';
                    $readLink = $id ? '?controller=notifications&action=read&id=' . urlencode($id) . '&redirect=' . urlencode($redirect) : $redirect;
                ?>
                <div class="list-group-item list-group-item-action <?= $isRead ? '' : 'notification-unread' ?>">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <div class="fw-semibold"><?= htmlspecialchars($title) ?></div>
                            <?php if ($message): ?>
                                <div class="text-muted small mt-1"><?= htmlspecialchars($message) ?></div>
                            <?php endif; ?>
                        </div>
                        <?php if ($time): ?>
                            <small class="text-muted"><?= htmlspecialchars($time) ?></small>
                        <?php endif; ?>
                    </div>
                    <?php if ($id): ?>
                        <a href="<?= htmlspecialchars($readLink) ?>" class="stretched-link"></a>
                    <?php elseif ($link): ?>
                        <a href="<?= htmlspecialchars($link) ?>" class="stretched-link"></a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="list-group-item text-muted">Chưa có thông báo.</div>
        <?php endif; ?>
    </div>
</div>
