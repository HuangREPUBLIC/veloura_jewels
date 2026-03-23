<?php
/**
 * @var \App\View\AppView $this
 */
$this->layout = 'login';
$this->assign('title', 'Login');
?>

<div class="users form content">
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
    </fieldset>
    <?= $this->Form->button('Login') ?>
    <?= $this->Form->end() ?>
    <?= $this->Html->link('Register', ['controller' => 'Auth', 'action' => 'register'], ['class' => 'btn-register']) ?>

    <?= $this->Html->link('Forgot password?', ['controller' => 'Auth', 'action' => 'forgetPassword']) ?>

</div>
