<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\ContactSubmission> $contactSubmissions
 */
$this->assign('title', 'Contact Submissions');
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>

<div class="admin-wrapper">
    <div class="contactSubmissions index content">
        <?= $this->Html->link(__('← Back'), ['controller' => 'Users', 'action' => 'dashboard'], ['class' => 'back-link']) ?>

        <div class="page-header-row">
            <div>
                <h3 class="page-title"><?= __('Contact Submissions') ?></h3>
            </div>
        </div>

        <div class="table-responsive" style="padding: 10px">
            <table id="contactFormsTable" class="display">
                <thead>
                <tr>
                    <th>Details</th>
                    <th>Subject</th>
                    <th>Date</th>
                    <th>Replied</th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($contactSubmissions as $contactSubmission): ?>
                    <tr>
                        <td>
                            <strong><?= h($contactSubmission->first_name) ?> <?= h($contactSubmission->last_name) ?></strong>
                            <br>
                            <small><?= h($contactSubmission->email) ?></small>
                        </td>
                        <td><?= h($contactSubmission->subject) ?></td>
                        <td><?= h($contactSubmission->created) ?></td>
                        <td>
                            <?php if ($contactSubmission->is_replied): ?>
                                <span class="replied-yes">Yes</span>
                            <?php else: ?>
                                <span class="replied-no">No</span>
                            <?php endif; ?>
                        </td>
                        <td class="actions">
                            <?= $this->Html->link(__('View'), ['action' => 'view', $contactSubmission->id], ['class' => 'btn-view']) ?>
                            <?= $this->Form->postLink(
                                __('Delete'),
                                ['action' => 'delete', $contactSubmission->id],
                                [
                                    'method'  => 'delete',
                                    'confirm' => __('Are you sure you want to delete # {0}?', $contactSubmission->id),
                                    'class'   => 'btn-delete',
                                ]
                            ) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#contactFormsTable').DataTable({
            order: [[2, 'desc']],
            language: {
                lengthMenu: '_MENU_ Entries Per Page',
                search: 'Search:'
            },
            columnDefs: [{ targets: [0, 1, 2, 3], searchable: true }]
        });
    });
</script>
