<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->assign('title', 'View User');
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>
<?php $role = $this->request->getAttribute('identity')->get('role'); ?>


<div class="admin-wrapper">
    <div class="users view content">
        <h3><?= h($user->email) ?></h3>

        <div class="action-buttons">
            <?= $this->Html->link(__('← Back to Users'), ['action' => 'index']) ?>

            <?php
            $identity = $this->request->getAttribute('identity');
            ?>

            <?php if ($role === 'admin'): ?>
                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id]) ?>
            <?php endif; ?>

            <?php if ($role === 'admin'): ?>
                <?= $this->Form->postLink(
                    __('Delete'),
                    ['action' => 'delete', $user->id],
                    [
                        'confirm' => __('Are you sure you want to delete # {0}?', $user->id),
                        'class' => 'btn-delete'
                    ]
                ) ?>
            <?php endif; ?>
        </div>

        <table class="view-table">
            <tr>
                <th><?= __('Email') ?></th>
                <td><?= h($user->email) ?></td>
            </tr>
            <tr>
                <th><?= __('First Name') ?></th>
                <td><?= h($user->first_name) ?: '—' ?></td>
            </tr>
            <tr>
                <th><?= __('Last Name') ?></th>
                <td><?= h($user->last_name) ?: '—' ?></td>
            </tr>
            <tr>
                <th><?= __('Phone') ?></th>
                <td><?= h($user->phone) ?: '—' ?></td>
            </tr>
            <tr>
                <th><?= __('Address') ?></th>
                <td><?= h($user->address) ?: '—' ?></td>
            </tr>
            <tr>
                <th><?= __('Role') ?></th>
                <td><?= h($user->role) ?></td>
            </tr>
            <tr>
                <th><?= __('Created') ?></th>
                <td><?= h($user->created) ?></td>
            </tr>
            <tr>
                <th><?= __('Modified') ?></th>
                <td><?= h($user->modified) ?></td>
            </tr>
        </table>
    </div>
</div>
