<?php
/**
 * @var \App\View\AppView $this
 */
use Cake\Core\Configure;
$this->assign('title', 'Login');
$this->Html->css('login', ['block' => true]);
?>

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

<div class="login-page">
    <div class="users form content">
        <p class="login-kicker">Welcome back</p>
        <?= $this->Form->create() ?>
        <fieldset>
            <legend>Login</legend>
            <?= $this->Flash->render() ?>

            <?php
            echo $this->Form->control('email', [
                'type' => 'email',
                'required' => true,
                'autofocus' => true,
            ]);

            echo $this->Form->control('password', [
                'type' => 'password',
                'required' => true,
            ]);
            ?>

            <div class="cf-turnstile"
                 data-sitekey="<?= Configure::read('Captcha.turnstile.siteKey') ?>"
                 data-theme="light"
                 data-size="flexible"
                 data-callback="turnstileOnSuccess"
                 data-error-callback="turnstileOnError"
                 data-expired-callback="turnstileOnExpired"
                 data-timeout-callback="turnstileOnTimeout">
            </div>

            <blockquote id="turnstile-message" style="display:none; margin-top:10px;"></blockquote>
        </fieldset>

        <?= $this->Form->button('Sign In', [
            'class' => 'login-button',
            'disabled' => true
        ]) ?>

        <?= $this->Form->end() ?>

        <?= $this->Html->link('Forgot password?', ['controller' => 'Auth', 'action' => 'forgetPassword'], ['class' => 'login-forgot-link']) ?>

        <div class="login-divider"><span>or</span></div>

        <p class="login-new-label">New to Veloura?</p>
        <?= $this->Html->link('Create Account', ['controller' => 'Auth', 'action' => 'register'], ['class' => 'login-register-btn']) ?>
    </div>
</div>

<script>
    const turnstileMessageBlock = document.getElementById('turnstile-message');
    const loginButton = document.querySelector('button.login-button');

    function turnstileOnSuccess(token) {
        turnstileMessageBlock.style.display = 'none';
        turnstileMessageBlock.innerText = '';
        loginButton.removeAttribute('disabled');
    }

    function turnstileOnError(errorCode) {
        turnstileMessageBlock.style.display = 'block';
        turnstileMessageBlock.innerText = 'Challenge error. Please refresh the page and try again.';
        loginButton.setAttribute('disabled', 'disabled');
    }

    function turnstileOnExpired() {
        turnstileMessageBlock.style.display = 'block';
        turnstileMessageBlock.innerText = 'Challenge expired. Please complete it again.';
        loginButton.setAttribute('disabled', 'disabled');
    }

    function turnstileOnTimeout() {
        turnstileMessageBlock.style.display = 'block';
        turnstileMessageBlock.innerText = 'Challenge timed out. Please complete it again.';
        loginButton.setAttribute('disabled', 'disabled');
    }
</script>
