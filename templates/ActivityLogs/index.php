<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\ORM\ResultSet $activityLogs
 * @var string|null $filterModel
 * @var string|null $filterAction
 */
$this->assign('title', 'Activity Logs');
?>

<div class="page-header-row">
    <div>
        <p class="cms-eyebrow">Admin</p>
        <h2 class="page-title">Activity Logs</h2>
    </div>
    <div class="cms-header-right">
        <?= $this->element('per_page_select') ?>
    </div>
</div>

<?= $this->Form->create(null, ['type' => 'get', 'class' => 'admin-filter-bar']) ?>
    <?= $this->Form->select('action', [
        '' => 'All actions',
        'created' => 'Created',
        'updated' => 'Updated',
        'deleted' => 'Deleted',
    ], ['value' => $filterAction ?? '', 'class' => 'admin-filter-bar__input', 'onchange' => 'this.form.submit()']) ?>
    <button type="submit" class="btn-sm"><?= $this->iconSvg('search') ?></button>
<?= $this->Form->end() ?>

<?php if ($activityLogs->isEmpty()): ?>
    <p>No logs yet.</p>
<?php else: ?>
    <?php
    $fieldLabels = [
        'name' => 'Name', 'sale_price' => 'Sale Price', 'purchase_price' => 'Purchase Price',
        'description' => 'Description', 'story' => 'Story', 'featured' => 'Featured',
        'category_id' => 'Category', 'supplier_email' => 'Supplier Email',
        'sizes' => 'Sizes', 'images' => 'Images',
    ];
    $fmtVal = function ($val) {
        if (is_bool($val)) return $val ? 'Yes' : 'No';
        if ($val === '' || $val === null) return '—';
        $str = (string)$val;
        return mb_strlen($str) > 60 ? mb_substr($str, 0, 60) . '…' : $str;
    };
    $fmtSizes = function ($arr) {
        $parts = [];
        foreach ((array)$arr as $sv) {
            $sv = (array)$sv;
            $parts[] = h($sv['size'] ?? '?') . ': ' . (int)($sv['stock'] ?? 0);
        }
        return $parts ? implode(', ', $parts) : '—';
    };
    ?>
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date / Time</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Model</th>
                    <th>Record</th>
                    <th>Changes</th>
                    <th class="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($activityLogs as $log): ?>
                    <tr>
                        <td><?= h($log->created->format('d M Y H:i')) ?></td>
                        <td><?= h($log->user_name ?? '—') ?></td>
                        <td>
                            <span class="al-badge al-badge--<?= h($log->action) ?>"><?= h($log->action) ?></span>
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
                        <td class="actions">
                            <?php if ($log->model === 'Product'): ?>
                                <?php if ($log->action === 'deleted'): ?>
                                    <?= $this->Form->postLink($this->iconSvg('undo'), ['controller' => 'Products', 'action' => 'restoreProduct', $log->id], [
                                        'confirm' => 'Restore "' . $log->model_label . '" from deleted?',
                                        'escape' => false,
                                        'aria-label' => __('Restore'),
                                    ]) ?>
                                <?php elseif ($log->action === 'updated'): ?>
                                    <?= $this->Form->postLink($this->iconSvg('undo'), ['controller' => 'Products', 'action' => 'restoreProduct', $log->id], [
                                        'confirm' => 'Revert "' . $log->model_label . '" to its state before this change?',
                                        'escape' => false,
                                        'aria-label' => __('Revert'),
                                    ]) ?>
                                <?php else: ?>
                                    <?= $this->Form->postLink($this->iconSvg('archive'), ['controller' => 'Products', 'action' => 'archiveLog', $log->id], [
                                        'escape' => false,
                                        'aria-label' => __('Archive'),
                                    ]) ?>
                                <?php endif; ?>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?= $this->element('paginator') ?>
<?php endif; ?>
