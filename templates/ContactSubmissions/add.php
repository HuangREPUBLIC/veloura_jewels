<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ContactSubmission $contactSubmission
 */

use Cake\Core\Configure;
?>

<?php $this->Html->css('contact', ['block' => true]); ?>

<?php
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

            <div class="contact-form-row">
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

            <?= $this->Form->control('subject', [
                'label' => 'Subject',
                'placeholder' => 'Enter Subject for Message',
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
            'class' => 'contact-submit-btn',
            'disabled' => true
        ]) ?>

        <?= $this->Form->button(__('Reset'), [
            'type' => 'reset',
            'class' => 'contact-reset-btn'
        ]) ?>

        <?= $this->Form->end() ?>
    </div>
</div>

<!-- Live Chat Button -->
<button class="live-chat-btn" onclick="toggleChat()">
    <svg class="chat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
    </svg>
    <span class="chat-label">Live Chat</span>
    <span class="chat-dot"></span>
</button>

<!-- Chat Popup -->
<div class="live-chat-popup" id="chatPopup">
    <div class="chat-popup-header">
        <div class="chat-popup-header-left">
            <div class="chat-popup-avatar">
                <img src="<?= $this->Url->image('icon.png') ?>" alt="Veloura Jewels" style="width:28px;height:28px;object-fit:contain;border-radius:50%;">
            </div>
            <div>
                <div class="chat-popup-title">Veloura Jewels</div>
                <div class="chat-popup-subtitle">Typically replies in minutes</div>
            </div>
        </div>
        <button class="chat-popup-close" onclick="toggleChat()">✕</button>
    </div>
    <div class="chat-popup-body">
        <div class="chat-bubble">
            👋 Hi there! Welcome to Veloura Jewels. How can we help you today?
        </div>
        <div class="chat-bubble">
            Feel free to send us a message or use the contact form below.
        </div>
    </div>
    <div class="chat-popup-footer">
        <a href="<?= $this->Url->build(['controller' => 'ContactSubmissions', 'action' => 'add']) ?>" class="chat-contact-btn">Contact Form</a>
        <a href="mailto:veloura.jewels@gmail.com" class="chat-email-btn">Send Email</a>
    </div>
</div>

<script>
    var turnstileMessageBlock = document.querySelector('#turnstile-message');
    var actionButton = document.querySelector('button.contact-submit-btn');

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

    function toggleChat() {
        document.getElementById('chatPopup').classList.toggle('open');
    }
</script>
