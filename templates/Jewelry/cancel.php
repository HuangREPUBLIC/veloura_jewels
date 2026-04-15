<?php
$this->assign('title', 'Payment Cancelled');
?>
<?php $this->Html->css('jewelry', ['block' => true]); ?>

<div class="jewelry-page">
    <div class="result-card">

        <div class="result-icon">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="15" y1="9" x2="9" y2="15"/>
                <line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
        </div>

        <h1 class="result-title">Payment Cancelled</h1>
        <p class="result-subtitle">No charge was made. Your cart is still saved and ready when you are.</p>

        <div class="result-actions">
            <a href="<?= $this->Url->build(['controller' => 'Jewelry', 'action' => 'cart']) ?>" class="result-btn">
                Return to Cart
            </a>
        </div>

    </div>
</div>
