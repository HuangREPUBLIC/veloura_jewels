<?php
$this->assign('title', 'Payment Success');
?>
<?php $this->Html->css('jewelry', ['block' => true]); ?>

<div class="jewelry-page">
    <section class="jewelry-hero">
        <h1>Payment Successful</h1>
        <p>Thank you for your purchase.</p>
        <?php if (!empty($order)): ?>
            <p>Your order ID is #<?= h($order->id) ?>.</p>
            <p>Status: <?= h($order->status) ?></p>
        <?php endif; ?>
        <a href="<?= $this->Url->build('/jewelry') ?>" class="jewelry-cart-primary-btn">Continue Shopping</a>
    </section>
</div>
