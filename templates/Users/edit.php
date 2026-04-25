<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->Html->css('login', ['block' => true]);
?>

<div class="login-page">
    <div class="users form content">
        <?= $this->Html->link(__('← Back'), ['action' => 'index']) ?>

        <?= $this->Form->create($user) ?>
        <fieldset>
            <legend><?= __('Edit User') ?></legend>

            <?= $this->Flash->render() ?>

            <?php
            echo $this->Form->control('email', [
                'label'    => 'Email',
                'required' => true,
                'disabled' => true,
            ]);

            $identity = $this->request->getAttribute('identity');
            $role = $identity ? $identity->get('role') : null;

            if ($role === 'admin') {
                echo $this->Form->control('role', [
                    'type'    => 'select',
                    'options' => [
                        'admin'     => 'Admin',
                        'staff' => 'Staff',
                        'customer'  => 'Customer',
                    ],
                    'label'    => 'Role',
                    'required' => true,
                    'disabled' => $user->id === $identity->get('id'),
                ]);
            }
            ?>
        </fieldset>

        <?= $this->Form->button(__('Save'), ['class' => 'login-button']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
