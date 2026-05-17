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

        <?php if ($order->status === 'pending' && $order->stripe_session_id):
            $windowEnd    = $order->created->modify('+30 minutes');
            $windowEndMs  = $windowEnd->getTimestamp() * 1000;
            $withinWindow = (new \DateTime()) < $windowEnd;
            if ($withinWindow): ?>
                <div class="profile-card order-cancel-window">
                    <div class="order-cancel-info">
                        <span class="order-cancel-label">Cancel window</span>
                        <span class="order-cancel-timer" id="order-cancel-timer">--:--</span>
                        <span class="order-cancel-expired" id="order-cancel-expired" style="display:none">Window closed</span>
                    </div>
                    <div class="order-cancel-actions">
                        <a href="<?= $this->Url->build(['controller' => 'Jewelry', 'action' => 'resumeCheckout', '?' => ['session_id' => $order->stripe_session_id]]) ?>"
                           class="order-resume-btn" id="order-resume-btn">
                            Return to Payment
                        </a>
                        <div id="order-cancel-btn-wrap">
                            <?= $this->Form->create(null, ['url' => '/checkout/cancel', 'method' => 'post']) ?>
                            <?= $this->Form->hidden('session_id', ['value' => $order->stripe_session_id]) ?>
                            <?= $this->Form->button('Cancel Order', ['type' => 'submit', 'class' => 'order-cancel-btn']) ?>
                            <?= $this->Form->end() ?>
                        </div>
                    </div>
                </div>
                <script>
                    (function () {
                        const end = <?= $windowEndMs ?>;
                        const timerEl  = document.getElementById('order-cancel-timer');
                        const btnWrap  = document.getElementById('order-cancel-btn-wrap');
                        const expiredEl = document.getElementById('order-cancel-expired');
                        const resumeBtn = document.getElementById('order-resume-btn');

                        function tick() {
                            const remaining = end - Date.now();
                            if (remaining <= 0) {
                                timerEl.style.display = 'none';
                                if (btnWrap) btnWrap.style.display = 'none';
                                if (resumeBtn) resumeBtn.style.display = 'none';
                                expiredEl.style.display = 'block';
                                return;
                            }
                            const m = Math.floor(remaining / 60000);
                            const s = Math.floor((remaining % 60000) / 1000);
                            timerEl.textContent = String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
                            setTimeout(tick, 1000);
                        }
                        tick();
                    })();
                </script>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Order Items -->
        <div class="profile-card od-card">
            <h2 class="od-title">Items</h2>
            <div class="od-items">
                <?php foreach ($order->order_items as $item): ?>
                <div class="od-item">
                    <div class="od-item-img-wrap">
                        <?php if (!empty($item->product->product_images)): ?>
                            <img src="<?= $this->Url->image('products/' . h($item->product->product_images[0]->filename)) ?>"
                                 alt="<?= h($item->product_name) ?>" class="od-item-img">
                        <?php else: ?>
                            <div class="od-item-placeholder">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="od-item-info">
                        <div class="od-item-name"><?= h($item->product_name) ?></div>
                        <?php if ($item->selected_size): ?>
                            <div class="od-item-meta">Size: <?= h($item->selected_size) ?></div>
                        <?php endif; ?>
                        <div class="od-item-meta">Qty: <?= (int)$item->quantity ?></div>
                    </div>
                    <div class="od-item-right">
                        <div class="od-item-unit">$<?= number_format((float)$item->unit_price, 2) ?> each</div>
                        <div class="od-item-subtotal">$<?= number_format((float)$item->subtotal, 2) ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="od-total">
                <span class="od-total-label">Order Total</span>
                <span class="od-total-value">$<?= number_format((float)$order->total_amount, 2) ?> AUD</span>
            </div>
        </div>

    </div>
</div>
