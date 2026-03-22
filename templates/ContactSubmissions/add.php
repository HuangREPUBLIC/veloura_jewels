<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ContactSubmission $contactSubmission
 */
?>
<?php $this->Html->css('default-styles', ['block' => true]); ?>

<div class="contactSubmissions form content">
    <h2><?= __('Contact Us') ?></h2>
    <?= $this->Form->create($contactSubmission) ?>
    <fieldset>
        <?php
        echo $this->Form->control('first_name', [
            'label' => 'First Name',
            'placeholder' => 'Enter your first name',
            'required' => true
        ]);
        echo $this->Form->control('last_name', [
            'label' => 'Last Name',
            'placeholder' => 'Enter your last name',
            'required' => true
        ]);
        echo $this->Form->control('email', [
            'label' => 'Your Email',
            'placeholder' => 'Enter your email address',
            'required' => true
        ]);
        echo $this->Form->control('message', [
            'label' => 'Message',
            'placeholder' => 'Write your message here...',
            'required' => true
        ]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Send Message')) ?>
    <?= $this->Form->end() ?>
</div>
