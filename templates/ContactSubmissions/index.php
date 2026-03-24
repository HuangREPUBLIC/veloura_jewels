<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\ContactSubmission> $contactSubmissions
 */
$this->assign('title', 'Contact Submissions');
?>

<?php $this->Html->css('admincontact', ['block' => true]); ?>

<div class="submissions-wrapper">
    <div class="contactSubmissions index content">
        <h3><?= __('Contact Submissions') ?></h3>

        <div class="table-responsive">
            <table>
                <thead>
                <tr>
                    <th><?= __('Details') ?></th>
                    <th><?= __('Subject') ?></th>
                    <th><?= $this->Paginator->sort('created', 'Submission Date/Time') ?></th>
                    <th><?= __('Replied') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($contactSubmissions)): ?>
                    <?php foreach ($contactSubmissions as $contactSubmission): ?>
                        <tr>
                            <td>
                                <strong><?= h($contactSubmission->first_name) ?> <?= h($contactSubmission->last_name) ?></strong>
                                <br>
                                <small><?= h($contactSubmission->email) ?></small>
                            </td>
                            <td><?= h($contactSubmission->subject) ?></td>
                            <td><?= h($contactSubmission->created) ?></td>
                            <td>
                                <?php if ($contactSubmission->is_replied): ?>
                                    <span style="color: green;">Yes</span>
                                <?php else: ?>
                                    <span style="color: #999;">No</span>
                                <?php endif; ?>
                            </td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $contactSubmission->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['action' => 'delete', $contactSubmission->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $contactSubmission->id),
                                    ]
                                ) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5"><?= __('No contact form submissions found.') ?></td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
