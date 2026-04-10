<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ContactSubmission $contactSubmission
 */
$this->assign('title', 'Reply History');
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>

<div class="admin-wrapper">
    <div class="contactSubmissions view content">
        <h3><?= __('Reply History') ?></h3>

        <div class="submission-info">
            <p><strong><?= __('Name:') ?></strong> <?= h($contactSubmission->first_name . ' ' . $contactSubmission->last_name) ?></p>
            <p><strong><?= __('Email:') ?></strong> <?= h($contactSubmission->email) ?></p>
        </div>

        <div class="related">
            <?php if (!empty($contactSubmission->contact_replies)): ?>
                <table>
                    <thead>
                    <tr>
                        <th><?= __('ID') ?></th>
                        <th><?= __('Subject') ?></th>
                        <th><?= __('Message') ?></th>
                        <th><?= __('Sent At') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($contactSubmission->contact_replies as $reply): ?>
                        <tr>
                            <td><?= h($reply->id) ?></td>
                            <td><?= h($reply->subject) ?></td>
                            <td><?= h($reply->message) ?></td>
                            <td><?= h($reply->sent_at) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted"><?= __('No replies have been sent yet.') ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
