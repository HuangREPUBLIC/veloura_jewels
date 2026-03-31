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

        $identity = $this->request->getAttribute('identity');
        $role = $identity ? $identity->get('role') : null;

        if ($role === 'admin') {
            echo $this->Form->control('role', [
                'type' => 'select',
                'options' => [
                    'admin' => 'Admin',
                    'full_time' => 'Full-time Staff',
                    'part_time' => 'Part-time Staff',
                    'customer' => 'Customer',
                ],
                'label' => 'Role',
                'required' => true
            ]);
        } elseif ($role === 'full_time') {
            echo $this->Form->control('role', [
                'type' => 'select',
                'options' => [
                    'full_time' => 'Full-time Staff',
                    'part_time' => 'Part-time Staff',
                    'customer' => 'Customer',
                ],
                'label' => 'Role',
                'required' => true
            ]);
        }
        ?>
    </fieldset>
    <?= $this->Form->button(__('Save')) ?>
    <?= $this->Form->end() ?>
</div>
