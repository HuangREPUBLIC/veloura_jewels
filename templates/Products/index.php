<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Product> $products
 */
$this->assign('title', 'Products');
$this->Html->css('https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css', ['block' => true]);
$this->Html->script('https://code.jquery.com/jquery-3.6.0.min.js', ['block' => true]);
$this->Html->script('https://cdn.datatables.net/2.2.2/js/dataTables.min.js', ['block' => true]);
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>

<?php $role = $this->request->getAttribute('identity')->get('role'); ?>

<div class="admin-wrapper">
    <div class="products index content">
        <?= $this->Html->link(__('← Back'), ['controller' => 'Users', 'action' => 'dashboard'], ['class' => 'back-link']) ?>

        <div class="page-header-row">
            <div>
                <h3 class="page-title"><?= __('Products') ?></h3>
            </div>

            <div style="display:flex;gap:10px;align-items:center">
                <?php if ($role === 'admin'): ?>
                    <button id="openActivityLogBtn" class="btn-activity-log">Activity Log</button>
                    <?= $this->Html->link(__('Add New Product'), ['action' => 'add'], ['class' => 'btn-new-product']) ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="table-responsive" style="padding: 10px">
            <table id="productsTable" class="display">
                <thead>
                <tr>
                    <th>Featured</th>
                    <th>Name</th>
                    <th>Purchase Price</th>
                    <th>Sale Price</th>
                    <th>Size & Stock</th>
                    <th>Category</th>
                    <th>Supplier Email</th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td style="text-align:center">
                            <?php if ($role === 'admin'): ?>
                                <button
                                    class="featured-toggle <?= !empty($product->featured) ? 'is-featured' : '' ?>"
                                    data-url="<?= $this->Url->build(['action' => 'toggleFeatured', $product->id]) ?>"
                                    title="Toggle featured"
                                >★</button>
                            <?php else: ?>
                                <?= !empty($product->featured) ? '★' : '☆' ?>
                            <?php endif; ?>
                        </td>
                        <td class="product-cell">
                            <div class="product-text">
                                <span class="product-name"><?= h($product->name) ?></span>
                            </div>
                        </td>

                        <td><?= $this->Number->format($product->purchase_price) ?></td>
                        <td><?= $this->Number->format($product->sale_price) ?></td>

                        <td>
                            <?php
                            $lowVariants = [];
                            if (!empty($product->product_variants)) {
                                foreach ($product->product_variants as $v) {
                                    if ($v->stock < 5) {
                                        $lowVariants[] = h($v->size) . ': ' . $v->stock;
                                    }
                                }
                            }
                            echo !empty($lowVariants)
                                ? '<span class="stock-low">' . implode(', ', $lowVariants) . '</span>'
                                : '✓';
                            ?>
                        </td>
                        <td>
                            <?php if (!empty($product->category)): ?>
                                <span class="admin-category-text"><?= h($product->category->name) ?></span>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td class="supplier-email"><?= h($product->supplier_email) ?></td>
                        <td class="actions">
                            <?= $this->Html->link(__('View'), ['action' => 'view', $product->id], ['class' => 'btn-view']) ?>
                            <?php if ($role === 'admin'): ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $product->id], ['class' => 'btn-edit']) ?>
                            <?php endif; ?>
                            <?php if ($role === 'admin'): ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $product->id], [
                                    'method' => 'delete',
                                    'confirm' => __('Are you sure you want to delete # {0}?', $product->id),
                                    'class' => 'btn-delete'
                                ]) ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if ($role === 'admin'): ?>
