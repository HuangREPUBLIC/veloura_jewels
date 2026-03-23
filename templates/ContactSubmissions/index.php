<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\ContactSubmission> $contactSubmissions
 */
?>
<div class="contactSubmissions index content">
    <h3><?= __('Contact Submissions') ?></h3>

    <div class="table-responsive">
        <table>
            <thead>
            <tr>
                <th><?= $this->Paginator->sort('first_name', 'First Name') ?></th>
                <th><?= $this->Paginator->sort('last_name', 'Last Name') ?></th>
                <th><?= $this->Paginator->sort('email', 'Email') ?></th>
                <th><?= __('Message') ?></th>
                <th><?= $this->Paginator->sort('created', 'Submission Date/Time') ?></th>
                <th><?= __('Reply') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($contactSubmissions)): ?>
                <?php foreach ($contactSubmissions as $contactSubmission): ?>
                    <tr>
                        <td><?= h($contactSubmission->first_name) ?></td>
                        <td><?= h($contactSubmission->last_name) ?></td>
                        <td><?= h($contactSubmission->email) ?></td>
                        <td><?= h(mb_strimwidth($contactSubmission->message, 0, 60, '...')) ?></td>
                        <td><?= h($contactSubmission->created) ?></td>
                        <td>
                            <?= $this->Html->link(
                                __('Reply'),
                                ['action' => 'reply', $contactSubmission->id]
                            ) ?>
                            </a>
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
                    <td colspan="7"><?= __('No contact form submissions found.') ?></td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
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
