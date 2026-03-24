<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<?php $this->Html->css('default-styles', ['block' => true]); ?>
<?php $this->Html->css('login', ['block' => true]); ?>

<div class="users form content">
    <h2><?= __('Edit User') ?></h2>
    <?= $this->Html->link(__('← Back'), ['action' => 'index'], ['class' => 'action-buttons-inline']) ?>
    <br>

    <?= $this->Form->create($user) ?>
    <fieldset>
        <?php
        echo $this->Form->control('email', [
            'label' => 'Email',
            'required' => true,
            'disabled' => true,
        ]);

        echo $this->Form->control('role', [
            'type' => 'select',
            'options' => [
                'admin' => 'Admin',
                'staff' => 'Staff',
                'customer' => 'Customer',
            ],
            'label' => 'Role',
            'required' => true
        ]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Save')) ?>
    <?= $this->Form->end() ?>
</div>
