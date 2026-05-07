<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Order|null $order
 * @var int $cancelWindowMinutes
 */
$this->assign('title', 'Payment Paused');
?>
<?php $this->Html->css('jewelry', ['block' => true]); ?>

<div class="cc-page">

<?php if (!empty($order) && $order->status === 'pending'):
    $windowEnd    = $order->created->modify("+{$cancelWindowMinutes} minutes");
    $windowEndMs  = $windowEnd->getTimestamp() * 1000;
    $totalMs      = $cancelWindowMinutes * 60 * 1000;
    $withinWindow = (new \DateTime()) < $windowEnd;
?>

    <div class="cc-card cc-card--warning">

        <div class="cc-card-top">
            <div class="cc-icon cc-icon--clock">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
            <div class="cc-card-titles">
                <h1 class="cc-heading">Payment Paused</h1>
                <p class="cc-sub">Your order is reserved — complete payment or cancel below.</p>
            </div>
        </div>

        <?php if ($withinWindow): ?>
        <div class="cc-timer-section" id="cc-timer-section">
            <span class="cc-timer-label">Cancel window closes in</span>
            <div class="cc-timer" id="cc-timer">--:--</div>
            <div class="cc-timer-track">
                <div class="cc-timer-bar" id="cc-timer-bar" style="width:100%"></div>
            </div>
        </div>
        <?php endif; ?>

        <div id="cc-expired-notice" class="cc-expired-notice" style="display:none">
            The cancellation window has closed — this order is being cancelled now.
        </div>

        <div class="cc-summary">
            <p class="cc-summary-label">Order Summary</p>
            <?php foreach ($order->order_items as $item): ?>
                <div class="cc-item">
                    <div class="cc-item-info">
                        <span class="cc-item-name"><?= h($item->product_name) ?></span>
                        <span class="cc-item-meta"><?= h($item->selected_size) ?> &times; <?= (int)$item->quantity ?></span>
                    </div>
                    <span class="cc-item-price">A$<?= number_format((float)$item->subtotal, 2) ?></span>
                </div>
            <?php endforeach; ?>
            <div class="cc-total">
                <span>Total</span>
                <span>A$<?= number_format((float)$order->total_amount, 2) ?> <span class="cc-currency"><?= strtoupper(h($order->currency)) ?></span></span>
            </div>
        </div>

        <div class="cc-actions">
            <a href="<?= $this->Url->build(['action' => 'resumeCheckout', '?' => ['session_id' => $order->stripe_session_id]]) ?>" class="cc-btn cc-btn--primary">
                Continue Payment
            </a>

            <?php if ($withinWindow): ?>
                <div id="cc-cancel-wrap">
                    <?= $this->Form->create(null, ['url' => ['action' => 'cancel'], 'method' => 'post', 'id' => 'cc-cancel-form']) ?>
                    <?= $this->Form->hidden('session_id', ['value' => $order->stripe_session_id]) ?>
                    <?= $this->Form->button('Cancel This Order', ['type' => 'submit', 'class' => 'cc-btn cc-btn--danger']) ?>
                    <?= $this->Form->end() ?>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <?php if ($withinWindow): ?>
    <script>
    (function () {
        const end     = <?= $windowEndMs ?>;
        const total   = <?= $totalMs ?>;
        const timerEl = document.getElementById('cc-timer');
        const barEl   = document.getElementById('cc-timer-bar');
        const section = document.getElementById('cc-timer-section');
        const cancelW = document.getElementById('cc-cancel-wrap');
        const expired = document.getElementById('cc-expired-notice');

        function tick() {
            const remaining = end - Date.now();

            if (remaining <= 0) {
                timerEl.textContent = '00:00';
                barEl.style.width = '0%';
                if (cancelW) cancelW.style.display = 'none';
                section.style.opacity = '0.35';
                expired.style.display = 'block';
                setTimeout(() => window.location.reload(), 2500);
                return;
            }

            const m = Math.floor(remaining / 60000);
            const s = Math.floor((remaining % 60000) / 1000);
            timerEl.textContent = String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
            barEl.style.width = Math.max(0, (remaining / total) * 100) + '%';

            timerEl.classList.remove('cc-timer--warn', 'cc-timer--urgent');
            if (remaining < 60000)       timerEl.classList.add('cc-timer--urgent');
            else if (remaining < 300000) timerEl.classList.add('cc-timer--warn');

            setTimeout(tick, 1000);
        }
        tick();
    })();
    </script>
    <?php endif; ?>

<?php elseif (!empty($order) && $order->status === 'cancelled'): ?>

    <div class="cc-card cc-card--done">

        <div class="cc-card-top">
            <div class="cc-icon cc-icon--done">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="15" y1="9" x2="9" y2="15"/>
                    <line x1="9" y1="9" x2="15" y2="15"/>
                </svg>
            </div>
            <div class="cc-card-titles">
                <h1 class="cc-heading">Order Cancelled</h1>
                <p class="cc-sub">This order has been cancelled. You will not be charged.</p>
            </div>
        </div>

        <div class="cc-summary cc-summary--muted">
            <p class="cc-summary-label">Cancelled Order</p>
            <?php foreach ($order->order_items as $item): ?>
                <div class="cc-item">
                    <div class="cc-item-info">
                        <span class="cc-item-name"><?= h($item->product_name) ?></span>
                        <span class="cc-item-meta"><?= h($item->selected_size) ?> &times; <?= (int)$item->quantity ?></span>
                    </div>
                    <span class="cc-item-price">A$<?= number_format((float)$item->subtotal, 2) ?></span>
                </div>
            <?php endforeach; ?>
            <div class="cc-total">
                <span>Total</span>
                <span>A$<?= number_format((float)$order->total_amount, 2) ?> <span class="cc-currency"><?= strtoupper(h($order->currency)) ?></span></span>
            </div>
        </div>

        <div class="cc-actions">
            <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="cc-btn cc-btn--primary">
                Back to Shop
            </a>
        </div>

    </div>

<?php else: ?>

    <div class="cc-card">
        <div class="cc-card-top cc-card-top--center">
            <div class="cc-icon cc-icon--done">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>
            <div class="cc-card-titles">
                <h1 class="cc-heading">No Order Found</h1>
                <p class="cc-sub">This session has expired or could not be located.</p>
            </div>
        </div>
        <div class="cc-actions">
            <a href="<?= $this->Url->build(['action' => 'cart']) ?>" class="cc-btn cc-btn--primary">Return to Cart</a>
        </div>
    </div>

<?php endif; ?>

</div>
