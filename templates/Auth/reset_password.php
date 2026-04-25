<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->assign('title', 'Reset Password');
$this->Html->css('login', ['block' => true]);
?>
<div class="login-page">
    <div class="users form content">

        <?= $this->Form->create($user) ?>

        <fieldset>
            <legend>Reset Your Password</legend>

            <?= $this->Flash->render() ?>

            <?php
            echo $this->Form->control('password', [
                'type' => 'password',
                'label' => 'New Password',
                'required' => true,
                'autofocus' => true,
                'value' => ''
            ]);
            echo $this->Form->control('password_confirm', [
                'type' => 'password',
                'label' => 'Confirm New Password',
                'required' => true,
                'value' => ''
            ]);
            ?>

        </fieldset>

        <?= $this->Form->button('Reset Password', ['class' => 'login-button']) ?>

        <?= $this->Form->end() ?>


    </div>
</div>
