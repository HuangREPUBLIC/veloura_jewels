<?php
$this->assign('title', 'Payment Successful');
?>
<?php $this->Html->css('jewelry', ['block' => true]); ?>

<div class="jewelry-page">
    <div class="result-card">

        <div class="result-icon">✅</div>
        <h1 class="result-title">Payment Successful</h1>
        <p class="result-subtitle">Thank you for your purchase from Veloura Jewels.</p>

        <?php if (!empty($order)): ?>
            <div class="result-order-info">
                <div class="result-order-row">
                    <span class="result-order-label">Email</span>
                    <span class="result-order-value"><?= h($order->customer_email) ?></span>
                </div>
                <div class="result-order-row">
                    <span class="result-order-label">Status</span>
                    <span class="result-order-value status"><?= ucfirst(h($order->status)) ?></span>
                </div>
            </div>
        <?php endif; ?>

        <a href="<?= $this->Url->build(['controller' => 'Jewelry', 'action' => 'index']) ?>" class="result-btn">
            Continue Shopping
        </a>

    </div>
</div>
