<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\ContactSubmission> $contactSubmissions
 * @var string $q
 */
$this->assign('title', 'Contact Submissions');
?>

<div class="page-header-row">
    <div>
        <p class="cms-eyebrow">Store</p>
        <h2 class="page-title">Contact Submissions</h2>
    </div>
    <div class="cms-header-right">
        <?= $this->element('per_page_select') ?>
        <?= $this->Html->link(__('Archived'), ['action' => 'archived'], ['class' => 'btn-sm']) ?>
    </div>
</div>

<?= $this->Form->create(null, ['type' => 'get', 'class' => 'admin-search']) ?>
    <input type="text" name="q" value="<?= h($q) ?>" placeholder="Search by name, email or subject" class="admin-search__input">
    <button type="submit" class="btn-sm"><?= $this->iconSvg('search') ?></button>
<?= $this->Form->end() ?>

<div class="table-wrap">
    <table class="data-table">
        <thead>
            <tr>
                <th>Details</th>
                <th>Subject</th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th>Replied</th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contactSubmissions as $contactSubmission): ?>
                <tr>
                    <td>
                        <strong><?= h($contactSubmission->first_name) ?> <?= h($contactSubmission->last_name) ?></strong><br>
                        <small><?= h($contactSubmission->email) ?></small>
                    </td>
                    <td><?= h($contactSubmission->subject) ?></td>
                    <td><?= h($contactSubmission->created->format('d M Y H:i')) ?></td>
                    <td>
                        <span class="status-badge <?= $contactSubmission->is_replied ? 'status-badge--success' : 'status-badge--neutral' ?>">
                            <?= $contactSubmission->is_replied ? 'Yes' : 'No' ?>
                        </span>
                    </td>
                    <td class="actions">
                        <?= $this->Html->link(
                            $this->iconSvg('eye'),
                            ['action' => 'view', $contactSubmission->id],
                            ['escape' => false, 'aria-label' => __('View')],
                        ) ?>
                        <?= $this->Form->postLink(
                            $this->iconSvg('trash'),
                            ['action' => 'archive', $contactSubmission->id],
                            ['confirm' => 'Archive this submission?', 'escape' => false, 'aria-label' => __('Archive'), 'class' => 'btn-sm'],
                        ) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->element('paginator') ?>
