<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */

$this->assign('title', 'Change Password');
$this->Html->css('profile', ['block' => true]);
?>

<div class="profile-page">
    <div class="profile-form-wrap">

        <div class="profile-form-header">
            <?= $this->Html->link('← Back to Profile', ['controller' => 'Profile', 'action' => 'index'], ['class' => 'profile-back-link']) ?>
            <h1>Change Password</h1>
        </div>

        <div class="profile-card">
            <?= $this->Form->create($user, ['url' => ['controller' => 'Auth', 'action' => 'changePassword']]) ?>

            <div class="profile-form-grid" style="grid-template-columns: 1fr;">

                <div class="profile-form-group">
                    <label class="profile-form-label">Current Password</label>
                    <?= $this->Form->control('current_password', [
                        'label'       => false,
                        'type'        => 'password',
                        'value'       => '',
                        'required'    => true,
                        'autofocus'   => true,
                        'placeholder' => 'Enter your current password',
                    ]) ?>
                </div>

                <div class="profile-form-group">
                    <label class="profile-form-label">New Password</label>
                    <?= $this->Form->control('password', [
                        'label'       => false,
                        'type'        => 'password',
                        'value'       => '',
                        'required'    => true,
                        'placeholder' => 'At least 8 characters',
                    ]) ?>
                </div>

                <div class="profile-form-group">
                    <label class="profile-form-label">Confirm New Password</label>
                    <?= $this->Form->control('confirm_password', [
                        'label'       => false,
                        'type'        => 'password',
                        'value'       => '',
                        'required'    => true,
                        'placeholder' => 'Repeat new password',
                    ]) ?>
                </div>

            </div>

            <div class="profile-form-actions">
                <?= $this->Form->button('Update Password', ['class' => 'profile-save-btn']) ?>
                <?= $this->Html->link('Cancel', ['controller' => 'Profile', 'action' => 'index'], ['class' => 'profile-cancel-btn']) ?>
            </div>

            <?= $this->Form->end() ?>
        </div>

    </div>
</div>
