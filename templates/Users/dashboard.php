<?php
/**
 * @var \App\View\AppView $this
 * @var \Authentication\Identity $authUser
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

    <div class="dashboard-grid">
        <a href="<?= $this->Url->build(['controller' => 'ContactSubmissions', 'action' => 'index']) ?>" class="dashboard-card">
            <h3>View Contact Submissions</h3>
            <p>Review messages submitted by customers.</p>
        </a>

        <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'index']) ?>" class="dashboard-card">
            <h3>Manage Users</h3>
            <p>Edit, update, and organise user accounts.</p>
        </a>
    </div>
</div>
</div>

