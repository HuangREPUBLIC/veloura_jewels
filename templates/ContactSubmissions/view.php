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
            <?= $this->Html->link(__('List Contact Submissions'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(
                __('Reply to this user'),
                ['action' => 'reply', $contactSubmission->id],
                ['class' => 'side-nav-item']
            ) ?>
            <?= $this->Form->postLink(
                __('Delete Contact Submission'),
                ['action' => 'delete', $contactSubmission->id],
                [
                    'confirm' => __('Are you sure you want to delete # {0}?', $contactSubmission->id),
                    'class' => 'side-nav-item'
                ]
            ) ?>
        </div>
    </aside>

    <div class="column column-80">
        <div class="contactSubmissions view content">
            <h3><?= h($contactSubmission->first_name . ' ' . $contactSubmission->last_name) ?></h3>

            <table>
                <tr>
                    <th><?= __('First Name') ?></th>
                    <td><?= h($contactSubmission->first_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Last Name') ?></th>
                    <td><?= h($contactSubmission->last_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($contactSubmission->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Submission Date/Time') ?></th>
                    <td><?= h($contactSubmission->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Captcha Passed') ?></th>
                    <td><?= $contactSubmission->captcha_passed ? __('Yes') : __('No') ?></td>
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
