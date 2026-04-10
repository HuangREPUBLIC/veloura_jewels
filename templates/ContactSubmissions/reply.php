<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ContactSubmission $contactSubmission
 */
$this->assign('title', 'Reply to Enquiry');
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>

<div class="login-page">
    <div class="users form content login-card--wide">
        <h3><?= __('Reply to Enquiry') ?></h3>

        <div class="action-buttons">
            <?= $this->Html->link(__('← Back to Submissions'), ['action' => 'index']) ?>
        </div>

        <table class="reply-context-table">
            <tr>
                <th><?= __('Name') ?></th>
                <td><?= h($contactSubmission->first_name . ' ' . $contactSubmission->last_name) ?></td>
            </tr>
            <tr>
                <th><?= __('Email') ?></th>
                <td><?= h($contactSubmission->email) ?></td>
            </tr>
            <tr>
                <th><?= __('Message') ?></th>
                <td><?= h($contactSubmission->message) ?></td>
            </tr>
        </table>

        <?= $this->Form->create() ?>
        <fieldset>
            <?= $this->Form->control('subject', [
                'label'    => 'Subject',
                'required' => true,
                'value'    => 'Re: ' . $contactSubmission->subject,
            ]) ?>
            <?= $this->Form->control('message', [
                'type'     => 'textarea',
                'label'    => 'Message',
                'required' => true,
                'value'    => 'Hi ' . $contactSubmission->first_name . ',',
            ]) ?>
        </fieldset>
        <?= $this->Form->button(__('Send Reply'), ['class' => 'login-button']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
