<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->assign('title', 'View User');
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>

<div class="admin-wrapper">
    <div class="users view content">
        <h3><?= h($user->email) ?></h3>

        <div class="action-buttons">
            <?= $this->Html->link(__('← Back to Users'), ['action' => 'index']) ?>

            <?php
            $identity = $this->request->getAttribute('identity');
            $currentRole = $identity ? $identity->get('role') : null;
            ?>

            <?php if (in_array($currentRole, ['admin', 'full_time'])): ?>
                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id]) ?>
            <?php endif; ?>

            <?php if ($currentRole === 'admin'): ?>
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

        <table>
            <tr>
                <th><?= __('Email') ?></th>
                <td><?= h($user->email) ?></td>
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
