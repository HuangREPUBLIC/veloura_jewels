<?php
/**
 * @var \App\View\AppView $this
 */

$this->layout = 'login';
$this->assign('title', 'Forget Password');
?>

<div class="container login">
    <div class="row">
        <div class="column column-50 column-offset-25">

            <div class="users form content">

                <?= $this->Form->create() ?>

                <fieldset>

                    <legend>Forget Password</legend>

                    <?= $this->Flash->render() ?>

                    <p>Enter your email address registered with our system below to reset your password: </p>

                    <?php
                    echo $this->Form->control('email', [
                        'type' => 'email',
                        'required' => true,
                        'autofocus' => true,
                        'label' => false,
                    ]);
                    ?>

                </fieldset>

                <?= $this->Form->button('Send verification email') ?>
                <?= $this->Form->end() ?>

                <hr class="hr-between-buttons">

                <?= $this->Html->link('Back to login', ['controller' => 'Auth', 'action' => 'login'], ['class' => 'button button-outline']) ?>

            </div>
        </div>
    </div>
</div>
