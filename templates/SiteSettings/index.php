<?php
/**
 * @var \App\View\AppView $this
 * @var array $groups
 */

$this->assign('title', 'Site Settings');
$this->Html->css('admincontact', ['block' => true]);

$groupLabels = [
    'general' => 'General',
    'hero'    => 'Homepage Hero',
    'brand'   => 'Brand Story',
    'contact' => 'Contact Details',
    'footer'  => 'Footer',
];

$textareaKeys = ['hero_subtext', 'brand_story_body_1', 'brand_story_body_2', 'footer_tagline', 'contact_hours'];
?>

<div class="admin-wrapper">
    <div class="siteSettings index content">
        <?= $this->Html->link('← Back', ['controller' => 'Users', 'action' => 'dashboard'], ['class' => 'back-link']) ?>

        <?= $this->Flash->render() ?>

        <?= $this->Form->create(null, ['url' => ['action' => 'index'], 'method' => 'post']) ?>

        <div class="page-header-row">
            <h3 class="page-title">Content Management</h3>
            <?= $this->Form->button('Save All Settings', ['class' => 'btn-new-product']) ?>
        </div>

        <?php foreach ($groups as $groupName => $rows): ?>
            <div class="cms-group">
                <h4 class="cms-group-label"><?= h($groupLabels[$groupName] ?? ucfirst($groupName)) ?></h4>
                <div class="cms-group-fields">
                    <?php foreach ($rows as $row): ?>
                        <div class="cms-field">
                            <label class="cms-label" for="field-<?= h($row->setting_key) ?>"><?= h($row->label) ?></label>
                            <?php if (in_array($row->setting_key, $textareaKeys)): ?>
                                <textarea id="field-<?= h($row->setting_key) ?>" name="<?= h($row->setting_key) ?>" class="cms-textarea" rows="3"><?= h($row->setting_value) ?></textarea>
                            <?php else: ?>
                                <input id="field-<?= h($row->setting_key) ?>" type="text" name="<?= h($row->setting_key) ?>" value="<?= h($row->setting_value) ?>" class="cms-input">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <?= $this->Form->end() ?>
    </div>
</div>
