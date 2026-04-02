<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Product> $products
 */
$this->assign('title', 'products');
?>

<?php $this->Html->css('admincontact', ['block' => true]); ?>

<?php $role = $this->request->getAttribute('identity')->get('role'); ?>

<div class="submissions-wrapper">
    <div class="products index content">
        <?= $this->Html->link(__('← Back'), ['controller' => 'Users','action' => 'dashboard'], ['class' => 'action-buttons-inline']) ?>

        <?php if (in_array($role, ['admin', 'part_time', 'full_time'])): ?>
            <?= $this->Html->link(__('New Product'), ['action' => 'add'], ['class' => 'button float-right']) ?>
        <?php endif; ?>

        <h3 class="page-title"><?= __('Products') ?></h3>
        <div class="table-responsive" id="datatable" style="padding: 10px">
            <table id="productsTable" class="display">
                <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('purchase_price') ?></th>
                    <th><?= $this->Paginator->sort('sale_price') ?></th>
                    <th><?= $this->Paginator->sort('stock') ?></th>
                    <th>Category</th>
                    <th><?= $this->Paginator->sort('supplier_email') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= $this->Number->format($product->id) ?></td>
                        <td><?= h($product->name) ?></td>
                        <td><?= $this->Number->format($product->purchase_price) ?></td>
                        <td><?= $this->Number->format($product->sale_price) ?></td>

                        <td>
                            <?php if ($product->stock < 5): ?>
                                <span style="color:red;font-weight:600;">
                                    <?= $product->stock ?>
                                </span>
                            <?php else: ?>
                                <?= $product->stock ?>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php
                            if (!empty($product->categories)) {
                                echo implode(', ', collection($product->categories)->extract('name')->toList());
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>

                        <td><?= h($product->supplier_email) ?></td>

                        <td class="actions">
                            <?= $this->Html->link(__('View'), ['action' => 'view', $product->id]) ?>

                            <?php if (in_array($role, ['admin', 'part_time', 'full_time'])): ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $product->id]) ?>
                            <?php endif; ?>

                            <?php if (in_array($role, ['admin', 'part_time', 'full_time'])): ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['action' => 'delete', $product->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $product->id),
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
        $('#productsTable').DataTable({
            order: [[1, 'desc']],
            language: {
                lengthMenu: '_MENU_ Entries Per Page',
                search: 'Search:'
            },
            columnDefs: [
                {
                    targets: [0, 1, 2, 3],
                    searchable: true
                }
            ]
        });
    });
</script>
