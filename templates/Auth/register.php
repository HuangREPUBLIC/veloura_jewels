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

            <div class="row">
                <?= $this->Form->control('first_name', [
                    'required' => true,
                    'templateVars' => ['container_class' => 'column']
                ]) ?>
                <?= $this->Form->control('last_name', [
                    'required' => true,
                    'templateVars' => ['container_class' => 'column']
                ]) ?>
            </div>

            <div class="row">
                <?= $this->Form->control('password', [
                    'type'     => 'password',
                    'value'    => '',
                    'required' => true,
                    'templateVars' => ['container_class' => 'column']
                ]) ?>
                <?= $this->Form->control('password_confirm', [
                    'type'     => 'password',
                    'value'    => '',
                    'label'    => 'Confirm Password',
                    'required' => true,
                    'templateVars' => ['container_class' => 'column']
                ]) ?>
            </div>

        </fieldset>

        <?= $this->Form->button('Create Account', ['class' => 'login-button']) ?>
        <?= $this->Form->end() ?>

        <?= $this->Html->link('Already have an account? Login', ['controller' => 'Auth', 'action' => 'login']) ?>
    </div>
</div>
