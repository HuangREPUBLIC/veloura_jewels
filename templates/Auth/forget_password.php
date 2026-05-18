<?php
/**
 * @var \App\View\AppView $this
 */

$this->assign('title', 'Forgot Password');
$this->Html->css('login', ['block' => true]);
?>

<div class="login-page">
    <div class="users form content">

        <?= $this->Form->create() ?>

        <fieldset>
            <legend>Forgot Password</legend>

            <?= $this->Flash->render() ?>

            <p class="form-hint">
                Enter the email address on your account and we'll send you a reset link.
            </p>

            <?php
            echo $this->Form->control('email', [
                'type' => 'email',
                'required' => true,
                'autofocus' => true,
                'label' => 'Email',
            ]);
            ?>

        </fieldset>

        <?= $this->Form->button('Send Reset Link', ['class' => 'login-button']) ?>
        <?= $this->Form->end() ?>

        <div class="login-divider"><span>or</span></div>

        <p class="login-new-label">Remember your password?</p>
        <?= $this->Html->link('Sign In', ['controller' => 'Auth', 'action' => 'login'], ['class' => 'login-register-btn']) ?>

    </div>
</div>
