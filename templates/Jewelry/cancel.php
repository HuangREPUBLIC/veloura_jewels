<?php
$this->assign('title', 'Payment Cancelled');
?>
<?php $this->Html->css('jewelry', ['block' => true]); ?>

<div class="jewelry-page">
    <section class="jewelry-hero">
        <h1>Payment Cancelled</h1>
        <p>Your payment was cancelled. Your cart is still available.</p>
        <a href="<?= $this->Url->build('/cart') ?>" class="jewelry-cart-primary-btn">Return to Cart</a>
    </section>
</div>
