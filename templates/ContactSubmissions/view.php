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
            <?= $this->Html->link(__('Edit Contact Submission'), ['action' => 'edit', $contactSubmission->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Contact Submission'), ['action' => 'delete', $contactSubmission->id], ['confirm' => __('Are you sure you want to delete # {0}?', $contactSubmission->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Contact Submissions'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Contact Submission'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="contactSubmissions view content">
            <h3><?= h($contactSubmission->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($contactSubmission->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($contactSubmission->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($contactSubmission->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($contactSubmission->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Captcha Passed') ?></th>
                    <td><?= $contactSubmission->captcha_passed ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Message') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($contactSubmission->message)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>