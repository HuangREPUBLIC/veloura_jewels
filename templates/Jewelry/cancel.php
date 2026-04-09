<?php
$this->assign('title', 'Payment Cancelled');
?>
<?php $this->Html->css('jewelry', ['block' => true]); ?>

<div class="jewelry-page">
    <div class="result-card">

        <div class="result-icon">❌</div>
        <h1 class="result-title">Payment Cancelled</h1>
        <p class="result-subtitle">Your payment was cancelled. Your cart is still available.</p>

        <a href="<?= $this->Url->build(['controller' => 'Jewelry', 'action' => 'cart']) ?>" class="result-btn">
            Return to Cart
        </a>

    </div>
</div>
