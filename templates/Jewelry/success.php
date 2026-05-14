<?php
$this->assign('title', 'Payment Successful');
?>
<?php $this->Html->css('jewelry', ['block' => true]); ?>

<div class="jewelry-page">
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
            <div class="result-order-info">

                <?php if (!empty($order->order_items)): ?>
                    <?php foreach ($order->order_items as $item): ?>
                        <div class="result-order-row">
                            <span class="result-order-label"><?= h($item->product_name) ?></span>
                            <span class="result-order-value">
                        <?= h($item->selected_size) ?> × <?= $item->quantity ?>
                        — $<?= number_format((float)$item->subtotal, 2) ?>
                    </span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <div class="result-order-row">
                    <span class="result-order-label">Email</span>
                    <span class="result-order-value"><?= h($order->customer_email) ?></span>
                </div>
                <div class="result-order-row">
                    <span class="result-order-label">Total</span>
                    <span class="result-order-value">$<?= number_format((float)$order->total_amount, 2) ?> <?= strtoupper(h($order->currency)) ?></span>
                </div>
                <div class="result-order-row">
                    <span class="result-order-label">Status</span>
                    <span class="result-order-value status">Your order is being processed</span>
                </div>
            </div>
        <?php endif; ?>

        <a href="<?= $this->Url->build(['controller' => 'Jewelry', 'action' => 'index']) ?>" class="result-btn">
            Continue Shopping
        </a>

    </div>
</div>
