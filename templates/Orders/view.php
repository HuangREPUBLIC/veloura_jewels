<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Order $order
 */
$this->assign('title', 'Order ' . $order->id);
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>

<div class="admin-wrapper">
    <div class="orders view content">
        <?= $this->Html->link(__('← Back'), ['action' => 'index'], ['class' => 'back-link']) ?>

        <div class="page-header-row">
            <div>
                <h3 class="page-title">Order #<?= h($order->id) ?></h3>
                <p class="page-subtitle">Review payment details, order items and estimated profit</p>
            </div>
        </div>

        <table class="view-table admin-view-table-spaced">
            <tr>
                <th><?= __('Customer Email') ?></th>
                <td><?= h($order->customer_email) ?></td>
            </tr>
            <tr>
                <th><?= __('Status') ?></th>
                <td>
                    <?php
                    $statusClass = [
                        'paid'      => 'status-pill status-pill-paid',
                        'pending'   => 'status-pill status-pill-pending',
                        'cancelled' => 'status-pill status-pill-cancelled',
                    ];
                    $cls = $statusClass[$order->status] ?? '';
                    ?>
                    <span class="<?= $cls ?>"><?= h(ucfirst($order->status)) ?></span>
                </td>
            </tr>
            <tr>
                <th><?= __('Total Amount') ?></th>
                <td>$<?= number_format((float)$order->total_amount, 2) ?> <?= strtoupper(h($order->currency)) ?></td>
            </tr>
            <?php
            $totalProfit = 0;
            foreach ($order->order_items as $item) {
                if ($item->product) {
                    $totalProfit += ($item->unit_price - $item->product->purchase_price) * $item->quantity;
                }
            }
            $profitClass = $totalProfit >= 0 ? 'admin-profit-positive' : 'admin-profit-negative';
            ?>
            <tr>
                <th><?= __('Total Profit') ?></th>
                <td class="<?= $profitClass ?>">
                    $<?= number_format($totalProfit, 2) ?> <?= strtoupper(h($order->currency)) ?>
                </td>
            </tr>
            <tr>
                <th><?= __('Stripe Session ID') ?></th>
                <td><?= h($order->stripe_session_id) ?: '' ?></td>
            </tr>
            <tr>
                <th><?= __('Payment Intent ID') ?></th>
                <td><?= h($order->stripe_payment_intent_id) ?: '' ?></td>
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

        <h4 class="admin-view-section-title"><?= __('Order Items') ?></h4>

        <?php if (!empty($order->order_items)): ?>
            <table class="view-table">
                <thead>
                <tr>
                    <th><?= __('Product') ?></th>
                    <th><?= __('Size') ?></th>
                    <th><?= __('Purchase Price') ?></th>
                    <th><?= __('Unit Price') ?></th>
                    <th><?= __('Quantity') ?></th>
                    <th><?= __('Subtotal') ?></th>
                    <th><?= __('Profit') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($order->order_items as $item): ?>
                    <?php
                    $purchasePrice = $item->product ? (float)$item->product->purchase_price : null;
                    $itemProfit = $purchasePrice !== null ? ($item->unit_price - $purchasePrice) * $item->quantity : null;
                    $itemProfitClass = ($itemProfit !== null && $itemProfit >= 0) ? 'admin-profit-positive' : 'admin-profit-negative';
                    ?>
                    <tr>
                        <td><?= h($item->product_name) ?></td>
                        <td><?= h($item->selected_size) ?: '' ?></td>
                        <td><?= $purchasePrice !== null ? '$' . number_format($purchasePrice, 2) : '' ?></td>
                        <td>$<?= number_format((float)$item->unit_price, 2) ?></td>
                        <td><?= h($item->quantity) ?></td>
                        <td>$<?= number_format((float)$item->subtotal, 2) ?></td>
                        <td class="<?= $itemProfitClass ?>">
                            <?= $itemProfit !== null ? '$' . number_format($itemProfit, 2) : '' ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted">No items found for this order.</p>
        <?php endif; ?>

    </div>
</div>
