<?php
/**
 * @var \App\View\AppView $this
 */
?>

<!-- CSS Styling -->
<?php $this->Html->css('default-styles', ['block' => true]); ?>
<?php $this->Html->css('login', ['block' => true]); ?>

<!-- Form Content -->
<div class="users form content">
    <h2><?= __('Admin Login') ?></h2>

    <?= $this->Form->create() ?>
    <fieldset>
        <?php
        echo $this->Form->control('email', [
            'label' => 'Email',
            'required' => true
        ]);

        echo $this->Form->control('password', [
            'label' => 'Password',
            'type' => 'password',
            'required' => true
        ]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Login')) ?>
    <?= $this->Form->end() ?>
</div>
