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

        <h1 class="result-title">Payment Not Completed</h1>

        <?php if (!empty($order)):
            $windowEnd    = $order->created->modify("+{$cancelWindowMinutes} minutes");
            $windowEndMs  = $windowEnd->getTimestamp() * 1000;
            $withinWindow = (new \DateTime()) < $windowEnd;
        ?>

            <?php if ($withinWindow): ?>
                <div class="cancel-countdown-wrap">
                    <p class="cancel-countdown-label">Window closes in</p>
                    <div class="cancel-countdown" id="cancel-countdown">--:--</div>
                    <p class="cancel-countdown-expired" id="cancel-expired-msg" style="display:none">The cancellation window has closed.</p>
                </div>
            <?php endif; ?>

            <div class="result-order-info">
                <?php foreach ($order->order_items as $item): ?>
                    <div class="result-order-row">
                        <span class="result-order-label"><?= h($item->product_name) ?></span>
                        <span class="result-order-value"><?= h($item->selected_size) ?> × <?= h($item->quantity) ?> — $<?= number_format((float)$item->subtotal, 2) ?></span>
                    </div>
                <?php endforeach; ?>
                <div class="result-order-row" style="border-top:1px solid #e8e2d4; padding-top:0.5rem; margin-top:0.3rem;">
                    <span class="result-order-label">Total</span>
                    <span class="result-order-value">$<?= number_format((float)$order->total_amount, 2) ?> <?= strtoupper(h($order->currency)) ?></span>
                </div>
            </div>

            <div class="result-actions">
                <a href="<?= $this->Url->build(['action' => 'cart']) ?>" class="result-btn">
                    Return to Cart
                </a>

                <?php if ($withinWindow): ?>
                    <div id="cancel-btn-wrap">
                        <?= $this->Form->create(null, ['url' => ['action' => 'cancel'], 'method' => 'post']) ?>
                        <?= $this->Form->hidden('session_id', ['value' => $order->stripe_session_id]) ?>
                        <?= $this->Form->button('Cancel Order', ['type' => 'submit', 'class' => 'result-btn result-btn--cancel']) ?>
                        <?= $this->Form->end() ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($withinWindow): ?>
                <script>
                    (function () {
                        const end = <?= $windowEndMs ?>;
                        const timerEl = document.getElementById('cancel-countdown');
                        const btnWrap = document.getElementById('cancel-btn-wrap');
                        const expiredMsg = document.getElementById('cancel-expired-msg');

                        function tick() {
                            const remaining = end - Date.now();
                            if (remaining <= 0) {
                                timerEl.textContent = '00:00';
                                if (btnWrap) btnWrap.style.display = 'none';
                                expiredMsg.style.display = 'block';
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

        <?php else: ?>
            <div class="result-actions">
                <a href="<?= $this->Url->build(['action' => 'cart']) ?>" class="result-btn">
                    Return to Cart
                </a>
            </div>
        <?php endif; ?>

    </div>
</div>
