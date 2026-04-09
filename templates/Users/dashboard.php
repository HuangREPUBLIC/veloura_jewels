<?php
/**
 * @var \App\View\AppView $this
 * @var \Authentication\Identity $authUser *
 * @var int $totalProducts
 * @var int $totalUsers
 * @var int $totalEnquiries
 * @var int $totalOrders
 * @var \Cake\ORM\ResultSet $lowStockProducts
 */
$this->assign('title', 'Admin Dashboard');
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>


<div class="admin-wrapper">
<div class="admin-dashboard">
    <div class="dashboard-hero">
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?= h($authUser->email) ?></p>
    </div>
    <div class="dashboard-summary" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:20px;margin:30px 0;">
        <a href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'index']) ?>" class="dashboard-card">
            <div class="dashboard-icon">💎</div>
            <div class="dashboard-content">
                <h3>Total Products</h3>
                <p><?= $totalProducts ?></p>
            </div>
        </a>

        <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'index']) ?>" class="dashboard-card">
            <div class="dashboard-icon">👤</div>
            <div class="dashboard-content">
                <h3>Total Users</h3>
                <p><?= $totalUsers ?></p>
            </div>
        </a>

        <a href="<?= $this->Url->build(['controller' => 'ContactSubmissions', 'action' => 'index']) ?>" class="dashboard-card">
            <div class="dashboard-icon">📩</div>
            <div class="dashboard-content">
                <h3>Total Enquiries</h3>
                <p><?= $totalEnquiries ?></p>
            </div>
        </a>
        <a href="<?= $this->Url->build(['controller' => 'Orders', 'action' => 'index']) ?>" class="dashboard-card">
            <div class="dashboard-icon">📦</div>
            <div class="dashboard-content">
                <h3>Total Orders</h3>
                <p><?= $totalOrders ?></p>
            </div>
        </a>
    </div>
    <?php if (!$lowStockProducts->isEmpty()): ?>
        <div class="low-stock-warning" style="margin:20px 0;padding:20px;background:#fff3cd;border:1px solid #ead28b;border-radius:12px;">

            <h3 style="margin:0 0 8px 0;font-size:1.2rem;">
                ⚠ Low Stock Products
            </h3>

            <p style="margin:0 0 15px 0;font-size:0.9rem;color:#5f6b7a;">
                These products are running low and may need restocking soon.
            </p>

            <div style="display:grid;gap:12px;">
                <?php foreach ($lowStockProducts as $product): ?>
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;background:#fff;border:1px solid #f1d98c;border-radius:10px;">

                        <div style="font-weight:600;color:#4b5563;">
                            <?= h($product->name) ?>
                        </div>

                        <div style="display:flex;align-items:center;gap:12px;">

                        <span style="font-weight:700;color:#d32f2f;">
                            Stock: <?= $product->stock ?>
                        </span>

                            <?= $this->Html->link(
                                'Restock',
                                ['controller' => 'Products', 'action' => 'edit', $product->id],
                                [
                                    'style' => 'padding:6px 12px;background:#d32f2f;color:white;border-radius:6px;font-size:0.8rem;text-decoration:none;'
                                ]
                            ) ?>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="dashboard-grid">

        <a href="<?= $this->Url->build(['controller' => 'ContactSubmissions', 'action' => 'index']) ?>" class="dashboard-card">
            <div class="dashboard-icon">📩</div>
            <div class="dashboard-content">
                <h3>Contact Submissions</h3>
                <p>View and respond to customer enquiries.</p>
            </div>
        </a>

        <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'index']) ?>" class="dashboard-card">
            <div class="dashboard-icon">👤</div>
            <div class="dashboard-content">
                <h3>Users</h3>
                <p>Manage system users and permissions.</p>
            </div>
        </a>
        <a href="<?= $this->Url->build(['controller' => 'Products', 'action' => 'index']) ?>" class="dashboard-card">
            <div class="dashboard-icon">💎</div>
            <div class="dashboard-content">
                <h3>Manage products</h3>
                <p>Add, update, and delete product listings.</p>
            </div>
        </a>
        <a href="<?= $this->Url->build(['controller' => 'Orders', 'action' => 'index']) ?>" class="dashboard-card">
            <div class="dashboard-icon">📦</div>
            <div class="dashboard-content">
                <h3>Manage Orders</h3>
                <p>View and manage customer orders.</p>
            </div>
        </a>

    </div>

