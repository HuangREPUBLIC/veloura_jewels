<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
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

        <?= $this->Form->button(__('Save'), ['class' => 'login-button']) ?>
        <?= $this->Form->end() ?>


    </div>
</div>
