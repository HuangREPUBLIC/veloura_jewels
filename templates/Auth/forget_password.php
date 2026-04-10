<?php
/**
 * @var \App\View\AppView $this
 */

$this->layout = 'login';
$this->assign('title', 'Forget Password');
?>

<div class="login-page">
    <div class="users form content">
        <?= $this->Html->link(__('← Back'), ['action' => 'index']) ?>

        <?= $this->Form->create() ?>

        <fieldset>
            <legend>Forget Password</legend>

            <?= $this->Flash->render() ?>

            <p class="form-hint">
                Enter your email address registered with our system below to reset your password.
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

        <?= $this->Form->button('Send verification email', ['class' => 'login-button']) ?>
        <?= $this->Form->end() ?>


    </div>
</div>
