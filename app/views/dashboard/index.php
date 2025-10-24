<section class="dashboard">
    <div class="dashboard__header">
        <h1>Dashboard</h1>
        <p>Tổng quan sản xuất & vận hành</p>
    </div>

    <div class="dashboard__grid">
        <?php foreach ($data['statistics'] as $stat): ?>
            <div class="card card--stat">
                <span class="card__label"><?= htmlspecialchars($stat['label']) ?></span>
                <span class="card__value"><?= htmlspecialchars($stat['value']) ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="dashboard__main">
        <section class="card card--stretch">
            <h2>Hoạt động trong tháng</h2>
            <ul class="list list--activities">
                <?php foreach ($data['activities'] as $activity): ?>
                    <li class="list__item">
                        <span class="list__title"><?= htmlspecialchars($activity['title']) ?></span>
                        <p class="list__desc"><?= htmlspecialchars($activity['description']) ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
        <section class="card card--stretch">
            <h2>Thông báo quan trọng</h2>
            <ul class="list list--alerts">
                <?php foreach ($data['alerts'] as $alert): ?>
                    <li class="list__item">
                        <span class="list__badge list__badge--<?= htmlspecialchars($alert['type']) ?>"><?= htmlspecialchars($alert['highlight']) ?></span>
                        <p class="list__desc"><?= htmlspecialchars($alert['message']) ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </div>

    <div class="dashboard__timeline card">
        <h2>Lịch làm việc</h2>
        <ul class="timeline">
            <?php foreach ($data['timeline'] as $item): ?>
                <li class="timeline__item">
                    <span class="timeline__date"><?= htmlspecialchars($item['date']) ?></span>
                    <div>
                        <span class="timeline__title"><?= htmlspecialchars($item['title']) ?></span>
                        <span class="timeline__status"><?= htmlspecialchars($item['status']) ?></span>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
