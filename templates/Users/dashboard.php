<?php
/**
 * @var \App\View\AppView $this
 * @var \Authentication\Identity $authUser
 * @var int $totalProducts
 * @var int $totalUsers
 * @var int $totalEnquiries
 * @var int $totalOrders
 * @var string $role
 * @var \Cake\ORM\ResultSet $lowStockProducts
 */
$this->assign('title', 'Admin Dashboard');
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>
<?php $role = $this->request->getAttribute('identity')->get('role'); ?>


<div class="admin-wrapper">
    <div class="admin-dashboard">

        <div class="dashboard-hero">
            <h1>Admin Dashboard</h1>
            <p>Hi, <?= h($authUser->first_name) ?></p>
        </div>

        <?php if ($role === 'staff'): ?>
        <div class="dashboard-schedule">
            <div class="dashboard-schedule-header">
                <span class="dashboard-schedule-title">My Schedule</span>
                <span class="dashboard-schedule-range"><?= $weekRange ?></span>
            </div>
            <div class="dashboard-schedule-grid">
                <?php
                    $todayStr = (new \DateTime('today'))->format('Y-m-d');
                    foreach ($upcomingDays as $day):
                        $dateStr  = $day->format('Y-m-d');
                        $isToday  = $dateStr === $todayStr;
                        $shift    = $schedule[$dateStr] ?? null;
                ?>
                <div class="dashboard-schedule-day <?= $isToday ? 'dashboard-schedule-day--today' : '' ?> <?= $shift ? 'dashboard-schedule-day--on' : 'dashboard-schedule-day--off' ?>">
                    <span class="dashboard-schedule-label"><?= $day->format('D') ?></span>
                    <span class="dashboard-schedule-date"><?= $day->format('j') ?></span>
                    <?php if ($shift): ?>
                        <span class="dashboard-schedule-time"><?= $shift->start_time->format('H:i') ?></span>
                        <span class="dashboard-schedule-time"><?= $shift->end_time->format('H:i') ?></span>
                    <?php else: ?>
                        <span class="dashboard-schedule-off">Off</span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="dashboard-summary">
            <a href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'index']) ?>" class="dashboard-card">
                <div class="dashboard-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#786c3b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/>
                    </svg>
                </div>
                <div class="dashboard-content">
                    <h3>Total Products</h3>
                    <p><?= $totalProducts ?></p>
                </div>
            </a>

            <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'index']) ?>" class="dashboard-card">
                <div class="dashboard-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#786c3b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <div class="dashboard-content">
                    <h3>Total Users</h3>
                    <p><?= $totalUsers ?></p>
                </div>
            </a>

            <a href="<?= $this->Url->build(['controller' => 'ContactSubmissions', 'action' => 'index']) ?>" class="dashboard-card">
                <div class="dashboard-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#786c3b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                    </svg>
                </div>
                <div class="dashboard-content">
                    <h3>Total Enquiries</h3>
                    <p><?= $totalEnquiries ?></p>
                </div>
            </a>

            <a href="<?= $this->Url->build(['controller' => 'Orders', 'action' => 'index']) ?>" class="dashboard-card">
                <div class="dashboard-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#786c3b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                </div>
                <div class="dashboard-content">
                    <h3>Total Orders</h3>
                    <p><?= $totalOrders ?></p>
                </div>
            </a>
        </div>

        <?php if (!$lowStockProducts->isEmpty()): ?>
            <div class="low-stock-warning">
                <h3>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-2px;margin-right:6px;">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    Low Stock Products
                </h3>
                <p>These products are running low and may need restocking soon.</p>

                <div class="low-stock-list">
                    <?php foreach ($lowStockProducts as $product): ?>
                        <div class="low-stock-row">
                            <div class="low-stock-row-top">
                                <span class="low-stock-name"><?= h($product->name) ?></span>
                                <?php if ($role === 'admin'): ?>
                                <?= $this->Html->link(
                                    'Restock',
                                    ['controller' => 'Products', 'action' => 'edit', $product->id, '?' => ['from' => 'dashboard']],
                                    ['class' => 'low-stock-btn']
                                ) ?>
                                <?php endif ?>
                            </div>
                            <div class="low-stock-pills">
                                <?php foreach ($product->product_variants as $v): ?>
                                    <?php if ($v->stock < 5): ?>
                                        <span class="low-stock-pill <?= $v->stock === 0 ? 'pill-zero' : 'pill-low' ?>">
                                            <?= h($v->size) ?>: <?= $v->stock ?>
                                        </span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="dashboard-grid">
            <a href="<?= $this->Url->build(['controller' => 'ContactSubmissions', 'action' => 'index']) ?>" class="dashboard-card">
                <div class="dashboard-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#786c3b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                    </svg>
                </div>
                <div class="dashboard-content">
                    <h3>Contact Submissions</h3>
                    <p>View and respond to customer enquiries.</p>
                </div>
            </a>

            <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'index']) ?>" class="dashboard-card">
                <div class="dashboard-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#786c3b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <div class="dashboard-content">
                    <h3>Users</h3>
                    <p>Manage system users and permissions.</p>
                </div>
            </a>

            <a href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'index']) ?>" class="dashboard-card">
                <div class="dashboard-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#786c3b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/>
                    </svg>
                </div>
                <div class="dashboard-content">
                    <h3>Manage Products</h3>
                    <p>Add, update, and delete product listings.</p>
                </div>
            </a>

            <a href="<?= $this->Url->build(['controller' => 'Orders', 'action' => 'index']) ?>" class="dashboard-card">
                <div class="dashboard-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#786c3b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                </div>
                <div class="dashboard-content">
                    <h3>Manage Orders</h3>
                    <p>View and manage customer orders.</p>
                </div>
            </a>
        </div>

    </div>
</div>
