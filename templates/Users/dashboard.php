<?php
/**
 * @var \App\View\AppView $this
 * @var array $authUser
 */
?>
<?php $this->Html->css('default-styles', ['block' => true]); ?>
<?php $this->Html->css('default-styles', ['block' => true]); ?>
<div class="users dashboard content">
    <h2><?= __('Admin Dashboard') ?></h2>

    <p><?= __('Welcome, {0}', h($authUser['email'])) ?></p>
    <p><?= __('Role: {0}', h($authUser['role'])) ?></p>

    <div class="dashboard-links">
        <ul>
            <?php if (in_array($authUser['role'], ['admin', 'full_time'], true)): ?>
                <li>
                    <?= $this->Html->link(
                        __('View Contact Form Submissions'),
                        ['controller' => 'ContactSubmissions', 'action' => 'index']
                    ) ?>
                </li>
            <?php endif; ?>

            <?php if ($authUser['role'] === 'admin'): ?>
                <li>
                    <?= $this->Html->link(
                        __('Manage Products'),
                        ['controller' => 'Products', 'action' => 'index']
                    ) ?>
                </li>
            <?php endif; ?>

        </ul>
    </div>
</div>
