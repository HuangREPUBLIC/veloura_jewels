<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Order $order
 */
$this->assign('title', 'Order ' . $order->id);
$this->assign('crumbRecord', 'Order #' . h($order->id));

$statusClass = [
    'paid'      => 'status-pill status-pill-paid',
    'pending'   => 'status-pill status-pill-pending',
    'cancelled' => 'status-pill status-pill-cancelled',
];

$totalProfit = 0;
$itemCount = 0;
foreach ($order->order_items as $item) {
    $itemCount += (int)$item->quantity;
    if ($item->product) {
        $totalProfit += ($item->unit_price - $item->product->purchase_price) * $item->quantity;
    }
}
$currency = strtoupper(h($order->currency));
?>

<div class="page-header-row">
    <div>
        <p class="cms-eyebrow">Orders</p>
        <h2 class="page-title">Order #<?= h($order->id) ?></h2>
    </div>
    <div class="cms-header-right">
        <span class="<?= $statusClass[$order->status] ?? 'status-pill' ?>"><?= h(ucfirst($order->status)) ?></span>
    </div>
</div>

<div class="orders-stat-cards">
    <div class="orders-stat-card">
        <div class="stat-top">
            <span class="stat-label"><?= __('Total') ?></span>
            <span class="stat-currency"><?= $currency ?></span>
        </div>
        <div class="stat-main">$<?= number_format((float)$order->total_amount, 2) ?></div>
    </div>

    <div class="orders-stat-card">
        <div class="stat-top">
            <span class="stat-label"><?= __('Profit') ?></span>
            <span class="stat-currency"><?= $currency ?></span>
        </div>
        <div class="stat-main <?= $totalProfit >= 0 ? 'admin-profit-positive' : 'admin-profit-negative' ?>">
            $<?= number_format($totalProfit, 2) ?>
        </div>
    </div>

    <div class="orders-stat-card">
        <div class="stat-top">
            <span class="stat-label"><?= __('Items') ?></span>
        </div>
        <div class="stat-main"><?= $itemCount ?></div>
    </div>

    <div class="orders-stat-card">
        <div class="stat-top">
            <span class="stat-label"><?= __('Placed') ?></span>
        </div>
        <div class="stat-main admin-stat-main--date"><?= $order->created ? $order->created->format('j M Y') : '' ?></div>
    </div>
</div>

<h4 class="admin-view-section-title"><?= __('Order Items') ?></h4>

<?php if (!empty($order->order_items)): ?>
    <table class="view-table view-table--list admin-view-table-spaced">
        <thead>
        <tr>
            <th><?= __('Product') ?></th>
            <th><?= __('Size') ?></th>
            <th><?= __('Cost') ?></th>
            <th><?= __('Unit Price') ?></th>
            <th><?= __('Qty') ?></th>
            <th><?= __('Subtotal') ?></th>
            <th><?= __('Profit') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($order->order_items as $item): ?>
            <?php
            $purchasePrice = $item->product ? (float)$item->product->purchase_price : null;
            $itemProfit = $purchasePrice !== null ? ($item->unit_price - $purchasePrice) * $item->quantity : null;
            $image = $item->product->product_images[0] ?? null;
            ?>
            <tr>
                <td class="product-cell">
                    <div class="product-media">
                        <?php if ($image): ?>
                            <img class="admin-thumb"
                                 src="<?= $this->Url->image('products/' . h($image->filename)) ?>"
                                 alt="">
                        <?php else: ?>
                            <span class="admin-thumb admin-thumb--empty"></span>
                        <?php endif; ?>
                        <span class="product-name"><?= h($item->product_name) ?></span>
                    </div>
                </td>
                <td><?= h($item->selected_size) ?: '' ?></td>
                <td><?= $purchasePrice !== null ? '$' . number_format($purchasePrice, 2) : '' ?></td>
                <td>$<?= number_format((float)$item->unit_price, 2) ?></td>
                <td><?= h($item->quantity) ?></td>
                <td>$<?= number_format((float)$item->subtotal, 2) ?></td>
                <td class="<?= ($itemProfit !== null && $itemProfit >= 0) ? 'admin-profit-positive' : 'admin-profit-negative' ?>">
                    <?= $itemProfit !== null ? '$' . number_format($itemProfit, 2) : '' ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p class="text-muted">No items found for this order.</p>
<?php endif; ?>

<h4 class="admin-view-section-title"><?= __('Order Details') ?></h4>

<table class="view-table">
    <tr>
        <th><?= __('Customer Email') ?></th>
        <td><?= h($order->customer_email) ?: '<span class="text-muted">Not provided</span>' ?></td>
    </tr>
    <tr>
        <th><?= __('Stripe Session ID') ?></th>
        <td><span class="admin-mono"><?= h($order->stripe_session_id) ?: '' ?></span></td>
    </tr>
    <tr>
        <th><?= __('Payment Intent ID') ?></th>
        <td><span class="admin-mono"><?= h($order->stripe_payment_intent_id) ?: '' ?></span></td>
    </tr>
    <tr>
        <th><?= __('Created') ?></th>
        <td><?= h($order->created) ?></td>
    </tr>
    <tr>
        <th><?= __('Modified') ?></th>
        <td><?= h($order->modified) ?></td>
    </tr>
</table>
