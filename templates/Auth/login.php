<?php
/**
 * @var \App\View\AppView $this
 */

use Cake\Core\Configure;

$debug = Configure::read('debug');

$this->layout = 'login';
$this->assign('title', 'Login');
?>
<div class="container login">
    <div class="row">
        <div class="column column-50 column-offset-25">
            <div class="users form content">

                <?= $this->Form->create() ?>

                <fieldset>

                    <legend>Login</legend>

                    <?= $this->Flash->render() ?>

                    <?php
                    /*
                     * NOTE: regarding 'value' config in the login page form controls
                     * In this demo the email and password fields will be filled by demo account
                     * credentials when debug mode is on. You should NOT do that in your production
                     * systems. Also it's a good practice to clear (set password value to empty)
                     * in the view so when an error occurred with form validation, the password
                     * values are always cleared.
                     */
                    echo $this->Form->control('email', [
                        'type' => 'email',
                        'required' => true,
                        'autofocus' => true,
                        'value' => $debug ? "test@example.com" : "",
                    ]);
                    echo $this->Form->control('password', [
                        'type' => 'password',
                        'required' => true,
                        'value' => $debug ? 'password' : '',
                    ]);
                    ?>
                </fieldset>

                <?= $this->Form->button('Login') ?>
                <?= $this->Html->link('Forgot password?', ['controller' => 'Auth', 'action' => 'forgetPassword'], ['class' => 'button button-outline']) ?>
                <?= $this->Form->end() ?>

                <hr class="hr-between-buttons">

                <?= $this->Html->link('Register new user', ['controller' => 'Auth', 'action' => 'register'], ['class' => 'button button-clear']) ?>
                <?= $this->Html->link('Go to Homepage', '/', ['class' => 'button button-clear']) ?>
            </div>
        </div>
    </div>
</div>
