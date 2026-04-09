<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */

$this->layout = 'login';
$this->assign('title', 'Register new user');
?>
<div class="login-page">
    <div class="users form content">
        <?= $this->Html->link(__('← Back'), ['action' => 'index']) ?>

        <fieldset>
            <legend>Register</legend>

            <?= $this->Flash->render() ?>

            <?= $this->Form->create($user) ?>

            <?= $this->Form->control('email'); ?>

            <div class="row">
                <?= $this->Form->control('first_name', ['templateVars' => ['container_class' => 'column']]); ?>
                <?= $this->Form->control('last_name', ['templateVars' => ['container_class' => 'column']]); ?>
            </div>

            <div class="row">
                <?php
                echo $this->Form->control('password', [
                    'value' => '',
                    'templateVars' => ['container_class' => 'column']
                ]);

                echo $this->Form->control('password_confirm', [
                    'type' => 'password',
                    'value' => '',
                    'label' => 'Retype Password',
                    'templateVars' => ['container_class' => 'column']
                ]);
                ?>
            </div>

            <?= $this->Form->control('avatar', ['type' => 'file']); ?>

        </fieldset>

        <?= $this->Form->button('Register', ['class' => 'login-button']) ?>

        <?= $this->Form->end() ?>


    </div>
</div>
