<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Order> $orders
 * @var string|null $statusFilter
 */
$this->assign('title', 'My Orders');
$this->Html->css('profile', ['block' => true]);

$statuses = ['pending', 'paid', 'shipped', 'completed', 'cancelled'];
?>

    <div class="profile-page">
    <div class="profile-form-wrap profile-form-wrap--wide">

        <div class="profile-form-header">
            <?= $this->Html->link('← Back to Profile', ['action' => 'index'], ['class' => 'profile-back-link']) ?>
            <h1>Order History</h1>
        </div>

        <!-- Status filter tabs -->
        <div class="profile-status-tabs">
            <a href="<?= $this->Url->build(['action' => 'orders']) ?>"
               class="profile-status-tab <?= !$statusFilter ? 'active' : '' ?>">All</a>
            <?php foreach ($statuses as $s): ?>
                <a href="<?= $this->Url->build(['action' => 'orders', '?' => ['status' => $s]]) ?>"
                   class="profile-status-tab <?= $statusFilter === $s ? 'active' : '' ?>">
                    <?= ucfirst($s) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="profile-card">
            <?php if (empty($orders) || (is_object($orders) && $orders->isEmpty())): ?>
                <div class="profile-empty-orders">
                    <p>No orders found<?= $statusFilter ? ' with status "' . ucfirst(h($statusFilter)) . '"' : '' ?>.</p>
                </div>
            <?php else: ?>
                <div class="profile-orders-table-wrap">
                    <table class="profile-orders-table">
                        <thead>
                        <tr>
                            <th>Order</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td class="order-id">#<?= h($order->id) ?></td>
                                <td><?= $order->created->format('d M Y') ?></td>
                                <td><?= count($order->order_items) ?> item<?= count($order->order_items) !== 1 ? 's' : '' ?></td>
                                <td>$<?= number_format((float)$order->total_amount, 2) ?></td>
                                <td>
                                    <span class="order-status order-status--<?= h($order->status) ?>"><?= ucfirst(h($order->status)) ?></span>
                                    <?php if ($order->status === 'pending'): ?>
                                        <?php $secsLeft = $order->created->modify('+30 minutes')->getTimestamp() - time(); ?>
                                        <?php if ($secsLeft > 0): ?>
                                            <span class="order-cancel-badge"><?= ceil($secsLeft / 60) ?> min left</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td><?= $this->Html->link('View', ['action' => 'orderDetail', $order->id], ['class' => 'profile-table-link']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <ul class="profile-pagination">
                    <?= $this->Paginator->prev('← Previous') ?>
                    <?= $this->Paginator->numbers() ?>
                    <?= $this->Paginator->next('Next →') ?>
                </ul>
            <?php endif; ?>
        </div>

    </div>
    </div>
