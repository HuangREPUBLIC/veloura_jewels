<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->assign('title', 'Edit Profile');
$this->Html->css('profile', ['block' => true]);
?>

<div class="profile-page">
    <div class="profile-form-wrap">

        <div class="profile-form-header">
            <?= $this->Html->link('← Back to Profile', ['action' => 'index'], ['class' => 'profile-back-link']) ?>
            <h1>Edit Profile</h1>
        </div>

        <div class="profile-card">
            <?= $this->Form->create($user, ['url' => ['action' => 'edit']]) ?>

            <div class="profile-form-grid">
                <div class="profile-form-group">
                    <label class="profile-form-label">First Name</label>
                    <?= $this->Form->control('first_name', [
                        'label' => false,
                        'class' => 'profile-form-input',
                        'placeholder' => 'First name',
                    ]) ?>
                </div>
                <div class="profile-form-group">
                    <label class="profile-form-label">Last Name</label>
                    <?= $this->Form->control('last_name', [
                        'label' => false,
                        'class' => 'profile-form-input',
                        'placeholder' => 'Last name',
                    ]) ?>
                </div>
                <div class="profile-form-group profile-form-group--full">
                    <label class="profile-form-label">Phone</label>
                    <?= $this->Form->control('phone', [
                        'label' => false,
                        'class' => 'profile-form-input',
                        'placeholder' => 'e.g. 04XX XXX XXX',
                        'type' => 'tel',
                    ]) ?>
                </div>
                <div class="profile-form-group profile-form-group--full">
                    <label class="profile-form-label">Address</label>
                    <?= $this->Form->control('address', [
                        'label' => false,
                        'class' => 'profile-form-input',
                        'placeholder' => 'Your delivery address',
                        'type' => 'textarea',
                        'rows' => 3,
                    ]) ?>
                </div>
            </div>

            <div class="profile-form-actions">
                <?= $this->Form->button('Save Changes', ['class' => 'profile-save-btn']) ?>
                <?= $this->Html->link('Cancel', ['action' => 'index'], ['class' => 'profile-cancel-btn']) ?>
            </div>

            <?= $this->Form->end() ?>
        </div>

    </div>
</div>
