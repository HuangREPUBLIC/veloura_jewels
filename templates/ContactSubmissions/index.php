<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\ContactSubmission> $contactSubmissions
 */
?>
<div class="contactSubmissions index content">
    <?= $this->Html->link(__('New Contact Submission'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Contact Submissions') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('email') ?></th>
                    <th><?= $this->Paginator->sort('captcha_passed') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contactSubmissions as $contactSubmission): ?>
                <tr>
                    <td><?= $this->Number->format($contactSubmission->id) ?></td>
                    <td><?= h($contactSubmission->name) ?></td>
                    <td><?= h($contactSubmission->email) ?></td>
                    <td><?= h($contactSubmission->captcha_passed) ?></td>
                    <td><?= h($contactSubmission->created) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $contactSubmission->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $contactSubmission->id]) ?>
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