<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Order> $orders
 * @var string $q
 * @var string $status
 * @var string $range
 * @var string $dateFrom
 * @var string $dateTo
 */
$this->assign('title', 'Orders');

$currentQuery = $this->request->getQueryParams();
$filterLink = function (array $params, string $label, bool $active) {
    $url = ['?' => array_merge($this->request->getQueryParams(), $params)];
    return sprintf('<a class="filter-btn%s" href="%s">%s</a>', $active ? ' active' : '', h($this->Url->build($url)), h($label));
};
?>

<div class="page-header-row">
    <div>
        <p class="cms-eyebrow">Store</p>
        <h2 class="page-title">Orders</h2>
    </div>
    <div class="cms-header-right">
        <?= $this->element('per_page_select') ?>
    </div>
</div>

<?= $this->Form->create(null, ['type' => 'get', 'class' => 'admin-search']) ?>
    <input type="text" name="q" value="<?= h($q) ?>" placeholder="Search by customer email" class="admin-search__input">
    <button type="submit" class="btn-sm"><?= $this->iconSvg('search') ?></button>
<?= $this->Form->end() ?>

<div class="orders-filter-section">
    <div class="orders-filter-grid">
        <div class="orders-filter-group">
            <span class="orders-filter-label">Date</span>
            <div class="orders-filter-controls">
                <?= $filterLink(['range' => null, 'date_from' => null, 'date_to' => null], 'All', $range === '' && $dateFrom === '') ?>
                <?= $filterLink(['range' => 'today', 'date_from' => null, 'date_to' => null], 'Today', $range === 'today') ?>
                <?= $filterLink(['range' => 'week', 'date_from' => null, 'date_to' => null], 'This Week', $range === 'week') ?>
                <?= $filterLink(['range' => 'month', 'date_from' => null, 'date_to' => null], 'This Month', $range === 'month') ?>
            </div>
        </div>

        <div class="orders-filter-group">
            <span class="orders-filter-label">Status</span>
            <div class="orders-filter-controls">
                <?= $filterLink(['status' => null], 'All', $status === '') ?>
                <?= $filterLink(['status' => 'paid'], 'Paid', $status === 'paid') ?>
                <?= $filterLink(['status' => 'pending'], 'Pending', $status === 'pending') ?>
                <?= $filterLink(['status' => 'cancelled'], 'Cancelled', $status === 'cancelled') ?>
            </div>
        </div>

        <?= $this->Form->create(null, ['type' => 'get']) ?>
            <?php foreach (['q', 'status', 'range'] as $preserve): ?>
                <?php if (!empty($currentQuery[$preserve])): ?>
                    <?= $this->Form->hidden($preserve, ['value' => $currentQuery[$preserve]]) ?>
                <?php endif; ?>
            <?php endforeach; ?>
            <div class="orders-filter-group">
                <span class="orders-filter-label">Custom Range</span>
                <div class="orders-date-range">
                    <input type="date" name="date_from" value="<?= h($dateFrom) ?>" class="date-field" max="<?= date('Y-m-d') ?>">
                    <span class="date-range-to">to</span>
                    <input type="date" name="date_to" value="<?= h($dateTo) ?>" class="date-field" max="<?= date('Y-m-d') ?>">
                    <button type="submit" class="btn-sm">Go</button>
                </div>
            </div>
        <?= $this->Form->end() ?>
    </div>
</div>

<?php if ($orders->isEmpty()): ?>
    <div class="admin-empty-state">
        <span class="admin-empty-state__icon"><?= $this->iconSvg('bag') ?></span>
        <p class="admin-empty-state__title">No orders match these filters</p>
        <p class="admin-empty-state__sub">Try a different date range or status, or <?= $this->Html->link('clear all filters', ['action' => 'index']) ?>.</p>
    </div>
<?php else: ?>
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Status</th>
                    <th><?= $this->Paginator->sort('total_amount', 'Total') ?></th>
                    <th>Profit</th>
                    <th><?= $this->Paginator->sort('created', 'Date') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $statusClass = ['paid' => 'status-badge--success', 'pending' => 'status-badge--pending', 'cancelled' => 'status-badge--error'];
                foreach ($orders as $order):
                    $orderProfit = 0;
                    foreach ($order->order_items as $item) {
                        if ($item->product) {
                            $orderProfit += ($item->unit_price - $item->product->purchase_price) * $item->quantity;
                        }
                    }
                    $cls = $statusClass[$order->status] ?? 'status-badge--pending';
                    ?>
                    <tr>
                        <td><?= h($order->customer_email) ?></td>
                        <td><span class="status-badge <?= $cls ?>"><?= h(ucfirst($order->status)) ?></span></td>
                        <td>$<?= number_format((float)$order->total_amount, 2) ?> <?= strtoupper(h($order->currency)) ?></td>
                        <td class="<?= $orderProfit >= 0 ? 'admin-profit-positive' : 'admin-profit-negative' ?>">$<?= number_format($orderProfit, 2) ?></td>
                        <td><?= h($order->created->format('d M Y H:i')) ?></td>
                        <td class="actions">
                            <?= $this->Html->link(
                                $this->iconSvg('eye'),
                                ['action' => 'view', $order->id],
                                ['escape' => false, 'aria-label' => __('View')],
                            ) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?= $this->element('paginator') ?>
<?php endif; ?>
