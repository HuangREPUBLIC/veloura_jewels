<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */
$this->assign('title', 'Users');
$identity = $this->request->getAttribute('identity');
$currentRole = $identity ? $identity->get('role') : null;
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>
<?php $role = $this->request->getAttribute('identity')->get('role'); ?>


<div class="admin-wrapper">
    <div class="users index content">
        <?= $this->Html->link(__('← Back'), ['controller' => 'Users', 'action' => 'dashboard']) ?>

        <div class="page-header-row">
            <h3 class="page-title"><?= __('Users') ?></h3>
        </div>

        <div class="table-responsive" style="padding: 10px">
            <table id="usersTable" class="display">
                <thead>
                <tr>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Created</th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= h($user->email) ?></td>
                        <td><?= h(trim($user->first_name . ' ' . $user->last_name)) ?: '<span style="color:#bbb">—</span>' ?></td>
                        <td><span class="role-pill role-pill-<?= h($user->role) ?>"><?= h($user->role) ?></span></td>
                        <td><?= h($user->created) ?></td>
                        <td class="actions">
                            <?= $this->Html->link(__('View'), ['action' => 'view', $user->id]) ?>

                            <?php if ($role === 'admin'): ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id]) ?>
                            <?php endif; ?>

                            <?php if ($currentRole === 'admin'): ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['action' => 'delete', $user->id],
                                    [
                                        'method'  => 'delete',
                                        'confirm' => __('Are you sure you want to delete {0}?', $user->email),
                                        'class'   => 'btn-delete',
                                    ]
                                ) ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#usersTable').DataTable({
            order: [[3, 'desc']],
            language: {
                lengthMenu: '_MENU_ Entries Per Page',
                search: 'Search:'
            },
            columnDefs: [{ targets: [0, 1, 2, 3], searchable: true }]
        });
    });
</script>
