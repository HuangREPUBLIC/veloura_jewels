<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ContactSubmission $contactSubmission
 */
$this->assign('title', 'View Submission');
$this->assign('crumbRecord', h($contactSubmission->first_name . ' ' . $contactSubmission->last_name));
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>

        <div class="page-header-row">
            <div>
                <h3 class="page-title"><?= h($contactSubmission->first_name . ' ' . $contactSubmission->last_name) ?></h3>
            </div>

            <div class="action-buttons">
                <?= $this->Html->link(__('Reply'), ['action' => 'reply', $contactSubmission->id], ['class' => 'btn-sm']) ?>
                <?= $this->Form->postLink(
                    'Archive',
                    ['action' => 'archive', $contactSubmission->id],
                    ['confirm' => 'Archive this submission?', 'class' => 'btn-sm btn-sm--danger'],
                ) ?>
            </div>
        </div>

        <table class="view-table admin-view-table-spaced">
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
            <h4 class="admin-view-section-title"><?= __('Replies') ?></h4>
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
