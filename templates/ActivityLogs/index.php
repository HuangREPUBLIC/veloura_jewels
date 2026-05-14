<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\ORM\ResultSet $activityLogs
 * @var string|null $filterModel
 * @var string|null $filterAction
 */

$this->assign('title', 'Activity Logs');
$this->Html->css('https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css', ['block' => true]);
$this->Html->script('https://code.jquery.com/jquery-3.6.0.min.js', ['block' => true]);
$this->Html->script('https://cdn.datatables.net/2.2.2/js/dataTables.min.js', ['block' => true]);
?>

<div class="admin-wrapper">
    <div class="admin-dashboard">

        <div class="dashboard-hero">
            <h1>Activity Logs</h1>
        </div>

        <div style="background:#fff;border-radius:10px;padding:1.5rem;margin-bottom:1.5rem;box-shadow:0 1px 4px rgba(0,0,0,.07);">
            <?= $this->Form->create(null, ['type' => 'get', 'url' => '/activity-logs', 'style' => 'display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;']) ?>
                <div>
                    <label style="display:block;font-size:.85rem;font-weight:600;margin-bottom:.3rem;">Model</label>
                    <?= $this->Form->select('model', [
                        ''       => 'All Models',
                        'Product' => 'Product',
                        'User'    => 'User',
                        'Order'   => 'Order',
                    ], ['value' => $filterModel ?? '', 'style' => 'padding:.45rem .75rem;border:1px solid #ccc;border-radius:6px;']) ?>
                </div>
                <div>
                    <label style="display:block;font-size:.85rem;font-weight:600;margin-bottom:.3rem;">Action</label>
                    <?= $this->Form->select('action', [
                        ''         => 'All Actions',
                        'created'  => 'Created',
                        'updated'  => 'Updated',
                        'deleted'  => 'Deleted',
                    ], ['value' => $filterAction ?? '', 'style' => 'padding:.45rem .75rem;border:1px solid #ccc;border-radius:6px;']) ?>
                </div>
                <div>
                    <?= $this->Form->button('Filter', ['class' => 'low-stock-btn', 'style' => 'padding:.45rem 1.2rem;']) ?>
                    <?= $this->Html->link('Clear', '/activity-logs', ['class' => 'low-stock-btn', 'style' => 'padding:.45rem 1.2rem;background:#999;']) ?>
                </div>
            <?= $this->Form->end() ?>
        </div>

        <div style="background:#fff;border-radius:10px;padding:1.5rem;box-shadow:0 1px 4px rgba(0,0,0,.07);overflow-x:auto;">
            <?php if ($activityLogs->isEmpty()): ?>
                <p style="text-align:center;color:#888;padding:2rem 0;">No logs yet.</p>
            <?php else: ?>
                <table id="activity-logs-table" style="width:100%;border-collapse:collapse;font-size:.9rem;">
                    <thead>
                        <tr style="border-bottom:2px solid #eee;">
                            <th style="text-align:left;padding:.6rem .8rem;white-space:nowrap;">Date / Time</th>
                            <th style="text-align:left;padding:.6rem .8rem;">User</th>
                            <th style="text-align:left;padding:.6rem .8rem;">Action</th>
                            <th style="text-align:left;padding:.6rem .8rem;">Model</th>
                            <th style="text-align:left;padding:.6rem .8rem;">Record</th>
                            <th style="text-align:left;padding:.6rem .8rem;">IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($activityLogs as $log): ?>
                            <tr style="border-bottom:1px solid #f0f0f0;">
                                <td style="padding:.55rem .8rem;white-space:nowrap;color:#555;">
                                    <?= h($log->created->format('d M Y H:i')) ?>
                                </td>
                                <td style="padding:.55rem .8rem;">
                                    <?= h($log->user_name ?? '—') ?>
                                </td>
                                <td style="padding:.55rem .8rem;">
                                    <?php
                                    $badgeColors = [
                                        'created' => 'background:#d4edda;color:#155724;',
                                        'updated' => 'background:#cce5ff;color:#004085;',
                                        'deleted' => 'background:#f8d7da;color:#721c24;',
                                    ];
                                    $badgeStyle = $badgeColors[$log->action] ?? 'background:#e2e3e5;color:#383d41;';
                                    ?>
                                    <span style="<?= $badgeStyle ?>padding:.2rem .55rem;border-radius:4px;font-size:.8rem;font-weight:600;text-transform:capitalize;">
                                        <?= h($log->action) ?>
                                    </span>
                                </td>
                                <td style="padding:.55rem .8rem;"><?= h($log->model) ?></td>
                                <td style="padding:.55rem .8rem;">
                                    <?= h($log->model_label ?: ('#' . $log->model_id)) ?>
                                </td>
                                <td style="padding:.55rem .8rem;color:#888;font-size:.85rem;">
                                    <?= h($log->ip_address ?? '—') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div style="margin-top:1rem;">
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
