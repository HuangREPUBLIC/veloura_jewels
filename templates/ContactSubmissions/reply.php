<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ContactSubmission $contactSubmission
 */
?>

<div class="contactSubmissions form content">
    <h2><?= __('Reply to Enquiry') ?></h2>
    <p>
        <strong><?= __('Name:') ?></strong>
        <?= h($contactSubmission->first_name . ' ' . $contactSubmission->last_name) ?>
    </p>

    <p><strong>Email:</strong> <?= h($contactSubmission->email) ?></p>

    <?= $this->Form->create() ?>
    <fieldset>
        <?= $this->Form->control('subject', [
            'label' => 'Subject',
            'required' => true,
            'value' => 'Re: Your enquiry'
        ]) ?>

        <?= $this->Form->control('message', [
            'type' => 'textarea',
            'label' => 'Message',
            'required' => true,
            'value' => 'Hi ' . $contactSubmission->first_name . ','
        ]) ?>
    </fieldset>

    <?= $this->Form->button(__('Send Reply')) ?>
    <?= $this->Form->end() ?>
</div>
