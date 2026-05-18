<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\ORM\ResultSet $activityLogs
 * @var string|null $filterModel
 * @var string|null $filterAction
 */

$this->assign('title', 'Activity Logs');
$this->Html->css('admincontact', ['block' => true]);
$this->Html->css('https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css', ['block' => true]);
$this->Html->script('https://code.jquery.com/jquery-3.6.0.min.js', ['block' => true]);
$this->Html->script('https://cdn.datatables.net/2.2.2/js/dataTables.min.js', ['block' => true]);
?>

<div class="admin-wrapper">
    <div class="admin-dashboard">

        <div class="dashboard-hero">
            <h1>Activity Logs</h1>
        </div>

        <div class="al-filter-card">
            <?= $this->Form->create(null, ['type' => 'get', 'url' => '/activity-logs', 'class' => 'al-filter-form']) ?>
                <div class="al-filter-group">
                    <label class="al-filter-label">Model</label>
                    <?= $this->Form->select('model', [
                        ''        => 'All Models',
                        'Product' => 'Product',
                        'User'    => 'User',
                        'Order'   => 'Order',
                    ], ['value' => $filterModel ?? '', 'class' => 'al-filter-select']) ?>
                </div>
                <div class="al-filter-group">
                    <label class="al-filter-label">Action</label>
                    <?= $this->Form->select('action', [
                        ''        => 'All Actions',
                        'created' => 'Created',
                        'updated' => 'Updated',
                        'deleted' => 'Deleted',
                    ], ['value' => $filterAction ?? '', 'class' => 'al-filter-select']) ?>
                </div>
                <div class="al-filter-actions">
                    <?= $this->Form->button('Filter', ['class' => 'btn-new-product']) ?>
                    <?= $this->Html->link('Clear', '/activity-logs', ['class' => 'btn-activity-log']) ?>
                </div>
            <?= $this->Form->end() ?>
        </div>

        <div class="al-table-card">
            <?php if ($activityLogs->isEmpty()): ?>
                <p class="al-empty">No logs yet.</p>
            <?php else: ?>
                <table id="activity-logs-table" class="al-table">
                    <thead>
                        <tr>
                            <th>Date / Time</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Model</th>
                            <th>Record</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($activityLogs as $log): ?>
                            <tr>
                                <td class="al-td-date"><?= h($log->created->format('d M Y H:i')) ?></td>
                                <td><?= h($log->user_name ?? '—') ?></td>
                                <td>
                                    <span class="al-badge al-badge--<?= h($log->action) ?>">
                                        <?= h($log->action) ?>
                                    </span>
                                </td>
                                <td><?= h($log->model) ?></td>
                                <td><?= h($log->model_label ?: ('#' . $log->model_id)) ?></td>
                                <td class="al-td-ip"><?= h($log->ip_address ?? '—') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="al-pagination">
                    <?= $this->Paginator->prev('&laquo; Previous', ['escape' => false]) ?>
                    <?= $this->Paginator->numbers() ?>
                    <?= $this->Paginator->next('Next &raquo;', ['escape' => false]) ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<script>
$(document).ready(function () {
    $('#activity-logs-table').DataTable({
        paging: false,
        info: false,
        order: [],
        language: {
            search: 'Search logs:'
        }
    });
});
</script>
