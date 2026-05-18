<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->assign('title', 'Edit User');
$this->Html->css('login', ['block' => true]);
?>

<div class="login-page">
    <div class="users form content login-card--wide">
        <div class="action-buttons">
            <?= $this->Html->link(__('← Back to Users'), ['action' => 'index']) ?>
        </div>

        <h3><?= __('Edit User') ?></h3>

        <?= $this->Form->create($user) ?>
        <fieldset>
            <?= $this->Flash->render() ?>

            <?php
            echo $this->Form->control('email', [
                'label'    => 'Email',
                'required' => true,
                'disabled' => true,
            ]);

            $identity = $this->request->getAttribute('identity');
            $role = $identity ? $identity->get('role') : null;

            if ($role === 'admin' && $user->id !== 6) {
                echo $this->Form->control('role', [
                    'type'    => 'select',
                    'options' => [
                        'admin'    => 'Admin',
                        'staff'    => 'Staff',
                        'customer' => 'Customer',
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
