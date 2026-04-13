<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Product> $products
 */
$this->assign('title', 'Products');
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>

<?php $role = $this->request->getAttribute('identity')->get('role'); ?>

<div class="admin-wrapper">
    <div class="products index content">
        <?= $this->Html->link(__('← Back'), ['controller' => 'Users', 'action' => 'dashboard']) ?>

        <div class="page-header-row">
            <h3 class="page-title"><?= __('Products') ?></h3>
            <?php if (in_array($role, ['admin', 'part_time', 'full_time'])): ?>
                <?= $this->Html->link(__('Add New Product'), ['action' => 'add'], ['class' => 'btn-new-product']) ?>
            <?php endif; ?>
        </div>

        <div class="table-responsive" id="datatable" style="padding: 10px">
            <table id="productsTable" class="display">
                <thead>
                <tr>
                    <th>ID</th>
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
                        <td><?= $this->Number->format($product->id) ?></td>
                        <td><?= h($product->name) ?></td>
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
                            if (!empty($lowVariants)) {
                                echo '<span class="stock-low">' . implode(', ', $lowVariants) . '</span>';
                            } else {
                                echo '✓';
                            }
                            ?>
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
                                        'method'  => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $product->id),
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
        $('#productsTable').DataTable({
            order: [[1, 'desc']],
            language: {
                lengthMenu: '_MENU_ Entries Per Page',
                search: 'Search:'
            },
            columnDefs: [{ targets: [0, 1, 2, 3, 4, 5, 6, 7], searchable: true }]
        });
    });
</script>
