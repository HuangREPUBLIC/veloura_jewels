<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Product> $products
 */
$this->assign('title', 'Products');
function catPillClass(string $name): string {
    $map = [
        'rings'     => 'rings',
        'necklaces' => 'necklaces',
        'earrings'  => 'earrings',
        'bracelets' => 'bracelets',
        'brooches'  => 'brooches',
        'candles'   => 'candles',
        'vases'     => 'vases',
        'cushions'  => 'cushions',
        'wall art'  => 'wall-art',
        'throws'    => 'throws',
    ];
    return 'cat-pill-' . ($map[strtolower($name)] ?? 'default');
}
?>
<?php $this->Html->css('admincontact', ['block' => true]); ?>

<?php $role = $this->request->getAttribute('identity')->get('role'); ?>

<div class="admin-wrapper">
    <div class="products index content">
        <?= $this->Html->link(__('← Back'), ['controller' => 'Users', 'action' => 'dashboard'], ['class' => 'back-link']) ?>

        <div class="page-header-row">
            <div>
                <h3 class="page-title"><?= __('Products') ?></h3>
                <p class="page-subtitle">Manage product listings and details</p>
            </div>

            <?php if ($role === 'admin'): ?>
                <?= $this->Html->link(__('Add New Product'), ['action' => 'add'], ['class' => 'btn-new-product']) ?>
            <?php endif; ?>
        </div>

        <div class="table-responsive" style="padding: 10px">
            <table id="productsTable" class="display">
                <thead>
                <tr>
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
                            <?php if (!empty($product->categories)): ?>
                                <?php foreach ($product->categories as $cat): ?>
                                    <span class="cat-pill <?= catPillClass($cat->name) ?>"><?= h($cat->name) ?></span>
                                <?php endforeach; ?>
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

<script>
    $(document).ready(function() {
        $('#productsTable').DataTable({
            order: [[0, 'desc']],
            language: { lengthMenu: '_MENU_ Entries Per Page', search: 'Search:' },
            columnDefs: [{ targets: [0,1,2,3,4,5,6], searchable: true }]
        });
    });
</script>
