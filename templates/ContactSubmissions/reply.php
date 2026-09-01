<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ContactSubmission $contactSubmission
 */
$this->assign('title', 'Reply to Enquiry');
$this->assign('crumbRecord', h($contactSubmission->first_name . ' ' . $contactSubmission->last_name));
$formId = 'reply-form';
?>

<div class="page-header-row">
    <div>
        <p class="cms-eyebrow">Contact Submissions</p>
        <h2 class="page-title"><?= h($contactSubmission->first_name . ' ' . $contactSubmission->last_name) ?></h2>
    </div>
    <div class="cms-header-right">
        <button type="submit" form="<?= h($formId) ?>" class="btn-new-product"><?= __('Send Reply') ?></button>
    </div>
</div>

<?= $this->Flash->render() ?>

<table class="view-table admin-view-table-spaced">
    <tr>
        <th><?= __('Email') ?></th>
        <td><?= h($contactSubmission->email) ?></td>
    </tr>
    <tr>
        <th><?= __('Message') ?></th>
        <td><?= h($contactSubmission->message) ?></td>
    </tr>
</table>

<?= $this->Form->create(null, ['id' => $formId]) ?>
<div class="cms-group">
    <div class="cms-group-label"><?= __('Your Reply') ?></div>
    <div class="cms-group-fields">
        <div class="cms-field">
            <?= $this->Form->control('subject', [
                'label'    => ['text' => __('Subject'), 'class' => 'cms-label'],
                'class'    => 'cms-input',
                'required' => true,
                'value'    => 'Re: ' . $contactSubmission->subject,
            ]) ?>
        </div>
        <div class="cms-field">
            <?= $this->Form->control('message', [
                'type'     => 'textarea',
                'label'    => ['text' => __('Message'), 'class' => 'cms-label'],
                'class'    => 'cms-textarea',
                'required' => true,
                'value'    => 'Hi ' . $contactSubmission->first_name . ',',
                'rows'     => 6,
            ]) ?>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>