<!-- Activity Log Modal -->
<div id="activityLogModal" style="display:none;position:fixed;inset:0;z-index:900">
    <div id="activityLogOverlay" style="position:absolute;inset:0;background:rgba(0,0,0,0.45)"></div>
    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:#fff;border-radius:14px;width:92%;max-width:960px;max-height:85vh;display:flex;flex-direction:column;box-shadow:0 12px 40px rgba(0,0,0,0.18);overflow:hidden">
        <div style="display:flex;justify-content:space-between;align-items:center;padding:20px 24px 16px;border-bottom:1px solid #eee;flex-shrink:0">
            <strong style="font-size:1.05rem;color:#2a2320">Product Activity Log</strong>
            <button id="activityLogClose" style="background:none;border:none;font-size:1.4rem;cursor:pointer;line-height:1;color:#555">&times;</button>
        </div>
        <div style="overflow:auto;padding:16px 24px 24px;font-family:var(--font-admin)">
            <table id="activityLogsTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>When</th>
                        <th>Action</th>
                        <th>Product</th>
                        <th>By</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productLogs as $log): ?>
                        <?php
                        $badgeStyle = match($log->action) {
                            'created'  => 'background:#d4edda;color:#155724',
                            'deleted'  => 'background:#f8d7da;color:#721c24',
                            'restored' => 'background:#d1ecf1;color:#0c5460',
                            default    => 'background:#fff3cd;color:#856404',
                        };
                        $logMeta = [
                            'Action'  => ucfirst($log->action),
                            'Product' => $log->model_label,
                            'By'      => $log->user_name,
                            'When'    => $log->created->format('Y-m-d H:i'),
                        ];
                        $logChanges = [];
                        if (!empty($log->changes)) {
                            foreach ($log->changes as $k => $v) {
                                if ($k === 'images') continue;
                                $label = ucwords(str_replace('_', ' ', $k));
                                if (is_array($v) && isset($v['from'], $v['to'])) {
                                    $logChanges[$label] = ['from' => $v['from'], 'to' => $v['to']];
                                } else {
                                    $logChanges[$label] = is_bool($v) ? ($v ? 'true' : 'false') : $v;
                                }
                            }
                        }
                        $logDataJson = json_encode(['meta' => $logMeta, 'changes' => $logChanges], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
                        ?>
                        <tr>
                            <td><?= h($log->created->format('Y-m-d H:i')) ?></td>
                            <td>
                                <span style="<?= $badgeStyle ?>; padding: 2px 8px; border-radius: 4px; font-size: 0.8em; font-weight: 600;">
                                    <?= h(ucfirst($log->action)) ?>
                                </span>
                            </td>
                            <td><?= h($log->model_label) ?></td>
                            <td><?= h($log->user_name) ?></td>
                            <td class="actions">
                                <button class="btn-view log-view-btn" data-changes="<?= h($logDataJson) ?>">View</button>
                                <?php if ($log->action === 'deleted'): ?>
                                    <?= $this->Form->postLink('Restore', ['action' => 'restoreProduct', $log->id], [
                                        'confirm' => 'Restore "' . $log->model_label . '" from deleted?',
                                        'class'   => 'btn-restore-log',
                                    ]) ?>
                                <?php elseif ($log->action === 'updated'): ?>
                                    <?= $this->Form->postLink('Revert', ['action' => 'restoreProduct', $log->id], [
                                        'confirm' => 'Revert "' . $log->model_label . '" to its state before this change?',
                                        'class'   => 'btn-restore-log',
                                    ]) ?>
                                <?php endif; ?>
                                <?php if (!in_array($log->action, ['updated', 'deleted'])): ?>
                                    <?= $this->Form->postLink('Archive', ['action' => 'archiveLog', $log->id], [
                                        'class' => 'btn-archive-log',
                                    ]) ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Changes detail popup (sits above the log modal) -->
<div id="logModal" style="display:none;position:fixed;inset:0;z-index:1100">
    <div id="logModalOverlay" style="position:absolute;inset:0;background:rgba(0,0,0,0.35)"></div>
    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:#fff;border-radius:10px;min-width:320px;max-width:520px;width:90%;box-shadow:0 8px 32px rgba(0,0,0,0.18);padding:28px 28px 20px">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
            <strong style="font-size:1rem">Changes</strong>
            <button id="logModalClose" style="background:none;border:none;font-size:1.3rem;cursor:pointer;line-height:1;color:#555">&times;</button>
        </div>
        <div id="logModalBody" style="font-size:0.9rem;color:#333;max-height:60vh;overflow-y:auto;font-family:var(--font-admin)"></div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#productsTable').DataTable({
            order: [[1, 'asc']],
            language: { lengthMenu: '_MENU_ Entries Per Page', search: 'Search:' },
            columnDefs: [
                { targets: [0], orderable: false, searchable: false },
                { targets: [1,2,3,4,5,6,7], searchable: true }
            ]
        });

        var csrfToken = '<?= $this->request->getAttribute('csrfToken') ?>';
        var logTableInit = false;

        $('#openActivityLogBtn').on('click', function() {
            $('#activityLogModal').fadeIn(180);
            if (!logTableInit) {
                $('#activityLogsTable').DataTable({
                    order: [[0, 'desc']],
                    language: { lengthMenu: '_MENU_ Entries Per Page', search: 'Search:' },
                    columnDefs: [{ targets: [-1], orderable: false, searchable: false }],
                });
                logTableInit = true;
            }
        });

        $('#activityLogOverlay, #activityLogClose').on('click', function() {
            $('#activityLogModal').fadeOut(180);
        });

        $(document).on('click', '.log-view-btn', function() {
            var data = $(this).data('changes');
            var tdStyle = 'padding:5px 16px 5px 0;white-space:nowrap;color:#555;font-weight:600';
            var tdVal   = 'padding:5px 0';

            function metaRow(k, v) {
                return '<tr><td style="' + tdStyle + '">' + k + '</td><td style="' + tdVal + '">' + v + '</td></tr>';
            }

            var metaHtml = Object.entries(data.meta).map(function([k, v]) {
                return metaRow(k, v);
            }).join('');

            var changesHtml = '';
            if (data.changes && Object.keys(data.changes).length > 0) {
                var changeRows = Object.entries(data.changes).map(function([k, v]) {
                    var val;
                    if (v !== null && typeof v === 'object' && 'from' in v && 'to' in v) {
                        val = '<span style="color:#721c24">' + (v.from ?? '—') + '</span>'
                            + ' <span style="color:#aaa;margin:0 4px">→</span>'
                            + '<span style="color:#155724">' + (v.to ?? '—') + '</span>';
                    } else {
                        val = v;
                    }
                    return '<tr><td style="' + tdStyle + '">' + k + '</td><td style="' + tdVal + '">' + val + '</td></tr>';
                }).join('');
                changesHtml = '<div style="margin-top:14px;padding-top:12px;border-top:1px solid #eee">'
                    + '<div style="font-size:0.75em;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#888;margin-bottom:6px">Changes</div>'
                    + '<table style="width:100%;border-collapse:collapse">' + changeRows + '</table></div>';
            }

            $('#logModalBody').html(
                '<table style="width:100%;border-collapse:collapse">' + metaHtml + '</table>' + changesHtml
            );
            $('#logModal').fadeIn(150);
        });

        $(document).on('click', '#logModalOverlay, #logModalClose', function() {
            $('#logModal').fadeOut(150);
        });

        $('#productsTable').on('click', '.featured-toggle', function() {
            var btn = $(this);
            $.ajax({
                url: btn.data('url'),
                method: 'POST',
                data: { _csrfToken: csrfToken },
                dataType: 'json',
                success: function(res) {
                    btn.toggleClass('is-featured', res.featured);
                }
            });
        });
    });
</script>
