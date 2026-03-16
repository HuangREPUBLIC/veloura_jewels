<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ContactSubmission $contactSubmission
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $contactSubmission->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $contactSubmission->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Contact Submissions'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="contactSubmissions form content">
            <?= $this->Form->create($contactSubmission) ?>
            <fieldset>
                <legend><?= __('Edit Contact Submission') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('email');
                    echo $this->Form->control('message');
                    echo $this->Form->control('captcha_passed');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
