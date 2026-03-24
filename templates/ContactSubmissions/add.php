<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ContactSubmission $contactSubmission
 */

use Cake\Core\Configure;
?>

<?php $this->Html->css('contact', ['block' => true]); ?>
<?php $this->Html->css('default-styles', ['block' => true]); ?>

<?php
// Load CF Turnstile captcha JS library
$this->Html->script('https://challenges.cloudflare.com/turnstile/v0/api.js', [
    'block' => true,
    'async' => true,
    'defer' => true,
]);

$this->Html->meta([
    'block' => true,
    'link' => 'https://challenges.cloudflare.com',
    'rel' => 'preconnect',
]);
?>

<div class="contact-page">
    <div class="contact-header">
        <h2>Contact Veloura Jewels</h2>
        <p>We'd love to hear from you! Whether you have questions about our handcrafted jewelry, want to discuss a custom piece, or simply want to learn more about our story, feel free to reach out.</p>
    </div>

    <div class="contactSubmissions form content">
        <?= $this->Form->create($contactSubmission) ?>
        <fieldset>
            <div class="form-row">
                <?= $this->Form->control('first_name', [
                    'label' => 'First Name',
                    'placeholder' => 'Your first name',
                    'required' => true
                ]) ?>

                <?= $this->Form->control('last_name', [
                    'label' => 'Last Name',
                    'placeholder' => 'Your last name',
                    'required' => true
                ]) ?>
            </div>

            <?= $this->Form->control('email', [
                'label' => 'Your Email',
                'placeholder' => 'Your email address',
                'required' => true
            ]) ?>

            <?= $this->Form->control('message', [
                'label' => 'Message',
                'placeholder' => 'Write your message here...',
                'required' => true
            ]) ?>

            <div class="cf-turnstile"
                 data-theme="light"
                 data-size="flexible"
                 data-callback="turnstileOnSuccess"
                 data-error-callback="turnstileOnError"
                 data-expired-callback="turnstileOnExpired"
                 data-timeout-callback="turnstileOnTimeout"
                 data-sitekey="<?= Configure::read('Captcha.turnstile.siteKey') ?>">
            </div>

            <blockquote id="turnstile-message" style="display:none"></blockquote>
        </fieldset>

        <?= $this->Form->button(__('Send Message'), [
            'class' => 'action-button',
            'disabled' => true
        ]) ?>

        <?= $this->Form->end() ?>
    </div>
</div>

<script>
    var turnstileMessageBlock = document.querySelector('#turnstile-message');
    var actionButton = document.querySelector('button.action-button');

    function turnstileOnSuccess(token) {
        turnstileMessageBlock.style.display = 'none';
        actionButton.removeAttribute('disabled');
    }

    function turnstileOnError(errorCode) {
        turnstileMessageBlock.style.display = 'block';
        turnstileMessageBlock.innerText = 'Challenge error. Please refresh the webpage and try again.';
        actionButton.setAttribute('disabled', 'disabled');
    }

    function turnstileOnExpired() {
        turnstileMessageBlock.style.display = 'block';
        turnstileMessageBlock.innerText = 'Challenge token expired. Please validate again.';
        actionButton.setAttribute('disabled', 'disabled');
    }

    function turnstileOnTimeout() {
        turnstileMessageBlock.style.display = 'block';
        turnstileMessageBlock.innerText = 'Challenge timed out. Please validate again.';
        actionButton.setAttribute('disabled', 'disabled');
    }
</script>
