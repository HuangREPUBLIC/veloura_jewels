Order Confirmed — Veloura Jewels
=================================

Thank you for your purchase! Your order #<?= h($order->id) ?> has been confirmed.

ITEMS ORDERED
-------------
<?php foreach ($items as $item): ?>
<?= h($item->product_name) ?><?= !empty($item->selected_size) && $item->selected_size !== 'One Size' ? ' (' . h($item->selected_size) . ')' : '' ?> x<?= (int)$item->quantity ?> — $<?= number_format((float)$item->subtotal, 2) ?>
<?php endforeach; ?>

TOTAL: $<?= number_format((float)$order->total_amount, 2) ?> <?= strtoupper(h($order->currency)) ?>

We'll be in touch once your order is on its way.

— Veloura Jewels
