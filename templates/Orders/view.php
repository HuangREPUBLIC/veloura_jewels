<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Order $order
 */
$this->assign('title', 'Order ' . $order->id);
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>

<div class="submissions-wrapper">
    <div class="orders view content">

        <?= $this->Html->link(__('← Back'), ['action' => 'index']) ?>

        <h3 class="page-title">Order <?= h($order->id) ?></h3>

        <table style="margin-bottom: 2rem;">
            <tr>
                <th><?= __('Customer Email') ?></th>
                <td><?= h($order->customer_email) ?></td>
            </tr>
            <tr>
                <th><?= __('Status') ?></th>
                <td>
                    <?php
                    $statusColors = [
                        'paid' => 'color:green;font-weight:600;',
                        'pending' => 'color:#b7860b;font-weight:600;',
                        'cancelled' => 'color:#c0392b;font-weight:600;',
                    ];
                    $style = $statusColors[$order->status] ?? '';
                    ?>
                    <span style="<?= $style ?>"><?= h(ucfirst($order->status)) ?></span>
                </td>
            </tr>
            <tr>
                <th><?= __('Total Amount') ?></th>
                <td>$<?= number_format((float)$order->total_amount, 2) ?> <?= strtoupper(h($order->currency)) ?></td>
            </tr>
            <tr>
                <th><?= __('Stripe Session ID') ?></th>
                <td><?= h($order->stripe_session_id) ?: '-' ?></td>
            </tr>
            <tr>
                <th><?= __('Payment Intent ID') ?></th>
                <td><?= h($order->stripe_payment_intent_id) ?: '-' ?></td>
            </tr>
            <tr>
                <th><?= __('Created') ?></th>
                <td><?= h($order->created) ?></td>
            </tr>
            <tr>
                <th><?= __('Modified') ?></th>
                <td><?= h($order->modified) ?></td>
            </tr>
        </table>

        <h3 class="page-title" style="font-size:1.2rem;">Order Items</h3>

        <?php if (!empty($order->order_items)): ?>
            <table>
                <thead>
                <tr>
                    <th><?= __('Product') ?></th>
                    <th><?= __('Unit Price') ?></th>
                    <th><?= __('Quantity') ?></th>
                    <th><?= __('Subtotal') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($order->order_items as $item): ?>
                    <tr>
                        <td><?= h($item->product_name) ?></td>
                        <td>$<?= number_format((float)$item->unit_price, 2) ?></td>
                        <td><?= h($item->quantity) ?></td>
                        <td>$<?= number_format((float)$item->subtotal, 2) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="color:#888;">No items found for this order.</p>
        <?php endif; ?>

    </div>
</div>
