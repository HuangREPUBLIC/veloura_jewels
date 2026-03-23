<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */

$this->layout = 'login';
$this->assign('title', 'Register new user');
?>
<div class="container register">
    <div class="users form content">

        <h2>Register new user</h2>

        <?= $this->Form->create($user) ?>

        <?= $this->Flash->render() ?>

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

        <?= $this->Form->button('Register') ?>

        <p style="margin-top: 1rem; text-align: center;">
            <?= $this->Html->link(
                'Back to login',
                ['controller' => 'Auth', 'action' => 'login']
            ) ?>
        </p>

        <?= $this->Form->end() ?>

    </div>
</div>
