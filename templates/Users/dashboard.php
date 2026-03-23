<?php
/**
 * @var \App\View\AppView $this
 * @var \Authentication\Identity $authUser
 */
$this->assign('title', 'Admin Dashboard');
?>
<?php $this->Html->css('default-styles', ['block' => true]); ?>

<div class="users form content">
    <h2>Admin Dashboard</h2>
    <p>Welcome, <?= h($authUser->get('email')) ?></p>

    <ul>
        <li><?= $this->Html->link('View Contact Submissions', ['controller' => 'ContactSubmissions', 'action' => 'index']) ?></li>
        <?php if ($authUser->get('role') === 'admin'): ?>
            <li><?= $this->Html->link('Manage Users', ['controller' => 'Users', 'action' => 'index']) ?></li>
        <?php endif; ?>
    </ul>
</div>
