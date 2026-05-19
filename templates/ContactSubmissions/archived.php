<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\ContactSubmission> $contactSubmissions
 */
$this->assign('title', 'Contact Submissions');
$this->Html->css('https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css', ['block' => true]);
$this->Html->script('https://code.jquery.com/jquery-3.6.0.min.js', ['block' => true]);
$this->Html->script('https://cdn.datatables.net/2.2.2/js/dataTables.min.js', ['block' => true]);
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>
<?php $role = $this->request->getAttribute('identity')->get('role'); ?>


<div class="admin-wrapper">
    <div class="contactSubmissions index content">
        <?= $this->Html->link(__('← Back'), ['controller' => 'ContactSubmissions', 'action' => 'index'], ['class' => 'back-link']) ?>

        <div class="page-header-row">
            <div>
                <h3 class="page-title"><?= __('Archived Contact Submissions') ?></h3>
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
                            <?= $this->Form->postLink(
                                'Restore',
                                ['action' => 'unarchive', $contactSubmission->id],
                                ['confirm' => 'Restore this submission?']
                            ) ?>
                            <?php if ($role === 'admin'): ?>
                            <?= $this->Form->postLink(
                                __('Delete'),
                                ['action' => 'delete', $contactSubmission->id],
                                [
                                    'method'  => 'delete',
                                    'confirm' => __('Are you sure you want to permanently delete this submission?'),
                                    'class'   => 'btn-delete',
                                ]
                            ) ?>
                            <?php endif; ?>
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
