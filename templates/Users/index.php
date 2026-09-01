<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 * @var string $q
 */
$this->assign('title', 'Users');
$identity = $this->request->getAttribute('identity');
$role = $identity->get('role');
?>

<div class="page-header-row">
    <div>
        <p class="cms-eyebrow">Admin</p>
        <h2 class="page-title">Users</h2>
    </div>
    <div class="cms-header-right">
        <?= $this->element('per_page_select') ?>
        <?php if ($role === 'admin'): ?>
            <?= $this->Html->link(__('Schedule'), ['controller' => 'Schedule', 'action' => 'index'], ['class' => 'btn-sm']) ?>
        <?php endif; ?>
    </div>
</div>

<?= $this->Form->create(null, ['type' => 'get', 'class' => 'admin-search']) ?>
    <input type="text" name="q" value="<?= h($q) ?>" placeholder="Search by name or email" class="admin-search__input">
    <button type="submit" class="btn-sm"><?= $this->iconSvg('search') ?></button>
<?= $this->Form->end() ?>

<div class="table-wrap">
    <table class="data-table">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('email') ?></th>
                <th>Name</th>
                <th><?= $this->Paginator->sort('role') ?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= h($user->email) ?></td>
                    <td><?= h(trim($user->first_name . ' ' . $user->last_name)) ?: '—' ?></td>
                    <td><span class="status-badge <?= $user->role === 'admin' ? 'status-badge--info' : 'status-badge--neutral' ?>"><?= h(ucfirst($user->role)) ?></span></td>
                    <td><?= h($user->created->format('d M Y')) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(
                            $this->iconSvg('eye'),
                            ['action' => 'view', $user->id],
                            ['escape' => false, 'aria-label' => __('View')],
                        ) ?>
                        <?php if ($role === 'admin'): ?>
                            <?= $this->Html->link(
                                $this->iconSvg('edit'),
                                ['action' => 'edit', $user->id],
                                ['escape' => false, 'aria-label' => __('Edit')],
                            ) ?>
                            <?php if ($user->id !== $identity->get('id') && $user->id !== 6): ?>
                                <button
                                    type="button"
                                    class="btn-sm btn-sm--danger"
                                    aria-label="<?= h(__('Delete')) ?>"
                                    data-confirm-delete
                                    data-delete-url="<?= h($this->Url->build(['action' => 'delete', $user->id])) ?>"
                                    data-confirm-title="<?= h(__('Delete {0}?', $user->email)) ?>"
                                    data-confirm-body="<?= h(__('This removes their account. This cannot be undone.')) ?>"
                                ><?= $this->iconSvg('trash') ?></button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->element('paginator') ?>
