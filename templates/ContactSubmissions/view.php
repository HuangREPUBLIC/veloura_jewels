<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ContactSubmission $contactSubmission
 */
$this->assign('title', 'View Submission');
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>

<div class="admin-wrapper">
    <div class="contactSubmissions view content">
        <h3><?= h($contactSubmission->first_name . ' ' . $contactSubmission->last_name) ?></h3>

        <div class="action-buttons">
            <?= $this->Html->link(__('← Back to Submissions'), ['action' => 'index']) ?>
            <?= $this->Html->link(__('Reply'), ['action' => 'reply', $contactSubmission->id]) ?>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $contactSubmission->id],
                [
                    'confirm' => __('Are you sure you want to delete # {0}?', $contactSubmission->id),
                    'class'   => 'btn-delete',
                ]
            ) ?>
        </div>

        <table class="view-table" style="margin-bottom: 1.5rem;">
            <tr>
                <th><?= __('Email') ?></th>
                <td><?= h($contactSubmission->email) ?></td>
            </tr>
            <tr>
                <th><?= __('Subject') ?></th>
                <td><?= h($contactSubmission->subject) ?></td>
            </tr>
            <tr>
                <th><?= __('Submitted') ?></th>
                <td><?= h($contactSubmission->created) ?></td>
            </tr>
            <tr>
                <th><?= __('Replied') ?></th>
                <td><?= $contactSubmission->is_replied ? '<span class="replied-yes">Yes</span>' : '<span class="replied-no">No</span>' ?></td>
            </tr>
        </table>

        <div class="submission-message-block">
            <div class="submission-message-header">
                <strong><?= __('Message') ?></strong>
                <?= $this->Html->link(__('Reply →'), ['action' => 'reply', $contactSubmission->id]) ?>
            </div>
            <div class="message-box">
                <?= $this->Text->autoParagraph(h($contactSubmission->message)) ?>
            </div>
        </div>

        <?php if (!empty($contactSubmission->contact_replies)): ?>
            <h4 style="margin: 1.5rem 0 0.8rem;"><?= __('Replies') ?></h4>
            <table class="view-table">
                <thead>
                <tr>
                    <th><?= __('Subject') ?></th>
                    <th><?= __('Message') ?></th>
                    <th><?= __('Sent At') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($contactSubmission->contact_replies as $reply): ?>
                    <tr>
                        <td><?= h($reply->subject) ?></td>
                        <td><?= h($reply->message) ?></td>
                        <td><?= h($reply->sent_at) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted"><?= __('No replies sent yet.') ?></p>
        <?php endif; ?>
    </div>
</div>
