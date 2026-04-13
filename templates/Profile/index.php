<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var iterable<\App\Model\Entity\Order> $recentOrders
 */
$this->assign('title', 'My Profile');
$this->Html->css('profile', ['block' => true]);
?>

<div class="profile-page">

    <!-- Hero -->
    <section class="profile-hero">
        <div class="profile-hero-avatar">
            <?= strtoupper(substr($user->first_name ?: $user->email, 0, 1)) ?>
        </div>
        <div class="profile-hero-info">
            <h1><?= h($user->full_name ?: $user->email) ?></h1>
            <p class="profile-hero-email"><?= h($user->email) ?></p>
            <p class="profile-hero-since">Member since <?= $user->created->format('F Y') ?></p>
        </div>
    </section>

    <div class="profile-body">

        <!-- Info Card -->
        <div class="profile-card">
            <div class="profile-card-header">
                <h2>Personal Information</h2>
                <?= $this->Html->link('Edit', ['action' => 'edit'], ['class' => 'profile-link-btn']) ?>
            </div>
            <div class="profile-info-grid">
                <div class="profile-info-item">
                    <span class="profile-info-label">First Name</span>
                    <span class="profile-info-value"><?= h($user->first_name) ?: '<span class="profile-empty">Not set</span>' ?></span>
                </div>
                <div class="profile-info-item">
                    <span class="profile-info-label">Last Name</span>
                    <span class="profile-info-value"><?= h($user->last_name) ?: '<span class="profile-empty">Not set</span>' ?></span>
                </div>
                <div class="profile-info-item">
                    <span class="profile-info-label">Email</span>
                    <span class="profile-info-value"><?= h($user->email) ?></span>
                </div>
                <div class="profile-info-item">
                    <span class="profile-info-label">Phone</span>
                    <span class="profile-info-value"><?= h($user->phone) ?: '<span class="profile-empty">Not set</span>' ?></span>
                </div>
                <div class="profile-info-item profile-info-item--full">
                    <span class="profile-info-label">Address</span>
                    <span class="profile-info-value"><?= h($user->address) ?: '<span class="profile-empty">Not set</span>' ?></span>
                </div>
            </div>
        </div>

        <!-- Security Card -->
        <div class="profile-card">
            <div class="profile-card-header">
                <h2>Security</h2>
            </div>
            <div class="profile-security-row">
                <div>
                    <p class="profile-info-label">Password</p>
                    <p class="profile-info-value">••••••••</p>
                </div>
                <?= $this->Html->link('Change Password', ['controller' => 'Auth', 'action' => 'changePassword'], ['class' => 'profile-link-btn']) ?>
            </div>
        </div>

        <!-- Recent Orders Card -->
        <div class="profile-card profile-card--full">
            <div class="profile-card-header">
                <h2>Recent Orders</h2>
                <?= $this->Html->link('View All', ['action' => 'orders'], ['class' => 'profile-link-btn']) ?>
            </div>

            <?php if ($recentOrders->isEmpty()): ?>
                <div class="profile-empty-orders">
                    <p>You haven't placed any orders yet.</p>
                    <?= $this->Html->link('Browse Jewelry', ['controller' => 'Jewelry', 'action' => 'index'], ['class' => 'profile-shop-btn']) ?>
                </div>
            <?php else: ?>
                <div class="profile-orders-table-wrap">
                    <table class="profile-orders-table">
                        <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td class="order-id">#<?= h($order->id) ?></td>
                                <td><?= $order->created->format('d M Y') ?></td>
                                <td><?= count($order->order_items) ?> item<?= count($order->order_items) !== 1 ? 's' : '' ?></td>
                                <td>$<?= number_format((float)$order->total_amount, 2) ?></td>
                                <td><span class="order-status order-status--<?= h($order->status) ?>"><?= ucfirst(h($order->status)) ?></span></td>
                                <td><?= $this->Html->link('View', ['action' => 'orderDetail', $order->id], ['class' => 'profile-table-link']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>
