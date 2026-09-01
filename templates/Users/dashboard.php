<?php
/**
 * @var \App\View\AppView $this
 * @var \Authentication\Identity $authUser
 * @var int $totalProducts
 * @var int $totalUsers
 * @var int $totalEnquiries
 * @var array<string, array{sales: float, profit: float}> $revenueStats
 * @var \Cake\ORM\ResultSet $lowStockProducts
 */

$this->assign('title', 'Admin Dashboard');
$this->Html->css('admincontact', ['block' => true]);
$this->Html->css('schedule', ['block' => true]);

$role = $authUser->get('role');
$todayStr = (new DateTime('today'))->format('Y-m-d');

// Stat tile: label + colour-coded icon chip on top, big count below, then a
// "View X" footer row so the card reads as info-plus-an-action rather than
// just a nav duplicate. These are counts the sidebar nav can't show — items
// with nothing to count (Staff Schedule, CMS) stay sidebar-only. Accent
// alternates emerald/gold across a section so the row reads apart.
$arrowIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>';
$accents = ['', 'gold'];
$accentIndex = 0;
$statTile = function (string $icon, int $value, string $label, array $url) use (&$accentIndex, $accents, $arrowIcon) {
    $accent = $accents[$accentIndex++ % count($accents)];
    $iconClass = 'stat-card__icon' . ($accent ? ' stat-card__icon--' . $accent : '');

    $inner = '<span class="stat-card__top">'
        . '<span class="stat-card__label">' . h($label) . '</span>'
        . '<span class="' . $iconClass . '">' . $this->iconSvg($icon) . '</span>'
        . '</span>'
        . '<span class="stat-card__value">' . h((string)$value) . '</span>'
        . '<span class="stat-card__footer">View ' . h($label) . $arrowIcon . '</span>';

    return $this->Html->link($inner, $url, ['class' => 'stat-card', 'escape' => false]);
};
?>

<div class="page-header-row">
    <div>
        <h2 class="page-title">Hi, <?= h($authUser->first_name) ?></h2>
    </div>
</div>

<section class="dash-section">
    <div class="orders-summary-header">
        <div>
            <h4>Revenue Summary</h4>
            <p>Track sales and estimated profit across different time periods.</p>
        </div>
    </div>

    <div class="orders-stat-cards">
        <?php foreach ([
            'Today'      => $revenueStats['today'],
            'This Week'  => $revenueStats['week'],
            'This Month' => $revenueStats['month'],
            'All Time'   => $revenueStats['all'],
        ] as $label => $s): ?>
            <div class="orders-stat-card">
                <div class="stat-top">
                    <span class="stat-label"><?= h($label) ?></span>
                    <span class="stat-currency">AUD</span>
                </div>

                <div class="stat-main">
                    $<?= number_format($s['profit'], 2) ?>
                </div>

                <div class="stat-sub">
                    Sales $<?= number_format($s['sales'], 2) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="dash-section">
    <div class="dash-subhead"><h3>Store</h3></div>
    <div class="tile-grid">
        <?= $statTile('tag', $totalProducts, 'Products', ['controller' => 'Products', 'action' => 'index']) ?>
        <?= $statTile('mail', $totalEnquiries, 'Contact Submissions', ['controller' => 'ContactSubmissions', 'action' => 'index']) ?>
    </div>
</section>

<?php if ($role === 'admin'): ?>
    <section class="dash-section">
        <div class="dash-subhead"><h3>Admin</h3></div>
        <div class="tile-grid">
            <?= $statTile('user', $totalUsers, 'Users', ['controller' => 'Users', 'action' => 'index']) ?>
        </div>
    </section>
<?php endif; ?>

<?php if ($role === 'staff' && !empty($upcomingDays)): ?>
    <div class="dashboard-schedule">
        <div class="dashboard-schedule-header">
            <span class="dashboard-schedule-title">My Schedule</span>
            <span class="dashboard-schedule-range"><?= h($weekRange) ?></span>
            <?= $this->Html->link('View all', ['controller' => 'Schedule', 'action' => 'index'], ['class' => 'dashboard-schedule-link']) ?>
        </div>

        <div class="dashboard-schedule-grid">
            <?php foreach ($upcomingDays as $day): ?>
                <?php
                $dateStr = $day->format('Y-m-d');
                $shift = $schedule[$dateStr] ?? null;
                $isToday = $dateStr === $todayStr;
                ?>
                <div class="dashboard-schedule-day <?= $isToday ? 'dashboard-schedule-day--today' : '' ?> <?= $shift ? 'dashboard-schedule-day--on' : 'dashboard-schedule-day--off' ?>">
                    <span class="dashboard-schedule-label"><?= h($day->format('D')) ?></span>
                    <span class="dashboard-schedule-date"><?= h($day->format('j M')) ?></span>
                    <?php if ($shift): ?>
                        <span class="dashboard-schedule-time"><?= h($shift->time_range) ?></span>
                    <?php else: ?>
                        <span class="dashboard-schedule-off">Off</span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<?php if (!$lowStockProducts->isEmpty()): ?>
    <div class="low-stock-warning">
        <div class="low-stock-header">
            <div>
                <h3>Low Stock Products</h3>
            </div>
        </div>

        <div class="low-stock-list">
            <?php foreach ($lowStockProducts as $product): ?>
                <div class="low-stock-row">
                    <div class="low-stock-row-top">
                        <span class="low-stock-name"><?= h($product->name) ?></span>
                        <?php if ($role === 'admin'): ?>
                            <?= $this->Html->link(
                                'Restock',
                                ['controller' => 'Products', 'action' => 'edit', $product->id],
                                ['class' => 'low-stock-btn']
                            ) ?>
                        <?php endif; ?>
                    </div>

                    <div class="low-stock-pills">
                        <?php foreach ($product->product_variants as $v): ?>
                            <?php if ($v->stock < 5): ?>
                                <span class="low-stock-pill <?= $v->stock === 0 ? 'pill-zero' : 'pill-low' ?>">
                                    <?= h($v->size) ?>: <?= $v->stock ?>
                                </span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
