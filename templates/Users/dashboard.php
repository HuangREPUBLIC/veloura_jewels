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
?>

<div class="page-header-row">
    <div>
        <h2 class="page-title">Hi, <?= h($authUser->first_name) ?></h2>
        <p class="page-subtitle">Here's how Veloura Jewels is doing today.</p>
    </div>
    <div class="dash-date-chip">
        <?= $this->iconSvg('calendar') ?>
        <?= h((new DateTime('today'))->format('l, j F Y')) ?>
    </div>
</div>

<?php
$renderBars = function (array $trend) use ($todayStr) {
    $max = 0.0;
    foreach ($trend as $d) {
        $max = max($max, $d['amount']);
    }
    if ($max <= 0) {
        echo '<p class="revenue-chart-empty">No sales in this period.</p>';
        return;
    }
    $count = count($trend);
    foreach ($trend as $i => $d) {
        $pct = $max > 0 ? round(($d['amount'] / $max) * 100, 1) : 0;
        $isToday = $d['date'] === $todayStr;
        $showLabel = $count <= 12 || $i === 0 || $i === $count - 1 || $i % 6 === 0;
        echo '<div class="revenue-bar-col">'
            . '<div class="revenue-bar-track">'
            . '<div class="revenue-bar' . ($isToday ? ' revenue-bar--today' : '') . '" style="height: ' . $pct . '%" aria-label="' . h($d['label']) . ': $' . number_format($d['amount'], 2) . '"></div>'
            . '</div>'
            . '<span class="revenue-bar-label">' . ($showLabel ? h($d['label']) : '') . '</span>'
            . '</div>';
    }
};
?>
<section class="dash-section">
    <div class="revenue-panel">
        <div class="revenue-panel-header">
            <div>
                <h4>Revenue</h4>
                <p>Estimated profit is sales minus each item's purchase price.</p>
            </div>
            <div class="revenue-panel-stats">
                <div class="revenue-stat">
                    <span class="revenue-stat-label">This Month</span>
                    <span class="revenue-stat-value">$<?= number_format($revenueStats['month']['profit'], 2) ?></span>
                    <span class="revenue-stat-sub">Sales $<?= number_format($revenueStats['month']['sales'], 2) ?></span>
                </div>
                <div class="revenue-stat">
                    <span class="revenue-stat-label">All Time</span>
                    <span class="revenue-stat-value">$<?= number_format($revenueStats['all']['profit'], 2) ?></span>
                    <span class="revenue-stat-sub">Sales $<?= number_format($revenueStats['all']['sales'], 2) ?></span>
                </div>
            </div>
        </div>

        <div class="revenue-panel-chart-head">
            <span>Sales</span>
            <div class="revenue-chart-tabs" role="tablist">
                <button type="button" class="revenue-chart-tab" data-chart-tab="week" role="tab">Week</button>
                <button type="button" class="revenue-chart-tab is-active" data-chart-tab="month" role="tab" aria-selected="true">Month</button>
                <button type="button" class="revenue-chart-tab" data-chart-tab="year" role="tab">Year</button>
            </div>
        </div>
        <div class="revenue-chart" data-chart-panel="week" hidden><?php $renderBars($revenueTrendWeek); ?></div>
        <div class="revenue-chart" data-chart-panel="month"><?php $renderBars($revenueTrendMonth); ?></div>
        <div class="revenue-chart" data-chart-panel="year" hidden><?php $renderBars($revenueTrendYear); ?></div>
    </div>
</section>

<section class="dash-section">
    <div class="dash-subhead"><h3>Top Selling Products</h3></div>
    <?php if ($topSellingProducts->isEmpty()): ?>
        <div class="admin-empty-state">
            <span class="admin-empty-state__icon"><?= $this->iconSvg('tag') ?></span>
            <p class="admin-empty-state__title">No sales yet</p>
        </div>
    <?php else: ?>
        <div class="top-products-list">
            <?php foreach ($topSellingProducts as $row):
                $product = $topProductsById[$row->product_id] ?? null;
                if (!$product) {
                    continue;
                }
                $img = !empty($product->product_images) ? $product->product_images[0]->filename : null;
                ?>
                <?= $this->Html->link(
                    ($img
                        ? $this->Html->image('products/' . $img, ['alt' => '', 'class' => 'top-product-thumb'])
                        : '<span class="top-product-thumb top-product-thumb--empty"></span>')
                    . '<span class="top-product-name">' . h($product->name) . '</span>'
                    . '<span class="top-product-units">' . (int)$row->units_sold . ' sold</span>',
                    ['controller' => 'Products', 'action' => 'view', $product->id],
                    ['class' => 'top-product-row', 'escape' => false]
                ) ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

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
