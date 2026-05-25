<?php
$this->assign('title', 'Payment Successful');
?>
<?php $this->Html->css('success', ['block' => true]); ?>

<div class="success-page">
    <div class="result-card">

        <div class="result-icon">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#284d40" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="9 12 11.5 14.5 15.5 9.5"/>
            </svg>
        </div>

        <h1 class="result-title">Payment Successful</h1>
        <p class="result-subtitle">Thank you for your purchase from Veloura Jewels. A confirmation email will be sent shortly.</p>

        <?php if (!empty($order)): ?>

            <?php if (!empty($order->order_items)): ?>
                <div class="result-items">
                    <?php foreach ($order->order_items as $item): ?>
                        <div class="result-item">
                            <div class="result-item-img-wrap">
                                <?php if (!empty($item->product->product_images)): ?>
                                    <img src="<?= $this->Url->image('products/' . h($item->product->product_images[0]->filename)) ?>"
                                         alt="<?= h($item->product_name) ?>" class="result-item-img">
                                <?php else: ?>
                                    <div class="result-item-placeholder">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="result-item-info">
                                <div class="result-item-name"><?= h($item->product_name) ?></div>
                                <?php if ($item->selected_size): ?>
                                    <div class="result-item-meta">Size: <?= h($item->selected_size) ?></div>
                                <?php endif; ?>
                                <div class="result-item-meta">Qty: <?= (int)$item->quantity ?></div>
                            </div>
                            <div class="result-item-price">$<?= number_format((float)$item->subtotal, 2) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="result-order-info">
                <div class="result-order-row">
                    <span class="result-order-label">Email</span>
                    <span class="result-order-value"><?= h($order->customer_email) ?></span>
                </div>
                <div class="result-order-row">
                    <span class="result-order-label">Total</span>
                    <span class="result-order-value">$<?= number_format((float)$order->total_amount, 2) ?> <?= strtoupper(h($order->currency)) ?></span>
                </div>
                <?php
                $deliveryFrom = $order->created->addDays(5);
                $deliveryTo   = $order->created->addDays(7);
                ?>
                <div class="result-order-row">
                    <span class="result-order-label">Estimated Delivery</span>
                    <span class="result-order-value"><?= $deliveryFrom->format('d M') ?> – <?= $deliveryTo->format('d M Y') ?></span>
                </div>
                <div class="result-order-row">
                    <span class="result-order-label">Status</span>
                    <span class="result-order-value status">Your order is being processed</span>
                </div>
            </div>

            <div class="result-pickup">
                <p class="result-pickup-label">Prefer to collect in store?</p>
                <p class="result-pickup-address">88 Elizabeth Road, Melbourne VIC 3000</p>
                <p class="result-pickup-hours">Open 10:00AM – 6:00PM</p>
            </div>

        <?php endif; ?>

        <a href="<?= $this->Url->build(['controller' => 'Jewelry', 'action' => 'index']) ?>" class="result-btn">
            Continue Shopping
        </a>

    </div>
</div>
