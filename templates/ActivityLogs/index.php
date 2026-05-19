<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\ORM\ResultSet $activityLogs
 * @var string|null $filterModel
 * @var string|null $filterAction
 */

$this->assign('title', 'Activity Logs');
$this->Html->css('admincontact', ['block' => true]);
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
            <?php if (!$activityLogs->isEmpty()): ?>
                <div class="al-search-bar">
                    <input type="search" id="al-search" placeholder="Search logs…" class="al-search-input">
                </div>
            <?php endif; ?>
            <?php if ($activityLogs->isEmpty()): ?>
                <p class="al-empty">No logs yet.</p>
            <?php else: ?>
                <?php
                $fieldLabels = [
                    'name'           => 'Name',
                    'sale_price'     => 'Sale Price',
                    'purchase_price' => 'Purchase Price',
                    'description'    => 'Description',
                    'story'          => 'Story',
                    'featured'       => 'Featured',
                    'category_id'    => 'Category',
                    'supplier_email' => 'Supplier Email',
                    'sizes'          => 'Sizes',
                    'images'         => 'Images',
                ];
                $fmtVal = function($val) {
                    if (is_bool($val)) return $val ? 'Yes' : 'No';
                    if ($val === '' || $val === null) return '—';
                    $str = (string)$val;
                    return mb_strlen($str) > 60 ? mb_substr($str, 0, 60) . '…' : $str;
                };
                $fmtSizes = function($arr) {
                    $parts = [];
                    foreach ((array)$arr as $sv) {
                        $sv = (array)$sv;
                        $parts[] = h($sv['size'] ?? '?') . ': ' . (int)($sv['stock'] ?? 0);
                    }
                    return $parts ? implode(', ', $parts) : '—';
                };
                ?>
                <table id="activity-logs-table" class="al-table">
                    <thead>
                        <tr>
                            <th>Date / Time</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Model</th>
                            <th>Record</th>
                            <th>Changes</th>
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
                                <td class="al-td-changes">
                                    <?php if (!empty($log->changes)): ?>
                                        <?php foreach ($log->changes as $field => $diff): ?>
                                            <?php $label = $fieldLabels[$field] ?? ucfirst(str_replace('_', ' ', $field)); ?>
                                            <?php if ($field === 'images' && is_array($diff) && isset($diff['from'], $diff['to'])): ?>
                                                <div class="al-change-row al-change-row--images">
                                                    <span class="al-change-field"><?= h($label) ?>:</span>
                                                    <div class="al-img-group">
                                                        <?php foreach ($diff['from'] as $f): ?>
                                                            <img src="<?= $this->Url->image('products/' . h($f)) ?>" class="al-img-thumb al-img-thumb--from" title="<?= h($f) ?>">
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <span class="al-change-arrow">→</span>
                                                    <div class="al-img-group">
                                                        <?php foreach ($diff['to'] as $f): ?>
                                                            <img src="<?= $this->Url->image('products/' . h($f)) ?>" class="al-img-thumb al-img-thumb--to" title="<?= h($f) ?>">
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            <?php elseif ($field === 'sizes' && is_array($diff) && isset($diff['from'], $diff['to'])): ?>
                                                <div class="al-change-row">
                                                    <span class="al-change-field"><?= h($label) ?>:</span>
                                                    <span class="al-change-from"><?= $fmtSizes($diff['from']) ?></span>
                                                    <span class="al-change-arrow">→</span>
                                                    <span class="al-change-to"><?= $fmtSizes($diff['to']) ?></span>
                                                </div>
                                            <?php elseif (is_array($diff) && isset($diff['from'], $diff['to'])): ?>
                                                <div class="al-change-row">
                                                    <span class="al-change-field"><?= h($label) ?>:</span>
                                                    <span class="al-change-from"><?= h($fmtVal($diff['from'])) ?></span>
                                                    <span class="al-change-arrow">→</span>
                                                    <span class="al-change-to"><?= h($fmtVal($diff['to'])) ?></span>
                                                </div>
                                            <?php else: ?>
                                                <div class="al-change-row">
                                                    <span class="al-change-field"><?= h($label) ?>:</span>
                                                    <span class="al-change-to"><?= h($fmtVal($diff)) ?></span>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>
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
(function () {
    var input = document.getElementById('al-search');
    var rows = document.querySelectorAll('#activity-logs-table tbody tr');
    input.addEventListener('input', function () {
        var q = this.value.toLowerCase();
        rows.forEach(function (tr) {
            tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
})();
</script>
