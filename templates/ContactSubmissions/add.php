<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ContactSubmission $contactSubmission
 */
?>
<?php $this->Html->css('contact', ['block' => true]); ?>
<?php $this->Html->css('default-styles', ['block' => true]); ?>


<div class="contact-page">
    <div class="contact-header">
        <h2>Contact Veloura Jewels</h2>
        <p>We'd love to hear from you! Whether you have questions about our handcrafted jewelry, want to discuss a custom piece, or simply want to learn more about our story, feel free to reach out.</p>
    </div>

    <div class="contactSubmissions form content">
        <?= $this->Form->create($contactSubmission) ?>
        <fieldset>
            <div class="form-row">
                <?php echo $this->Form->control('first_name', [
                    'label' => 'First Name',
                    'placeholder' => 'Your first name',
                    'required' => true
                ]); ?>
                <?php echo $this->Form->control('last_name', [
                    'label' => 'Last Name',
                    'placeholder' => 'Your last name',
                    'required' => true
                ]); ?>
            </div>
            <?php echo $this->Form->control('email', [
                'label' => 'Your Email',
                'placeholder' => 'Your email address',
                'required' => true
            ]); ?>
            <?php echo $this->Form->control('message', [
                'label' => 'Message',
                'placeholder' => 'Write your message here...',
                'required' => true
            ]); ?>
        </fieldset>
        <?= $this->Form->button(__('Send Message')) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
