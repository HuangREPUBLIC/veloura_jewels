<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->assign('title', 'Edit User');
$this->assign('crumbRecord', h($user->email));

$identity = $this->request->getAttribute('identity');
$role = $identity ? $identity->get('role') : null;
$formId = 'edit-user-form';
?>

<div class="page-header-row">
    <div>
        <p class="cms-eyebrow">Users</p>
        <h2 class="page-title"><?= h($user->email) ?></h2>
    </div>
    <div class="cms-header-right">
        <button type="submit" form="<?= h($formId) ?>" class="btn-new-product"><?= __('Save') ?></button>
    </div>
</div>

<?= $this->Flash->render() ?>

<?= $this->Form->create($user, ['id' => $formId]) ?>
<div class="cms-group">
    <div class="cms-group-fields">
        <div class="cms-field">
            <?php
            echo $this->Form->control('email', [
                'label'    => ['text' => 'Email', 'class' => 'cms-label'],
                'class'    => 'cms-input',
                'required' => true,
                'disabled' => true,
            ]);
            ?>
        </div>

        <?php if ($role === 'admin' && $user->id !== 6): ?>
            <div class="cms-field">
                <?php
                echo $this->Form->control('role', [
                    'type'    => 'select',
                    'options' => [
                        'admin'    => 'Admin',
                        'staff'    => 'Staff',
                        'customer' => 'Customer',
                    ],
                    'label'    => ['text' => 'Role', 'class' => 'cms-label'],
                    'class'    => 'cms-input',
                    'required' => true,
                    'disabled' => $user->id === $identity->get('id'),
                ]);
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->Form->end() ?>
