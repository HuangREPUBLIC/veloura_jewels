<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ContactSubmission $contactSubmission
 */
$this->assign('title', 'Reply to Enquiry');
$this->Html->css('login', ['block' => true]);
?>

<div class="login-page">
    <div class="users form content login-card--reply">

        <div class="action-buttons">
            <?= $this->Html->link(__('← Back to Submissions'), ['action' => 'index']) ?>
        </div>

        <h3><?= __('Reply to Enquiry') ?></h3>

        <div class="enquiry-summary">
            <div class="enquiry-row">
                <span class="enquiry-label"><?= __('Name') ?></span>
                <span class="enquiry-value"><?= h($contactSubmission->first_name . ' ' . $contactSubmission->last_name) ?></span>
            </div>
            <div class="enquiry-row">
                <span class="enquiry-label"><?= __('Email') ?></span>
                <span class="enquiry-value"><?= h($contactSubmission->email) ?></span>
            </div>
            <div class="enquiry-row enquiry-row--message">
                <span class="enquiry-label"><?= __('Message') ?></span>
                <span class="enquiry-value"><?= h($contactSubmission->message) ?></span>
            </div>
        </div>

        <div class="login-divider">Your Reply</div>

        <?= $this->Form->create() ?>
        <fieldset>
            <?= $this->Flash->render() ?>
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
