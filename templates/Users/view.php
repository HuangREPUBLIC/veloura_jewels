<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->assign('title', 'View User');
$this->assign('crumbRecord', h($user->email));
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>
<?php $role = $this->request->getAttribute('identity')->get('role'); ?>

        <div class="page-header-row">
            <div>
                <h3 class="page-title"><?= h($user->email) ?></h3>
            </div>

            <?php
            $identity = $this->request->getAttribute('identity');
            ?>

            <div class="action-buttons">
                <?php if ($role === 'admin'): ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id], ['class' => 'btn-sm']) ?>
                <?php endif; ?>

                <?php if ($role === 'admin' && $user->id !== $identity->get('id')): ?>
                    <?= $this->Form->postLink(
                        __('Delete'),
                        ['action' => 'delete', $user->id],
                        [
                            'confirm' => __('Are you sure you want to delete # {0}?', $user->id),
                            'class' => 'btn-sm btn-sm--danger',
                        ]
                    ) ?>
                <?php endif; ?>
            </div>
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
                <td><span class="role-pill role-pill-<?= h($user->role) ?>"><?= h($user->role) ?></span></td>
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
