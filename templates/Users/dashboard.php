<?php
/**
 * @var \App\View\AppView $this
 * @var \Authentication\Identity $authUser *
 * @var int $totalProducts
 * @var int $totalUsers
 * @var int $totalEnquiries
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
    <div class="dashboard-summary" style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin:30px 0;">
        <div class="dashboard-card">
            <div class="dashboard-icon">💎</div>
            <div class="dashboard-content">
                <h3>Total Products</h3>
                <p><?= $totalProducts ?></p>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="dashboard-icon">👤</div>
            <div class="dashboard-content">
                <h3>Total Users</h3>
                <p><?= $totalUsers ?></p>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="dashboard-icon">📩</div>
            <div class="dashboard-content">
                <h3>Total Enquiries</h3>
                <p><?= $totalEnquiries ?></p>
            </div>
        </div>
    </div>

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

    </div>

