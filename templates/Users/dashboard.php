<?php
/**
 * @var \App\View\AppView $this
 * @var \Authentication\Identity $authUser
 * @var int $totalProducts
 * @var int $totalUsers
 * @var int $totalEnquiries
 * @var int $totalOrders
 * @var \Cake\ORM\ResultSet $lowStockProducts
 */

$this->assign('title', 'Admin Dashboard');
$this->Html->css('admincontact', ['block' => true]);

$role = $authUser->get('role');

// Cards for Orders, Products, Users, CMS, Staff Schedule & Contacts
$cards = [
    // Row 1
    [
        'title' => 'Total Products',
        'value' => $totalProducts,
        'url'   => ['controller' => 'Products', 'action' => 'index'],
        'icon'  => '<path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/>'
    ],
    [
        'title' => 'Total Orders',
        'value' => $totalOrders,
        'url'   => ['controller' => 'Orders', 'action' => 'index'],
        'icon'  => '<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>'
    ],
    [
        'title' => 'Total Enquiries',
        'value' => $totalEnquiries,
        'url'   => ['controller' => 'ContactSubmissions', 'action' => 'index'],
        'icon'  => '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>'
    ],

    // Row 2
    [
        'title' => 'Staff Schedule',
        'value' => null,
        'url'   => ['controller' => 'Schedule', 'action' => 'index'],
        'icon'  => '<rect x="3" y="4" width="18" height="17" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>'
    ],
    [
        'title' => 'Total Users',
        'value' => $totalUsers,
        'url'   => ['controller' => 'Users', 'action' => 'index'],
        'icon'  => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>'
    ],
    [
        'title' => 'Content Management System',
        'value' => null,
        'url'   => ['controller' => 'Pages', 'action' => 'index'],
        'icon'  => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5z"/><path d="M8 7h8"/><path d="M8 11h6"/>'
    ],
];
?>

<div class="admin-wrapper">
    <div class="admin-dashboard">

        <!-- HEADER -->
        <div class="dashboard-hero">
            <h1>Admin Dashboard</h1>
            <p>Hi, <?= h($authUser->first_name) ?></p>
        </div>

        <!-- CARDS -->
        <div class="dashboard-summary">
            <?php foreach ($cards as $card): ?>
                <a href="<?= $this->Url->build($card['url']) ?>" class="dashboard-card">

                    <div class="dashboard-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24"
                             fill="none" stroke="#786c3b" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <?= $card['icon'] ?>
                        </svg>
                    </div>

                    <div class="dashboard-content">
                        <h3><?= h($card['title']) ?></h3>

                        <?php if ($card['value'] !== null): ?>
                            <p><?= $card['value'] ?></p>
                        <?php else: ?>
                            <p class="card-subtext">Open</p>
                        <?php endif; ?>
                    </div>

                </a>
            <?php endforeach; ?>
        </div>

        <!-- LOW STOCK -->
        <?php if (!$lowStockProducts->isEmpty()): ?>
            <div class="low-stock-warning">
                <h3>Low Stock Products</h3>

                <?php foreach ($lowStockProducts as $product): ?>
                    <div class="low-stock-row">

                        <div class="low-stock-row-top">
                            <span><?= h($product->name) ?></span>

                            <?php if ($role === 'admin'): ?>
                                <?= $this->Html->link(
                                    'Restock',
                                    ['controller'=>'Products','action'=>'edit',$product->id],
                                    ['class'=>'low-stock-btn']
                                ) ?>
                            <?php endif; ?>
                        </div>

                        <div class="low-stock-pills">
                            <?php foreach ($product->product_variants as $v): ?>
                                <?php if ($v->stock < 5): ?>
                                    <span class="<?= $v->stock === 0 ? 'pill-zero' : 'pill-low' ?>">
                            <?= h($v->size) ?>: <?= $v->stock ?>
                        </span>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>

                    </div>
                <?php endforeach; ?>

            </div>
        <?php endif; ?>

    </div>
</div>

<style>
    .dashboard-summary {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }

    .dashboard-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        text-decoration: none;
    }
</style>
