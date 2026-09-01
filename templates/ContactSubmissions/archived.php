<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\ContactSubmission> $contactSubmissions
 * @var string $q
 */
$this->assign('title', 'Contact Submissions');
$this->assign('crumbRecord', 'Archived');
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>
<?php $role = $this->request->getAttribute('identity')->get('role'); ?>

<div class="page-header-row">
    <div>
        <p class="cms-eyebrow">Store</p>
        <h2 class="page-title"><?= __('Archived Contact Submissions') ?></h2>
    </div>
    <div class="cms-header-right">
        <?= $this->element('per_page_select') ?>
    </div>
</div>

<?= $this->Form->create(null, ['type' => 'get', 'class' => 'admin-search']) ?>
    <input type="text" name="q" value="<?= h($q) ?>" placeholder="Search by name, email or subject" class="admin-search__input">
    <button type="submit" class="btn-sm"><?= $this->iconSvg('search') ?></button>
<?= $this->Form->end() ?>

<?php if ($contactSubmissions->isEmpty()): ?>
    <div class="admin-empty-state">
        <span class="admin-empty-state__icon"><?= $this->iconSvg('mail') ?></span>
        <p class="admin-empty-state__title">No archived submissions</p>
        <p class="admin-empty-state__sub">Submissions you archive from the main list will show up here.</p>
    </div>
<?php else: ?>
    <div class="table-wrap">
        <table class="data-table">
            <thead>
            <tr>
                <th>Details</th>
                <th>Subject</th>
                <th><?= $this->Paginator->sort('created', 'Date') ?></th>
                <th>Replied</th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($contactSubmissions as $contactSubmission): ?>
                <tr>
                    <td>
                        <strong><?= h($contactSubmission->first_name) ?> <?= h($contactSubmission->last_name) ?></strong>
                        <br>
                        <small><?= h($contactSubmission->email) ?></small>
                    </td>
                    <td><?= h($contactSubmission->subject) ?></td>
                    <td><?= h($contactSubmission->created->format('d M Y H:i')) ?></td>
                    <td>
                        <?php if ($contactSubmission->is_replied): ?>
                            <span class="status-badge status-badge--success">Yes</span>
                        <?php else: ?>
                            <span class="status-badge status-badge--neutral">No</span>
                        <?php endif; ?>
                    </td>
                    <td class="actions">
                        <?= $this->Form->postLink(
                            $this->iconSvg('undo'),
                            ['action' => 'unarchive', $contactSubmission->id],
                            ['confirm' => 'Restore this submission?', 'escape' => false, 'aria-label' => __('Restore')]
                        ) ?>
                        <?php if ($role === 'admin'): ?>
                        <?= $this->Form->postLink(
                            $this->iconSvg('trash'),
                            ['action' => 'delete', $contactSubmission->id],
                            [
                                'method'  => 'delete',
                                'confirm' => __('Are you sure you want to permanently delete this submission?'),
                                'escape'  => false,
                                'aria-label' => __('Delete'),
                                'class'   => 'btn-sm btn-sm--danger',
                            ]
                        ) ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?= $this->element('paginator') ?>
<?php endif; ?>
