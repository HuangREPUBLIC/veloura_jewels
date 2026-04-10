<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */
$this->assign('title', 'Users');
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>

<div class="admin-wrapper">
    <div class="users index content">
        <?= $this->Html->link(__('← Back'), ['controller' => 'Users', 'action' => 'dashboard']) ?>

        <h3 class="page-title"><?= __('Users') ?></h3>

        <div class="table-responsive" id="datatable" style="padding: 10px">
            <table id="usersTable" class="display">
                <thead>
                <tr>
                    <th><?= $this->Paginator->sort('email', 'Email') ?></th>
                    <th><?= $this->Paginator->sort('role', 'Role') ?></th>
                    <th><?= $this->Paginator->sort('created', 'Created') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $user): ?>
                    <?php
                    $identity = $this->request->getAttribute('identity');
                    $currentRole = $identity ? $identity->get('role') : null;
                    ?>
                    <tr>
                        <td><?= h($user->email) ?></td>
                        <td><?= h($user->role) ?></td>
                        <td><?= h($user->created) ?></td>
                        <td class="actions">
                            <?= $this->Html->link(__('View'), ['action' => 'view', $user->id]) ?>

                            <?php if (in_array($currentRole, ['admin', 'full_time'])): ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id]) ?>
                            <?php endif; ?>

                            <?php if ($currentRole === 'admin'): ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['action' => 'delete', $user->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete {0}?', $user->email),
                                        'class' => 'btn-delete',
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
            order: [[1, 'desc']],
            language: {
                lengthMenu: '_MENU_ Entries Per Page',
                search: 'Search:'
            },
            columnDefs: [{ targets: [0, 1, 2, 3], searchable: true }]
        });
    });
</script>
