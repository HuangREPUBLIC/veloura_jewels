<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->assign('title', 'Create Account');
$this->Html->css('login', ['block' => true]);
?>
<div class="login-page">
    <div class="users form content login-card--wide">

        <fieldset>
            <legend>Create Account</legend>
            <?= $this->Flash->render() ?>
            <?= $this->Form->create($user) ?>

            <?= $this->Form->control('email', ['required' => true, 'autofocus' => true]) ?>

            <div class="register-row">
                <?= $this->Form->control('first_name', ['required' => true]) ?>
                <?= $this->Form->control('last_name', ['required' => true]) ?>
            </div>

            <div class="register-row">
                <?= $this->Form->control('password', [
                    'type'  => 'password',
                    'value' => '',
                    'required' => true,
                ]) ?>
                <?= $this->Form->control('password_confirm', [
                    'type'     => 'password',
                    'value'    => '',
                    'label'    => 'Confirm Password',
                    'required' => true,
                ]) ?>
            </div>

        </fieldset>

        <?= $this->Form->button('Create Account', ['class' => 'login-button']) ?>
        <?= $this->Form->end() ?>

        <div class="login-divider"><span>or</span></div>

        <p class="login-new-label">Already have an account?</p>
        <?= $this->Html->link('Sign In', ['controller' => 'Auth', 'action' => 'login'], ['class' => 'login-register-btn']) ?>
    </div>
</div>
