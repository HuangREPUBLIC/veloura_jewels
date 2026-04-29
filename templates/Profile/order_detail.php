<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Order $order
 */
$this->assign('title', 'My Order');
$this->Html->css('profile', ['block' => true]);
?>

<div class="profile-page">
    <div class="profile-form-wrap profile-form-wrap--wide">

        <div class="profile-form-header">
            <?= $this->Html->link('← Back to Orders', ['action' => 'orders'], ['class' => 'profile-back-link']) ?>
            <h1>My Order</h1>
        </div>

        <!-- Order Meta -->
        <div class="profile-card profile-order-meta">
            <div class="order-meta-grid">
                <div class="order-meta-item">
                    <span class="profile-info-label">Date Placed</span>
                    <span class="profile-info-value"><?= $order->created->format('d M Y, g:ia') ?></span>
                </div>
                <div class="order-meta-item">
                    <span class="profile-info-label">Status</span>
                    <span class="order-status order-status--<?= h($order->status) ?>"><?= ucfirst(h($order->status)) ?></span>
                </div>
                <div class="order-meta-item">
                    <span class="profile-info-label">Total</span>
                    <span class="profile-info-value order-total-highlight">$<?= number_format((float)$order->total_amount, 2) ?> <?= strtoupper(h($order->currency)) ?></span>
                </div>
                <?php if ($order->stripe_payment_intent_id): ?>
                    <div class="order-meta-item">
                        <span class="profile-info-label">Payment Reference</span>
                        <span class="profile-info-value order-ref"><?= h($order->stripe_payment_intent_id) ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Order Items -->
        <div class="profile-card">
            <div class="profile-card-header">
                <h2>Items</h2>
            </div>
            <div class="profile-orders-table-wrap">
                <table class="profile-orders-table">
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th>Size</th>
                        <th>Unit Price</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($order->order_items as $item): ?>
                        <tr>
                            <td class="order-item-name"><?= h($item->product_name) ?></td>
                            <td><?= h($item->selected_size) ?: '—' ?></td>
                            <td>$<?= number_format((float)$item->unit_price, 2) ?></td>
                            <td><?= h($item->quantity) ?></td>
                            <td>$<?= number_format((float)$item->subtotal, 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr class="order-total-row">
                        <td colspan="4">Total</td>
                        <td>$<?= number_format((float)$order->total_amount, 2) ?></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
</div>
